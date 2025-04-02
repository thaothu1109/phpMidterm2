<?php
session_start();
include('ketnoi.php'); // Kết nối đến cơ sở dữ liệu
include('session.php'); // Bao gồm mã kiểm tra session

// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

    // Kiểm tra mật khẩu mới và xác nhận mật khẩu
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Mật khẩu xác nhận không khớp.'];
        header("Location: reset-password.php");
        exit();
    }

    // Cập nhật mật khẩu mới trong cơ sở dữ liệu
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Mã hóa mật khẩu
    $email = $_SESSION['otp_email']; // Lấy email từ session

    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $email);
        // Cập nhật thời điểm thay đổi mật khẩu vào bảng user_log
        $updateLogQuery = "UPDATE user_log 
        SET last_password_change = NOW() 
        WHERE user_id = (SELECT id FROM users WHERE username = :username LIMIT 1)";
        
        $updateLogStmt = $pdo->prepare($updateLogQuery);
        $updateLogStmt->bindValue(':username', $username, PDO::PARAM_STR);
        $updateLogStmt->execute();

    if ($stmt->execute()) {
        $_SESSION['message'] = ['type' => 'success', 'text' => 'Đặt lại mật khẩu thành công. Bạn có thể đăng nhập với mật khẩu mới.'];
       
        unset($_SESSION['otp_email']); // Xóa email sau khi đặt lại mật khẩu
        header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
        exit();
    } else {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Có lỗi xảy ra khi đặt lại mật khẩu.'];
        header("Location: reset-password.php");
        exit();
    }

?>
