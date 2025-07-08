<?php
session_start();
include '../db.php';
date_default_timezone_set('Asia/Seoul');

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 수정할 수 있습니다.'); location.href='notice.php';</script>";
    exit;
}

$nno = intval($_POST['nno']);
$ntit = trim($_POST['ntit']);
$ncontent = trim($_POST['ncontent']);

// 기존 이미지
$oldQuery = $conn->query("SELECT nimg FROM notice WHERE nno = $nno");
$old = $oldQuery->fetch_assoc();
$nimg = $old['nimg'];

// 새 이미지
if (isset($_FILES['nimg']) && $_FILES['nimg']['error'] === UPLOAD_ERR_OK) {
    $imgTmp = $_FILES['nimg']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['nimg']['name'], PATHINFO_EXTENSION));
    $newName = date('Ymd_His') . '.' . $ext;
    $uploadDir = '../upload/notice/';
    $imgPath = $uploadDir . $newName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($imgTmp, $imgPath)) {
        $nimg = $newName;
    } else {
        echo "<script>alert('이미지 업로드 실패'); history.back();</script>";
        exit;
    }
}

$stmt = $conn->prepare("UPDATE notice SET ntit=?, ncontent=?, nimg=? WHERE nno=?");
$stmt->bind_param("sssi", $ntit, $ncontent, $nimg, $nno);

if ($stmt->execute()) {
    echo "<script>alert('공지사항이 수정되었습니다.'); location.href='noticeView.php?nno=$nno';</script>";
} else {
    echo "<script>alert('수정 실패: " . $stmt->error . "'); history.back();</script>";
}

$stmt->close();
$conn->close();
