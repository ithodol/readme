<?php
session_start();
include 'db.php';
?>

<?php include 'header.php'; ?>

<!-- 🔍 검색 기능 -->
<div class="searchBox">
    <h1>🔍 도서 검색</h1>
    <form method="get" action="search.php">
        <input type="text" name="keyword" placeholder="도서명 또는 저자 검색" required>
        <button type="submit">검색</button>
    </form>
</div>

<hr>

<!-- 📖 대출 가능 도서 -->
<?php
// book 표 전체 조회해서 도서별 조건 해결
$sqlAllBooks = "SELECT bno, btitle, briter, bimg FROM book ORDER BY bno DESC";
$resultAll = $conn->query($sqlAllBooks);

$availableBooks = [];

if ($resultAll->num_rows > 0) {
    while ($book = $resultAll->fetch_assoc()) {
        $bno = $book['bno'];

        // 현재 재고 확인
        $sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
        $stmtStock = $conn->prepare($sqlStock);
        $stmtStock->bind_param("i", $bno);
        $stmtStock->execute();
        $resStock = $stmtStock->get_result();
        $currentStock = 0;
        if ($resStock->num_rows > 0) {
            $rowStock = $resStock->fetch_assoc();
            $currentStock = intval($rowStock['istock']);
        }

        // 대출 중인지 확인
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

        // 대출 가능 조건 확인
        if ($currentStock > 0 && !$isLoaned) {
            $availableBooks[] = $book;
        }

        if (count($availableBooks) >= 5) break; // 5개만
    }
}
?>

<div class="bookBox">
    <h1>📖 대출 가능 도서</h1>
    <div class="bookList">
        <?php if (count($availableBooks) > 0): ?>
            <?php foreach ($availableBooks as $book): ?>
            <div class="bookItem">
                <a href="./book/bookView.php?bno=<?= htmlspecialchars($book['bno']) ?>">
                    <img src="img/<?= htmlspecialchars($book['bimg']) ?>" alt="<?= htmlspecialchars($book['btitle']) ?>" />
                </a>
                <div class="bookTitle"><?= htmlspecialchars($book['btitle']) ?></div>
                <div class="bookAuthor"><?= htmlspecialchars($book['briter']) ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="noBooks">대출 가능 도서가 없습니다.</p>
        <?php endif; ?>
    </div>
    <a href="./book/bookList.php" class="moreButton">더 보기</a>
</div>

<hr>

<!-- 공지사항 -->
<h1 class="noticeTitle">📢 공지사항</h1>
<?php
$notices = $conn->query("SELECT nno, ntitle, ndate FROM notice ORDER BY ndate DESC LIMIT 3");

if ($notices && $notices->num_rows > 0) {
    echo "<ul class=\"noticeList\">";
    while ($notice = $notices->fetch_assoc()) {
        echo "<li class=\"noticeItem\">" . htmlspecialchars($notice['ndate']) . " - <strong>" . htmlspecialchars($notice['ntitle']) . "</strong></li>";
    }
    echo "</ul>";
} else {
    echo "<p class=\"noNotices\">등록된 공지사항이 없습니다.</p>";
}
?>

<link rel="stylesheet" href="styles/home.css">
</body>
</html>
