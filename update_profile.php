<?php
session_start();
include 'koneksi.php'; // koneksi ke database

if (!isset($_SESSION['username'])) {
    echo "Anda harus login terlebih dahulu.";
    exit;
}

$username_session = $_SESSION['username'];
$success = $error = "";

// Ambil data user (binding)
$user_stmt = mysqli_prepare($conn, "SELECT * FROM act_users WHERE username = ?");
mysqli_stmt_bind_param($user_stmt, "s", $username_session);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);
mysqli_stmt_close($user_stmt);

if (!$user) {
    echo "Data pengguna tidak ditemukan atau terjadi kesalahan query.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $new_username = $_POST['username'];
    $birthdate = $_POST['birthdate'];
    $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : 2;
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    if ($role_id !== 1 && $role_id !== 2) {
        $error = "Role ID tidak tersedia.";
    } else {
        $check_stmt = mysqli_prepare($conn, "SELECT * FROM act_users WHERE username = ? AND username != ?");
        mysqli_stmt_bind_param($check_stmt, "ss", $new_username, $username_session);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username sudah digunakan oleh pengguna lain.";
        } elseif (!empty($password) && $password !== $retype_password) {
            $error = "Password yang Anda masukkan tidak cocok!";
        } else {
            if (!empty($password)) {
                $hashed_password = md5($password);
                $update_stmt = mysqli_prepare($conn, "UPDATE act_users SET 
                    first_name = ?, last_name = ?, email = ?, username = ?, password = ?, tanggal_lahir = ?, role_id = ? 
                    WHERE username = ?");
                mysqli_stmt_bind_param($update_stmt, "ssssssis", $first_name, $last_name, $email, $new_username, $hashed_password, $birthdate, $role_id, $username_session);
            } else {
                $update_stmt = mysqli_prepare($conn, "UPDATE act_users SET 
                    first_name = ?, last_name = ?, email = ?, username = ?, tanggal_lahir = ?, role_id = ? 
                    WHERE username = ?");
                mysqli_stmt_bind_param($update_stmt, "sssssis", $first_name, $last_name, $email, $new_username, $birthdate, $role_id, $username_session);
            }

            if (mysqli_stmt_execute($update_stmt)) {
                mysqli_stmt_close($update_stmt);
                $success = "Profil berhasil diperbarui.";
                $_SESSION['username'] = $new_username;

                // Ambil ulang data user terbaru
                $user_stmt = mysqli_prepare($conn, "SELECT * FROM act_users WHERE username = ?");
                mysqli_stmt_bind_param($user_stmt, "s", $new_username);
                mysqli_stmt_execute($user_stmt);
                $user_result = mysqli_stmt_get_result($user_stmt);
                $user = mysqli_fetch_assoc($user_result);
                mysqli_stmt_close($user_stmt);
            } else {
                $error = "Terjadi kesalahan saat memperbarui: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($check_stmt);
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
  <style>
    .alert-success {
      color: #155724;
      background-color: #d4edda;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
    .alert-error {
      color: #721c24;
      background-color: #f8d7da;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
  </style>
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
        <div class="alert-success"><?= $success ?></div>
      <?php elseif ($error): ?>
        <div class="alert-error"><?= $error ?></div>
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
