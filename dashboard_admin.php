<?php
// Mulai session
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    // Jika tidak, redirect ke halaman login_admin.php
    header("Location: login_admin.php");
    exit();
}

// Periksa apakah role_id pengguna adalah 1
if ($_SESSION['role_id'] != 1) {
    // Jika tidak, redirect ke halaman login_admin.php
    header("Location: login_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="manage_user_style.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="container.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

<?php include 'header.php'; ?>

<aside id="sidebar">
    <nav class="category-bar">
        <a href="manage_user.php" class="category-btn">Manage User</a>
        <a href="manage_artikel_admin.php" class="category-btn">Manage Article</a>
    </nav>
</aside>


</body>
</html>
