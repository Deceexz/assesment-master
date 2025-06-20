<?php
// Sisipkan koneksi ke database
include 'koneksi.php';

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah data user_id dikirim melalui POST
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        // Periksa apakah pengguna dengan ID yang diberikan ada di database
        $query = "SELECT * FROM act_users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            // Update status_acc pengguna menjadi 1 (active)
            $update_query = "UPDATE act_users SET status_acc = 1 WHERE user_id = ?";
            $stmt_update = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt_update, "i", $user_id);
            $update_result = mysqli_stmt_execute($stmt_update);

            if ($update_result) {
                echo json_encode(array("status" => "success", "message" => "User unsuspended successfully."));
                exit();
            } else {
                echo json_encode(array("status" => "error", "message" => "Failed to unsuspend user."));
                exit();
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "User not found."));
            exit();
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "User ID not provided."));
        exit();
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
    exit();
}
?>
