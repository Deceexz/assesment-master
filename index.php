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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal Berita Neuron</title>
  <link rel="stylesheet" href="main.css" />
  <link rel="stylesheet" href="index_style.css" />
  <link rel="stylesheet" href="container.css" />
  <link rel="stylesheet" href="footer.css" />
  <style>
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      margin: 30px 0;
      flex-wrap: wrap;
    }
    .pagination a {
      padding: 8px 14px;
      border: 1px solid #ddd;
      background-color: #fff;
      color: #333;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 500;
    }
    .pagination a:hover {
      background-color: #007bff;
      color: white;
      border-color: #007bff;
    }
    .pagination a.active {
      background-color: #007bff;
      color: white;
    }
    .like-form {
      margin-top: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .like-btn {
      background-color: transparent;
      border: none;
      color: red;
      font-size: 16px;
      cursor: pointer;
    }
    .like-btn:hover {
      font-weight: bold;
    }
    .like-disabled {
      margin-top: 10px;
      font-size: 14px;
      color: #999;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="main-grid">
  <div class="left-sidebar"></div>
  <div class="main-content">
    <div class="container">
      <div class="top-category-bar">
        <a href="sport.php" class="category-btn">Sports</a>
        <a href="health.php" class="category-btn">Health</a>
        <a href="politics.php" class="category-btn">Politics</a>
        <a href="entertainment.php" class="category-btn">Entertainment</a>
        <a href="business.php" class="category-btn">Business</a>
        <a href="favorite.php" class="category-btn">Favorite</a>
      </div>
      <section class="content">
        <div class="search-bar">
          <form action="" method="GET">
            <input type="text" name="search" placeholder="Search..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
          </form>
        </div>

        <?php
        $limit = 5;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
        $isLoggedIn = isset($_SESSION['user_id']);

        $stmtCount = mysqli_prepare($conn, "SELECT COUNT(1) as total FROM post_article WHERE status = 'active' AND (title LIKE ? OR username LIKE ?)");
        mysqli_stmt_bind_param($stmtCount, "ss", $search, $search);
        mysqli_stmt_execute($stmtCount);
        $resultCount = mysqli_stmt_get_result($stmtCount);
        $totalRows = mysqli_fetch_assoc($resultCount)['total'];
        mysqli_stmt_close($stmtCount);

        $totalPages = ceil($totalRows / $limit);

        $stmt = mysqli_prepare($conn, "SELECT p.*, c.category_description FROM post_article p LEFT JOIN category_post c ON p.category_id = c.category_id WHERE p.status = 'active' AND (p.title LIKE ? OR p.username LIKE ?) ORDER BY p.post_date DESC LIMIT ? OFFSET ?");
        mysqli_stmt_bind_param($stmt, "ssii", $search, $search, $limit, $offset);
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
            echo '    <h3><a href="full_article.php?post_id=' . $row['post_id'] . '&from=index">' . htmlspecialchars($row['title']) . '</a></h3>';
            echo '    <span>🖊️ ' . htmlspecialchars($row['username']) . '</span> ';
            echo '    <span class="category-tag">' . htmlspecialchars($row['category_description'] ?? 'Umum') . '</span>';
            echo '    <p class="description">' . substr(strip_tags($row['description']), 0, 150) . '...</p>';

            if ($isLoggedIn) {
              $liked = in_array($row['post_id'], $_SESSION['liked_articles']);
              $like_text = $liked ? '❤️ Liked' : '🤍 Like';
              $like_disabled = $liked ? 'disabled' : '';
              echo '<form method="POST" action="index.php?page=' . $page . (isset($_GET['search']) ? '&search=' . urlencode(trim($_GET['search'])) : '') . '" class="like-form">';
              echo '  <input type="hidden" name="like_post_id" value="' . $row['post_id'] . '">';
              echo '  <button type="submit" name="like" class="like-btn" ' . $like_disabled . '>' . $like_text . '</button>';
              echo '  <span class="like-count">' . (int)$row['total_like'] . ' likes</span>';
              echo '</form>';
            } else {
              echo '<div class="like-disabled">❤️ <span class="like-count">' . (int)$row['total_like'] . ' likes</span> (Login untuk like)</div>';
            }
            echo '  </div>';
            echo '</div>';
          }
          echo '</div>';
        } else {
          echo '<p>Tidak ditemukan artikel.</p>';
        }
        mysqli_stmt_close($stmt);

        if ($totalPages > 1) {
          echo '<div class="pagination">';
          if ($page > 1) {
            echo '<a href="?page=' . ($page - 1) . (isset($_GET['search']) ? '&search=' . urlencode(trim($_GET['search'])) : '') . '">&laquo; Prev</a>';
          }
          for ($i = 1; $i <= $totalPages; $i++) {
            $active = $i == $page ? 'class="active"' : '';
            echo '<a href="?page=' . $i . (isset($_GET['search']) ? '&search=' . urlencode(trim($_GET['search'])) : '') . '" ' . $active . '>' . $i . '</a>';
          }
          if ($page < $totalPages) {
            echo '<a href="?page=' . ($page + 1) . (isset($_GET['search']) ? '&search=' . urlencode(trim($_GET['search'])) : '') . '">Next &raquo;</a>';
          }
          echo '</div>';
        }
        ?>
      </section>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>