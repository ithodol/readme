<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

$bNo = isset($_GET['bno']) ? intval($_GET['bno']) : 0;
if ($bNo <= 0) {
    echo "잘못된 접근입니다.";
    exit;
}

$sql = "SELECT b.*, c.cname, s.srow, s.scol
        FROM book b
        JOIN category c ON b.cno = c.cno
        JOIN slot s ON b.sno = s.sno
        WHERE b.bno = $bNo";

$result = $conn->query($sql);
if ($result->num_rows == 0) {
    echo "해당 도서를 찾을 수 없습니다.";
    exit;
}

$book = $result->fetch_assoc();
?>

<?php include '../header.php'; ?>

<div class="pageWrapper">
    <h1 class="pageTitle">📘 도서 정보</h1>
    <div class="bookDetailContainer">
        <div class="bookImageContainer">
            <img class="bookImage" src="../img/<?= htmlspecialchars($book['bimg']) ?>" alt="<?= htmlspecialchars($book['btitle']) ?>">
        </div>
        <div class="bookInfo">
            <h2 class="bookTitle"><?= htmlspecialchars($book['btitle']) ?></h2>
            <ul class="bookInfoList">
                <li><span class="label">저자</span> <?= htmlspecialchars($book['briter']) ?></li>
                <li><span class="label">출판사</span> <?= htmlspecialchars($book['bpub']) ?></li>
                <li><span class="label">카테고리</span> <?= htmlspecialchars($book['cname']) ?></li>
                <li><span class="label">도서 상태</span> <?= $book['bstate'] ? '대출 중' : '대출 가능' ?></li>
                <li><span class="label">위치</span> <?= htmlspecialchars($book['srow']) . '-' . htmlspecialchars($book['scol']) ?></li>
            </ul>
            <?php
                // 현재 재고 확인
                $sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
                $stmtStock = $conn->prepare($sqlStock);
                $stmtStock->bind_param("i", $book['bno']);
                $stmtStock->execute();
                $resStock = $stmtStock->get_result();
                $currentStock = 0;
                if ($resStock->num_rows > 0) {
                    $rowStock = $resStock->fetch_assoc();
                    $currentStock = intval($rowStock['istock']);
                }
            ?>

            

            <?php if ($book['bstate'] == 0 && $currentStock > 0): ?>
                <form class="loanForm" method="post" action="loanPro.php">
                    <input type="hidden" name="bNo" value="<?= $book['bno'] ?>">
                    <button type="submit" class="loanButton">대출하기</button>
                </form>
            <?php elseif ($currentStock == 0): ?>
                <p class="unavailableText">현재 재고가 없어 대출이 불가합니다.</p>
            <?php else: ?>
                <p class="unavailableText">현재 대출 중입니다.</p>
            <?php endif; ?>

            <p class="backLink"><a href="bookList.php">도서 목록</a></p>
        </div>
    </div>
</div>

</body>
</html>
