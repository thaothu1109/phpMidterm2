<?php
  session_start();
//Thiết lập múi giờ và khởi tạo session
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Thay đổi múi giờ nếu cần
include('connect.php');
// Kiểm tra CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit;
}
//Lấy thông tin từ form đăng nhập
$username_or_email = $_POST['username_or_email'];
$password = $_POST['password'];
// Kiểm tra dữ liệu
if (empty($username_or_email) || empty($password)) {
    $_SESSION['login_error'] = "Vui lòng nhập đầy đủ thông tin.";
    header("Location: index.php");
    exit();
}
// Truy vấn người dùng
if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :value");
} else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :value");
}
$stmt->bindValue(':value', $username_or_email);
$stmt->execute();
$user = $stmt->fetch();
//Bỏ khóa khi hết thời gian
if (isset($_SESSION['locked']) && $_SESSION['locked'] <= time()) {
    unset($_SESSION['locked']);
    unset($_SESSION['login_attempts']);
}
//Đăng nhập & xác thực 2FA
// Kiểm tra thời gian đăng ký và lần đổi mật khẩu cuối cùng
// Bắt buộc đổi mật khẩu sau 30 ngày (2592000 giây)
// Giới hạn số lần đăng nhập sai (lock 5 phút sau 10 lần)
if ($user && password_verify($password, $user['password'])) {
    // Xoá bộ đếm sai
    unset($_SESSION['login_attempts']);
    unset($_SESSION['locked']);
    $_SESSION['user'] = $user['username'];
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['secret_2fa'] = $user['secret_2fa'] ?? null;

    // Kiểm tra ngày đăng ký & đổi mật khẩu
    $stmt_log = $pdo->prepare("SELECT registration_date, last_password_change FROM user_log WHERE user_id = :user_id");
    $stmt_log->bindParam(':user_id', $user['id']);
    $stmt_log->execute();
    $user_log = $stmt_log->fetch();

    $require_change = false;

    if (!empty($user_log)) {
        $now = time();

        if (!empty($user_log['registration_date']) && $now - strtotime($user_log['registration_date']) > 2592000) {
            $require_change = true;
        }

        if (!empty($user_log['last_password_change']) && $now - strtotime($user_log['last_password_change']) > 2592000) {
            $require_change = true;
        }
    }

    if ($require_change) {
        $_SESSION['change_password_required'] = true;
        header("Location: user_password.php");
        exit();
    }

    $_SESSION['logged'] = true;
    header("Location: xacthuc.php?tennguoidung=" . urlencode($user['username']));
    exit();
} else {
    // Sai thông tin
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 1;
    } else {
        $_SESSION['login_attempts']++;
    }

    if ($_SESSION['login_attempts'] >= 2) {
        $_SESSION['locked'] = time() + 50; // Khóa 5 phút
        $_SESSION['login_error'] = "Đăng nhập bị khóa. Vui lòng thử lại sau 5 phút.";
    } else {
        $_SESSION['login_error'] = "Sai thông tin. Lần thử: " . $_SESSION['login_attempts'];
    }

    header("Location: index.php");
    exit();
}

?>