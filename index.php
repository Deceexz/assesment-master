<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal Berita Neuron</title>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="main.css" />
  <link rel="stylesheet" href="index_style.css" />
  <link rel="stylesheet" href="container.css" />
  <link rel="stylesheet" href="footer.css" />
</head>
<body>

  <?php include 'header.php'; ?>

  <section class="main-grid">
    <div class="left-sidebar"></div>

    <div class="main-content">
      <div class="container">

        <!-- Kategori -->
        <div class="top-category-bar">
          <a href="sport.php" class="category-btn">Sports</a>
          <a href="health.php" class="category-btn">Health</a>
          <a href="politics.php" class="category-btn">Politics</a>
          <a href="entertainment.php" class="category-btn">Entertainment</a>
          <a href="business.php" class="category-btn">Business</a>
          <a href="favorite.php" class="category-btn">Favorite</a>
        </div>

        <!-- Artikel -->
        <section class="content">
          <div class="search-bar">
            <form action="#" method="GET">
              <input type="text" name="search" placeholder="Search..." />
              <button type="submit">Search</button>
            </form>
          </div>

          <?php
          include 'koneksi.php';

          if (isset($_GET['search'])) {
            $keyword = mysqli_real_escape_string($conn, $_GET['search']);
            $query = "
                SELECT p.*, c.category_description 
                FROM post_article p
                LEFT JOIN category_post c ON p.category_id = c.category_id
                WHERE (p.title LIKE '%$keyword%' OR p.username LIKE '%$keyword%')
                AND p.status = 'active'";
            $result = mysqli_query($conn, $query);
            echo '<h2>Hasil Pencarian untuk "' . htmlspecialchars($keyword) . '"</h2>';
        } else {
            $query = "
                SELECT p.*, c.category_description 
                FROM post_article p
                LEFT JOIN category_post c ON p.category_id = c.category_id
                WHERE p.status = 'active'";
            $result = mysqli_query($conn, $query);
            echo '<h2>Artikel Utama</h2>';
        }

          if (mysqli_num_rows($result) > 0) {
            echo '<div class="article-list">';
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<div class="article-card">';
              echo '  <div class="article-image">';
              echo '    <img src="http://localhost/assessment-master/uploads/' .
                str_replace("-", "/", explode(" ", $row['post_date'])[0]) . '/' .
                $row['post_img'] . '" alt="Article Image" />';
              echo '  </div>';
              echo '  <div class="article-content">';
              echo '    <h3><a href="full_article.php?post_id=' . $row['post_id'] . '&from=index">' . $row['title'] . '</a></h3>';
              echo '    <span>🖊️ ' . htmlspecialchars($row['username']) . '</span> ';
              echo '    <span class="category-tag">' . htmlspecialchars($row['category_description'] ?? 'Umum') . '</span>';
              echo '    <p class="description">' . substr($row['description'], 0, 150) . '...</p>';
              echo '  </div>';
              echo '</div>';
            }
            echo '</div>';
          } else {
            echo '<p>Tidak ditemukan artikel dengan kata kunci "' . ($keyword ?? '') . '"</p>';
          }
          ?>

          <div class="pagination">
            <button class="prev-btn">&laquo; Prev</button>
            <button>1</button>
            <button>2</button>
            <button>3</button>
            <button class="next-btn">Next &raquo;</button>
          </div>
        </section>
      </div>
    </div>

    <?php
    // Tetap jalankan query favorit di sini untuk counting like di database
    $query_favorite = "
      SELECT * 
      FROM post_article 
      WHERE total_like > 0 AND status = 'active' 
      ORDER BY total_like DESC 
      LIMIT 5";
    $result_favorite = mysqli_query($conn, $query_favorite);

    // Data hasil bisa digunakan di tempat lain atau hanya untuk proses internal
    $favorite_articles = [];
    if (mysqli_num_rows($result_favorite) > 0) {
      while ($row_fav = mysqli_fetch_assoc($result_favorite)) {
        $favorite_articles[] = $row_fav;
      }
    }
    ?>
  </section>

  <?php include 'footer.php'; ?>
</body>
</html>
