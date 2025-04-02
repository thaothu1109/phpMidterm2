<?php
session_start();
require_once 'vendor/autoload.php'; // Đảm bảo thư viện PHPGangsta/GoogleAuthenticator đã được cài đặt
include('ketnoi.php'); // Kết nối cơ sở dữ liệu

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    $_SESSION['login_error'] = 'Bạn chưa đăng nhập!';
    header("Location: login.php");
    exit();
}

// Lấy username từ session
$username = $_SESSION['user'];

// Khởi tạo Google Authenticator
$ga = new PHPGangsta_GoogleAuthenticator();

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $otp = $_POST['otp'] ?? ''; // Lấy mã OTP từ form

    // Kiểm tra nếu thiếu dữ liệu
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword) || empty($otp)) {
        $_SESSION['login_error'] = 'Vui lòng điền đầy đủ thông tin!';
        header("Location: user_password.php");
        exit();
    }

    // Kiểm tra mật khẩu mới và xác nhận
    if ($newPassword !== $confirmPassword) {
        $_SESSION['login_error'] = 'Mật khẩu mới và xác nhận mật khẩu không khớp!';
        header("Location: user_password.php");
        exit();
    }

    // Kiểm tra OTP với Google Authenticator
    $secret = $_SESSION['secret_2fa'] ?? '';  // Lấy secret từ session (được lưu khi người dùng thiết lập 2FA)
    
    if (!$secret) {
        $_SESSION['login_error'] = 'Mã QR không hợp lệ hoặc chưa được thiết lập.';
        header("Location: user_password.php");
        exit();
    }

    $isValid = $ga->verifyCode($secret, $otp, 2);  // 2 là số lần thử cho phép (thường là 2 phút)

    if (!$isValid) {
        $_SESSION['login_error'] = 'Mã OTP không chính xác!';
        header("Location: user_password.php");
        exit();
    }

    try {
        // Lấy mật khẩu hiện tại của người dùng từ cơ sở dữ liệu
        $query = "SELECT password FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $dbPasswordHash = $stmt->fetchColumn();

        // Kiểm tra nếu không tìm thấy mật khẩu
        if (!$dbPasswordHash) {
            $_SESSION['login_error'] = 'Tài khoản không tồn tại!';
            header("Location: user_password.php");
            exit();
        }

        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $dbPasswordHash)) {
            $_SESSION['login_error'] = 'Mật khẩu hiện tại không đúng!';
            header("Location: user_password.php");
            exit();
        }

        // Mã hóa mật khẩu mới
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        // Cập nhật mật khẩu mới vào bảng users
        $updateQuery = "UPDATE users SET password = :password WHERE username = :username";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindValue(':password', $newPasswordHash, PDO::PARAM_STR);
        $updateStmt->bindValue(':username', $username, PDO::PARAM_STR);
        $updateStmt->execute();

        // Cập nhật thời điểm thay đổi mật khẩu vào bảng user_log
        $updateLogQuery = "UPDATE user_log 
        SET last_password_change = NOW() 
        WHERE user_id = (SELECT id FROM users WHERE username = :username LIMIT 1)";
        
        $updateLogStmt = $pdo->prepare($updateLogQuery);
        $updateLogStmt->bindValue(':username', $username, PDO::PARAM_STR);
        $updateLogStmt->execute();

        // Thông báo thành công
        $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
        unset($_SESSION['otp_code']); // Xóa mã OTP sau khi đổi mật khẩu thành công
        header("Location: user_password.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Lỗi hệ thống: ' . $e->getMessage();
        header("Location: user_password.php");
        exit();
    }
} else {
    echo "Yêu cầu không hợp lệ!";
    exit();
}
?>
