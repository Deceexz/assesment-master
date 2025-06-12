<?php
// Mulai session
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

include 'koneksi.php';

$username = $_SESSION['username'];

$query = "SELECT pa.*, cp.category_description 
          FROM post_article pa, category_post cp 
          WHERE username = '$username' 
          AND pa.category_id = cp.category_id";

$result = mysqli_query($conn, $query);
$articles = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
}

$articles_per_page = 10;
$total_pages = ceil(count($articles) / $articles_per_page);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_index = ($page - 1) * $articles_per_page;
$end_index = min($start_index + $articles_per_page, count($articles));
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Berita Neuron</title>
  <link rel="stylesheet" href="manage_user_style.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="container.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    .article-table {
      width: 100%;
      border-collapse: collapse;
    }

    .article-table td, .article-table th {
      border: 1px solid #ddd;
      padding: 8px;
    }

    .article-table th {
      background-color: #f2f2f2;
    }

    .pagination {
      margin-top: 20px;
      text-align: center;
    }

    .pagination a {
      color: black;
      display: inline-block;
      padding: 8px 16px;
      text-decoration: none;
      transition: background-color .3s;
      border: 1px solid #ddd;
      margin: 0 4px;
    }

    .pagination a.active {
      background-color: #4CAF50;
      color: white;
      border: 1px solid #4CAF50;
    }

    .pagination a:hover:not(.active) {
      background-color: #ddd;
    }

    /* MODAL STYLING */
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: white;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
      animation: slideDown 0.3s ease;
    }

    .modal-content h3 {
      margin-bottom: 10px;
    }

    .modal-buttons {
      margin-top: 20px;
    }

    .modal-buttons button {
      padding: 10px 20px;
      margin: 0 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .modal-buttons button:first-child {
      background-color: #ccc;
      color: #333;
    }

    .modal-buttons button:last-child {
      background-color: #e74c3c;
      color: white;
    }

    @keyframes slideDown {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
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
    <?php if(isset($_GET['notif']) && $_GET['notif'] == 'success'): ?>
      <div class="notif">Artikel berhasil disimpan.</div>
    <?php endif; ?>

    <h2>Daftar Artikel</h2>
    <table class="article-table">
      <tr>
        <th>Gambar</th>
        <th>Judul Artikel</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Isi Artikel</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($articles as $article): 
        $isi_artikel = strlen($article['description']) > 20 ? substr($article['description'], 0, 20) . "..." : $article['description'];
      ?>
      <tr>
        <td><img src="http://localhost/assessment-master/uploads/<?php echo str_replace("-","/",explode(" ",$article['post_date'])[0]) . "/" . $article['post_img']; ?>" alt="Article Image" style="max-width: 100px;"></td>
        <td><a href="full_article.php?post_id=<?php echo $article['post_id']; ?>"><?php echo $article['title']; ?></a></td>
        <td><?php echo $article['username']; ?></td>
        <td><?php echo $article['category_description']; ?></td>
        <td><?php echo $isi_artikel; ?></td>
        <td>
          <a href="edit_artikel.php?post_id=<?php echo $article['post_id']; ?>">
            <i class='btn fa fa-pencil' style='font-size:9px' tooltips='edit'></i>
          </a>
          <a href="#" onclick="confirmDelete(<?php echo $article['post_id']; ?>)">
            <i class='btn_del fa fa-trash' style='font-size:9px' tooltips='delete'></i>
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

    <!-- Pagination -->
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?page=<?php echo ($page - 1); ?>">Previous</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>"<?php if ($page == $i) echo ' class="active"'; ?>><?php echo $i; ?></a>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo ($page + 1); ?>">Next</a>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h3>Konfirmasi Hapus</h3>
    <p>Yakin Anda ingin menghapus artikel ini?</p>
    <div class="modal-buttons">
      <button onclick="closeModal()">Batal</button>
      <button id="confirmDeleteBtn">Hapus</button>
    </div>
  </div>
</div>

<!-- Script Modal -->
<script>
  let deletePostId = null;

  function confirmDelete(postId) {
    deletePostId = postId;
    document.getElementById("deleteModal").style.display = "flex";
  }

  function closeModal() {
    document.getElementById("deleteModal").style.display = "none";
    deletePostId = null;
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("confirmDeleteBtn").addEventListener("click", () => {
      if (deletePostId !== null) {
        window.location.href = "proses_delete_artikel.php?post_id=" + deletePostId;
      }
    });

    window.addEventListener("click", function(e) {
      const modal = document.getElementById("deleteModal");
      if (e.target === modal) {
        closeModal();
      }
    });
  });
</script>

</body>
</html>
