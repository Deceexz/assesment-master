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
            // Update status_acc pengguna menjadi 1 (active)
            $update_query = "UPDATE act_users SET status_acc = 1 WHERE user_id = '$user_id'";
            $update_result = mysqli_query($conn, $update_query);

            if ($update_result) {
                // Berhasil memperbarui status_acc, kirim respons berhasil
                echo json_encode(array("status" => "success", "message" => "User unsuspended successfully."));
                exit();
            } else {
                // Gagal memperbarui status_acc, kirim respons error
                echo json_encode(array("status" => "error", "message" => "Failed to unsuspend user."));
                exit();
            }
        } else {
            // User tidak ditemukan, kirim respons error
            echo json_encode(array("status" => "error", "message" => "User not found."));
            exit();
        }
    } else {
        // Data user_id tidak diterima, kirim respons error
        echo json_encode(array("status" => "error", "message" => "User ID not provided."));
        exit();
    }
} else {
    // Permintaan bukan dari metode POST, kirim respons error
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
    exit();
}
?>
