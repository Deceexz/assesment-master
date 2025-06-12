<?php
// Sisipkan koneksi ke database
include 'koneksi.php';

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah data user_id dikirim melalui POST
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Periksa apakah pengguna dengan ID yang diberikan ada di database
        $query = "SELECT * FROM act_users WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            // Update status_acc pengguna menjadi 2 (suspended)
            $update_query = "UPDATE act_users SET status_acc = 2 WHERE user_id = '$user_id'";
            $update_result = mysqli_query($conn, $update_query);

            if ($update_result) {
                // Berhasil memperbarui status_acc, redirect kembali ke halaman manage_user.php
                header("Location: manage_user.php");
                exit();
            } else {
                // Gagal memperbarui status_acc, redirect kembali ke halaman manage_user.php dengan pesan error
                header("Location: manage_user.php?error=failed_suspension");
                exit();
            }
        } else {
            // User tidak ditemukan, redirect kembali ke halaman manage_user.php dengan pesan error
            header("Location: manage_user.php?error=user_not_found");
            exit();
        }
    } else {
        // Data user_id tidak diterima, redirect kembali ke halaman manage_user.php dengan pesan error
        header("Location: manage_user.php?error=no_user_id");
        exit();
    }
} else {
    // Permintaan bukan dari metode POST, redirect kembali ke halaman manage_user.php dengan pesan error
    header("Location: manage_user.php?error=invalid_request_method");
    exit();
}
?>

<!-- Form untuk mengirim data user_id ke suspend_user.php -->
<form id="suspendForm" action="suspend_user.php" method="POST" style="display: none;">
    <input type="hidden" id="userId" name="user_id">
</form>
