<?php
session_start();
include '../db.php';

// 로그인 체크
if (!isset($_SESSION['uno'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); location.href='../user/login.php';</script>";
    exit;
}

$uno = $_SESSION['uno'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['bno']) || empty($_POST['bno'])) {
        echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
        exit;
    }

    $bno = intval($_POST['bno']);

    // 도서 상태 확인
    $stmt = $conn->prepare("SELECT bstate FROM book WHERE bno = ?");
    $stmt->bind_param("i", $bno);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('존재하지 않는 도서입니다.'); history.back();</script>";
        exit;
    }

    $row = $result->fetch_assoc();

    if ($row['bstate'] == 1) {
        echo "<script>alert('이미 대출 중인 도서입니다.'); history.back();</script>";
        exit;
    }

    // 도서 상태 업데이트 (대출 중으로 변경)
    $stmtUpdate = $conn->prepare("UPDATE book SET bstate = 1 WHERE bno = ?");
    $stmtUpdate->bind_param("i", $bno);
    $stmtUpdate->execute();

    if ($stmtUpdate->affected_rows === 0) {
        echo "<script>alert('대출 처리에 실패했습니다.'); history.back();</script>";
        exit;
    }

    // 대출 내역에 추가
    $today = date('Y-m-d');
    $returnDate = date('Y-m-d', strtotime('+7 days')); // 7일 후 반납 예정일

    $stmtLoan = $conn->prepare("INSERT INTO loan (ldate, lddate, lrdate, lstate, uno, bno) VALUES (?, ?, NULL, 0, ?, ?)");
    $stmtLoan->bind_param("ssii", $today, $returnDate, $uno, $bno);
    $stmtLoan->execute();

    if ($stmtLoan->affected_rows > 0) {
        echo "<script>alert('대출이 완료되었습니다.\\n반납 기한은 $returnDate 입니다.'); location.href='bookList.php';</script>";
        exit;
    } else {
        $conn->query("UPDATE book SET bstate = 0 WHERE bno = $bno");
        echo "<script>alert('대출 신청 중 오류가 발생했습니다.'); history.back();</script>";
        exit;
    }

} else {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}
?>
