<?php
// Mulai session
session_start();

// Sisipkan koneksi ke database
include 'koneksi.php';

// Periksa apakah parameter post_id dikirimkan melalui URL
if (!isset($_GET['post_id'])) {
    header("Location: dashboard_user.php");
    exit();
}

// Ambil dan sanitasi post_id
$post_id = intval($_GET['post_id']);

// Ambil data artikel terlebih dahulu untuk mengetahui nama file gambarnya
$query_select = "SELECT post_img, post_date FROM post_article WHERE post_id = ?";
$stmt_select = mysqli_prepare($conn, $query_select);
mysqli_stmt_bind_param($stmt_select, "i", $post_id);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt_select);

// Jika tidak ditemukan, redirect
if (!$article) {
    header("Location: dashboard_user.php");
    exit();
}

// Hapus artikel menggunakan prepared statement
$query_delete = "DELETE FROM post_article WHERE post_id = ?";
$stmt_delete = mysqli_prepare($conn, $query_delete);
mysqli_stmt_bind_param($stmt_delete, "i", $post_id);

if (mysqli_stmt_execute($stmt_delete)) {
    mysqli_stmt_close($stmt_delete);

    // Hapus gambar dari direktori
    $tanggal = explode(" ", $article['post_date'])[0]; // Format: YYYY-MM-DD
    $folder_path = 'C:/xampp/htdocs/assessment-master/uploads/' . str_replace("-", "/", $tanggal) . '/';
    $lokasi_gambar_lama = $folder_path . $article['post_img'];

    if (file_exists($lokasi_gambar_lama)) {
        unlink($lokasi_gambar_lama);
    }

    header("Location: dashboard_user.php?notif=delete_success");
    exit();
} else {
    echo "Gagal menghapus artikel: " . mysqli_error($conn);
}
?>
