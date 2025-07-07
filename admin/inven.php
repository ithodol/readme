<?php
session_start();
include '../db.php';


if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

$adNo = $_SESSION['adno'];


$itype = isset($_GET['itype']) && ($_GET['itype'] == '1') ? 1 : 0;
$title = $itype === 0 ? "입고 등록" : "출고 등록";
$btnText = $itype === 0 ? "입고하기" : "출고하기";


$sqlBooks = "SELECT bno, btitle FROM book ORDER BY btitle ASC";
$resultBooks = $conn->query($sqlBooks);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $postItype = isset($_POST['itype']) ? intval($_POST['itype']) : null;
    $bno = isset($_POST['bno']) ? intval($_POST['bno']) : 0;
    $icount = isset($_POST['icount']) ? intval($_POST['icount']) : 0;
    $imemo = isset($_POST['imemo']) ? trim($_POST['imemo']) : '';

    if ($postItype === null || ($postItype !== 0 && $postItype !== 1)) {
        echo "<script>alert('입출고 유형이 올바르지 않습니다.'); history.back();</script>";
        exit;
    }
    if ($bno <= 0 || $icount <= 0) {
        echo "<script>alert('도서 선택과 수량은 필수입니다.'); history.back();</script>";
        exit;
    }

    // 현재 재고 조회
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


    if ($postItype === 1 && $icount > $currentStock) {
        echo "<script>alert('출고 수량이 현재 재고보다 많을 수 없습니다.'); history.back();</script>";
        exit;
    }

    // 새 재고 계산
    $newStock = ($postItype === 0) ? $currentStock + $icount : $currentStock - $icount;

    $sqlInsert = "INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iiisii", $postItype, $icount, $newStock, $imemo, $bno, $adNo);

    if ($stmtInsert->execute()) {

        echo "<script>alert('{$btnText}가 완료되었습니다.'); location.href='invenList.php';</script>";
        exit;
    } else {
        echo "<script>alert('처리 중 오류가 발생했습니다.'); history.back();</script>";
        exit;
    }
}

?>

<?php include '../header.php'; ?>
<div class="stockFormBox">
    <h2 class="stockTitle"><?= $title ?></h2>
    <form method="post" action="inven.php?itype=<?= $itype ?>" class="stockForm">
        <input type="hidden" name="itype" value="<?= $itype ?>">

        <div class="formGroup">
            <label for="bno">도서</label>
            <select name="bno" id="bno" required>
                <option value="">선택</option>
                <?php while ($book = $resultBooks->fetch_assoc()): ?>
                    <option value="<?= $book['bno'] ?>"><?= htmlspecialchars($book['btitle']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="formGroup">
            <label for="icount">수량</label>
            <input type="number" name="icount" id="icount" min="1" required>
        </div>

        <div class="formGroup">
            <label for="imemo">메모</label>
            <input type="text" name="imemo" id="imemo" maxlength="255" placeholder="필요시 입력">
        </div>

        <div class="stockBtnGroup">
            <button type="submit" class="stockButton"><?= $btnText ?></button>
            <button type="button" class="stockButton stockBack">
                <a href="invenList.php">취소</a>
            </button>
        </div>
    </form>

</div>

</body>
</html>
