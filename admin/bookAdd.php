<?php
session_start();
include '../db.php';

// 관리자 체크
if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='../home.php';</script>";
    exit;
}

// 위치, 카테고리 불러오기
$slot_result = mysqli_query($conn, "SELECT sno, srow, scol FROM slot ORDER BY sno");

$cate_result = mysqli_query($conn, "SELECT cno, cname FROM category ORDER BY cno");
?>

<?php include '../header.php'; ?>
<div class="bookAddWrapper">
    <h2 class="bookAddTitle">신규 도서 등록</h2>

    <form method="post" action="bookAddPro.php" enctype="multipart/form-data" class="bookAddForm">
        <div class="bookAddField">
            <label for="btitle">도서명</label>
            <input type="text" name="btitle" id="btitle" required>
        </div>

        <div class="bookAddField">
            <label for="briter">저자</label>
            <input type="text" name="briter" id="briter" required>
        </div>

        <div class="bookAddField">
            <label for="bpub">출판사</label>
            <input type="text" name="bpub" id="bpub" required>
        </div>

        <div class="bookAddField">
            <label for="sno">위치</label>
            <select name="sno" id="sno" required>
                <option value="">선택</option>
                <?php while($row = mysqli_fetch_assoc($slot_result)): ?>
                    <option value="<?= $row['sno'] ?>">
                        <?= htmlspecialchars($row['srow'] . '-' . $row['scol']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="bookAddField">
            <label for="cno">카테고리</label>
            <select name="cno" id="cno" required>
                <option value="">선택</option>
                <?php while($row = mysqli_fetch_assoc($cate_result)): ?>
                    <option value="<?= $row['cno'] ?>"><?= $row['cname'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="bookAddField">
            <label for="bimg">도서 이미지</label>
            <input type="file" name="bimg" id="bimg">
        </div>

        <div class="bookAddBtnGroup">
            <button type="submit" class="bookAddBtn">도서 등록</button>
            <button type="button" class="bookAddBtn bookAddBack">
                <a href="bookListAll">취소</a>
            </button>
        </div>
    </form>
</div>
</body>
</html>
