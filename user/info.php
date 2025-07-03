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

<h1>📖 마이페이지</h1>

<hr>

<!-- 사용자 정보 -->
<h2>👤 내 정보</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr><th>아이디</th><td><?php echo htmlspecialchars($user['uid']); ?></td></tr>
    <tr><th>이름</th><td><?php echo htmlspecialchars($user['uname']); ?></td></tr>
    <tr><th>전화번호</th><td><?php echo htmlspecialchars($user['uphone']); ?></td></tr>
</table>
<br>
<form action="update.php" method="get">
    <button type="submit">✏️ 수정하기</button>
</form>
<form action="deletePro.php" method="post">
    <button type="submit">회원 탈퇴</button>
</form>

<hr>

<!-- 대출 현황 -->
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

if ($result->num_rows > 0) {
    echo "<h2>📚 대출 현황</h2>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<thead><tr>";
    echo "<th>도서명</th><th>저자</th><th>대출일</th><th>반납예정일</th><th>상태</th><th>반납</th>";
    echo "</tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['btitle']) . "</td>";
        echo "<td>" . htmlspecialchars($row['briter']) . "</td>";
        echo "<td>" . htmlspecialchars($row['ldate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['lddate']) . "</td>";
        echo "<td>" . ($row['lstate'] == 0 ? '대출중' : '반납완료') . "</td>";
        echo "<td>";
        echo "<form method='post' action='returnBook.php' style='margin:0;'>";
        echo "<input type='hidden' name='lno' value='" . $row['lno'] . "'>";
        echo "<button type='submit'>반납하기</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>현재 대출 중인 도서가 없습니다.</p>";
}
?>

</body>
</html>
