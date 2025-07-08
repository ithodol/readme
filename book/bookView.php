<?php
session_start();
include '../db.php';


$bNo = isset($_GET['bno']) ? intval($_GET['bno']) : 0;
if ($bNo <= 0) {
    echo "잘못된 접근입니다.";
    exit;
}

// 도서 기본 정보 조회
$sql = "SELECT b.*, c.cname, s.srow, s.scol
        FROM book b
        JOIN category c ON b.cno = c.cno
        JOIN slot s ON b.sno = s.sno
        WHERE b.bno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bNo);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "해당 도서를 찾을 수 없습니다.";
    exit;
}
$book = $result->fetch_assoc();

// 현재 재고 확인
$sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
$stmtStock = $conn->prepare($sqlStock);
$stmtStock->bind_param("i", $bNo);
$stmtStock->execute();
$resStock = $stmtStock->get_result();
$currentStock = 0;
if ($resStock->num_rows > 0) {
    $rowStock = $resStock->fetch_assoc();
    $currentStock = intval($rowStock['istock']);
}

// 대출 여부 확인
$sqlLoan = "SELECT COUNT(*) AS cnt FROM loan WHERE bno = ? AND lstate = 0";
$stmtLoan = $conn->prepare($sqlLoan);
$stmtLoan->bind_param("i", $bNo);
$stmtLoan->execute();
$resLoan = $stmtLoan->get_result();
$isLoaned = false;
if ($resLoan->num_rows > 0) {
    $rowLoan = $resLoan->fetch_assoc();
    $isLoaned = intval($rowLoan['cnt']) > 0;
}

$canLoan = $currentStock > 0 && !$isLoaned;
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
                <li><span class="label">도서 상태</span> 
                    <?php
                        if ($canLoan) {
                            echo '<span>대출 가능</span>';
                        } elseif ($currentStock <= 0) {
                            echo '<span>재고 부족</span>';
                        } elseif ($isLoaned) {
                            echo '<span>대출 중</span>';
                        } else {
                            echo '<span>대출 불가</span>';
                        }
                    ?>
                </li>
                <li><span class="label">위치</span> <?= htmlspecialchars($book['srow']) . '-' . htmlspecialchars($book['scol']) ?></li>
            </ul>

            <form class="loanForm" method="post" action="loanPro.php">
                <input type="hidden" name="bno" value="<?= $book['bno'] ?>">
                <input type="hidden" name="return" value="bookView.php?bno=<?= $book['bno'] ?>">
                <button type="submit" class="loanButton"
                    <?= $canLoan ? '' : 'disabled style="background-color: #E53935; cursor: not-allowed;"' ?>>
                    <?= $canLoan ? '대출하기' : '대출 불가' ?>
                </button>
            </form>

            <p class="backLink"><a href="bookList.php">도서 목록</a></p>
        </div>
    </div>
</div>

</body>
</html>