<?php

// Mulai session
session_start();

// Sisipkan koneksi ke database
include 'koneksi.php';
date_default_timezone_set("Asia/Jakarta");

// Periksa apakah parameter post_id dikirimkan melalui URL
if (!isset($_POST['post_id'])) {
    // Jika tidak ada parameter post_id, redirect ke halaman dashboard atau halaman lainnya
    header("Location: dashboard_user.php");
    exit();
}

// Ambil post_id dari parameter URL
$post_id = $_POST['post_id'];

// Query untuk mengambil data artikel berdasarkan post_id
$query = "SELECT * FROM post_article WHERE post_id = '$post_id'";
$result = mysqli_query($conn, $query);

// Periksa apakah artikel ditemukan
if (!$result || mysqli_num_rows($result) === 0) {
    // Jika artikel tidak ditemukan, redirect ke halaman dashboard atau halaman lainnya
    header("Location: dashboard_user.php");
    exit();
}

// Tangkap data yang dikirimkan melalui form
$nama_penulis = $_SESSION['username']; // Ambil username dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data artikel
$article = mysqli_fetch_assoc($result);

// Ambil nilai input dari form
$judul_artikel = $_POST['judul_artikel'];
$isi_artikel = $_POST['isi_artikel'];

// Periksa apakah ada file gambar yang diunggah
if (!empty($_FILES['gambar_baru']['name'])) {

    // Mendapatkan informasi tentang file yang diupload
    $file_name = $_FILES['gambar_baru']['name'];
    $file_tmp = $_FILES['gambar_baru']['tmp_name'];
    $file_size = $_FILES['gambar_baru']['size'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Batasan ukuran file (dalam bytes)
    $max_size = 3 * 1024 * 1024; // 3 MB

    // Periksa ukuran file
    if ($file_size > $max_size) {
        echo "Ukuran file terlalu besar. Maksimal 3 MB.";
        exit();
    }

    // Mendapatkan tanggal saat ini
    $tanggal_sekarang = str_replace("-","/",explode(" ",$article['post_date'])[0]);
    $tahun=explode("-",$tanggal_sekarang)[0];
    $bulan=explode("-",$tanggal_sekarang)[1];
    $hari=explode("-",$tanggal_sekarang)[2];
    

    // Generate nama file yang unik
    
    
    // Lokasi penyimpanan file
    $upload_directory = "C:/xampp/htdocs/assessment-master/uploads/".$tahun."/".$bulan."/".$hari."/";

    // Buat folder untuk menyimpan gambar jika belum ada
    if (!file_exists($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    // Ambil nama file gambar yang diunggah
    $nama_file = $_FILES['gambar_baru']['name'];
    $nama_file_baru = generateRandomName($user_id, $tanggal_sekarang) . "." . $file_ext;
    // Lokasi sementara file yang diunggah
    $lokasi_temp = $_FILES['gambar_baru']['tmp_name'];
    // Tentukan lokasi untuk menyimpan gambar di server
    $lokasi_gambar = 'C:/xampp/htdocs/assessment-master/uploads/' . str_replace("-","/",explode(" ",$article['post_date'])[0]) . "/" . $nama_file_baru;

    // Pindahkan file yang diunggah ke lokasi yang ditentukan
    if (move_uploaded_file($lokasi_temp, $lokasi_gambar)) {
        // Jika berhasil mengunggah gambar baru, hapus gambar lama dari server
        if (!empty($article['post_img'])) {
            $lokasi_gambar_lama = 'C:/xampp/htdocs/assessment-master/uploads/' . str_replace("-","/",explode(" ",$article['post_date'])[0]) . "/" . $article['post_img'];
            if (file_exists($lokasi_gambar_lama)) {
                unlink($lokasi_gambar_lama); // Hapus gambar lama
            }
        }

        // Perbarui artikel di database dengan gambar baru
        $query_update = "UPDATE post_article SET title = '$judul_artikel', description = '$isi_artikel', post_img = '$nama_file_baru' WHERE post_id = '$post_id'";

        if (mysqli_query($conn, $query_update)) {
            // Jika berhasil, redirect ke halaman dashboard dengan notifikasi sukses
            header("Location: dashboard_user.php?notif=success");
            exit();
        } else {
            // Jika gagal, tampilkan pesan error
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Jika gagal mengunggah gambar baru, tampilkan pesan error
        echo "Error: Gambar gagal diunggah.";
    }
} else {
    // Jika tidak ada gambar yang diunggah, hanya perbarui judul dan isi artikel
    $query_update = "UPDATE post_article SET title = '$judul_artikel', description = '$isi_artikel' WHERE post_id = '$post_id'";

    if (mysqli_query($conn, $query_update)) {
        // Jika berhasil, redirect ke halaman dashboard dengan notifikasi sukses
        header("Location: dashboard_user.php?notif=success");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
}

function generateRandomName($user_id, $date) {
    $randomString = '';

    // Ambil tahun, bulan, dan tanggal dari tanggal yang diberikan
    $year = date("Y", strtotime($date));
    $month = date("m", strtotime($date));
    $day = date("d", strtotime($date));

    // Format nama file: tahun-bulan-tanggal_user_id_randomstring
    // $randomString .= $year . "-" . $month . "-" . $day . "_" . $user_id;

    // Karakter yang mungkin digunakan untuk nama file
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Panjang string random yang diinginkan
    $length = 10;

    // Pembentukan bagian random dari nama file
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}

?>
