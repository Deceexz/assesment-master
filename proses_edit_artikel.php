<?php
session_start();
include 'koneksi.php';
date_default_timezone_set("Asia/Jakarta");

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

// Pastikan post_id diset dari form
if (!isset($_POST['post_id'])) {
    header("Location: dashboard_user.php");
    exit();
}

$post_id       = intval($_POST['post_id']);
$user_id       = $_SESSION['user_id'];
$nama_penulis  = $_SESSION['username'];
$judul_artikel = isset($_POST['judul_artikel']) ? strip_tags(trim($_POST['judul_artikel'])) : '';
$isi_artikel   = isset($_POST['isi_artikel']) ? trim($_POST['isi_artikel']) : ''; // JANGAN strip_tags karena TinyMCE pakai HTML

// Ambil artikel lama
$query = "SELECT * FROM post_article WHERE post_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$article) {
    header("Location: dashboard_user.php");
    exit();
}

$update_with_image = false;
$nama_file_baru = '';

if (!empty($_FILES['gambar_baru']['name'])) {
    $file_name = $_FILES['gambar_baru']['name'];
    $file_tmp  = $_FILES['gambar_baru']['tmp_name'];
    $file_size = $_FILES['gambar_baru']['size'];
    $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_size = 3 * 1024 * 1024;

    if ($file_size > $max_size) {
        echo "<script>alert('Ukuran file terlalu besar. Maksimal 3 MB.'); history.back();</script>";
        exit();
    }

    if (!in_array($file_ext, $allowed_ext)) {
        echo "<script>alert('Format file tidak sesuai! Hanya jpg, jpeg, png, pdf.'); history.back();</script>";
        exit();
    }

    $original_date = explode(" ", $article['post_date'])[0];
    $upload_directory = __DIR__ . "/uploads/" . str_replace("-", "/", $original_date) . "/";

    if (!file_exists($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    $nama_file_baru = generateRandomName($user_id, $original_date) . '.' . $file_ext;
    $path_file = $upload_directory . $nama_file_baru;

    if (move_uploaded_file($file_tmp, $path_file)) {
        if (!empty($article['post_img'])) {
            $old_file = $upload_directory . $article['post_img'];
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        $update_with_image = true;
    } else {
        echo "<script>alert('Gagal mengunggah gambar baru.'); history.back();</script>";
        exit();
    }
}

if ($update_with_image) {
    $query_update = "UPDATE post_article SET title = ?, description = ?, post_img = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt, "sssi", $judul_artikel, $isi_artikel, $nama_file_baru, $post_id);
} else {
    $query_update = "UPDATE post_article SET title = ?, description = ? WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt, "ssi", $judul_artikel, $isi_artikel, $post_id);
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
        header("Location: manage_artikel_admin.php?notif=success");
    } else {
        header("Location: dashboard_user.php?notif=success");
    }
    exit();
} else {
    echo "Error saat update artikel: " . mysqli_error($conn);
    mysqli_stmt_close($stmt);
}

function generateRandomName($user_id, $date) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random = '';
    for ($i = 0; $i < 10; $i++) {
        $random .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $user_id . '_' . date("Ymd", strtotime($date)) . '_' . $random;
}
?>