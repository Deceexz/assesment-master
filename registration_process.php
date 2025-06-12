<?php
// Sisipkan koneksi ke database
include 'koneksi.php';

// Proses registrasi
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email']; // Tambahkan email
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $tanggal_lahir = $_POST['birthdate']; // Gunakan $_POST['birthdate'] untuk mendapatkan nilai tanggal lahir

    // Ambil role ID dari form (1 = admin, 2 = author). Default ke author jika tidak diset.
    $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 2;

    // Lakukan pengecekan apakah username sudah digunakan
    $query = "SELECT * FROM act_users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Username sudah digunakan, tampilkan pesan kesalahan
        echo '<script>alert("Username sudah digunakan!");</script>';
    } elseif ($password != $retype_password) {
        // Password tidak cocok dengan re-type password, tampilkan pesan kesalahan
        echo '<script>alert("Password yang Anda masukkan tidak cocok!");</script>';
    } else {
        // Jika username belum digunakan dan password cocok, tambahkan pengguna baru ke database
        // Hash password menggunakan md5
        $hashed_password = md5($password);

        $query = "INSERT INTO act_users (first_name, last_name, email, username, password, tanggal_lahir, role_id) 
                  VALUES ('$first_name', '$last_name', '$email', '$username', '$hashed_password', '$tanggal_lahir', '$role_id')";
        $insert_result = mysqli_query($conn, $query);
        
        if ($insert_result) {
            // Registrasi berhasil, tampilkan pesan sukses
            echo '<script>alert("Registrasi Anda Telah Berhasil, Silahkan untuk login kembali."); window.location.href = "login_user.php";</script>';
        } else {
            // Terjadi kesalahan saat menambahkan data ke database, tampilkan pesan kesalahan
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
