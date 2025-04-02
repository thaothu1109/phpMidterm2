<?php
session_start();
require_once 'vendor/autoload.php';
include('ketnoi.php'); // Đảm bảo rằng ketnoi.php đã có kết nối CSDL


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin và mã hóa mật khẩu
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $registrationDate = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại cho ngày đăng ký

    try {
        // Kiểm tra xem người dùng đã tồn tại hay chưa
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['message'] = ['type' => 'error', 'text' => "Tên người dùng hoặc email đã tồn tại."];
            header("Location: signup.php"); // Giữ lại trang đăng ký
            exit();
        }

        // Thêm người dùng vào cơ sở dữ liệu
        $stmt = $pdo->prepare("INSERT INTO users (fullname, email, username, password) VALUES (:fullname, :email, :username, :password)");
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Lấy ID của người dùng vừa tạo
        $userId = $pdo->lastInsertId();

        // Thêm ngày đăng ký vào bảng user_log
        $stmt = $pdo->prepare("INSERT INTO user_log (user_id, registration_date) VALUES (:user_id, :registration_date)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':registration_date', $registrationDate);
        $stmt->execute();

        // Tạo mã QR với Google Authenticator
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();

        // Lưu secret vào CSDL
        $stmt = $pdo->prepare("UPDATE users SET secret_2fa = :secret WHERE username = :username");
        $stmt->bindParam(':secret', $secret);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Tạo URL mã QR và lưu vào session
        $_SESSION['qrCodeUrl'] = $ga->getQRCodeGoogleUrl("Tên người dùng: " . $username, $secret);
        $_SESSION['message'] = ['type' => 'success', 'text' => "Đăng ký thành công! Quét mã QR để hoàn tất."];

    } catch (PDOException $e) {
        $_SESSION['message'] = ['type' => 'error', 'text' => "Lỗi xảy ra: " . $e->getMessage()];
    }

    // Giữ người dùng ở lại trang đăng ký và hiển thị thông báo
    header("Location: signup.php");
    exit();
    }
?>
