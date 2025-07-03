<?php
session_start();
include '../db.php';

// 로그인 체크
if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

// 도서 리스트 조회 (모든 도서)
$sql = "SELECT bno, btitle, briter, bpub, bimg, bstate FROM book ORDER BY bno ASC";
$resultBooks = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<h2>📚 도서 리스트 - 대출 신청</h2>
<p><a href="../home.php">홈</a> </p>

<div class="bookList">
<?php
if ($resultBooks->num_rows > 0) {
    while ($bookItem = $resultBooks->fetch_assoc()) {
        echo '<div class="bookItem">';
        // 도서 이미지 + 제목 클릭 시 상세 페이지로 이동
        echo '<a href="bookView.php?bno=' . $bookItem['bno'] . '">';
        echo '<img src="../img/' . htmlspecialchars($bookItem['bimg']) . '" alt="' . htmlspecialchars($bookItem['btitle']) . '">';
        echo '<h4>' . htmlspecialchars($bookItem['btitle']) . '</h4>';
        echo '</a>';

        echo '<p>저자: ' . htmlspecialchars($bookItem['briter']) . '</p>';
        echo '<p>출판사: ' . htmlspecialchars($bookItem['bpub']) . '</p>';

        if ($bookItem['bstate'] == 0) {
            echo '<form method="post" action="loanRequestProcess.php">';
            echo '<input type="hidden" name="bno" value="' . $bookItem['bno'] . '">';
            echo '<button type="submit">📖 대출 신청</button>';
            echo '</form>';
        } else {
            echo '<p class="unavailable">대출 중</p>';
        }

        echo '</div>';
    }
} else {
    echo '<p>등록된 도서가 없습니다.</p>';
}
?>
</div>


</body>
</html>
