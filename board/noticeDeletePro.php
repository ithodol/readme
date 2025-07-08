<?php
session_start();
include '../db.php';


if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 삭제할 수 있습니다.'); location.href='notice.php';</script>";
    exit;
}

$nno = isset($_GET['nno']) ? intval($_GET['nno']) : 0;

if ($nno === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='notice.php';</script>";
    exit;
}


$sql = "SELECT nimg FROM notice WHERE nno = $nno";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $nimg = $row['nimg'];

    if (!empty($nimg)) {
        $filePath = "../upload/notice/" . $nimg;
        if (file_exists($filePath)) {
            unlink($filePath); // 이미지 삭제
        }
    }

    $conn->query("DELETE FROM notice WHERE nno = $nno");
    echo "<script>alert('공지사항이 삭제되었습니다.'); location.href='notice.php';</script>";
} else {
    echo "<script>alert('존재하지 않는 공지입니다.'); location.href='notice.php';</script>";
}
