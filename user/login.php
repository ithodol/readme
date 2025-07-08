<?php include '../header.php'; ?>
<div class="loginBox">
    <div class="loginCont">
        <h2 class="loginTit">로그인</h2>
        <form method="post" action="loginPro.php" class="loginForm">
            <label class="loginLabel">아이디
                <input type="text" name="uid" class="loginInput" required>
            </label><br><br>
            <label class="loginLabel">비밀번호
                <input type="password" name="upwd" class="loginInput" required>
            </label><br><br>
            
            <input type="submit" value="로그인" class="loginBtn"><br>
            <a href="/readme/user/join.php" class="loginLink" >회원가입</a>
            <a href="/readme/admin/login.php" class="loginLink" style="margin-top: 0px;">관리자 로그인</a>

        </form>
    </div>
</div>
</body>
</html>
