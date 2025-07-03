<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../home.php';</script>";
    exit;
}

$uno = $_SESSION['uno'];
$sql = "SELECT uid, uname, uphone FROM user WHERE uno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uno);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<?php include '../header.php'; ?>

<h2>👤 회원 정보 수정</h2>
<form action="updatePro.php" method="post">
    <input type="hidden" name="uno" value="<?php echo $uno; ?>">

    <p>아이디: <strong><?php echo htmlspecialchars($user['uid']); ?></strong></p>

    <p>이름: <input type="text" name="uname" value="<?php echo htmlspecialchars($user['uname']); ?>" required></p>

    <p>전화번호: <input type="text" name="uphone" value="<?php echo htmlspecialchars($user['uphone']); ?>" required></p>

    <p>새 비밀번호: <input type="password" name="upwd" placeholder="변경 시에만 입력"></p>

    <button type="submit">수정 완료</button>
    <button type="button"><a href="info.php">취소</a></button>
    
</form>

</body>
</html>
