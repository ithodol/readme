<?php
session_start();
include '../db.php';

if (!isset($_SESSION['adno'])) {
    echo "<script>alert('관리자 로그인 후 이용하세요.'); location.href='../admin/login.php';</script>";
    exit;
}


$filterDate = isset($_GET['date']) ? $_GET['date'] : '';
$dateValid = preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterDate);

$sql = "
    SELECT i.ino, i.itype, i.icount, i.istock, i.idate, i.imemo,
           b.bno, b.btitle,
           a.adname
    FROM inven i
    JOIN book b ON i.bno = b.bno
    LEFT JOIN admin a ON i.adno = a.adno
";


if ($dateValid) {
    $sql .= " WHERE DATE(i.idate) = ? ";
}

$sql .= " ORDER BY i.idate DESC";  


$stmt = $conn->prepare($sql);


if ($dateValid) {
    $stmt->bind_param("s", $filterDate);
}


$stmt->execute();
$result = $stmt->get_result();
?>

<?php include '../header.php'; ?>
<div class="invenBox">
    <h2 class="invenTitle">도서 입출고 내역</h2>
    <!-- 버튼 -->
    <div class="invenButtonWrapper">
        <form action="inven.php" method="get">
            <input type="hidden" name="itype" value="0"> 
            <button type="submit" class="invenButton inButton">입고</button>
        </form>
        <form action="inven.php" method="get">
            <input type="hidden" name="itype" value="1">
            <button type="submit" class="invenButton outButton">출고</button>
        </form>
    </div>


    <form method="get" action="invenList.php" class="dateFilterForm">
        <label for="date">처리일:</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($filterDate) ?>">
        <button type="submit">조회</button>
        <button type="button" onclick="location.href='invenList.php'">전체보기</button>
    </form>

    <div class="invenTableWrapper">
        <table class="invenTable">
            <thead>
                <tr>
                    <th>번호</th>
                    <th>도서명</th>
                    <th>입출고</th>
                    <th>수량</th>
                    <th>재고</th>
                    <th>날짜</th>
                    <th>메모</th>
                    <th>처리 관리자</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $displayNo = 1;
                    while ($row = $result->fetch_assoc()) {
                        $typeText = $row['itype'] == 0 ? '입고' : '출고';
                        $typeClass = $row['itype'] == 0 ? 'inType' : 'outType';
                        echo "<tr>";
                        echo "<td>" . $displayNo++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['btitle']) . "</td>";
                        echo "<td class='$typeClass'>" . $typeText . "</td>";
                        echo "<td>" . htmlspecialchars($row['icount']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['istock']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['idate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['imemo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['adname']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>입출고 내역이 없습니다.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
