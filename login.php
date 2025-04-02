<?php
    session_start();
    include('session.php'); // Đảm bảo tạo token CSRF
    ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- Link to Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
         /* Reset các style mặc định */
         * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Căn giữa toàn bộ trang */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #ffffff;
        }

        /* Container chính bao gồm ảnh và form */
        .main-container {
            display: flex;
            align-items: center;
            gap: 70px; /* Tăng khoảng cách giữa ảnh và form */
        }

        /* Style ảnh nhân vật */
        .character-image {
            width: 400px; /* Tăng kích thước ảnh nhân vật */
            height: auto;
        }

        /* Khung chứa toàn bộ form đăng nhập */
        .login-container {
            text-align: center;
            width: 800px; /* Tăng kích thước của form */
        }

        /* Style logo */
        .logo {
            width: 150px; /* Tăng kích thước logo */
            margin-bottom: 30px;
        }

        /* Style các trường input */
        .login-input {
            width: 100%;
            padding: 20px; /* Tăng kích thước padding */
            margin: 15px 0;
            border: 2px solid #00bcd4;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
        }

        /* Style nút đăng nhập */
        .login-button {
            width: 50%;
            padding: 20px; /* Tăng kích thước padding */
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            border-radius: 30px;
            font-size: 18px; /* Tăng kích thước chữ */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Hiệu ứng hover cho nút đăng nhập */
        .login-button:hover {
            background-color: #008c9e;
        }

        /* Hiển thị thông báo lỗi */
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-top: -10px;
            border-radius: 20px;
            font-size: 14px;
            text-align: left;
        }

        .success {
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
        /* Liên kết đăng ký */
        .register-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .register-link a {
            color: #007BFF;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Icon mắt */
        .eye-icon {
            position: absolute;
            right: 15px;
            top: 35px;
            cursor: pointer;
            font-size: 20px;
            color: #0c046d;
        }
        /* Liên kết quên mật khẩu */
.forgot-password {
    text-align: right;
    margin: 10px 0;
}

.forgot-password a {
    color: #007BFF;
    font-size: 14px;
    text-decoration: none;
}

.forgot-password a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

<div class="login-container">
    <div class="main-container">
        <!-- Ảnh nhân vật minh họa -->
        <img src="Screenshot 2024-11-02 005547.png" alt="Brain Character" class="character-image">
 <div class="login-container">
            <!-- Logo của English with Mai Anh -->
            <img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
            <h3 style = "margin-bottom: 20px; margin-top: -30px; color: #0c046d;">Đăng nhập</h3>
               <!-- Hiển thị thông báo lỗi nếu có -->
    <?php
    if (isset($_SESSION['login_error'])) {
        echo "<div class='error'>" . $_SESSION['login_error'] . "</div>";
        unset($_SESSION['login_error']); 
    }
    ?>
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
    <!-- Form đăng nhập -->
    <form method="POST" action="xllogin.php">
    <input type="text" name="username_or_email" class="login-input" placeholder="Tên người dùng hoặc Email" required>

    <div style="position: relative;">
        <input type="password" id="password" name="password" class="login-input" placeholder="Mật khẩu" required minlength="5">
        <i class="fa-regular fa-eye-slash eye-icon" id="togglePassword" onclick="togglePassword()"></i>
    </div>
    <div class="forgot-password">
        <a style = "color: #00bcd4;" href="fgpassword.php">Quên mật khẩu?</a>
    </div>

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit" class="login-button">Đăng nhập</button>
</form>


    <div class="register-link">
        <p>Chưa có tài khoản? <a style = "color: #00bcd4;" href="signup.php">Đăng ký</a></p>
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
