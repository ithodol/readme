<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>도서 관리 시스템</title>
    <link rel="stylesheet" href="/readme/css/main.css">
    <link rel="stylesheet" href="/readme/css/header.css">
    <link rel="stylesheet" href="/readme/css/book.css">
    <link rel="stylesheet" href="/readme/css/user.css">
    <link rel="stylesheet" href="/readme/css/admin.css">
    <link rel="stylesheet" href="/readme/css/board.css">
</head>
<body>
    <header class="globalHeader">
        <div class="headerWrapper">
            <h1 class="siteTitle"><a href="/readme/home.php">📚 ReadMe</a></h1>
            <div class="loginStatus">
            <?php
            if (isset($_SESSION['adno'])) {
                echo "<strong>" . htmlspecialchars($_SESSION['adname']) . "</strong>님 
                      <a href='/readme/admin/logout.php'>로그아웃</a>  
                      <a href='/readme/admin/info.php'>관리자 전용</a>";
            } else if (isset($_SESSION['uno'])) {
                echo "<strong>" . htmlspecialchars($_SESSION['uname']) . "</strong>님 환영합니다. 
                      <a href='/readme/user/logout.php'>로그아웃 </a>
                      <a href='/readme/user/info.php'>마이페이지</a>";
            } else {
                echo "
                    <a href='/readme/user/join.php'>회원가입</a>
                    <a href='/readme/user/login.php'>로그인</a>
                ";
            }
            ?>
            </div>
        </div>
    </header>
