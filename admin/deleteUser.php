<?php
session_start();
include '../db.php';

// 관리자 로그인 체크 (필요 시)
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

if (!isset($_POST['uno'])) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$uno = (int)$_POST['uno'];

$sql = "UPDATE user SET udelete = 1 WHERE uno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uno);

if ($stmt->execute()) {
    echo "<script>alert('회원 탈퇴가 완료되었습니다.'); location.href='userList.php';</script>";
} else {
    echo "<script>alert('탈퇴 처리 중 오류가 발생했습니다.'); history.back();</script>";
}
?>
