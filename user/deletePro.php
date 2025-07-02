<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../home.php';</script>";
    exit;
}

echo "<script>
    if (confirm('정말 탈퇴하시겠습니까? 탈퇴 후에는 복구가 불가능합니다.')) {
        window.location.href = 'delete_confirm.php';
    } else {
        history.back();
    }
</script>";
?>
