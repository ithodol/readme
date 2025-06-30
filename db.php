<?php
$host = "localhost";
$user = "root";
$pass = "1234";
$db = "readme";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB 연결 실패: " . $conn->connect_error);
?>