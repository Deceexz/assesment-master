<?php
session_start();

// Hapus variabel session tertentu
unset($_SESSION['username']);
unset($_SESSION['user_id']);
unset($_SESSION['liked_articles']); // Penting agar bisa like lagi saat login ulang

// Hapus semua session yang tersisa
session_unset();

// Hancurkan seluruh session
session_destroy();

// Redirect kembali ke halaman utama
header("Location: index.php");
exit();
?>
