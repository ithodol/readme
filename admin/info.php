<?php
session_start();

// 관리자만 접근 가능
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능한 페이지입니다.'); location.href='../home.php';</script>";
    exit;
}
?>

<?php include '../header.php'; ?>

<h1>👮 관리자 전용 페이지</h1>

<div class="adminButtonContainer">
    <form action="loanList.php" method="get">
        <button type="submit" class="adminBtn">대출 관리</button>
    </form>
    <form action="userList.php" method="get">
        <button type="submit" class="adminBtn">회원 관리</button>
    </form>
    <form action="invenList.php" method="get">
        <button type="submit" class="adminBtn">📚 도서관리</button>
    </form>
</div>

</body>
</html>
