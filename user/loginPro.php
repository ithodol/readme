<?php
session_start();
include(__DIR__ . "/../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $conn->real_escape_string($_POST['uid']);
    $upwd = $conn->real_escape_string($_POST['upwd']);

    // 탈퇴 회원(udelete=1)이 로그인 못하도록 조건 추가
    $sql = "SELECT uno, uid, uname FROM user WHERE uid = '$uid' AND upwd = '$upwd' AND (udelete IS NULL OR udelete = 0)";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // 로그인 성공, 세션에 사용자 정보 저장
        $_SESSION['uno'] = $user['uno'];
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['uname'] = $user['uname'];

        header("Location: ../home.php");
        exit();
    } else {
        echo "<script>alert('아이디 또는 비밀번호가 잘못되었거나 탈퇴된 회원입니다.'); location.href='/readme/user/login.php';</script>";
    }
}
$conn->close();
?>
