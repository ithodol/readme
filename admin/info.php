<?php include '../header.php'; ?>

<div class="adminPageCon">
  <h1 class="adminTitle">👮 관리자 전용</h1>

  <div class="adminButtonContainer">
    <form action="loanList.php" method="get">
        <button type="submit" class="adminBtn">대출 관리</button>
    </form>
    <form action="userList.php" method="get">
        <button type="submit" class="adminBtn">회원 관리</button>
    </form>
    <form action="bookListAll.php" method="get">
        <button type="submit" class="adminBtn">도서 관리</button>
    </form>
    <form action="../board/notice.php" method="get">
        <button type="submit" class="adminBtn">공지사항 관리</button>
    </form>
  </div>
</div>

</body>
</html>
