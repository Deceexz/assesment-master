<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login_admin.php");
    exit();
}

// Handle suspend / unsuspend
if (isset($_GET['action']) && isset($_GET['userId'])) {
    $action = $_GET['action'];
    $userId = $_GET['userId'];

    if ($action == 'suspend') {
        $update_query = "UPDATE act_users SET status_acc = 2 WHERE user_id = $userId";
    } elseif ($action == 'unsuspend') {
        $update_query = "UPDATE act_users SET status_acc = 1 WHERE user_id = $userId";
    }

    if (isset($update_query)) {
        $update_result = mysqli_query($conn, $update_query);
        $notif = ($update_result) ? "{$action}_success" : "{$action}_failed";
        header("Location: {$_SERVER['PHP_SELF']}?notif=$notif");
        exit();
    }
}

// Ambil semua user selain admin saat ini
$query = "SELECT * FROM act_users WHERE user_id != {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);
$users = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}

// Mapping role_id ke nama role
function getRoleName($role_id) {
    switch ($role_id) {
        case 1:
            return 'Admin';
        case 2:
            return 'Author';
        default:
            return 'Unknown';
    }
}

// Mapping status_acc ke status akun
function getAccountStatus($status_acc) {
    switch ($status_acc) {
        case 1:
            return 'Aktif';
        case 2:
            return 'Ditangguhkan';
        default:
            return 'Tidak Diketahui';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage User</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="manage_user_style.css">
    <link rel="stylesheet" href="container.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
</head>
<body>

<?php include 'header.php'; ?>

<aside id="sidebar">
    <nav class="category-bar">
        <a href="dashboard_admin.php" class="category-btn">Dashboard Admin</a>
        <a href="manage_user.php" class="category-btn">Manage User</a>
        <a href="membuat_berita.php" class="category-btn">Membuat Berita</a>
    </nav>
</aside>

<main class="main-content">
    <div class="container">
        <h2>Daftar Pengguna</h2>

        <table class="styled-table">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Role</th>
                <th>Status Akun</th>
                <th>Action</th>
            </tr>
            <?php
            $no = 1;
            foreach ($users as $user) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><strong><?= $user['username']; ?></strong></td>
                    <td><?= getRoleName($user['role_id']); ?></td>
                    <td><?= getAccountStatus($user['status_acc']); ?></td>
                    <td>
                        <a href="manage_user.php?action=<?= $user['status_acc'] == 1 ? 'suspend' : 'unsuspend' ?>&userId=<?= $user['user_id']; ?>"
                            class="btn <?= $user['status_acc'] == 1 ? 'btn-suspend' : 'btn-unsuspend' ?>">
                            <?= $user['status_acc'] == 1 ? 'Suspend' : 'Unsuspend'; ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
