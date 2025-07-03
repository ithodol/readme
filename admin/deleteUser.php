<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

$uno = isset($_POST['uno']) ? intval($_POST['uno']) : 0;
if ($uno <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

// 탈퇴 처리: udelete = 1, ustate = 1 (대출 불가능)
$sql = "UPDATE user SET udelete = 1, ustate = 1 WHERE uno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uno);

if ($stmt->execute()) {
    echo "<script>alert('회원이 탈퇴 처리되었습니다.'); location.href='userList.php';</script>";
} else {
    echo "<script>alert('처리에 실패했습니다.'); history.back();</script>";
}
?>
