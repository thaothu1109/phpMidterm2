<?php
require 'session.php';
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
        background-color: rgb(200, 237, 200);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Đảm bảo chiều cao toàn màn hình */
        margin: 0;
    }

    /* Khung đăng ký */
    .register-container {
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
            border: 2px solid #006400;
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
            background-color: #006400;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 18px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-container button:hover {
            background-color: #006400;
        }

        /* Liên kết đăng nhập */
        .login-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .login-link a {
            color: #006400;
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
            color: #006400;
        }

    </style>
</head>
<body>

<div class="register-container">
<img src="logo-manhha.png" alt="English with Mai Anh Logo" class="logo">
    <h3 style = " margin-right:8px;color: #006400;">Đăng ký</h3>
    <?php
    //Thắm thêm đoạn này vào để hiển thị thông báo tên đăng nhập hoặc mail đã tồn tại 
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'exists') {
        echo "<div class='error'>Tên đăng nhập hoặc email đã tồn tại!</div>";
    } elseif ($_GET['error'] == 'failed') {
        echo "<div class='error'>Lỗi khi đăng ký! Vui lòng thử lại.</div>";
    }
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
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePassword');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }
    </script>

</body>
</html>
