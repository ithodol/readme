<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

// ① URL 파라미터로 cno 받아오기 (없으면 전체)
$cno = isset($_GET['cno']) ? intval($_GET['cno']) : null;

// ② 기본 SQL (전체 도서)
$sql = "SELECT b.bno, btitle, briter, bpub, bimg, bstate 
        FROM book b";

// ③ 카테고리 필터가 있으면 조건 추가
if ($cno) {
    $sql .= " WHERE b.cno = $cno";
}

$sql .= " ORDER BY bno ASC";
$resultBooks = $conn->query($sql);
?>



<?php include '../header.php'; ?>


<h1 style="text-align: center; margin:60px 0px;">📚 도서 전체 목록</h1>

<!-- 카테고리 버튼 -->
<div class="categoryButtons">
    <a href="bookList.php" <?= !$cno ? 'class="active"' : '' ?>>전체</a>
    <?php
    $resultCats = $conn->query("SELECT cno, cname FROM category ORDER BY cno ASC");
    while ($cat = $resultCats->fetch_assoc()) {
        $active = ($cat['cno'] == $cno) ? 'class="active"' : '';
        echo '<a href="?cno=' . $cat['cno'] . '" ' . $active . '>' . htmlspecialchars($cat['cname']) . '</a> ';
    }
    ?>
</div>



<div class="bookList">
<?php
if ($resultBooks->num_rows > 0) {
    while ($bookItem = $resultBooks->fetch_assoc()) {
        echo '<div class="bookItem">';
        echo '<a href="bookView.php?bno=' . $bookItem['bno'] . '">';
        echo '<img src="../img/' . htmlspecialchars($bookItem['bimg']) . '" alt="' . htmlspecialchars($bookItem['btitle']) . '">';
        echo '<h4>' . htmlspecialchars($bookItem['btitle']) . '</h4>';
        echo '</a>';

        echo '<p>' . htmlspecialchars($bookItem['briter']) . '</p>';
        echo '<p>' . htmlspecialchars($bookItem['bpub']) . '</p>';

        if ($bookItem['bstate'] == 0) {
            echo '<hr><p class="available">대출 가능</p>';
        } else {
            echo '<hr><p class="unavailable">대출 불가</p>';
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
