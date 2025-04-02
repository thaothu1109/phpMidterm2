<?php
    session_start();
    include('session.php'); // Đảm bảo tạo token CSRF
    if (!isset($_SESSION['change_password_required'])) {
        header("Location: login.php");
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Đặt nền toàn trang */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e6f7ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Container chứa form */
        .login-container {
            background-color: #ffffff;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* Tiêu đề form */
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
        }

        /* Các trường input */
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 25px;
            box-sizing: border-box;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .login-container input[type="text"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #00bcd4;
        }

        /* Nút đăng nhập */
        .login-container button {
            width: 50%;
            padding: 10px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #0288d1;
        }

        /* Hiển thị thông báo lỗi */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 12px;
            font-size: 14px;
            text-align: left;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: 20px;
            border-radius: 12px;
            font-size: 14px;
            text-align: left;
        }

        /* Nút quay lại */
        .back-button {
            width: 50%;
            padding: 12px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #5a6268;
        }

        /* Icon mắt */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 20px;
           cursor: pointer;
            font-size: 20px;
            color: #0c046d;
        }

        /* Link quay lại trang chủ */
        .login-link p {
            font-size: 16px;
            margin-top: 20px;
            color: #333;
        }

        .login-link a {
            color: #00bcd4;
            text-decoration: none;
      
        }

        .login-link a:hover {
            text-decoration: underline;
        }
        .logo {
            width: 150px; /* Tăng kích thước logo */
          
        }
    </style>
</head>
<body>

<div class="login-container">
<img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
<h3 style = " color: #0c046d;">Đổi mật khẩu</h3>
    

    
    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php
        if (isset($_SESSION['user'])) {
            echo "<p style='font-size: 16px; color:  #0c046d;'>Tài khoản: " . htmlspecialchars($_SESSION['user']) . "</p>";
        }
    if (isset($_SESSION['success_message'])) {
        echo "<div class='success'>" . $_SESSION['success_message'] . "</div>";
        unset($_SESSION['success_message']); // Xóa thông báo sau khi đã hiển thị
    }

    if (isset($_SESSION['login_error'])) {
        echo "<div class='error'>" . $_SESSION['login_error'] . "</div>";
        unset($_SESSION['login_error']); // Xóa thông báo lỗi sau khi đã hiển thị
    }
    ?>

    <!-- Form đăng nhập -->
    <form method="POST" action="xluser_password.php">
        <div style="position: relative;">
            <input type="password" id="current_password" name="current_password" placeholder="Nhập mật khẩu cũ" required minlength="8">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('current_password', this)"></i>
        </div>
        <div style="position: relative;">
            <input type="password" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required minlength="8"
            pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
        title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('new_password', this)"></i>
        </div>
        <div style="position: relative;">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required minlength="8" 
            pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
            title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            <i class="fa-regular fa-eye-slash eye-icon" onclick="togglePassword('confirm_password', this)"></i>
        </div>
        <input style="width: 100%;" type="text" name="otp" placeholder="Nhập mã OTP từ Google Authenticator" required>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Đổi mật khẩu</button>
    </form>



</div>

<script>
    function togglePassword(fieldId, icon) {
        var passwordField = document.getElementById(fieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
</script>

</body>
</html>
