<?php
session_start();
include '../db.php';


$cno = isset($_GET['cno']) ? intval($_GET['cno']) : 0;

// 도서 목록
$sql = "SELECT bno, btitle, briter, bpub, bimg, cno FROM book";
if ($cno > 0) {
    $sql .= " WHERE cno = $cno";
}
$sql .= " ORDER BY bno ASC";
$resultBooks = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<h1 style="text-align: center; margin:60px 0px;">📚 도서 전체 목록</h1>

<!-- 카테고리 버튼 -->
<div class="categoryButtons">
    <a href="bookList.php" <?= $cno == 0 ? 'class="active"' : '' ?>>전체</a>
    <?php
    $resultCats = $conn->query("SELECT cno, cname FROM category ORDER BY cno ASC");
    while ($cat = $resultCats->fetch_assoc()) {
        $active = ($cat['cno'] == $cno) ? 'class="active"' : '';
        echo '<a href="?cno=' . $cat['cno'] . '" ' . $active . '>' . htmlspecialchars($cat['cname']) . '</a> ';
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
        echo '<img src="../img/' . htmlspecialchars($book['bimg']) . '" alt="' . htmlspecialchars($book['btitle']) . '">';
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
    echo '<p style="text-align:center; margin-top:40px;">등록된 도서가 없습니다.</p>';
}
?>
</div>
</body>
</html>
