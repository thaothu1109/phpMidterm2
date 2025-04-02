<?php
session_start();
include('ketnoi.php'); // Kết nối đến cơ sở dữ liệu
include('session.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-password-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .reset-password-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .reset-password-container input[type="password"] {
            width: 80%;
            padding: 15px; /* Tăng kích thước padding */
           margin-bottom: 20px;
            border: 2px solid #00bcd4;
            border-radius: 25px;
            outline: none;
            font-size: 15px; /* Tăng kích thước chữ */
        }

        .reset-password-container button {
            width: 40%;
    padding: 12px;
    background-color: #00bcd4;
    color: white;
    border: none;
    border-radius: 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
        }

        .reset-password-container button:hover {
            background-color: #0056b3;
        }

        .error, .success {
            justify-content: center;
            align-items: center;
            width: 80%;
            padding: 10px;
    margin-bottom: 20px;
    margin-left: 30px;
    border-radius: 20px;
    font-size: 14px;

        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
    label {
    display: block;
    margin-bottom: 8px;
    text-align: left;
    font-size: 15px;
    color: #0c046d;
    }
    .logo {
            width: 150px; /* Tăng kích thước logo */
            margin-bottom: 30px;
        }
        a.back-to-login {
    display: inline-block;
    margin-top: 20px;
    color: #00bcd4;
    text-decoration: none;
    font-size: 14px;
}

a.back-to-login:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>
    <div class="reset-password-container">
    <img src="Screenshot 2024-10-30 040256.png" alt="English with Mai Anh Logo" class="logo">
    <h3 style = "margin-bottom: 20px; margin-top: -30px; color: #0c046d;">Đặt lại mật khẩu</h3>
        <!-- Hiển thị thông báo -->
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

        <form action="xlrspw.php" method="POST">
            <label for="new_password">Mật khẩu mới:</label>
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required minlength="8"
        pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
        title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            
            <label for="confirm_password">Xác nhận mật khẩu mới:</label>
            <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required minlength="8"
        pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
        title="Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ cái, chữ số và ký tự đặc biệt">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit">Đặt Lại Mật Khẩu</button>
        </form>
        <a href="login.php" class="back-to-login">Quay lại trang đăng nhập</a>
    </div>
</body>
</html>
