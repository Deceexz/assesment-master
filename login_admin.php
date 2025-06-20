<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Admin</title>
  <link rel="stylesheet" href="login_styles.css?v=1" />
</head>
<body>
  <div class="page-background">
    <div class="form-container">
      <form class="login-form" action="login_process.php" method="POST">
        <img src="http://localhost/assessment-master/uploads/thebloglogo.jpg" class="avatar" alt="Avatar" />
        <h2>Login Admin</h2>
        <?php
          if (isset($_GET['error'])) {
              $error = $_GET['error'];
              echo '<div class="error-message">' . htmlspecialchars($error) . '</div>';
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
    </div>
  </div>
</body>
</html>
