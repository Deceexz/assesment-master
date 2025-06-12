<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Registration Author</title>
  <link rel="stylesheet" href="register_styles.css" />
  <link rel="stylesheet" href="footer.css" />
</head>
<body>
  <div class="wave-background"></div>

  <div class="container">
    <form class="form-wrapper" action="registration_process.php" method="POST">
      <div class="avatar-section">
        <div class="avatar">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar" />
          <label class="upload-icon">+</label>
        </div>
      </div>

      <div class="input-group-custom">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="input-group-custom">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required />
      </div>

      <div class="input-group-custom">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required />
      </div>

      <div class="input-group-custom">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
      </div>

      <div class="input-group-custom">
        <label for="birthdate">Tanggal Lahir</label>
        <input type="date" id="birthdate" name="birthdate" required />
      </div>

      <!-- Tambahan: Role ID -->
      <div class="input-group-custom">
        <label for="role_id">Pilih Role</label>
        <select id="role_id" name="role_id" required>
          <option value="2" selected>Author</option>
          <option value="1">Admin</option>
        </select>
      </div>

      <div class="input-group-custom password-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
        <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
      </div>

      <div class="input-group-custom password-group">
        <label for="retype_password">Re-type Password</label>
        <input type="password" id="retype_password" name="retype_password" required />
        <span class="password-toggle" onclick="togglePassword('retype_password')">👁️</span>
      </div>

      <button type="submit" class="primary-btn" name="register">Create My Account</button>
    </form>
  </div>

  <?php include 'footer.php'; ?>

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
