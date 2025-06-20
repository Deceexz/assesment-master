<?php
session_start();
include 'koneksi.php';
date_default_timezone_set("Asia/Jakarta");

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

// Fungsi untuk membersihkan input dari tag HTML
function bersihkan_input($text) {
    return strip_tags($text); // Menghapus semua tag HTML
}

// Ambil data dari session & POST
$user_id       = $_SESSION['user_id'];
$nama_penulis  = $_SESSION['username'];
$judul_artikel = isset($_POST['judul_artikel']) ? bersihkan_input(trim($_POST['judul_artikel'])) : '';
$isi_artikel   = isset($_POST['isi_artikel']) ? bersihkan_input(trim($_POST['isi_artikel'])) : '';
$kategori      = isset($_POST['kategori']) ? intval($_POST['kategori']) : 0;

// Validasi minimal
if (empty($judul_artikel) || empty($isi_artikel) || $kategori <= 0) {
    echo "<script>alert('Judul, isi, dan kategori tidak boleh kosong.'); history.back();</script>";
    exit();
}

// Validasi dan proses upload file
if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['gambar']['name'];
    $file_tmp  = $_FILES['gambar']['tmp_name'];
    $file_size = $_FILES['gambar']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_size    = 3 * 1024 * 1024; // 3MB

    // Validasi ukuran
    if ($file_size > $max_size) {
        echo "<script>alert('Ukuran file terlalu besar. Maksimal 3 MB.'); history.back();</script>";
        exit();
    }

    // Validasi ekstensi
    if (!in_array($file_ext, $allowed_ext)) {
        echo "<script>alert('Format file tidak sesuai! Hanya jpg, jpeg, png, atau pdf.'); history.back();</script>";
        exit();
    }

    // Buat folder upload jika belum ada
    $upload_directory = __DIR__ . "/uploads/" . date("Y/m/d/");
    if (!file_exists($upload_directory)) {
        if (!mkdir($upload_directory, 0777, true)) {
            die("Gagal membuat folder upload: $upload_directory");
        }
    }

    // Nama file unik
    $nama_file_baru = generateRandomName($user_id) . "." . $file_ext;
    $path_file = $upload_directory . $nama_file_baru;

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($file_tmp, $path_file)) {

        // Simpan data ke database
        $query = "INSERT INTO post_article 
                  (user_id, username, title, description, category_id, post_date, post_img, status)
                  VALUES (?, ?, ?, ?, ?, SYSDATE(), ?, 'active')";

        $stmt = mysqli_prepare($conn, $query);
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "isssis", 
            $user_id, 
            $nama_penulis, 
            $judul_artikel, 
            $isi_artikel, 
            $kategori, 
            $nama_file_baru
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: dashboard_user.php?notif=success");
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan artikel.'); history.back();</script>";
        }

    } else {
        echo "<script>alert('Gagal mengunggah file ke server.'); history.back();</script>";
    }

} else {
    echo "<script>alert('Gambar wajib diunggah.'); history.back();</script>";
}

// Fungsi untuk menghasilkan nama file unik
function generateRandomName($user_id) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random = '';
    for ($i = 0; $i < 10; $i++) {
        $random .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $user_id . '_' . date("Ymd") . '_' . $random;
}
?>
