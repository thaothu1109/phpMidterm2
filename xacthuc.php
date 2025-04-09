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
            background-color: rgb(200, 237, 200);
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
            border: 2px solid #155724;
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
            background-color: #155724;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 15px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #155724;
        }

        .re-send {
            text-align: right;
            margin: 10px 0;
        }

        .re-send a {
            color: #155724;
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
require_once 'vendor/autoload.php'; 
include('session.php'); 
?>
    <!-- Form nhập mã OTP -->
    <form method="POST" action="xlxacthuc.php">
    <img src="logo-manhha.png" alt="Học english toiec" class="logo">
    <h3 style = " color: #155724;">Xác thực mã OTP từ ứng dụng Google Authenticator</h3>
        <input style="width: 450px;" type="text" name="otp" placeholder="Nhập mã OTP" required>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <!-- Nút xác thực -->
        <button type="submit">Xác thực</button>
    </form>
    <?php
    $secret = $_SESSION['secret_2fa'] ?? '';
    $username = $_SESSION['user'] ?? '';

    if ($secret && $username) {
    $ga = new PHPGangsta_GoogleAuthenticator();
    $qrCodeUrl = $ga->getQRCodeGoogleUrl($username, $secret, 'ManhHaApp'); // Thay tên app của bạn

    echo "<p>Quét mã QR bằng ứng dụng Google Authenticator:</p>";
    echo "<img src='$qrCodeUrl' alt='QR Code'>";
}
?>
</div>
</body>
</html>
