<?php
    session_start();
// Bắt đầu phiên làm việc
if (empty($_SESSION['csrf_token'])) {
    // Tạo token ngẫu nhiên nếu chưa có
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Cấu hình timeout (tính bằng giây)
$timeout = 900; // 15 phút

// Kiểm tra nếu đã lưu thời gian hoạt động trước đó
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $duration = time() - $_SESSION['LAST_ACTIVITY'];

    if ($duration > $timeout) {
        // Hết hạn → hủy session
        session_unset();
        session_destroy();
    header("Location: login.php");
        exit();
    }
}
// Cập nhật thời gian hoạt động mới nhất
$_SESSION['LAST_ACTIVITY'] = time();

?>