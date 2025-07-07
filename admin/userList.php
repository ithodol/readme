<?php
session_start();
include '../db.php';


if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

$sql = "SELECT uno, uid, uname, uphone, ustate, udelete FROM user ORDER BY uno ASC";
$result = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<div class="userListBox">
    <h2 class="userListTitle">👥 회원 관리</h2>
    <table class="userTableAdmin">
        <thead>
            <tr>
                <th>번호</th>
                <th>아이디</th>
                <th>이름</th>
                <th>전화번호</th>
                <th>대출 가능</th>
                <th>탈퇴 여부</th>
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
                echo "<td class='" . ($row['ustate'] ? "userStateInactive" : "userStateActive") . "'>" . ($row['ustate'] ? 'X' : 'O') . "</td>";
                echo "<td>" . ($row['udelete'] ? '<span class="userDeleted">탈퇴</span>' : '회원') . "</td>";
                echo "<td>";
                echo "<form method='post' action='deleteUser.php' onsubmit=\"return confirm('정말 이 회원을 탈퇴시키겠습니까?');\">";
                echo "<input type='hidden' name='uno' value='" . $row['uno'] . "'>";
                echo "<button type='submit' class='userDeleteBtn'>탈퇴</button>";
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
</div>


</body>
</html>