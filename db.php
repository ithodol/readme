<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "readme";
$port = 3307;
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) die("DB 연결 실패: " . $conn->connect_error);
?>