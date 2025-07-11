<?php
session_start();
include '../db.php';

$cno = isset($_GET['cno']) ? intval($_GET['cno']) : 0;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// 1. 기본 SQL
$sql = "SELECT bno, btitle, briter, bpub, bimg, cno FROM book";

// 2. 조건 조립 (카테고리, 검색어)
if ($cno > 0 && $keyword !== "") {
    // 카테고리 + 검색어 둘 다 있는 경우
    $sql .= " WHERE cno = $cno AND (btitle LIKE '%$keyword%' OR briter LIKE '%$keyword%')";
} else if ($cno > 0) {
    // 카테고리만 있는 경우
    $sql .= " WHERE cno = $cno";
} else if ($keyword !== "") {
    // 검색어만 있는 경우
    $sql .= " WHERE btitle LIKE '%$keyword%' OR briter LIKE '%$keyword%'";
}

$sql .= " ORDER BY bno ASC";

// 3. 도서 조회
$resultBooks = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<h1 style="text-align: center; margin:70px 0px 40px 0px;">
<?php
if ($keyword !== "") {
    echo "🔍 '" . htmlspecialchars($keyword) . "' 검색 결과";
} else {
    echo "📚 도서 전체 목록";
}
?>
</h1>

<div class="searchBox">
    <form method="get" action="bookList.php" class="searchForm">
    <input type="text" name="keyword" placeholder="도서명 또는 저자 검색" value="<?= htmlspecialchars($keyword) ?>" class="searchInput">
    <?php if ($cno > 0): ?>
        <input type="hidden" name="cno" value="<?= $cno ?>">
    <?php endif; ?>
    <button type="submit" class="searchButton">검색</button>
    <button type="button" onclick="location.href='bookList.php'" class="resetButton">검색 초기화</button>
    </form>
</div>


<!-- 카테고리 버튼 -->
<div class="categoryButtons">
    <a href="bookList.php<?= $keyword ? '?keyword=' . urlencode($keyword) : '' ?>" <?= $cno == 0 ? 'class="active"' : '' ?>>전체</a>
    <?php
    $resultCats = $conn->query("SELECT cno, cname FROM category ORDER BY cno ASC");
    while ($cat = $resultCats->fetch_assoc()) {
        $active = ($cat['cno'] == $cno) ? 'class="active"' : '';
        $href = "?cno={$cat['cno']}";
        if ($keyword !== "") $href .= "&keyword=" . urlencode($keyword);
        echo "<a href='$href' $active>" . htmlspecialchars($cat['cname']) . "</a> ";
    }
    ?>
</div>

<div class="bookList">
<?php
if ($resultBooks->num_rows > 0) {
    while ($book = $resultBooks->fetch_assoc()) {
        $bno = $book['bno'];

        // 최신 재고 확인
        $sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
        $stmtStock = $conn->prepare($sqlStock);
        $stmtStock->bind_param("i", $bno);
        $stmtStock->execute();
        $resStock = $stmtStock->get_result();
        $stock = 0;
        if ($resStock->num_rows > 0) {
            $rowStock = $resStock->fetch_assoc();
            $stock = intval($rowStock['istock']);
        }

        // 현재 대출 중인지 확인
        $sqlLoan = "SELECT COUNT(*) AS cnt FROM loan WHERE bno = ? AND lstate = 0";
        $stmtLoan = $conn->prepare($sqlLoan);
        $stmtLoan->bind_param("i", $bno);
        $stmtLoan->execute();
        $resLoan = $stmtLoan->get_result();
        $isLoaned = false;
        if ($resLoan->num_rows > 0) {
            $rowLoan = $resLoan->fetch_assoc();
            $isLoaned = intval($rowLoan['cnt']) > 0;
        }

        echo '<div class="bookItem">';
        echo '<a href="bookView.php?bno=' . $bno . '">';
        echo '<img src="../upload/book/' . htmlspecialchars($book['bimg']) . '" alt="' . htmlspecialchars($book['btitle']) . '">';
        echo '<h4>' . htmlspecialchars($book['btitle']) . '</h4>';
        echo '</a>';
        echo '<p>' . htmlspecialchars($book['briter']) . '</p>';
        echo '<p>' . htmlspecialchars($book['bpub']) . '</p>';

        if ($stock > 0 && !$isLoaned) {
            echo '<hr><p class="available">대출 가능</p>';
        } else {
            echo '<hr><p class="unavailable">대출 불가</p>';
        }

        echo '</div>';
    }
} else {
    echo '<p style="text-align:center; margin-top:40px;">검색 결과가 없습니다.</p>';
}
?>
</div>
</body>
</html>
