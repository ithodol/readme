<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../home.php';</script>";
    exit;
}

$bno     = intval($_POST['bno']);
$btitle  = $conn->real_escape_string($_POST['btitle']);
$briter  = $conn->real_escape_string($_POST['briter']);
$bpub    = $conn->real_escape_string($_POST['bpub']);
$sno     = intval($_POST['sno']);
$cno     = intval($_POST['cno']); 

// 이미지
$imgName = '';
if (isset($_FILES['bimg']) && $_FILES['bimg']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['bimg']['tmp_name'];
    $originName = basename($_FILES['bimg']['name']);
    $imgExt = strtolower(pathinfo($originName, PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($imgExt, $allowedExt)) {
        $imgName = uniqid('book_') . '.' . $imgExt;
        move_uploaded_file($tmpName, "../upload/book/" . $imgName);
    } else {
        echo "<script>alert('지원하지 않는 이미지 형식입니다.'); history.back();</script>";
        exit;
    }

    // 이미지 업데이트
    $sql = "UPDATE book SET btitle='$btitle', briter='$briter', bpub='$bpub', sno=$sno, cno=$cno, bimg='$imgName' WHERE bno=$bno";
} else { // 이미지 없으면
    $sql = "UPDATE book SET btitle='$btitle', briter='$briter', bpub='$bpub', sno=$sno, cno=$cno WHERE bno=$bno";
}

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('도서 정보가 수정되었습니다.'); location.href='bookListAll.php';</script>";
} else {
    echo "<script>alert('수정에 실패했습니다.'); history.back();</script>";
}
?>
