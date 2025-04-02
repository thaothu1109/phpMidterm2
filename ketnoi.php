<?php
$host = 'localhost';
$db = 'user_database';
$user = 'root'; // Tên người dùng MySQL
$pass = '';     // Mật khẩu MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi kết nối: " . $e->getMessage());
}
?>
