<?php
// Mulai session
session_start();

// Sisipkan koneksi ke database
include 'koneksi.php';

// Periksa apakah parameter post_id dikirimkan melalui URL
if (!isset($_GET['post_id'])) {
    // Jika tidak ada parameter post_id, redirect ke halaman dashboard atau halaman lainnya
    header("Location: dashboard_user.php");
    exit();
}

// Ambil post_id dari parameter URL
$post_id = $_GET['post_id'];

// Query untuk mengambil data artikel berdasarkan post_id
$query = "SELECT * FROM post_article WHERE post_id = '$post_id'";
$result = mysqli_query($conn, $query);

// Ambil data artikel
$article = mysqli_fetch_assoc($result);

// Periksa apakah artikel ditemukan
if (!$result || mysqli_num_rows($result) === 0) {
    // Jika artikel tidak ditemukan, redirect ke halaman dashboard atau halaman lainnya
    header("Location: dashboard_user.php");
    exit();
}

// Query untuk menghapus artikel dari database
$query_delete = "DELETE FROM post_article WHERE post_id = '$post_id'";

if (mysqli_query($conn, $query_delete)) {
    $lokasi_gambar_lama = 'C:/xampp/htdocs/assessment-master/uploads/' . str_replace("-","/",explode(" ",$article['post_date'])[0]) . "/" . $article['post_img'];
    if (file_exists($lokasi_gambar_lama)) {
        unlink($lokasi_gambar_lama); // Hapus gambar lama
    }
    // Jika berhasil menghapus, redirect ke halaman dashboard dengan notifikasi sukses
    header("Location: dashboard_user.php?notif=delete_success");
    exit();
} else {
    // Jika gagal, tampilkan pesan error
    echo "Error: " . mysqli_error($conn);
}
?>
