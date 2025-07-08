<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 수정할 수 있습니다.'); location.href='notice.php';</script>";
    exit;
}

$nno = isset($_GET['nno']) ? intval($_GET['nno']) : 0;

$sql = "SELECT * FROM notice WHERE nno = $nno";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "<script>alert('존재하지 않는 공지사항입니다.'); location.href='notice.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
?>

<?php include '../header.php'; ?>
<link rel="stylesheet" href="../css/notice.css">

<div class="noticeFormContainer">
    <h1>공지사항 수정</h1>
    <form action="noticeUpdatePro.php" method="post" enctype="multipart/form-data" class="noticeForm">
        <input type="hidden" name="nno" value="<?= $nno ?>">

        <label for="ntit">제목</label>
        <input type="text" id="ntit" name="ntit" value="<?= htmlspecialchars($row['ntit']) ?>" required>

        <label for="ncontent">내용</label>
        <textarea id="ncontent" name="ncontent" rows="8" required><?= htmlspecialchars($row['ncontent']) ?></textarea>

        <?php if ($row['nimg']): ?>
            <p>현재 이미지: <strong><?= htmlspecialchars($row['nimg']) ?></strong></p>
        <?php endif; ?>
        <label for="nimg">이미지 (선택)</label>
        <input type="file" id="nimg" name="nimg" accept="image/*">
        <small>※ 이미지를 새로 선택하지 않으면 기존 이미지가 유지됩니다.</small>

        <div class="formBtnGroup">
            <button type="submit" class="submitBtn">수정하기</button>
            <a href="notice.php" class="cancelBtn">취소</a>
        </div>
    </form>
</div>
</body>
</html>
