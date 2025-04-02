<?php
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Thay đổi múi giờ nếu cần
session_start();
include('ketnoi.php');
// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

// Kiểm tra nếu tài khoản đã bị khóa
if (isset($_SESSION['locked']) && $_SESSION['locked'] > time()) {
    $lockTimeRemaining = $_SESSION['locked'] - time();
    $_SESSION['login_error'] = "Đăng nhập bị hạn chế, vui lòng thử lại sau " . ceil($lockTimeRemaining / 60) . " phút.";
    header("Location: login.php");
    exit();
}

// Kiểm tra và reset số lần đăng nhập sai nếu khóa tài khoản đã hết
if (isset($_SESSION['locked']) && $_SESSION['locked'] <= time()) {
    unset($_SESSION['login_attempts']);
    unset($_SESSION['locked']);
}
// Lấy thông tin từ form đăng nhập
$username_or_email = $_POST['username_or_email'];
$password = $_POST['password'];

// Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $username_or_email);
} else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username_or_email);
}

$stmt->execute();
$user = $stmt->fetch();

// Kiểm tra nếu thông tin đăng nhập hợp lệ
if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['login_attempts']); 
    $_SESSION['user'] = $user['username'];
    $_SESSION['secret_2fa'] = $user['secret_2fa'];

    
    $stmt_log = $pdo->prepare("SELECT user_id, registration_date, last_password_change FROM user_log WHERE user_id = :user_id");
    $stmt_log->bindParam(':user_id', $user['id']);
    $stmt_log->execute();
    $user_log = $stmt_log->fetch();


    $registrationDate = $user_log['registration_date'];
    if ($registrationDate) {
        $timeSinceRegistration = time() - strtotime($registrationDate);
        if ($timeSinceRegistration > 2592000) { 
            $_SESSION['change_password_required'] = true;
            header("Location: user_password.php");
            exit();
        }
    }

    $lastPasswordChange = $user_log['last_password_change'];
    if ($lastPasswordChange) {
        $timeSinceLastPasswordChange = time() - strtotime($lastPasswordChange);
        if ($timeSinceLastPasswordChange > 2592000) { 
            $_SESSION['change_password_required'] = true;
            header("Location: user_password.php");
            exit();
        }
    }

    $_SESSION['logged'] = true;
    // Chuyển hướng đến trang xác thực
    header("Location: xacthuc.php?tennguoidung=" . urlencode($user['username']));
    exit();
} else {
    // Xử lý khi đăng nhập thất bại
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }

    if ($_SESSION['login_attempts'] >= 10) {
        $_SESSION['locked'] = time() + 300; // Khóa trong 5 phút
        $_SESSION['login_error'] = "Đăng nhập bị hạn chế. Vui lòng thử lại sau 5 phút.";
    } else {
        $_SESSION['login_error'] = "Tên người dùng hoặc mật khẩu không đúng. Bạn đã thử " . $_SESSION['login_attempts'] . " lần.";
    }

    header("Location: login.php");
    exit();
}
?>
