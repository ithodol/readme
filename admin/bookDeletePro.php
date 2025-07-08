<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

$bno = isset($_POST['bno']) ? intval($_POST['bno']) : 0;
if ($bno <= 0) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$sql_img = "SELECT bimg FROM book WHERE bno = ?";
$stmt_img = $conn->prepare($sql_img);
$stmt_img->bind_param("i", $bno);
$stmt_img->execute();
$result_img = $stmt_img->get_result();
$row_img = $result_img->fetch_assoc();

if ($row_img && $row_img['bimg']) {
    $imgPath = "../img/" . $row_img['bimg'];
    if (file_exists($imgPath)) {
        unlink($imgPath); // 이미지 삭제
    }
}

// 도서 삭제
$sql_delete = "DELETE FROM book WHERE bno = ?";
$stmt_del = $conn->prepare($sql_delete);
$stmt_del->bind_param("i", $bno);

if ($stmt_del->execute()) {
    echo "<script>alert('도서가 삭제되었습니다.'); location.href='bookListAll.php';</script>";
} else {
    echo "<script>alert('도서 삭제에 실패했습니다.'); history.back();</script>";
}
?>
