<?php
session_start();
include '../db.php';

$uid = $_POST['uid'];
$upwd = $_POST['upwd'];
$uname = $_POST['uname'];
$uphone = $_POST['uphone'];

// 간단한 유효성 검사
if (!$uid || !$upwd || !$uname || !$uphone) {
    echo "<script>alert('모든 항목을 입력해주세요.'); history.back();</script>";
    exit;
}

// 중복 아이디 확인
$checkSql = "SELECT * FROM user WHERE uid = '$uid'";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('이미 사용 중인 아이디입니다.'); history.back();</script>";
    exit;
}

// 회원 등록 (비밀번호 평문 저장)
$sql = "INSERT INTO user (uid, upwd, uname, uphone) 
        VALUES ('$uid', '$upwd', '$uname', '$uphone')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('회원가입이 완료되었습니다'); location.href='login.php';</script>";
} else {
    echo "<script>alert('회원가입에 실패했습니다.'); history.back();</script>";
}
?>
