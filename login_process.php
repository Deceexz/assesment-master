<?php
// Sisipkan koneksi ke database
include 'koneksi.php';

// Mulai session
session_start();

// Proses login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cari pengguna dengan username yang cocok
    $query = "SELECT user_id, role_id, username, password, status_acc FROM act_users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) { // Periksa apakah query berhasil dieksekusi
        if (mysqli_num_rows($result) == 1) {
            // Pengguna ditemukan, periksa password
            // Pengguna ditemukan, periksa password
$row = mysqli_fetch_assoc($result);
$stored_password = $row['password'];
$status_acc = $row['status_acc'];

if (md5($password) == $stored_password) {
                // Periksa status akun
                if ($status_acc == 2) {
                    // Akun ter-suspend, tampilkan pesan kesalahan menggunakan JavaScript
                    echo "<script>
                            if(confirm('Mohon Maaf, akun Anda telah di-suspend. Silakan menghubungi admin untuk membuka akun kembali.')) {
                                window.location.href = 'login_user.php';
                            }
                          </script>";
                    exit();
                } else {
                    // Password benar, simpan user_id, role_id, dan username ke dalam session
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['role_id'] = $row['role_id'];
                    $_SESSION['username'] = $row['username'];

                    // Periksa role_id
                    if ($_SESSION['role_id'] == 1) {
                        // Jika role_id = 1, redirect ke dashboard_admin.php
                        header("Location: dashboard_admin.php");
                        exit();
                    } else {
                        // Redirect ke halaman dashboard_user.php
                        header("Location: dashboard_user.php");
                        exit();
                    }
                }
            } else {
                // Password salah, kembalikan ke halaman login_user.php bersama pesan kesalahan
                header("Location: login_user.php?error=Password salah.");
                exit();
            }
        } else {
            // Username tidak terdaftar, kembalikan ke halaman login_user.php bersama pesan kesalahan
            header("Location: login_user.php?error=Username tidak terdaftar.");
            exit();
        }
    } else {
        // Jika query tidak berhasil dieksekusi, kembalikan ke halaman login_user.php dengan pesan kesalahan
        header("Location: login_user.php?error=Terjadi kesalahan. Silakan coba lagi.");
        exit();
    }
}
?>
