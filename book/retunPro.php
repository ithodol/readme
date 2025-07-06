<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

$lno = isset($_GET['lno']) ? intval($_GET['lno']) : 0;
if ($lno <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 1. 해당 대출 내역 확인
$sqlLoan = "SELECT bno FROM loan WHERE lno = ? AND lstate = 0";
$stmt = $conn->prepare($sqlLoan);
$stmt->bind_param("i", $lno);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "<script>alert('이미 반납되었거나 유효하지 않은 대출입니다.'); history.back();</script>";
    exit;
}

$row = $res->fetch_assoc();
$bno = $row['bno'];

// 2. 대출 상태 변경
$conn->query("UPDATE loan SET lstate = 1, lrdate = NOW() WHERE lno = $lno");

// 3. 재고 1 증가
$conn->query("UPDATE inven SET istock = istock + 1 WHERE bno = $bno");

// 4. 전체 재고 재확인 후 bstate 갱신
$resStock = $conn->query("SELECT SUM(istock) AS total_stock FROM inven WHERE bno = $bno");
$rowStock = $resStock->fetch_assoc();
$totalStock = (int)$rowStock['total_stock'];

$newState = ($totalStock <= 0) ? 1 : 0;
$conn->query("UPDATE book SET bstate = $newState WHERE bno = $bno");

echo "<script>alert('반납이 완료되었습니다.'); location.href='../user/mypage.php';</script>";
