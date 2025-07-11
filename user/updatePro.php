<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno']) || !isset($_POST['uno'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='../home.php';</script>";
    exit;
}

$uno = $_POST['uno'];
$uname = $_POST['uname'];
$uphone = $_POST['uphone'];
$upwd = $_POST['upwd'];

if (!empty($upwd)) {
    $sql = "UPDATE user SET uname = ?, uphone = ?, upwd = ? WHERE uno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $uname, $uphone, $upwd, $uno);
} else {
    $sql = "UPDATE user SET uname = ?, uphone = ? WHERE uno = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $uname, $uphone, $uno);
}

if ($stmt->execute()) {
    $_SESSION['uname'] = $uname;
    echo "<script>alert('회원 정보가 수정되었습니다.'); location.href='info.php';</script>";
} else {
    echo "<script>alert('수정에 실패했습니다.'); history.back();</script>";
}
?>
