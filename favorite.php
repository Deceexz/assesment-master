<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Berita Neuron - Politics</title>
  <link rel="stylesheet" href="index_style.css">
  <link rel="stylesheet" href="container.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="article_card.css">
  <link rel="stylesheet" href="main.css">
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
  <a href="technology.php" class="category-btn">Favorite</a>
</div>

<section class="content">
  <div class="row">
    <div class="col-sm-8">
      <div class="container">
        <div class="search-bar">
          <form action="politics.php" method="GET">
            <input type="text" name="search" placeholder="Search...">
            <button type="submit">Search</button>
          </form>
        </div>

        <?php
        if (isset($_GET['search'])) {
          $keyword = $_GET['search'];
          $query = "SELECT * FROM post_article WHERE category_id = 14 AND title LIKE '%$keyword%'";
          $result = mysqli_query($conn, $query);
          echo '<h2>Hasil Pencarian: "' . htmlspecialchars($keyword) . '"</h2>';
        } else {
          $query = "SELECT * FROM post_article WHERE category_id = 14";
          $result = mysqli_query($conn, $query);
          echo '<h2>Artikel Favorite</h2>';
        }

        if (mysqli_num_rows($result) > 0) {
          echo '<div class="article-list">';
          while ($row = mysqli_fetch_assoc($result)) {
            $imgPath = 'http://localhost/assessment-master/uploads/' . str_replace("-", "/", explode(" ", $row['post_date'])[0]) . '/' . $row['post_img'];
            echo '<div class="article-card">';
              echo '<div class="article-image">';
                echo '<img src="' . $imgPath . '" alt="Article Image">';
              echo '</div>';
              echo '<div class="article-content">';
                echo '<h3><a href="full_article.php?post_id=' . $row['post_id'] . '">' . $row['title'] . '</a></h3>';
                echo '<p class="meta">' . date('d F Y', strtotime($row['post_date'])) . '</p>';
                echo '<p class="description">' . substr($row['description'], 0, 150) . '...</p>';
              echo '</div>';
            echo '</div>';
          }
          echo '</div>';
        } else {
          echo '<p>Tidak ditemukan artikel favorite.</p>';
        }
        ?>

        <div class="pagination">
          <button class="prev-btn">&laquo; Prev</button>
          <button>1</button>
          <button>2</button>
          <button>3</button>
          <button class="next-btn">Next &raquo;</button>
        </div>
      </div>
    </div>

    <!-- Kolom kanan - artikel favorit 
    <div class="right-sidebar">
      <div class="container_fav">
        <section class="content">
          <div class="favorite-articles">
            <h2>Artikel Favorite</h2>
            <table class="favorite-table">
              /*<?php
              $query_favorite = "SELECT *, RANK() OVER (ORDER BY total_like DESC) AS 'rank' FROM post_article WHERE total_like > 0 LIMIT 5";
              $result_favorite = mysqli_query($conn, $query_favorite);

              if (mysqli_num_rows($result_favorite) > 0) {
                while ($row_favorite = mysqli_fetch_assoc($result_favorite)) {
                  echo '<tr><td><a href="full_article.php?post_id=' . $row_favorite['post_id'] . '">' . $row_favorite['title'] . '</a></td></tr>';
                }
              } else {
                echo '<tr><td>Tidak ada artikel favorit saat ini.</td></tr>';
              }
              ?>*/
            </table>
          </div>
        </section>
      </div>
    </div> -->
  </div>
</section>

<?php include 'footer.php'; ?>
</body>
</html>
