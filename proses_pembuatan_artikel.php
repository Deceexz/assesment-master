<?php
session_start();
include 'koneksi.php';
date_default_timezone_set("Asia/Jakarta");

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$nama_penulis     = $_SESSION['username'];
$judul_artikel    = $_POST['judul_artikel'];
$isi_artikel      = $_POST['isi_artikel'];
$kategori         = $_POST['kategori'];
$user_id          = $_SESSION['user_id'];
$tanggal_posting  = date("j F Y");

if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['gambar']['name'];
    $file_tmp  = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];
    $file_ext  = pathinfo($file_name, PATHINFO_EXTENSION);

    $max_size = 3 * 1024 * 1024;
    if ($file_size > $max_size) {
        echo "Ukuran file terlalu besar. Maksimal 3 MB.";
        exit();
    }

    // Buat folder upload berdasarkan tanggal
    $upload_directory = __DIR__ . "/uploads/" . date("Y/m/d/");

    if (!file_exists($upload_directory)) {
        if (!mkdir($upload_directory, 0777, true)) {
            die("Gagal membuat folder: " . $upload_directory);
        }
    }

    // Buat nama file unik
    $nama_file_baru = generateRandomName($user_id, date("Y-m-d")) . "." . $file_ext;
    $path_file = $upload_directory . $nama_file_baru;

    if (move_uploaded_file($file_tmp, $path_file)) {
        $isi_artikel_dengan_tanggal = $tanggal_posting . " (" . $isi_artikel . ")";

        // 🟢 Simpan artikel dan langsung set status = 'active'
        $query = "INSERT INTO post_article (
                    user_id, username, title, description, category_id, post_date, post_img, status
                  ) VALUES (
                    '$user_id', '$nama_penulis', '$judul_artikel', '$isi_artikel_dengan_tanggal',
                    '$kategori', SYSDATE(), '$nama_file_baru', 'active'
                  )";

        if (mysqli_query($conn, $query)) {
            header("Location: dashboard_user.php?notif=success");
            exit();
        } else {
            echo "Error saat menyimpan artikel: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal memindahkan gambar ke direktori tujuan.";
    }
} else {
    echo "Terjadi kesalahan saat mengunggah gambar.";
}

// Fungsi untuk generate nama acak
function generateRandomName($user_id, $date) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 10;
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $user_id . '_' . date("Ymd") . '_' . $randomString;
}
?>
