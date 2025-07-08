<?php
session_start();
include '../db.php';

// 관리자 로그인 확인
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../home.php';</script>";
    exit;
}

if (!isset($_GET['bno'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='bookListAll.php';</script>";
    exit;
}

$bno = intval($_GET['bno']);

// 도서 정보 조회
$sql = "
    SELECT b.bno, b.btitle, b.briter, b.bpub, b.bimg, b.sno, b.cno,
           s.srow, s.scol
    FROM book b
    JOIN slot s ON b.sno = s.sno
    LEFT JOIN category c ON b.cno = c.cno
    WHERE b.bno = $bno
";

$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<script>alert('도서를 찾을 수 없습니다.'); location.href='bookListAll.php';</script>";
    exit;
}

// 위치, 카테고리 불러오기
$slot_result = mysqli_query($conn, "SELECT sno, srow, scol FROM slot ORDER BY sno");
$cate_result = mysqli_query($conn, "SELECT cno, cname FROM category ORDER BY cno");
?>

<?php include '../header.php'; ?>
<div class="bookUpdateWrapper">
    <h2 class="bookUpdateTitle">도서 정보 수정</h2>

    <form method="post" action="bookUpdatePro.php" enctype="multipart/form-data" class="bookUpdateForm">
        <input type="hidden" name="bno" value="<?= $book['bno'] ?>">

        <div class="updateField">
            <label for="btitle">도서명</label>
            <input type="text" id="btitle" name="btitle" value="<?= htmlspecialchars($book['btitle']) ?>" required>
        </div>

        <div class="updateField">
            <label for="briter">저자</label>
            <input type="text" id="briter" name="briter" value="<?= htmlspecialchars($book['briter']) ?>" required>
        </div>

        <div class="updateField">
            <label for="bpub">출판사</label>
            <input type="text" id="bpub" name="bpub" value="<?= htmlspecialchars($book['bpub']) ?>" required>
        </div>

        <div class="updateField">
            <label for="sno">위치</label>
            <select name="sno" id="sno" required>
                <?php while ($row = mysqli_fetch_assoc($slot_result)): ?>
                    <option value="<?= $row['sno'] ?>" <?= $book['sno'] == $row['sno'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['srow'] . '-' . $row['scol']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="updateField">
            <label for="cno">카테고리</label>
            <select name="cno" id="cno" required>
                <option value="">선택</option>
                <?php while ($row = mysqli_fetch_assoc($cate_result)): ?>
                    <option value="<?= $row['cno'] ?>" <?= $book['cno'] == $row['cno'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['cname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="updateField">
            <label for="bimg">이미지</label>
            <input type="file" name="bimg" id="bimg">
            <small>※ 새로운 이미지를 선택하지 않으면 기존 이미지가 유지됩니다.</small>
        </div>

        <div class="bookUpdateBtnGroup">
            <button type="submit" class="updateBtn">수정하기</button>
            <button type="button" class="updateBtn updateBack">
                <a href="bookListAll">취소</a>
            </button>
        </div>
    </form>

</div>
</body>
</html>
