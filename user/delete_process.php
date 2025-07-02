<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../home.php';</script>";
    exit;
}

// 자바스크립트 confirm 창 띄우기
echo "<script>
    if (confirm('정말 탈퇴하시겠습니까? 탈퇴 후에는 복구가 불가능합니다.')) {
        // PHP로 넘어와서 탈퇴 처리할 수 있도록 폼 제출
        window.location.href = 'delete_confirm.php';
    } else {
        history.back();
    }
</script>";
?>
