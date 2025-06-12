<?php
session_start();
include 'koneksi.php'; // koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$username_session = $_SESSION['username'];
$success = $error = "";

// Ambil data user berdasarkan username dari session
$user_query = "SELECT * FROM act_users WHERE username = '$username_session'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Jika data user tidak ditemukan
if (!$user) {
    echo "Data pengguna tidak ditemukan atau terjadi kesalahan query: " . mysqli_error($conn);
    exit;
}

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $new_username = $_POST['username'];
    $birthdate = $_POST['birthdate'];
    $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 2;
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    // Cek apakah username baru sudah digunakan oleh user lain
    $check_query = "SELECT * FROM act_users WHERE username = '$new_username' AND username != '$username_session'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Username sudah digunakan oleh pengguna lain.";
    } elseif (!empty($password) && $password !== $retype_password) {
        $error = "Password yang Anda masukkan tidak cocok!";
    } else {
        if (!empty($password)) {
            $hashed_password = md5($password);
            $update_query = "UPDATE act_users SET 
                first_name = '$first_name',
                last_name = '$last_name',
                email = '$email',
                username = '$new_username',
                password = '$hashed_password',
                tanggal_lahir = '$birthdate',
                role_id = '$role_id'
                WHERE username = '$username_session'";
        } else {
            $update_query = "UPDATE act_users SET 
                first_name = '$first_name',
                last_name = '$last_name',
                email = '$email',
                username = '$new_username',
                tanggal_lahir = '$birthdate',
                role_id = '$role_id'
                WHERE username = '$username_session'";
        }

        if (mysqli_query($conn, $update_query)) {
            $success = "Profil berhasil diperbarui.";

            // Perbarui session username jika berubah
            $_SESSION['username'] = $new_username;

            // Refresh user data
            $user_query = "SELECT * FROM act_users WHERE username = '$new_username'";
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);
        } else {
            $error = "Terjadi kesalahan saat memperbarui: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Update Profile</title>
  <link rel="stylesheet" href="register_styles.css" />
</head>
<body>
  <div class="wave-background"></div>

  <div class="container">
    <form class="form-wrapper" method="POST">
      <div class="avatar-section">
        <div class="avatar">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar" />
          <label class="upload-icon">+</label>
        </div>
      </div>

      <?php if ($success): ?>
        <p style="color: green;"><?= $success ?></p>
      <?php elseif ($error): ?>
        <p style="color: red;"><?= $error ?></p>
      <?php endif; ?>

      <div class="input-group-custom">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required />
      </div>

      <div class="input-group-custom">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required />
      </div>

      <div class="input-group-custom">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required />
      </div>

      <div class="input-group-custom">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required />
      </div>

      <div class="input-group-custom">
        <label for="birthdate">Tanggal Lahir</label>
        <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($user['tanggal_lahir'] ?? '') ?>" required />
      </div>

      <div class="input-group-custom">
        <label for="role_id">Pilih Role</label>
        <select id="role_id" name="role_id" required>
          <option value="2" <?= ($user['role_id'] == 2) ? 'selected' : '' ?>>Author</option>
          <option value="1" <?= ($user['role_id'] == 1) ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <div class="input-group-custom password-group">
        <label for="password">Password Baru (opsional)</label>
        <input type="password" id="password" name="password" />
        <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
      </div>

      <div class="input-group-custom password-group">
        <label for="retype_password">Re-type Password</label>
        <input type="password" id="retype_password" name="retype_password" />
        <span class="password-toggle" onclick="togglePassword('retype_password')">👁️</span>
      </div>

      <button type="submit" class="primary-btn" name="update">Update Profile</button>
    </form>
  </div>


  <script>
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const icon = input.nextElementSibling;
      if (input.type === "password") {
        input.type = "text";
        icon.textContent = "🙈";
      } else {
        input.type = "password";
        icon.textContent = "👁️";
      }
    }
  </script>
</body>
</html>
