<?php
require_once 'vendor/autoload.php'; // Thư viện PHPGangsta_GoogleAuthenticator
require 'connect.php';
require 'session.php';

// Kiểm tra CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Lỗi CSRF token. Yêu cầu không hợp lệ.");
}

// Nhận mã OTP từ người dùng
$otp = $_POST['otp'] ?? '';
$username = $_SESSION['user'] ?? null;

// Kiểm tra người dùng đã đăng nhập chưa
if (!$username) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

// Lấy secret 2FA từ CSDL
$stmt = $pdo->prepare("SELECT secret_2fa FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php?error=user_not_found");
    exit();
}

// Xác thực mã OTP
$ga = new PHPGangsta_GoogleAuthenticator();
$secret = $user['secret_2fa'];
$checkResult = $ga->verifyCode($secret, $otp, 2); // 2 là khoảng lệch thời gian (2 x 30s)

if ($checkResult) {
    // Xác thực thành công
    $_SESSION['authenticated'] = true;

    // Redirect tới trang chính
    header("Location: trangchu.php");
    exit();
} else {
    // Mã OTP không hợp lệ
echo "<script>
alert('Mã OTP không hợp lệ');
window.location.href = 'xacthuc.php';
</script>";
exit();
}
?>
