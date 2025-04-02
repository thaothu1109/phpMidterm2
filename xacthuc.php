<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực 2FA</title>
    <style>
        .error {
            color: #e74c3c;
            font-size: 14px;
            font-weight: 500;
        }

        .success {
            color: #27ae60;
            font-size: 14px;
            font-weight: 500;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 0 20px;
        }

        .container {
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

        input[type="text"] {
            width: 80%;
            padding: 15px; /* Tăng kích thước padding */
            margin: 15px 15px;
            border: 2px solid #00bcd4;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
        }

        input[type="text"]:focus {
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
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

        .re-send {
            text-align: right;
            margin: 10px 0;
        }

        .re-send a {
            color: #007BFF;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .re-send a:hover {
            text-decoration: underline;
        }
        /* Thông báo */
        .success, .error {
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            font-size: 15px;
            text-align: left;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            width: 80%;
            margin-left: 42px;
            border: 1px solid #c3e6cb;
            text-align: center;
        }

        .error {
            width: 80%;
            margin-left: 42px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            text-align: center;
        }
        .logo {
            width: 150px; /* Tăng kích thước logo */
          
        }
    </style>
</head>
<body>

<div class="container">
<?php
session_start();
require_once 'vendor/autoload.php'; 
include('session.php'); 
// Nếu có thông báo từ session, hiển thị thông báo
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_class = $message['type'] === 'success' ? 'success' : 'error';
    echo "<div class='$message_class'>{$message['text']}</div>";
    unset($_SESSION['message']); 
}
// Kiểm tra nếu session đã hết hạn (3 phút)
if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 180) {
        session_unset(); 
        session_destroy(); 
        header("Location: index.php");
        exit();
    }
    $_SESSION['last_activity'] = time();
} else {
    header("Location: index.php");
    exit();
}

?>
    <!-- Form nhập mã OTP -->
    <form method="POST" action="xlxacthuc.php">
    <img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
    <h3 style = " color: #0c046d;">Xác thực mã OTP từ ứng dụng Google Authenticator</h3>
        <input style="width: 450px;" type="text" name="otp" placeholder="Nhập mã OTP" required>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Yêu cầu cấp lại mã QR -->
        <div class="re-send">
            <a href="yeucauQR.php" onclick="showBottomBar()">Yêu cầu cấp lại mã QR</a>
        </div>
        <!-- Nút xác thực -->
        <button type="submit">Xác thực</button>
    </form>

</div>
</body>
</html>
