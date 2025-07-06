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
    if (!isset($_POST['lno']) || !is_numeric($_POST['lno'])) {
        echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
        exit;
    }

    $lno = intval($_POST['lno']);

    // 대출 정보 가져오기
    $sql = "SELECT bno, lstate FROM loan WHERE lno = ? AND uno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $lno, $uno);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo "<script>alert('대출 정보를 찾을 수 없습니다.'); history.back();</script>";
        exit;
    }

    $loan = $res->fetch_assoc();
    if ($loan['lstate'] == 1) {
        echo "<script>alert('이미 반납된 도서입니다.'); history.back();</script>";
        exit;
    }

    $bno = $loan['bno'];

    // 반납 처리: loan 테이블 업데이트
    $stmtUpdate = $conn->prepare("UPDATE loan SET lstate = 1, lrdate = NOW() WHERE lno = ?");
    $stmtUpdate->bind_param("i", $lno);
    $stmtUpdate->execute();

    // 현재 재고 조회
    $sqlStock = "SELECT istock FROM inven WHERE bno = ? ORDER BY idate DESC LIMIT 1";
    $stmtStock = $conn->prepare($sqlStock);
    $stmtStock->bind_param("i", $bno);
    $stmtStock->execute();
    $resStock = $stmtStock->get_result();

    $currentStock = 0;
    if ($resStock->num_rows > 0) {
        $stockRow = $resStock->fetch_assoc();
        $currentStock = intval($stockRow['istock']);
    }

    $newStock = $currentStock + 1;
    $memo = '사용자 반납';

    // inven 테이블에 입고 기록 추가 (관리자 없음: adno=NULL)
    $stmtInsert = $conn->prepare("INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES (0, 1, ?, ?, ?, NULL)");
    $stmtInsert->bind_param("isi", $newStock, $memo, $bno);
    $stmtInsert->execute();

    echo "<script>alert('반납이 완료되었습니다.'); location.href='../user/info.php';</script>";
    exit;

} else {
    echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
    exit;
}
?>