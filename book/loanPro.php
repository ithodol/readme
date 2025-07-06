<?php
session_start();
include '../db.php';

// 로그인 체크
if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

$uno = $_SESSION['uno'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['bno']) || empty($_POST['bno'])) {
        echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
        exit;
    }

    $bno = intval($_POST['bno']);

    // 현재 재고 조회 (가장 최신 재고)
    $sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
    $stmtStock = $conn->prepare($sqlStock);
    $stmtStock->bind_param("i", $bno);
    $stmtStock->execute();
    $resStock = $stmtStock->get_result();

    if ($resStock->num_rows === 0) {
        echo "<script>alert('해당 도서 재고 정보가 없습니다.'); history.back();</script>";
        exit;
    }

    $rowStock = $resStock->fetch_assoc();
    $currentStock = intval($rowStock['istock']);

    if ($currentStock <= 0) {
        echo "<script>alert('재고가 부족하여 대출이 불가합니다.'); history.back();</script>";
        exit;
    }

    // 해당 도서가 현재 대출중인지 확인 (lstate=0인 대출 내역 존재 여부)
    $sqlLoanCheck = "SELECT COUNT(*) AS cnt FROM loan WHERE bno = ? AND lstate = 0";
    $stmtLoanCheck = $conn->prepare($sqlLoanCheck);
    $stmtLoanCheck->bind_param("i", $bno);
    $stmtLoanCheck->execute();
    $resLoanCheck = $stmtLoanCheck->get_result();
    $loanCheck = $resLoanCheck->fetch_assoc();

    if ($loanCheck['cnt'] > 0) {
        echo "<script>alert('현재 대출 중인 도서입니다.'); history.back();</script>";
        exit;
    }

    // 대출 내역 추가
    $today = date('Y-m-d');
    $returnDate = date('Y-m-d', strtotime('+7 days')); // 7일 후 반납 예정일

    $stmtLoan = $conn->prepare("INSERT INTO loan (ldate, lddate, lrdate, lstate, uno, bno) VALUES (?, ?, NULL, 0, ?, ?)");
    $stmtLoan->bind_param("ssii", $today, $returnDate, $uno, $bno);
    $stmtLoan->execute();

    if ($stmtLoan->affected_rows === 0) {
        echo "<script>alert('대출 신청 중 오류가 발생했습니다.'); history.back();</script>";
        exit;
    }

    // 재고 출고 기록 추가 (itype=1, icount=1, istock=currentStock-1)
    $newStock = $currentStock - 1;
    $imemo = "대출 출고";
    // 관리자 세션 없으면 null
    $adNo = isset($_SESSION['adno']) ? intval($_SESSION['adno']) : null;

    // adno가 null일 경우 쿼리에 NULL 직접 넣기 위해 분기 처리
    if (is_null($adNo)) {
        $sqlInsert = "INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES (1, 1, ?, ?, ?, NULL)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("isi", $newStock, $imemo, $bno);
    } else {
        $sqlInsert = "INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES (1, 1, ?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("isii", $newStock, $imemo, $bno, $adNo);
    }
    $stmtInsert->execute();

    if ($stmtInsert->affected_rows === 0) {
        // 롤백: 대출 내역 삭제
        $conn->query("DELETE FROM loan WHERE lno = " . $stmtLoan->insert_id);
        echo "<script>alert('재고 출고 처리 중 오류가 발생했습니다.'); history.back();</script>";
        exit;
    }

    echo "<script>alert('대출이 완료되었습니다.\\n반납 기한은 $returnDate 입니다.'); location.href='bookList.php';</script>";
    exit;

} else {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}
?>
