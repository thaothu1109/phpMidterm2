<?php
// Kết nối CSDL
require 'connect.php'; // Sử dụng kết nối PDO từ file connect.php
require_once 'vendor/autoload.php';
require 'session.php';

// Kiểm tra CSRF token
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Lỗi CSRF token. Yêu cầu không hợp lệ.");
}
// Nhận dữ liệu từ form
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Kiểm tra người dùng đã tồn tại chưa
$sql = "SELECT * FROM users WHERE username = :username OR email = :email";
$stmt = $pdo->prepare($sql); //Chống SQL Injection bằng prepared statements
$stmt->execute(['username' => $username, 'email' => $email]);

if ($stmt->rowCount() > 0) {
    // Điều hướng về trang đăng ký với thông báo lỗi
    header("Location: signup.php?error=exists");
    exit();
} else {
    $ga = new PHPGangsta_GoogleAuthenticator();
    $secret_2fa = $ga->createSecret();
    // Lưu vào CSDL
    $insert = "INSERT INTO users (fullname, email, username, password, secret_2fa) 
               VALUES (:fullname, :email, :username, :password, :secret_2fa)";
   $stmt = $pdo->prepare($insert);
   $result = $stmt->execute([
       'fullname' => $fullname,
       'email' => $email,
       'username' => $username,
       'password' => $hashedPassword,
       'secret_2fa' => $secret_2fa
   ]);

    if ($result) {
        // Điều hướng đến trang đăng nhập với thông báo thành công
        $_SESSION['user'] = $username;
        $_SESSION['secret_2fa'] = $secret_2fa;
        header("Location: login.php?success=1");
        exit();
    } else {
        // Điều hướng về trang đăng ký với thông báo lỗi
        header("Location: signup.php?error=failed");
        exit();
    }
}
?>