<?php include '../header.php'; ?>

<div class="joinBox">
    <div class="joinCont">
        <h2 class="joinTit">회원가입</h2>
        <form method="post" action="joinPro.php">
            <label class="joinLabel">아이디</label><br>
            <input type="text" name="uid" required class="joinInput"><br><br>

            <label class="joinLabel">비밀번호</label><br>
            <input type="password" name="upwd" required class="joinInput"><br><br>

            <label class="joinLabel">이름</label><br>
            <input type="text" name="uname" required class="joinInput"><br><br>

            <label class="joinLabel">연락처</label><br>
            <input type="text" name="uphone" placeholder="010-1234-5678" required class="joinInput"><br><br>

            <button type="submit" class="joinBtn">회원가입</button>
        </form>
    </div>
</div>



</body>
</html>
