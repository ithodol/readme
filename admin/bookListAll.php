<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}

// 도서 목록 조회
$sql = "
    SELECT 
        b.bno, b.btitle, b.briter, b.bpub, b.bimg,
        s.srow, s.scol,
        c.cname,
        IFNULL(SUM(CASE WHEN i.itype = 0 THEN i.icount ELSE -i.icount END), 0) AS stock
    FROM book b
    JOIN slot s ON b.sno = s.sno
    LEFT JOIN category c ON b.cno = c.cno
    LEFT JOIN inven i ON b.bno = i.bno
    GROUP BY b.bno
    ORDER BY b.bno DESC
";
$result = mysqli_query($conn, $sql);
?>

<?php include '../header.php'; ?>
<div class="bookListWrapper">
    <h1 class="bookListTitle">도서 전체 목록</h1>

    <div class="bookListMenu">
        <a href="bookAdd.php" class="bookListMenuLink active">신규 도서 등록</a>
        <a href="invenList.php" class="bookListMenuLink">입출고 관리</a>
    </div>

    <table class="bookListTable">
        <thead>
            <tr>
                <th>번호</th>
                <th>표지</th>
                <th>도서명</th>
                <th>저자</th>
                <th>출판사</th>
                <th>위치</th>
                <th>카테고리</th>
                <th>재고</th>
                <th>관리</th>
            </tr>
        </thead>
        <!-- 테이블 내용 -->
        <tbody>
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $imgSrc = $row['bimg'] ? "../img/" . htmlspecialchars($row['bimg']) : "../img/default.png";
                $location = htmlspecialchars($row['srow'] . '-' . $row['scol']);
                $category = $row['cname'] ? htmlspecialchars($row['cname']) : '-';
                $stock = (int)$row['stock'];

                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td><img src='{$imgSrc}' alt='도서 이미지' class='bookListImage'></td>";
                echo "<td>" . htmlspecialchars($row['btitle']) . "</td>";
                echo "<td>" . htmlspecialchars($row['briter']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bpub']) . "</td>";
                echo "<td>" . $location . "</td>";
                echo "<td>" . $category . "</td>";
                echo "<td>" . $stock . "</td>";

                echo "<td class='bookManageCell'>";
                echo "<div class='bookEditBtnBox'>";

                echo "<a href='bookUpdate.php?bno={$row['bno']}' class='bookUpdateBtn'>수정</a> ";

                if ($stock === 0) {
                    echo "<a href='bookDelete.php?bno={$row['bno']}' class='bookDeleteBtn' onclick='return confirm(\"정말 삭제하시겠습니까?\")'>삭제</a>";
                } else {
                    echo "<a href='#' class='bookDeleteBtn disabled' onclick='alert(\"재고가 존재합니다. 삭제할 수 없습니다.\"); return false;'>삭제</a>";
                }

                echo "</div></td></tr>";

                $no++;
            }
        } else {
            echo "<tr><td colspan='9'>등록된 도서가 없습니다.</td></tr>";
        }
        ?>
        </tbody>

    </table>

</div>
    
</body>
</html>
