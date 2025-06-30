<?php
session_start();
?>

<?php if (isset($_SESSION['adno'])): ?>
    <h2>📚 <?= htmlspecialchars($_SESSION['uname']) ?>님의 대시보드</h2>
    <ul>
        <li><a href="">회원관리</a></li>
        <li><a href="">내 정보</a></li>
        <li><a href="user/logout.php">로그아웃</a></li>
    </ul>
<?php else: ?>
    <h2>환영합니다, 방문자님!</h2>
    <p><a href="user/login.php">로그인</a> 후 서비스를 이용하세요.</p>
    <p><a href="admin/login.php">관리자 로그인</a></p>
<?php endif; ?>
