<?php
session_start();
include '../db.php';

// 관리자 로그인 여부 확인
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../home.php';</script>";
    exit;
}
?>

<?php include '../header.php'; ?>

<div class="noticeFormContainer">
    <h1>공지사항 등록</h1>
    <form action="noticeWritePro.php" method="post" enctype="multipart/form-data" class="noticeForm">
        <label for="ntit">제목</label>
        <input type="text" id="ntit" name="ntit" required>

        <label for="ncontent">내용</label>
        <textarea id="ncontent" name="ncontent" rows="8" required></textarea>

        <label for="nimg">이미지 (선택)</label>
        <input type="file" id="nimg" name="nimg" accept="image/*">

        <div class="formBtnGroup">
            <button type="submit" class="submitBtn">등록</button>
            <a href="notice.php" class="cancelBtn">취소</a>
        </div>
    </form>
</div>
</body>
</html>
