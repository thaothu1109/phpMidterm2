<?php
session_start(); // Bắt đầu session để kiểm tra trạng thái đăng nhập

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user'])) {
    // Nếu đã đăng nhập, chuyển hướng đến trang chủ
    header("Location: trangchu.php");
    exit(); // Dừng mã ở đây để tránh tiếp tục xử lý
} else {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit(); // Dừng mã ở đây để tránh tiếp tục xử lý
}
?>
