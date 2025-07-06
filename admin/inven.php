<?php
session_start();
include '../db.php';

// 관리자 로그인 체크
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

$adNo = $_SESSION['adno'];

// itype 받아서 입고(0) 출고(1) 구분
$itype = isset($_GET['itype']) && ($_GET['itype'] == '1') ? 1 : 0;
$title = $itype === 0 ? "입고 등록" : "출고 등록";
$btnText = $itype === 0 ? "입고하기" : "출고하기";

// 도서 리스트 가져오기 (선택할 수 있게)
$sqlBooks = "SELECT bno, btitle FROM book ORDER BY btitle ASC";
$resultBooks = $conn->query($sqlBooks);

// 입출고 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postItype = isset($_POST['itype']) ? intval($_POST['itype']) : 0;
    $bno = isset($_POST['bno']) ? intval($_POST['bno']) : 0;
    $icount = isset($_POST['icount']) ? intval($_POST['icount']) : 0;
    $imemo = isset($_POST['imemo']) ? trim($_POST['imemo']) : '';

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

    // 출고 수량이 재고보다 많으면 에러
    if ($postItype === 1 && $icount > $currentStock) {
        echo "<script>alert('출고 수량이 현재 재고보다 많을 수 없습니다.'); history.back();</script>";
        exit;
    }

    // 새 재고 계산
    $newStock = ($postItype === 0) ? $currentStock + $icount : $currentStock - $icount;

    // insert inven 테이블에 입출고 기록 추가
    $sqlInsert = "INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iiisii", $postItype, $icount, $newStock, $imemo, $bno, $adNo);

    if ($stmtInsert->execute()) {

        // 재고 총합 계산 (inven 테이블에서 해당 bno 재고 모두 합산)
        $sqlSum = "SELECT SUM(istock) AS totalStock FROM inven WHERE bno = ?";
        $stmtSum = $conn->prepare($sqlSum);
        $stmtSum->bind_param("i", $bno);
        $stmtSum->execute();
        $resSum = $stmtSum->get_result();
        $totalStock = 0;
        if ($resSum->num_rows > 0) {
            $rowSum = $resSum->fetch_assoc();
            $totalStock = intval($rowSum['totalStock']);
        }

        // book 테이블의 bstate 업데이트
        // 재고가 0 이하이면 1(대출불가), 그 외는 0(대출가능)
        $newBstate = ($totalStock <= 0) ? 1 : 0;
        $stmtUpdate = $conn->prepare("UPDATE book SET bstate = ? WHERE bno = ?");
        $stmtUpdate->bind_param("ii", $newBstate, $bno);
        $stmtUpdate->execute();

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
