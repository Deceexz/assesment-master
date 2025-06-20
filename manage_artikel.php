<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

$username = $_SESSION['username'];

// Handle aksi takedown/restore
if (isset($_GET['action']) && isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
    $action = $_GET['action'];
    $new_status = ($action === 'restore') ? 'active' : 'takedown';

    // Binding query untuk update status artikel
    $update_query = "UPDATE post_article SET status = ? WHERE post_id = ? AND username = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sis", $new_status, $post_id, $username);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        $notif = ($new_status === 'active') ? 'restore_success' : 'takedown_success';
        header("Location: manage_artikel.php?notif=$notif");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        echo "Terjadi kesalahan saat memproses data.";
        exit();
    }
}

// Ambil semua artikel milik user (binding)
$articles = [];
$select_query = "
    SELECT pa.*, cp.category_description 
    FROM post_article pa 
    JOIN category_post cp ON pa.category_id = cp.category_id 
    WHERE pa.username = ?";
$stmt = mysqli_prepare($conn, $select_query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
}
mysqli_stmt_close($stmt);

// Pagination
$articles_per_page = 10;
$total_pages = ceil(count($articles) / $articles_per_page);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start_index = ($page - 1) * $articles_per_page;
$end_index = min($start_index + $articles_per_page, count($articles));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Artikel - Portal Berita Neuron</title>
  <link rel="stylesheet" href="manage_user_style.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="container.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .article-table { width: 100%; border-collapse: collapse; }
    .article-table th, .article-table td { border: 1px solid #ddd; padding: 8px; }
    .article-table th { background-color: #f2f2f2; }
    .notif { background-color: #dff0d8; padding: 10px; margin-bottom: 10px; border-radius: 5px; color: #3c763d; }
    .pagination { margin-top: 20px; text-align: center; }
    .pagination a { color: black; padding: 8px 12px; text-decoration: none; border: 1px solid #ccc; margin: 0 4px; }
    .pagination a.active { background-color: #4CAF50; color: white; }
    .pagination a:hover:not(.active) { background-color: #ddd; }

    /* Tombol aksi */
    .action-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      margin: 2px;
      text-decoration: none;
    }
    .edit-button {
      background-color: #3399ff;
      border-radius: 50%;
      color: white;
    }
    .takedown-button, .restore-button {
      background-color: red;
      border-radius: 5px;
      color: white;
    }
    .action-button i {
      font-size: 16px;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<aside id="sidebar">
  <nav class="category-bar">
    <a href="index.php" class="category-btn">Home</a>
    <a href="membuat_berita.php" class="category-btn">Membuat Artikel</a>
    <a href="manage_artikel.php" class="category-btn">Manage Artikel</a>
  </nav>
</aside>

<main class="main-content">
  <div class="container">
    <?php if (isset($_GET['notif'])): ?>
      <?php if ($_GET['notif'] === 'takedown_success'): ?>
        <div class="notif">Artikel berhasil di-takedown.</div>
      <?php elseif ($_GET['notif'] === 'restore_success'): ?>
        <div class="notif">Artikel berhasil dipulihkan.</div>
      <?php endif; ?>
    <?php endif; ?>

    <h2>Daftar Artikel Anda</h2>
    <table class="article-table">
      <tr>
        <th>Gambar</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Isi</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
      <?php for ($i = $start_index; $i < $end_index; $i++):
        $article = $articles[$i];
        $isi_singkat = strlen($article['description']) > 20 ? substr($article['description'], 0, 20) . "..." : $article['description'];
        $status = $article['status'] ?? 'published';
        $img_path = "http://localhost/assessment-master/uploads/" . str_replace("-", "/", explode(" ", $article['post_date'])[0]) . "/" . $article['post_img'];
      ?>
        <tr>
          <td><img src="<?= $img_path ?>" alt="Gambar" style="max-width: 100px;"></td>
          <td><a href="full_article.php?post_id=<?= $article['post_id'] ?>"><?= $article['title'] ?></a></td>
          <td><?= $article['username'] ?></td>
          <td><?= $article['category_description'] ?></td>
          <td><?= $isi_singkat ?></td>
          <td><?= ucfirst($status) ?></td>
          <td>
            <?php if ($status === 'takedown'): ?>
              <a href="javascript:void(0);" onclick="confirmRestore(<?= $article['post_id'] ?>)" class="action-button restore-button" title="Pulihkan">
                <i class="fa fa-undo"></i>
              </a>
            <?php else: ?>
              <a href="edit_artikel.php?post_id=<?= $article['post_id'] ?>" class="action-button edit-button" title="Edit">
                <i class="fa fa-pencil"></i>
              </a>
              <a href="javascript:void(0);" onclick="confirmTakedown(<?= $article['post_id'] ?>)" class="action-button takedown-button" title="Takedown">
                <i class="fa fa-trash"></i>
              </a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endfor; ?>
    </table>

    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">Sebelumnya</a>
      <?php endif; ?>
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <a href="?page=<?= $p ?>"<?= $p == $page ? ' class="active"' : '' ?>><?= $p ?></a>
      <?php endfor; ?>
      <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>">Berikutnya</a>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Modal Konfirmasi -->
<div id="actionModal" class="modal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
  <div class="modal-content" style="background-color: #fff; padding: 20px; border-radius: 8px; text-align: center;">
    <h3 id="modalTitle">Konfirmasi Aksi</h3>
    <p id="modalMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
    <div class="modal-buttons" style="margin-top: 20px;">
      <button onclick="closeModal()">Batal</button>
      <button id="confirmActionBtn">Lanjut</button>
    </div>
  </div>
</div>

<script>
  let actionType = null;
  let targetPostId = null;

  function confirmTakedown(postId) {
    actionType = 'takedown';
    targetPostId = postId;
    showModal('Konfirmasi Takedown', 'Yakin ingin men-takedown artikel ini?');
  }

  function confirmRestore(postId) {
    actionType = 'restore';
    targetPostId = postId;
    showModal('Konfirmasi Restore', 'Pulihkan artikel ini?');
  }

  function showModal(title, message) {
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalMessage").innerText = message;
    document.getElementById("actionModal").style.display = "flex";
  }

  function closeModal() {
    document.getElementById("actionModal").style.display = "none";
    actionType = null;
    targetPostId = null;
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("confirmActionBtn").addEventListener("click", () => {
      if (targetPostId && actionType) {
        window.location.href = `manage_artikel.php?action=${actionType}&post_id=${targetPostId}`;
      }
    });

    window.addEventListener("click", function(e) {
      const modal = document.getElementById("actionModal");
      if (e.target === modal) {
        closeModal();
      }
    });
  });
</script>

</body>
</html>
