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
    $userId = intval($_GET['userId']);

    if ($action == 'suspend') {
        $status_acc = 2;
    } elseif ($action == 'unsuspend') {
        $status_acc = 1;
    }

    if (isset($status_acc)) {
        $stmt = mysqli_prepare($conn, "UPDATE act_users SET status_acc = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $status_acc, $userId);
        $update_result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $notif = ($update_result) ? "{$action}_success" : "{$action}_failed";
        header("Location: {$_SERVER['PHP_SELF']}?notif=$notif");
        exit();
    }
}

// Ambil semua user selain admin saat ini
$users = [];
$stmt = mysqli_prepare($conn, "SELECT * FROM act_users WHERE user_id != ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
mysqli_stmt_close($stmt);

function getRoleName($role_id) {
    switch ($role_id) {
        case 1: return 'Admin';
        case 2: return 'Author';
        default: return 'Unknown';
    }
}

function getAccountStatus($status_acc) {
    switch ($status_acc) {
        case 1: return 'Aktif';
        case 2: return 'Ditangguhkan';
        default: return 'Tidak Diketahui';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manage User</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="manage_user_style.css">
    <link rel="stylesheet" href="container.css">
    <link rel="stylesheet" href="footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-evenly;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .cancel-btn {
            background-color: #ccc;
            color: #333;
        }

        .cancel-btn:hover {
            background-color: #bbb;
        }

        .confirm-btn {
            background-color: #d9534f;
            color: white;
        }

        .confirm-btn:hover {
            background-color: #c9302c;
        }

        .btn-suspend {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-unsuspend {
            background-color: #2ecc71;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-suspend:hover {
            background-color: #c0392b;
        }

        .btn-unsuspend:hover {
            background-color: #27ae60;
        }
    </style>
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
                    <td><strong><?= htmlspecialchars($user['username']); ?></strong></td>
                    <td><?= getRoleName($user['role_id']); ?></td>
                    <td><?= getAccountStatus($user['status_acc']); ?></td>
                    <td>
                        <button onclick="openModal('<?= $user['status_acc'] == 1 ? 'suspend' : 'unsuspend' ?>', <?= $user['user_id']; ?>)"
                                class="<?= $user['status_acc'] == 1 ? 'btn-suspend' : 'btn-unsuspend' ?>">
                            <?= $user['status_acc'] == 1 ? 'Suspend' : 'Unsuspend'; ?>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</main>

<!-- Modal Konfirmasi -->
<div id="actionModal" class="modal">
  <div class="modal-content">
    <h3 id="modalTitle">Konfirmasi Aksi</h3>
    <p id="modalMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
    <div class="modal-buttons">
      <button class="cancel-btn" onclick="closeModal()">Batal</button>
      <button class="confirm-btn" id="confirmActionBtn">Lanjut</button>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
  let actionType = null;
  let targetUserId = null;

  function openModal(action, userId) {
    actionType = action;
    targetUserId = userId;

    const title = action === 'suspend' ? 'Konfirmasi Suspend' : 'Konfirmasi Unsuspend';
    const message = action === 'suspend'
        ? 'Apakah Anda yakin ingin menangguhkan pengguna ini?'
        : 'Apakah Anda yakin ingin mengaktifkan kembali pengguna ini?';

    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalMessage").innerText = message;
    document.getElementById("actionModal").style.display = "flex";
  }

  function closeModal() {
    document.getElementById("actionModal").style.display = "none";
    actionType = null;
    targetUserId = null;
  }

  document.getElementById("confirmActionBtn").addEventListener("click", () => {
    if (targetUserId && actionType) {
      window.location.href = `manage_user.php?action=${actionType}&userId=${targetUserId}`;
    }
  });

  window.addEventListener("click", function(e) {
    const modal = document.getElementById("actionModal");
    if (e.target === modal) {
      closeModal();
    }
  });
</script>

</body>
</html>
