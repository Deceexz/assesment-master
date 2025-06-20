<?php
// Sisipkan koneksi ke database
include 'koneksi.php';

// Proses registrasi
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $tanggal_lahir = $_POST['birthdate'];
    $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 2;

    // Validasi apakah username sudah digunakan
    $check_username = "SELECT 1 FROM act_users WHERE username = ?";
    $stmt_username = mysqli_prepare($conn, $check_username);
    mysqli_stmt_bind_param($stmt_username, "s", $username);
    mysqli_stmt_execute($stmt_username);
    mysqli_stmt_store_result($stmt_username);

    // Validasi apakah email sudah digunakan
    $check_email = "SELECT 1 FROM act_users WHERE email = ?";
    $stmt_email = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt_email, "s", $email);
    mysqli_stmt_execute($stmt_email);
    mysqli_stmt_store_result($stmt_email);

    if (mysqli_stmt_num_rows($stmt_username) > 0) {
        echo '<script>alert("Username sudah digunakan!"); window.location.href = "login_user.php";</script>';
    } elseif (mysqli_stmt_num_rows($stmt_email) > 0) {
        echo '<script>alert("Email sudah digunakan!"); window.location.href = "login_user.php";</script>';
    } elseif ($password !== $retype_password) {
        echo '<script>alert("Password yang Anda masukkan tidak cocok!");</script>';
    } else {
        // Hash password (gunakan password_hash agar lebih aman)
        $hashed_password = md5($password);

        $insert_query = "INSERT INTO act_users (first_name, last_name, email, username, password, tanggal_lahir, role_id) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "ssssssi", $first_name, $last_name, $email, $username, $hashed_password, $tanggal_lahir, $role_id);

        if (mysqli_stmt_execute($stmt_insert)) {
            echo '<script>alert("Registrasi Anda Telah Berhasil, Silahkan untuk login kembali."); window.location.href = "login_user.php";</script>';
        } else {
            echo "Error saat insert: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_insert);
    }

    // Tutup semua statement
    mysqli_stmt_close($stmt_username);
    mysqli_stmt_close($stmt_email);
}
?>
