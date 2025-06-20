<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Favorite Articles - Portal Berita Neuron</title>
  <link rel="stylesheet" href="index_style.css">
  <link rel="stylesheet" href="container.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="article_card.css">
  <link rel="stylesheet" href="main.css">
  <style>
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
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
  <a href="favorite.php" class="category-btn active">Favorite</a>
</div>

<section class="content">
  <div class="row">
    <div class="col-sm-8">
      <div class="container">
        <h2>Top Artikel Favorit</h2>

        <?php
          $limit = 5;
          $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
          $offset = ($page - 1) * $limit;

          // Hitung total artikel favorit
          $countQuery = "SELECT COUNT(1) AS total FROM post_article WHERE total_like > 0";
          $countResult = mysqli_query($conn, $countQuery);
          $totalRows = mysqli_fetch_assoc($countResult)['total'];
          $totalPages = ceil($totalRows / $limit);

          // Ambil data favorit dengan pagination dan ranking
          $query_favorite = "SELECT * FROM post_article WHERE total_like > 0 ORDER BY total_like DESC LIMIT ? OFFSET ?";
          $stmt_favorite = mysqli_prepare($conn, $query_favorite);
          mysqli_stmt_bind_param($stmt_favorite, "ii", $limit, $offset);
          mysqli_stmt_execute($stmt_favorite);
          $result_favorite = mysqli_stmt_get_result($stmt_favorite);

          if (mysqli_num_rows($result_favorite) > 0) {
            echo '<div class="article-list">';
            $rank = $offset + 1;
            while ($row = mysqli_fetch_assoc($result_favorite)) {
              $imgPath = 'http://localhost/assessment-master/uploads/' . str_replace("-", "/", explode(" ", $row['post_date'])[0]) . '/' . $row['post_img'];
              echo '<div class="article-card">';
                echo '<div class="article-image">';
                  echo '<img src="' . $imgPath . '" alt="Article Image">';
                echo '</div>';
                echo '<div class="article-content">';
                  echo '<h3><a href="full_article.php?post_id=' . $row['post_id'] . '&from=favorite">' . $row['title'] . '</a></h3>';
                  echo '<p class="meta">Rangking #' . $rank++ . ' | ' . date('d F Y', strtotime($row['post_date'])) . '</p>';
                  echo '<p class="description">' . substr($row['description'], 0, 150) . '...</p>';
                  echo '<p class="meta"><strong>Total Like:</strong> ' . $row['total_like'] . '</p>';
                echo '</div>';
              echo '</div>';
            }
            echo '</div>';
          } else {
            echo '<p>Tidak ada artikel favorit saat ini.</p>';
          }

          mysqli_stmt_close($stmt_favorite);
        ?>

        <?php if ($totalPages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a class="prev-btn" href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <a href="?page=<?= $i ?>" <?= $i == $page ? 'style="font-weight: bold;"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
              <a class="next-btn" href="?page=<?= $page + 1 ?>">Next &raquo;</a>
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
