<?php
session_start();
require_once 'vendor/autoload.php';

use PHPGangsta_GoogleAuthenticator;

$secret = $_SESSION['secret_2fa'] ?? null;
if (!$secret) {
    die("Không có mã bảo mật 2FA.");
}

$ga = new PHPGangsta_GoogleAuthenticator();
$username = $_SESSION['user'] ?? 'user';
$qrCodeUrl = $ga->getQRCodeGoogleUrl($username, $secret, 'MyApp');

// Hiển thị mã QR
echo "<h2>Quét mã QR bằng Google Authenticator</h2>";
echo "<img src='" . $qrCodeUrl . "' />";
echo "<p><strong>Hoặc nhập mã thủ công:</strong> $secret</p>";
echo "<a href='xacthuc.php'>Tới trang xác thực</a>";