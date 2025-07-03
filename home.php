<?php
session_start();
include 'db.php';
?>

<?php include 'header.php'; ?>

<!-- 🔍 검색 기능 -->
<h2>🔍 도서 검색</h2>
<form method="get" action="search.php">
    <input type="text" name="keyword" placeholder="도서명 또는 저자 검색" required>
    <button type="submit">검색하기</button>
</form>

<hr>

<!-- 🆕 신착 도서 -->
<!-- <h2 class="sectionTitle">🆕 신착 도서</h2>

<div class="bookList">
<?php
$recentBooks = $conn->query("SELECT btitle, briter, bimg FROM book ORDER BY bno DESC LIMIT 5");

if ($recentBooks->num_rows > 0) {
    while ($book = $recentBooks->fetch_assoc()) {
        echo '<div class="bookItem">';
        echo '<img src="img/' . htmlspecialchars($book['bimg']) . '" alt="' . htmlspecialchars($book['btitle']) . '">';
        echo '<div><strong>' . htmlspecialchars($book['btitle']) . '</strong></div>';
        echo '<div style="font-size: 0.9em; color: #666;">' . htmlspecialchars($book['briter']) . '</div>';
        echo '</div>';
    }
} else {
    echo "<p>신착 도서가 없습니다.</p>";
}
?>
</div> -->


<!-- 📖 대출 가능 도서 -->

<?php
// 대출 가능 도서 4권 조회
$data = "SELECT bno, btitle, briter, bimg FROM book WHERE bstate = 0 ORDER BY bno DESC LIMIT 4";
$result = $conn->query($data);
?>

<div>
    <div class="sectionTitle">📖 대출 가능 도서</div>
    <div class="bookList">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($book = $result->fetch_assoc()): ?>
                <div class="bookItem">
                    <img src="img/<?php echo htmlspecialchars($book['bimg']); ?>" alt="<?php echo htmlspecialchars($book['btitle']); ?>" />
                    <div><?php echo htmlspecialchars($book['btitle']); ?></div>
                    <div style="font-size: 0.9em; color: #666;"><?php echo htmlspecialchars($book['briter']); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>대출 가능 도서가 없습니다.</p>
        <?php endif; ?>
    </div>
    <a href="./book/bookList.php" class="moreButton">더 보기 →</a>
</div>

<!-- 공지사항 -->
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
?>

</body>
</html>
