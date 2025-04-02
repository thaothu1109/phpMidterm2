<?php
session_start();
include('ketnoi.php'); 
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPGangsta_GoogleAuthenticator;

// Kiểm tra xác minh CSRF Token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    unset($_SESSION['csrf_token']);
    $_SESSION['login_error'] = "CSRF token không hợp lệ";
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Giả sử email được chuyển từ đầu vào biểu mẫu
    $username_or_email = $_POST['username_or_email'];
    $userEmail = $username_or_email; 
    $username = $username_or_email; 

    try {
        // Kiểm tra xem email hoặc tài khoản người dùng đã tồn tại chưa
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->bindParam(':email', $userEmail);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            // Nếu tài khoản người dùng không tồn tại
            $_SESSION['message'] = ['type' => 'error', 'text' => "Email hoặc tên người dùng không tồn tại."];
            header("Location: yeucauQR.php");
            exit();
        }

        // Tạo mã QR với Google Authenticator
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        $_SESSION['secret_2fa'] = $secret;
    
        // Update mã vào CSDL
        $stmt = $pdo->prepare("UPDATE users SET secret_2fa = :secret WHERE username = :username");
        $stmt->bindParam(':secret', $secret);
        $stmt->bindParam(':username', $_SESSION['user']);
        $stmt->execute();
    
        // Tạo URL mã QR mới
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('Tên người dùng:' . $_SESSION['user'], $secret);
        // Lấy nội dung ảnh QR từ URL
        $qrImageData = file_get_contents($qrCodeUrl);
        if ($qrImageData === false) {
            throw new Exception("Could not retrieve QR code image.");
        }

        // Lưu ảnh QR tạm thời vào bộ nhớ mà không cần lưu vào ổ đĩa
        $qrImagePath = tempnam(sys_get_temp_dir(), 'qr_code_') . '.png';
        file_put_contents($qrImagePath, $qrImageData);

        // Chuẩn bị PHPMailer để gửi email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tramy1532k3@gmail.com';
        $mail->Password = 'zvwq oeqy kedg ugez'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Đặt người gửi và người nhận
        $mail->setFrom('tramy1532k3@gmail.com', 'Phamtramy153');
        $mail->addAddress($userEmail);

        // Nội dung email
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Mã QR để xác nhận đăng nhập';
        $mail->Body = "<p>Xin chào tài khoản $userEmail, đây là mã QR của bạn để xác thực tài khoản của bạn:</p>";

        // Đính kèm ảnh QR code
        $mail->addAttachment($qrImagePath, 'qr_code.png');

        // Gửi email
        if ($mail->send()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => "Mã QR đã được gửi tới email của bạn."];
            $_SESSION['qr_sent'] = true; // Mark QR as sent
            header("Location: yeucauQR.php?qr_sent=true"); // Redirect to QR verification page
            exit();
        }
    } catch (Exception $e) {
        // Có lỗi xảy ra khi gửi email
        $_SESSION['message'] = ['type' => 'error', 'text' => "Lỗi không gửi được email QR! " . $mail->ErrorInfo];
        header("Location: yeucauQR.php"); // Điều hướng về trang yeucauQR
        exit();
    } finally {
        // Xóa file tạm thời sau khi gửi email
        if (file_exists($qrImagePath)) {
            unlink($qrImagePath);
        }
    }
} else {
    // Nếu phương thức sử dụng không phải là POST
    header("Location: yeucauQR.php");
    exit();
}
?>
