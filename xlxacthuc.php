<?php
session_start();
require_once 'vendor/autoload.php'; 
include('ketnoi.php');

//Kiểm tra CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

    // Khởi tạo Google Authenticator
    $ga = new PHPGangsta_GoogleAuthenticator();

    // Lấy secret key từ session
    if (!isset($_SESSION['secret_2fa'])) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã QR không hợp lệ hoặc chưa được thiết lập.'];
        header("Location: xacthuc.php?tennguoidung=" . urlencode($_SESSION['user'])); // Lấy tên người dùng từ session
        exit();
    }

    $secret = $_SESSION['secret_2fa'];

    // Lấy OTP từ form
    $otp = $_POST['otp'];

    // Xác thực mã OTP bằng dùng hàm verifyCode() của thư viện
    $isValid = $ga->verifyCode($secret, $otp, 4); // 4 là số lần thử cho phép trong 2 phút

    if ($isValid) {
        // Xác thực thành công, lưu vào session để người dùng đã đăng nhập thành công
        $_SESSION['logged_in'] = true;

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Xác thực thành công!'];

        // Chuyển hướng đến trang chủ
        header("Location: trangchu.php"); 
        exit();
    } else {
        // Nếu mã OTP không hợp lệ, hiển thị thông báo lỗi
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Mã OTP không hợp lệ!'];
        header("Location: xacthuc.php?tennguoidung=" . urlencode($_SESSION['user'])); // Redirect lại trang xác thực
        exit();
    }

?>
