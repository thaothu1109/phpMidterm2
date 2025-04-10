<?php 
include('session.php'); 
$otpSent = isset($_GET['otp_sent']) && $_GET['otp_sent'] == 'true'; // Kiểm tra nếu OTP đã gửi thành công
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        /* Đặt lại một số thuộc tính CSS cơ bản để giao diện đồng nhất */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color:rgb(200, 237, 200);;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.forgot-password-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

label {
    display: block;
    margin-bottom: 8px;
    text-align: left;
    font-size: 15px;
    color:  #006400;
}

input[type="text"] {
    width: 100%;
            padding: 15px; /* Tăng kích thước padding */
           margin-bottom: 20px;
            border: 2px solid  #006400;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
}

button {
    width: 50%;
    padding: 12px;
    background-color:  #006400;
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color:  #006400;
}

button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

a.back-to-login {
    display: inline-block;
    margin-top: 20px;
    color:  #006400;
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
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
    <img src="logo-manhha.png" alt="English with Mai Anh Logo" class="logo">
        <h3 style = "margin-bottom: 20px; margin-top: -30px; color:  #006400;">Quên Mật Khẩu</h3>
             
            <form action="xlfgpassword.php" method="POST">
                <label for="username_or_email">Nhập tên người dùng hoặc Email:</label>
                <input type="text" name="username_or_email" required>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit">Gửi Mã OTP</button>
            </form>

        <!-- Nút quay lại trang đăng nhập -->
        <a href="login.php" class="back-to-login">Quay lại trang đăng nhập</a>
    </div>
</body>
</html>
