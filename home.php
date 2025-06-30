<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>홈</title>
</head>
<body>

<?php
// ✅ 관리자 로그인 상태
if (isset($_SESSION['adno'])) {
    echo "<h2>📢 관리자님, 환영합니다!</h2>";
    echo "<p><strong>" . htmlspecialchars($_SESSION['adname']) . "</strong>님이 로그인하셨습니다.</p>";
    echo "<p><a href='admin/logout.php'>로그아웃</a></p>";
    echo "<hr>";
    echo "<p>관리자 전용 메뉴를 여기에 추가하세요.</p>";

// ✅ 회원 로그인 상태
} else if (isset($_SESSION['uno'])) {
    echo "<h2>📚 " . htmlspecialchars($_SESSION['uname']) . "님 환영합니다!</h2>";
    echo "<p><a href='user/logout.php'>로그아웃</a></p>";
    echo "<hr>";
    echo "<p>도서 대출, 반납, 조회 등의 기능을 사용할 수 있습니다.</p>";

// ✅ 비로그인 상태
} else {
    echo "<h2>환영합니다!</h2>";
    echo "<p><a href='user/login.php'>회원 로그인</a> | <a href='admin/login.php'>관리자 로그인</a></p>";
    echo "<hr>";
    echo "<p>로그인 후 다양한 서비스를 이용하실 수 있습니다.</p>";
}
?>

</body>
</html>
