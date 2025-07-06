<?php
session_start();
include '../db.php';

// 로그인한 회원만 접근 가능
if (!isset($_SESSION['uno'])) {
    echo "<script>alert('회원만 접근 가능합니다. 로그인 후 이용해주세요.'); location.href='../home.php';</script>";
    exit;
}

// 사용자 정보 조회
$uno = $_SESSION['uno'];
$sql_user = "SELECT uid, uname, uphone FROM user WHERE uno = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $uno);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
?>

<?php include '../header.php'; ?>

<div class="userInfoCon">
    <h1 class="pageTitle">📖 마이페이지</h1>

    <!-- 사용자 정보 -->
    <h2>👤 내 정보</h2>
    <table class="userTable" border="1" cellpadding="5" cellspacing="0">
        <tr><th>아이디</th><td><?= htmlspecialchars($user['uid']) ?></td></tr>
        <tr><th>이름</th><td><?= htmlspecialchars($user['uname']) ?></td></tr>
        <tr><th>전화번호</th><td><?= htmlspecialchars($user['uphone']) ?></td></tr>
    </table>
    <br>
    <div class="formBox">
        <form action="update.php" method="get">
            <button class="infoUpdateBtn" type="submit">수정하기</button>
        </form>
        <form action="deletePro.php" method="post">
            <button class="infoDeleteBtn" type="submit">회원탈퇴</button>
        </form>
    </div>

    <hr>

    <h2 class="sectionTitle">📚 대출 중인 도서</h2>    
    <?php
    $sql = "
        SELECT loan.lno, book.bno, book.btitle, book.briter, loan.ldate, loan.lddate, loan.lstate
        FROM loan
        JOIN book ON loan.bno = book.bno
        WHERE loan.uno = ? AND loan.lstate = 0
        ORDER BY loan.ldate DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $uno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): ?>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>도서명</th>
                    <th>저자</th>
                    <th>대출일</th>
                    <th>반납예정일</th>
                    <th>상태</th>
                    <th>반납</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <a href="../book/bookView.php?bno=<?= $row['bno'] ?>" class="link">
                            <?= htmlspecialchars($row['btitle']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($row['briter']) ?></td>
                    <td><?= htmlspecialchars($row['ldate']) ?></td>
                    <td><?= htmlspecialchars($row['lddate']) ?></td>
                    <td><?= $row['lstate'] == 0 ? '대출중' : '반납완료' ?></td>
                    <td>
                        <form method="post" action="/readme/book/returnBookPro.php" onsubmit="return confirm('반납하시겠습니까?');">
                        <input type="hidden" name="lno" value="<?= $row['lno'] ?>">
                        <button type="submit" class="rBook">반납하기</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>현재 대출 중인 도서가 없습니다.</p>
    <?php endif; ?>

    <hr>
    
    <h2>📚 반납 완료 도서</h2>
    <?php
    $sqlHistory = "
        SELECT loan.lno, book.bno, book.btitle, book.briter, loan.ldate, loan.lddate, loan.lrdate
        FROM loan
        JOIN book ON loan.bno = book.bno
        WHERE loan.uno = ? AND loan.lstate = 1
        ORDER BY loan.lrdate DESC
    ";

    $stmtHistory = $conn->prepare($sqlHistory);
    $stmtHistory->bind_param("i", $uno);
    $stmtHistory->execute();
    $resultHistory = $stmtHistory->get_result();

    if ($resultHistory->num_rows > 0): ?>
        
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>도서명</th>
                    <th>저자</th>
                    <th>대출일</th>
                    <th>반납예정일</th>
                    <th>반납일</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $resultHistory->fetch_assoc()): ?>
                <tr>
                    <td><a href="../book/bookView.php?bno=<?= $row['bno'] ?>"><?= htmlspecialchars($row['btitle']) ?></a></td>
                    <td><?= htmlspecialchars($row['briter']) ?></td>
                    <td><?= htmlspecialchars($row['ldate']) ?></td>
                    <td><?= htmlspecialchars($row['lddate']) ?></td>
                    <td><?= htmlspecialchars($row['lrdate']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>반납 완료된 도서 내역이 없습니다.</p>
    <?php endif; ?>
</div>

</body>
</html>