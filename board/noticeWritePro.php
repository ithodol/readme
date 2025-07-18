<?php
session_start();
date_default_timezone_set('Asia/Seoul');
include '../db.php';


if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 등록할 수 있습니다.'); location.href='../home.php';</script>";
    exit;
}


$ntit = trim($_POST['ntit']);
$ncontent = trim($_POST['ncontent']);
$adno = $_SESSION['adno'];


if (empty($ntit) || empty($ncontent)) {
    echo "<script>alert('제목과 내용을 입력해주세요.'); history.back();</script>";
    exit;
}


$nimg = null;
if (isset($_FILES['nimg']) && $_FILES['nimg']['error'] === UPLOAD_ERR_OK) {
    $imgTmp = $_FILES['nimg']['tmp_name'];
    $originalName = basename($_FILES['nimg']['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // 날짜+시간
    $newName = date('Ymd_His') . '.' . $ext;

    $uploadDir = '../upload/notice/';
    $imgPath = $uploadDir . $newName;


    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($imgTmp, $imgPath)) {
        $nimg = $newName;
    } else {
        echo "<script>alert('이미지 업로드에 실패했습니다.'); history.back();</script>";
        exit;
    }
}


$stmt = $conn->prepare("INSERT INTO notice (ntit, ncontent, nimg, adno) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $ntit, $ncontent, $nimg, $adno);

if ($stmt->execute()) {
    echo "<script>alert('공지사항이 등록되었습니다.'); location.href='notice.php';</script>";
} else {
    echo "<script>alert('등록 실패: " . $stmt->error . "'); history.back();</script>";
}

$stmt->close();
$conn->close();
