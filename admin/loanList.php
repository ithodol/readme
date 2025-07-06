<?php
session_start();
include '../db.php';

// 관리자 체크
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../home.php';</script>";
    exit;
}

// 대출 전체 내역 조회
$sql = "
    SELECT l.lno, l.ldate, l.lddate, l.lrdate, l.lstate,
           u.uid, u.uname,
           b.bno, b.btitle, b.briter
    FROM loan l
    JOIN user u ON l.uno = u.uno
    JOIN book b ON l.bno = b.bno
    ORDER BY l.ldate DESC
";

$result = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<div class="loanListBox">
    <h1>📚 전체 대출 관리</h1>
    <hr>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="userTable" border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th>회원 ID</th>
                    <th>회원 이름</th>
                    <th>도서명</th>
                    <th>저자</th>
                    <th>대출일</th>
                    <th>반납예정일</th>
                    <th>반납일</th>
                    <th>상태</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['uid']) ?></td>
                    <td><?= htmlspecialchars($row['uname']) ?></td>
                    <td><a href="../book/bookView.php?bno=<?= (int)$row['bno'] ?>"><?= htmlspecialchars($row['btitle']) ?></a></td>
                    <td><?= htmlspecialchars($row['briter']) ?></td>
                    <td><?= htmlspecialchars($row['ldate']) ?></td>
                    <td><?= htmlspecialchars($row['lddate']) ?></td>
                    <td><?= $row['lrdate'] ? htmlspecialchars($row['lrdate']) : '-' ?></td>
                    <td><?= $row['lstate'] ? '반납 완료' : '<span style="color:red;">대출 중</span>' ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>대출 내역이 없습니다.</p>
    <?php endif; ?>
</div>

</body>
</html>
