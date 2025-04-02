<?php
    session_start();
    include('session.php'); 
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Đăng ký</title>
    <style>
        /* Thiết lập chung cho nền và font chữ */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
           
            margin: 0;
        }

        /* Khung đăng ký */
        .register-container {
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(10px);
            position: relative;
        }

        .register-container h2 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #333;
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

        /* Ô nhập liệu */
        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container input[type="email"],
        .register-container input[type="tel"] {
            width: 80%;
            padding: 15px; /* Tăng kích thước padding */
            margin: 15px 15px;
            border: 2px solid #00bcd4;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
        }
        .register-container input[type="text"]:focus,
        .register-container input[type="password"]:focus,
        .register-container input[type="email"]:focus,
        .register-container input[type="tel"]:focus {
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Nút đăng ký */
        .register-container button {
            width: 50%;
            padding: 15px; /* Tăng kích thước padding */
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 18px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-container button:hover {
            background-color: #008c9e;
        }

        /* Liên kết đăng nhập */
        .login-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: #00796b;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Khung mã QR */
        .qr-code {
            max-width: 160px;
            height: auto;
            margin-top: 10px;
        }
        .logo {
            width: 150px; /* Tăng kích thước logo */
          
        }
         /* Icon mắt */
         .eye-icon {
            position: absolute;
            right: 60px;
            top: 30px;
            cursor: pointer;
            font-size: 20px;
            color: #0c046d;
        }

    </style>
</head>
<body>

<div class="register-container">
<img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
    <h3 style = " color: #0c046d;">Đăng ký</h3>
<?php
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $message['type'] === 'success' ? 'success' : 'error';
        echo "<div class='$message_type'>";
        echo $message['text'];
        echo "</div>";
        unset($_SESSION['message']); 
    }

?>
    <!-- Hiển thị form đăng ký -->
    <form method="POST" action="xlsignup.php">
        <input type="text" name="fullname" placeholder="Họ tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Tên đăng nhập" required minlength="5">
        <div style="position: relative;">
    <input type="password" id="password" name="password" placeholder="Mật khẩu" required minlength="8"
        pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
        title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
        <i class="fas fa-eye-slash eye-icon" id="togglePassword" onclick="togglePassword()"></i>
</div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Đăng ký</button>
    </form>

    <!-- Kiểm tra nếu có URL mã QR trong session -->
    <?php if (isset($_SESSION['qrCodeUrl'])): ?>
        <h5>Quét mã QR bằng Google Authenticator</h5>
        <p style="color: red; font-size: 13px; margin-top: -10px; padding: 20px">Lưu ý: Bạn cần quét mã để có thể nhập mã OTP để xác minh trong mỗi lần đăng nhập (Mã QR chỉ hiển thị duy nhất 1 lần)</p>

        <img src="<?php echo $_SESSION['qrCodeUrl']; ?>" alt="QR Code" class="qr-code">
        <?php unset($_SESSION['qrCodeUrl']); ?>
    <?php endif; ?>

    <div class="login-link">
        <p>Đã có tài khoản? <a style = "color: #00bcd4;" href="login.php">Đăng nhập</a></p>
    </div>
</div>
<script>
   function togglePassword() {
    var passwordField = document.getElementById("password");
    var eyeIcon = document.getElementById("togglePassword");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}

</script>


</body>
</html>
