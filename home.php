<?php
session_start();
include 'db.php';
?>

<?php include 'header.php'; ?>

<!-- 🔍 검색 기능 -->
<!-- <div class="searchBox">
    <h1>🔍 도서 검색</h1>
    <form method="get" action="search.php">
        <input type="text" name="keyword" placeholder="도서명 또는 저자 검색" required>
        <button type="submit">검색</button>
    </form>
</div> -->


<!-- 📖 대출 가능 도서 -->
<?php
// book 표 전체 조회해서 도서별 조건 해결
$sqlAllBooks = "SELECT bno, btitle, briter, bimg FROM book ORDER BY bno DESC";
$resultAll = $conn->query($sqlAllBooks);

$availableBooks = [];

if ($resultAll->num_rows > 0) {
    while ($book = $resultAll->fetch_assoc()) {
        $bno = $book['bno'];

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
                    <img src="/readme/upload/book/<?= htmlspecialchars($book['bimg']) ?>" alt="<?= htmlspecialchars($book['btitle']) ?>" />
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

<hr class="homeHr">

<!-- 공지사항 -->
<?php
$sql = "SELECT n.nno, n.ntit, n.ndate, n.nview, a.adname 
        FROM notice n
        JOIN admin a ON n.adno = a.adno
        ORDER BY n.nno DESC
        LIMIT 5";

$result = $conn->query($sql);
?>

<div class="noticeBoard">
    <h1>📢 공지사항</h1>
    <table class="noticeTable">
        <thead>
            <tr>
                <th style="width: 5%;">번호</th>
                <th style="width: 65%;">제목</th>
                <th>작성일</th>
                <th>작성자</th>
                <th>조회수</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['nno'] ?></td>
                    <td class="noticeTitle" style="text-align: left; padding:10px 20px;">
                        <a href="/readme/board/noticeView.php?nno=<?= $row['nno'] ?>">
                            <?= htmlspecialchars($row['ntit']) ?>
                        </a>
                    </td>
                    <td><?= substr($row['ndate'], 0, 10) ?></td>
                    <td><?= htmlspecialchars($row['adname']) ?></td>
                    <td><?= $row['nview'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- 더보기 버튼 -->
    <div class="noticeMore">
        <a href="/readme/board/notice.php" class="moreButton">더보기</a>
    </div>
</div>

</body>
</html>
