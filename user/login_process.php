<?php
session_start();
include(__DIR__ . "/../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $conn->real_escape_string($_POST['uid']);
    $upwd = $conn->real_escape_string($_POST['upwd']);

    $sql = "SELECT uno, uid, uname FROM user WHERE uid = '$uid' AND upwd = '$upwd'";
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
        echo "아이디 또는 비밀번호가 잘못되었습니다.";
    }
}
$conn->close();
?>
