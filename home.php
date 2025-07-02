<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>도서관리 홈</title>
</head>
<body>

<h1>📚 READ ME</h1>

<?php
// 로그인 상태 표시
if (isset($_SESSION['adno'])) {
    echo "<p><strong>" . htmlspecialchars($_SESSION['adname']) . "</strong>님 환영합니다. <a href='admin/logout.php'>로그아웃</a> | <a href='admin/admin_mypage.php'>관리자 전용</a></p>";
} else if (isset($_SESSION['uno'])) {
    echo "<p><strong>" . htmlspecialchars($_SESSION['uname']) . "</strong>님 환영합니다. <a href='user/logout.php'>로그아웃</a> | <a href='user/mypage.php'>마이페이지</a></p>";
} else {
    echo "<p><a href='user/login.php'>회원 로그인</a> | <a href='admin/login.php'>관리자 로그인</a></p>";
}
?>

<hr>

<!-- 🔍 검색 기능 -->
<h2>🔍 도서 검색</h2>
<form method="get" action="search.php">
    <input type="text" name="keyword" placeholder="도서명 또는 저자 검색" required>
    <button type="submit">검색하기</button>
</form>

<hr>

<!-- 🆕 신착 도서 -->
<h2>🆕 신착 도서</h2>
<?php
$recent_books = $conn->query("SELECT btitle, briter, bimg FROM book ORDER BY bno DESC LIMIT 5");

if ($recent_books->num_rows > 0) {
    echo "<ul>";
    while ($book = $recent_books->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($book['btitle']) . "</strong> - " . htmlspecialchars($book['briter']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>신착 도서가 없습니다.</p>";
}
?>

<hr>

<!-- 📢 공지사항
<h2>📢 공지사항</h2>
<?php
$notices = $conn->query("SELECT nno, ntitle, ndate FROM notice ORDER BY ndate DESC LIMIT 3");

if ($notices && $notices->num_rows > 0) {
    echo "<ul>";
    while ($notice = $notices->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($notice['ndate']) . " - <strong>" . htmlspecialchars($notice['ntitle']) . "</strong></li>";
    }
    echo "</ul>";
} else {
    echo "<p>등록된 공지사항이 없습니다.</p>";
}
?> -->

</body>
</html>
