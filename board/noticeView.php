<?php
session_start();
include '../db.php';

// 공지 번호(nno) 받기
$nno = isset($_GET['nno']) ? intval($_GET['nno']) : 0;

if ($nno === 0) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='notice.php';</script>";
    exit;
}

// 조회수 증가
$conn->query("UPDATE notice SET nview = nview + 1 WHERE nno = $nno");

// 공지 내용 가져오기
$sql = "SELECT n.ntit, n.ncontent, n.nimg, n.nview, n.ndate, a.adname 
        FROM notice n
        JOIN admin a ON n.adno = a.adno
        WHERE n.nno = $nno";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "<script>alert('존재하지 않는 공지사항입니다.'); location.href='notice.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
?>

<?php include '../header.php'; ?>
<div class="noticeView">
    <h1 class="noticeViewTitle"><?= htmlspecialchars($row['ntit']) ?></h1>
    <div class="noticeMeta">
        <span>작성자 : <?= htmlspecialchars($row['adname']) ?></span> &nbsp;&nbsp;|&nbsp;&nbsp;
        <span>작성일 : <?= substr($row['ndate'], 0, 10) ?></span> &nbsp;&nbsp;|&nbsp;&nbsp;
        <span>조회수 : <?= $row['nview'] ?></span>
    </div>
    <hr style="margin: 30px 0px;">
    <div class="noticeContent">
        <?= nl2br(htmlspecialchars($row['ncontent'])) ?>
        <?php if (!empty($row['nimg'])): ?>
            <div class="noticeImage">
                <img src="../upload/notice/<?= htmlspecialchars($row['nimg']) ?>" alt="공지 이미지">
            </div>
        <?php endif; ?>
    </div>

    <div class="noticeBack">
        <a href="notice.php" class="backBtn">목록</a>
        <br>
        <?php if (isset($_SESSION['adno'])): ?>
            <a href="noticeEdit.php?nno=<?= $nno ?>" class="editBtn">수정</a>
            <a href="noticeDelete.php?nno=<?= $nno ?>" class="deleteBtn" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
        <?php endif; ?>
    </div>
</div>