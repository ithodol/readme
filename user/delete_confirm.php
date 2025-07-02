<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='../home.php';</script>";
    exit;
}

$uno = $_SESSION['uno'];

$sql = "UPDATE user SET udelete = 1 WHERE uno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uno);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    echo "<script>alert('탈퇴가 완료되었습니다.'); location.href='../home.php';</script>";
} else {
    echo "<script>alert('탈퇴 처리 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
