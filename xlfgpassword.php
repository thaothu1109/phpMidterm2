<?php
session_start();
include('ketnoi.php'); // Kết nối cơ sở dữ liệu
require 'vendor/autoload.php'; // Đảm bảo autoload được tải cho PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


    // Kiểm tra CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']);
        $_SESSION['login_error'] = "CSRF token không hợp lệ";
        header("Location: index.php");
        exit();
    }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = $_POST['username_or_email'];

    // Kiểm tra xem người dùng có tồn tại trong cơ sở dữ liệu không
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username_or_email OR email = :username_or_email");
    $stmt->bindParam(':username_or_email', $username_or_email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Tạo mã OTP ngẫu nhiên 6 chữ số
        $otp = rand(100000, 999999);

        // Lưu mã OTP vào biến session để xác minh sau này
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $user['email'];
        
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cấu hình SMTP của Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'tramy1532k3@gmail.com'; // Email của bạn
            $mail->Password = 'zvwq oeqy kedg ugez'; // Mật khẩu ứng dụng Gmail của bạn
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587; 

            // Đặt người gửi và người nhận
            $mail->setFrom('tramy1532k3@gmail.com', 'Phamtramy153');
            $mail->addAddress($user['email']); 

            // Cấu hình nội dung email
            $mail->isHTML(true);
            $mail->Subject = 'Ma OTP de thay doi mat khau';
            $mail->Body = "<p>Đây là mã OTP của bạn: <strong>$otp</strong></p><p>Vui lòng nhập mã này để tiếp tục thay đổi mật khẩu của bạn.</p>";

            // Gửi email
            if ($mail->send()) {
                $_SESSION['message'] = ['type' => 'success', 'text' => "Mã OTP đã được gửi tới email của bạn."];
                $_SESSION['otp_sent'] = true; // Đánh dấu OTP đã được gửi
                header("Location: fgpassword.php?otp_sent=true"); // Chuyển hướng đến trang xác minh OTP
                exit();
            }
        } catch (Exception $e) {
            // Lỗi khi gửi email OTP
            $_SESSION['message'] = ['type' => 'error', 'text' => "Lỗi khi gửi email OTP: " . $mail->ErrorInfo];
            header("Location: fgpassword.php"); // Quay lại trang quên mật khẩu
            exit();
        }
    } else {
        // Người dùng không tồn tại
        $_SESSION['message'] = ['type' => 'error', 'text' => "Người dùng hoặc email không tồn tại"];
        header("Location: fgpassword.php");
        exit();
    }
} else {
    // Trường hợp không phải là phương thức POST
    header("Location: fgpassword.php");
    exit();
}

?>
