<?php
session_start();
include '../db.php';

// 관리자 로그인 체크
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

// 회원 목록 조회 (모든 회원 포함)
$sql = "SELECT uno, uid, uname, uphone, ustate, udelete FROM user ORDER BY uno ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원 관리 - 회원 리스트</title>
</head>
<body>

<h2>회원 관리 - 회원 리스트</h2>
<p><a href="info.php">관리자 마이페이지</a> | <a href="../admin/logout.php">로그아웃</a></p>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>번호</th>
            <th>아이디</th>
            <th>이름</th>
            <th>전화번호</th>
            <th>대출가능여부</th>
            <th>탈퇴여부</th>
            <th>탈퇴 처리</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['uno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uid']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['uphone']) . "</td>";
                echo "<td>" . ($row['ustate'] ? 'X' : 'O') . "</td>";
                echo "<td>" . ($row['udelete'] ? '탈퇴' : '회원') . "</td>";
                echo "<td>";
                echo "<form method='post' action='deleteUser.php' onsubmit=\"return confirm('정말 이 회원을 탈퇴시키겠습니까?');\">";
                echo "<input type='hidden' name='uno' value='" . $row['uno'] . "'>";
                echo "<button type='submit'>탈퇴</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>회원이 없습니다.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>