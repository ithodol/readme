<?php
session_start();
include '../db.php';

$sql = "SELECT n.nno, n.ntit, n.ndate, n.nview, a.adname 
        FROM notice n
        JOIN admin a ON n.adno = a.adno
        ORDER BY n.nno DESC";

$result = $conn->query($sql);
?>

<?php include '../header.php'; ?>
<div class="noticeBoard">
    <h1>📢 공지사항</h1>
    <?php if (isset($_SESSION['adno'])) { ?>
        <div class="noticeWriteBtn">
            <a href="noticeWrite.php" class="writeBtn">등록</a>
        </div>
    <?php } ?>
    <table class="noticeTable">
        <thead>
            <tr>
                <th style="width: 5%;">번호</th>
                <th style="width: 65%;">제목</th>
                <th>작성일</th>
                <th>작성자</th>
                <th>조회수</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['nno'] ?></td>
                    <td class="noticeTitle" style="text-align: left; padding:10px 20px;">
                        <a href="noticeView.php?nno=<?= $row['nno'] ?>">
                            <?= htmlspecialchars($row['ntit']) ?>
                        </a>
                    </td>
                    <td><?= substr($row['ndate'], 0, 10) ?></td>
                    <td><?= htmlspecialchars($row['adname']) ?></td>
                    <td><?= $row['nview'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>