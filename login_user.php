<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Author</title>
  <link rel="stylesheet" href="login_style.css" />
  <link rel="stylesheet" href="footer.css" />
</head>
<body>
  <div class="login-container">
    <form class="login-form" action="login_process.php" method="POST">
      <h2>Login Author</h2>
      <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            echo '<script>alert("' . $error . '");</script>';
        }
      ?>
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit" name="login">Login</button>
    </form>
    <p class="register-link">Don't Have Acount? <a href="registration_user.php">Registration Here</a></p>
  </div>
</body>
</html>
