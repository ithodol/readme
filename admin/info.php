<?php
session_start();

// 관리자만 접근 가능
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능한 페이지입니다.'); location.href='../home.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>관리자 마이페이지</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding-top: 50px;
        }
        h1 {
            margin-bottom: 30px;
        }
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 30px;
        }
        .btn-container form {
            display: inline-block;
        }
        .btn-container button {
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            background-color: #007BFF;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>👮 관리자 전용 페이지</h1>
<p><a href="../home.php">🏠 홈으로</a> | <a href="logout.php">로그아웃</a></p>

<div class="btn-container">
    <form action="loan_manage.php" method="get">
        <button type="submit">📦 대출관리</button>
    </form>
    <form action="userList.php" method="get">
        <button onclick="location.href='userList.php'">회원관리</button>
    </form>
    <form action="book_manage.php" method="get">
        <button type="submit">📚 도서관리</button>
    </form>
</div>

</body>
</html>
