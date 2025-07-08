<?php
session_start();
include '../db.php';

$btitle = $_POST['btitle'];
$briter = $_POST['briter'];
$bpub = $_POST['bpub'];
$sno = $_POST['sno'];
$cno = $_POST['cno'];

if (!$btitle || !$briter || !$bpub || !$sno || !$cno) {
    echo "<script>alert('모든 필수 항목을 입력해주세요.'); history.back();</script>";
    exit;
}

// 이미지 업로드
$bimg = 'default.png';

if (isset($_FILES['bimg']) && $_FILES['bimg']['error'] === 0) {
    $uploadDir = '../upload/book';
    $fileName = time() . '_' . basename($_FILES['bimg']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['bimg']['tmp_name'], $targetPath)) {
        $bimg = $fileName;
    }
}

// 도서 등록
$sql = "INSERT INTO book (btitle, briter, bpub, bimg, sno, cno)
        VALUES ('$btitle', '$briter', '$bpub', '$bimg', '$sno', '$cno')";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('도서가 등록되었습니다.'); location.href='bookListAll.php';</script>";
} else {
    echo "<script>alert('도서 등록 실패: " . mysqli_error($conn) . "'); history.back();</script>";
}
?>
