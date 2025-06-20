<?php
// Konfigurasi koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_berita";

// Buat koneksi prosedural
$conn = mysqli_connect($servername, $username, $password, $database);

// Periksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Tambahan: set charset agar mendukung karakter Jepang (Hiragana, dll)
mysqli_set_charset($conn, "utf8mb4");
?>
