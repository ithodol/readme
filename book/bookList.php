<?php
session_start();
include '../db.php';

if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

// ① URL 파라미터로 cno 받아오기 (없으면 전체)
$cno = isset($_GET['cno']) ? intval($_GET['cno']) : 0;

// ② 기본 SQL
$sql = "SELECT b.bno, btitle, briter, bpub, bimg, bstate, 
               SUM(i.istock) AS istock
        FROM book b
        JOIN inven i ON b.bno = i.bno";

// ③ 카테고리 조건 추가
if ($cno > 0) {
    $sql .= " WHERE b.cno = $cno";
}

// ④ 그룹핑 및 정렬
$sql .= " GROUP BY b.bno, btitle, briter, bpub, bimg, bstate
          ORDER BY bno ASC";

$resultBooks = $conn->query($sql);
?>

<?php include '../header.php'; ?>

<h1 style="text-align: center; margin:60px 0px;">📚 도서 전체 목록</h1>

<!-- 카테고리 버튼 -->
<div class="categoryButtons">
    <a href="bookList.php" <?= $cno == 0 ? 'class="active"' : '' ?>>전체</a>
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

        $currentStock = isset($bookItem['istock']) ? (int)$bookItem['istock'] : 0;

        if ($bookItem['bstate'] == 0 && $currentStock > 0) {
            echo '<hr><p class="available">대출 가능</p>';
        } elseif ($currentStock == 0) {
            echo '<hr><p class="unavailable">현재 재고가 없어 대출이 불가합니다.</p>';
        } else {
            echo '<hr><p class="unavailable">현재 대출 중입니다.</p>';
        }

        echo '</div>';
    }
} else {
    echo '<p style="text-align:center; margin-top:40px;">등록된 도서가 없습니다.</p>';
}
?>
</div>

</body>
</html>
