<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['lno'])) {
        echo "<script>alert('잘못된 요청입니다.'); history.back();</script>";
        exit;
    }

    $lno = intval($_POST['lno']);
    $uno = $_SESSION['uno'];

    // 1. 대출 정보 조회 (본인의 대출인지 확인)
    $stmt = $conn->prepare("SELECT bno FROM loan WHERE lno = ? AND uno = ? AND lstate = 0");
    $stmt->bind_param("ii", $lno, $uno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('해당 대출 정보를 찾을 수 없거나 이미 반납된 도서입니다.'); history.back();</script>";
        exit;
    }

    $row = $result->fetch_assoc();
    $bno = $row['bno'];

    $today = date('Y-m-d');

    // 반납처리
    $stmtUpdateLoan = $conn->prepare("UPDATE loan SET lstate = 1, lrdate = ? WHERE lno = ?");
    $stmtUpdateLoan->bind_param("si", $today, $lno);
    $stmtUpdateLoan->execute();

    // 상태수정
    $stmtUpdateBook = $conn->prepare("UPDATE book SET bstate = 0 WHERE bno = ?");
    $stmtUpdateBook->bind_param("i", $bno);
    $stmtUpdateBook->execute();

    echo "<script>alert('반납이 완료되었습니다.'); location.href='/readme/user/info.php';</script>";
    exit;
} else {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}
?>
