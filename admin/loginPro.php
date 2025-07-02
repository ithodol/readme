<?php
session_start();
include(__DIR__ . "/../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adid = $conn->real_escape_string($_POST['adid']);
    $adpwd = $conn->real_escape_string($_POST['adpwd']);

    $sql = "SELECT adno, adid, adname FROM admin WHERE adid = '$adid' AND adpwd = '$adpwd'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['adno'] = $admin['adno'];
        $_SESSION['adname'] = $admin['adname'];

        header("Location: ../admin/info.php");
        exit();
    } else {
        echo "아이디 또는 비밀번호가 잘못되었습니다.";
    }
}

$conn->close();
?>
