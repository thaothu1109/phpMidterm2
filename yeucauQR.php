<?php 
session_start(); 
// Kiểm tra nếu URL có tham số qr_sent và giá trị của nó là true
$qrSent = isset($_GET['qr_sent']) && $_GET['qr_sent'] == 'true'; // Kiểm tra nếu QR đã gửi thành công
include('session.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu cấp lại mã QR xác thực</title>
    <style>
        /* Đặt lại một số thuộc tính CSS cơ bản để giao diện đồng nhất */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.re-send-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 100%;
    max-width: 500px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

label {
    display: block;
    margin-top: 20px;
    text-align: left;
    font-size: 15px;
    color: #0c046d
}

input[type="text"] {
    width: 80%;
            padding: 15px; /* Tăng kích thước padding */
            margin: 15px 15px;
            border: 2px solid #00bcd4;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
}

button {
    width: 50%;
            padding: 15px; /* Tăng kích thước padding */
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 15px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
}

button:hover {
    background-color: #008c9e;
}

button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

a.back-to-login {
    display: inline-block;
    margin-top: 20px;
   color:  #00bcd4;
    text-decoration: none;
    font-size: 14px;
}

a.back-to-login:hover {
    text-decoration: underline;
}

.success, .error {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 14px;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.logo {
            width: 150px; /* Tăng kích thước logo */
          
        }

    </style>
</head>
<body>
    <div class="re-send-container">
    <img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
    <h3 style = " color: #0c046d;">Yêu cầu cấp lại QR xác thực</h3>
        
        <!-- Hiển thị thông báo nếu có -->
        <?php
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            $message_type = $message['type'] === 'success' ? 'success' : 'error';
            echo "<div class='$message_type'>{$message['text']}</div>";
            unset($_SESSION['message']);
        }
        ?>

    <?php if (!$qrSent): ?>
    <!-- Form gửi mã QR -->
        <form action="xlyeucauQR.php" method="POST">
            <label for="username_or_email">Nhập Email nhận QR xác thực:</label>
            <input type="text" name="username_or_email" required>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Gửi QR xác thực</button>
        </form>
    <?php endif; ?>
        <!-- Nút quay lại trang xác thực -->
        <a href="xacthuc.php" class="back-to-login">Quay lại trang nhập mã OTP từ Google Authenticator</a>
    </div>
</body>
</html>
