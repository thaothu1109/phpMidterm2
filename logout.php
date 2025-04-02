<?php
session_start();

// Xóa tất cả dữ liệu session
session_unset();

// Hủy session
session_destroy();
include('session.php'); 
// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
