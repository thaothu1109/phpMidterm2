<?php
session_start();

// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['message'] = ['type' => 'error', 'text' => 'CSRF token không hợp lệ'];
    header("Location: fgpassword.php");
    exit();
}

// Kiểm tra xem OTP và email đã được lưu trong session hay chưa
if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_email'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Vui lòng nhập đúng yêu cầu!'];
    header("Location: fgpassword.php");
    exit();
}

// Lấy mã OTP người dùng nhập
$userOtp = $_POST['otp'] ;

// Kiểm tra mã OTP
if ($userOtp == $_SESSION['otp']) {
    // OTP hợp lệ
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Xác thực OTP thành công! Bạn có thể đặt lại mật khẩu.'];
    unset($_SESSION['otp']); // Xóa OTP sau khi sử dụng
    header("Location: reset-password.php"); // Chuyển đến trang đặt lại mật khẩu
    exit();
} else {
    // OTP không hợp lệ
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP không chính xác. Vui lòng thử lại.'];
    header("Location: fgpassword.php?otp_sent=true"); // Quay lại trang nhập OTP
    exit();
}

?>
