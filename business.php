<?php
session_start();
include 'koneksi.php';

// Inisialisasi session like jika belum ada
if (!isset($_SESSION['liked_articles'])) {
    $_SESSION['liked_articles'] = [];
}

// Handle Like jika user sudah login
if (isset($_POST['like']) && isset($_POST['like_post_id']) && isset($_SESSION['user_id'])) {
    $liked_id = intval($_POST['like_post_id']);
    if (!in_array($liked_id, $_SESSION['liked_articles'])) {
        $like_stmt = mysqli_prepare($conn, "UPDATE post_article SET total_like = total_like + 1 WHERE post_id = ?");
        mysqli_stmt_bind_param($like_stmt, "i", $liked_id);
        mysqli_stmt_execute($like_stmt);
        mysqli_stmt_close($like_stmt);

        $_SESSION['liked_articles'][] = $liked_id;

        // Redirect to prevent resubmission
        $redirect_url = strtok($_SERVER["REQUEST_URI"], '?') . '?' . http_build_query($_GET);
        header("Location: $redirect_url");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Berita Neuron - Business</title>
  <link rel="stylesheet" href="index_style.css">
  <link rel="stylesheet" href="container.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="article_card.css">
  <link rel="stylesheet" href="main.css">

  <style>
    .pagination {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin: 30px 0;
      flex-wrap: wrap;
    }

    .pagination a,
    .pagination button {
      padding: 8px 14px;
      border: 1px solid #ddd;
      background-color: #fff;
      color: #333;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.2s ease, color 0.2s ease;
      cursor: pointer;
      font-weight: 500;
    }

    .pagination a:hover,
    .pagination button:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }

    .pagination a[style*="font-weight: bold"] {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
      font-weight: bold;
    }

    form button[name="like"] {
      margin-top: 8px;
      padding: 6px 12px;
      background-color: #e7e7e7;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
    }

    form button[name="like"]:hover {
      background-color: #007bff;
      color: white;
    }

    form button[disabled] {
      background-color: #ccc;
      color: #777;
      cursor: not-allowed;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- TOP KATEGORI -->
<div class="top-category-bar">
  <a href="sport.php" class="category-btn">Sports</a>
  <a href="health.php" class="category-btn">Health</a>
  <a href="politics.php" class="category-btn">Politics</a>
  <a href="entertainment.php" class="category-btn">Entertainment</a>
  <a href="business.php" class="category-btn">Business</a>
  <a href="favorite.php" class="category-btn">Favorite</a>
</div>

<section class="content">
  <div class="row">
    <div class="col-sm-8">
      <div class="container">
        <div class="search-bar">
          <form action="business.php" method="GET">
            <input type="text" name="search" placeholder="Search..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
          </form>
        </div>

        <?php
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $category_id = 15; // Business category_id

        // Count total data
        if ($search !== '') {
          $countQuery = "SELECT COUNT(1) AS total FROM post_article WHERE category_id = ? AND title LIKE ? AND status = 'active'";
          $stmt = mysqli_prepare($conn, $countQuery);
          $search_param = "%$search%";
          mysqli_stmt_bind_param($stmt, "is", $category_id, $search_param);
        } else {
          $countQuery = "SELECT COUNT(1) AS total FROM post_article WHERE category_id = ? AND status = 'active'";
          $stmt = mysqli_prepare($conn, $countQuery);
          mysqli_stmt_bind_param($stmt, "i", $category_id);
        }

        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);
        $totalRows = mysqli_fetch_assoc($countResult)['total'];
        $totalPages = ceil($totalRows / $limit);
        mysqli_stmt_close($stmt);

        // Fetch data
        if ($search !== '') {
          echo '<h2>Hasil Pencarian: "' . htmlspecialchars($search) . '"</h2>';
          $query = "SELECT * FROM post_article WHERE category_id = ? AND title LIKE ? AND status = 'active' ORDER BY post_date DESC LIMIT ? OFFSET ?";
          $stmt = mysqli_prepare($conn, $query);
          mysqli_stmt_bind_param($stmt, "isii", $category_id, $search_param, $limit, $offset);
        } else {
          echo '<h2>Artikel Business</h2>';
          $query = "SELECT * FROM post_article WHERE category_id = ? AND status = 'active' ORDER BY post_date DESC LIMIT ? OFFSET ?";
          $stmt = mysqli_prepare($conn, $query);
          mysqli_stmt_bind_param($stmt, "iii", $category_id, $limit, $offset);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
          echo '<div class="article-list">';
          while ($row = mysqli_fetch_assoc($result)) {
            $imgPath = 'http://localhost/assessment-master/uploads/' . str_replace("-", "/", explode(" ", $row['post_date'])[0]) . '/' . $row['post_img'];
            echo '<div class="article-card">';
            echo '  <div class="article-image">';
            echo '    <img src="' . $imgPath . '" alt="Article Image">';
            echo '  </div>';
            echo '  <div class="article-content">';
            echo '    <h3><a href="full_article.php?post_id=' . $row['post_id'] . '&from=business">' . $row['title'] . '</a></h3>';
            echo '    <p class="meta">' . date('d F Y', strtotime($row['post_date'])) . '</p>';
            echo '    <p class="description">' . substr(strip_tags($row['description']), 0, 150) . '...</p>';

            // LIKE BUTTON
            if (isset($_SESSION['user_id'])) {
              $liked = in_array($row['post_id'], $_SESSION['liked_articles']);
              $like_text = $liked ? '❤️ Liked' : '🤍 Like';
              $like_disabled = $liked ? 'disabled' : '';
              echo '<form method="POST" action="business.php?page=' . $page . ($search ? '&search=' . urlencode($search) : '') . '">';
              echo '  <input type="hidden" name="like_post_id" value="' . $row['post_id'] . '">';
              echo '  <button type="submit" name="like" ' . $like_disabled . '>' . $like_text . ' (' . $row['total_like'] . ')</button>';
              echo '</form>';
            } else {
              echo '<p><small>Login untuk menyukai artikel ❤️</small></p>';
            }

            echo '  </div>';
            echo '</div>';
          }
          echo '</div>';
        } else {
          echo '<p>Tidak ditemukan artikel business.</p>';
        }

        mysqli_stmt_close($stmt);
        ?>

        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>" <?= $i == $page ? 'style="font-weight: bold;"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
              <a href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">Next &raquo;</a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
