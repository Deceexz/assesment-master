<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Statistik Portal Berita</title>
  <link rel="stylesheet" href="manage_user_style.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="container.css">

  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
      background: #f5f5f5;
    }
    h2 {
      text-align: center;
      margin-bottom: 2rem;
    }
    .chart-container {
      width: 90%;
      max-width: 900px;
      margin: 2rem auto;
      background: white;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<?php
include 'koneksi.php';

// Artikel per kategori
$queryKategori = "SELECT c.category_description, COUNT(p.post_id) AS total 
                  FROM post_article p
                  JOIN category_post c ON p.category_id = c.category_id
                  GROUP BY c.category_description";
$resultKategori = mysqli_query($conn, $queryKategori);
$kategoriLabels = [];
$kategoriCounts = [];
while ($row = mysqli_fetch_assoc($resultKategori)) {
  $kategoriLabels[] = $row['category_description'];
  $kategoriCounts[] = $row['total'];
}

// 10 Penulis terbanyak
$queryPenulis = "SELECT username, COUNT(*) AS total 
                 FROM post_article 
                 GROUP BY username 
                 ORDER BY total DESC 
                 LIMIT 10";
$resultPenulis = mysqli_query($conn, $queryPenulis);
$penulisLabels = [];
$penulisCounts = [];
while ($row = mysqli_fetch_assoc($resultPenulis)) {
  $penulisLabels[] = $row['username'];
  $penulisCounts[] = $row['total'];
}

// Artikel dengan Like terbanyak
$queryLikeArtikel = "SELECT title, total_like 
                     FROM post_article 
                     WHERE total_like > 0 
                     ORDER BY total_like DESC 
                     LIMIT 10";
$resultLikeArtikel = mysqli_query($conn, $queryLikeArtikel);
$likeArtikelLabels = [];
$likeArtikelCounts = [];
while ($row = mysqli_fetch_assoc($resultLikeArtikel)) {
  $likeArtikelLabels[] = $row['title'];
  $likeArtikelCounts[] = $row['total_like'];
}

// Penulis dengan Like terbanyak
$queryLikePenulis = "SELECT username, SUM(total_like) as total_likes
                     FROM post_article
                     GROUP BY username
                     ORDER BY total_likes DESC
                     LIMIT 10";
$resultLikePenulis = mysqli_query($conn, $queryLikePenulis);
$likePenulisLabels = [];
$likePenulisCounts = [];
while ($row = mysqli_fetch_assoc($resultLikePenulis)) {
  $likePenulisLabels[] = $row['username'];
  $likePenulisCounts[] = $row['total_likes'];
}
?>

<aside id="sidebar">
  <nav class="category-bar">
    <a href="dashboard_admin.php" class="category-btn">Dashboard Admin</a>
    <a href="manage_artikel_admin.php" class="category-btn">Manage Article</a>
  </nav>
</aside>

<h2>📊 Statistik Portal Berita</h2>

<div class="chart-container">
  <h3>Jumlah Artikel per Kategori</h3>
  <canvas id="kategoriChart"></canvas>
</div>

<div class="chart-container">
  <h3>10 Penulis Terbanyak</h3>
  <canvas id="penulisChart"></canvas>
</div>

<div class="chart-container">
  <h3>10 Artikel dengan Like Terbanyak</h3>
  <canvas id="likeArtikelChart"></canvas>
</div>

<div class="chart-container">
  <h3>10 Penulis dengan Total Like Terbanyak</h3>
  <canvas id="likePenulisChart"></canvas>
</div>

<script>
  const kategoriData = {
    labels: <?php echo json_encode($kategoriLabels); ?>,
    datasets: [{
      label: 'Jumlah Artikel',
      data: <?php echo json_encode($kategoriCounts); ?>,
      backgroundColor: [
        '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f',
        '#edc948', '#b07aa1', '#ff9da7', '#9c755f', '#bab0ac'
      ]
    }]
  };

  const penulisData = {
    labels: <?php echo json_encode($penulisLabels); ?>,
    datasets: [{
      label: 'Total Artikel',
      data: <?php echo json_encode($penulisCounts); ?>,
      backgroundColor: '#4CAF50'
    }]
  };

  const likeArtikelData = {
    labels: <?php echo json_encode($likeArtikelLabels); ?>,
    datasets: [{
      label: 'Jumlah Like',
      data: <?php echo json_encode($likeArtikelCounts); ?>,
      backgroundColor: '#f28e2b'
    }]
  };

  const likePenulisData = {
    labels: <?php echo json_encode($likePenulisLabels); ?>,
    datasets: [{
      label: 'Total Like',
      data: <?php echo json_encode($likePenulisCounts); ?>,
      backgroundColor: '#e15759'
    }]
  };

  new Chart(document.getElementById('kategoriChart'), {
    type: 'doughnut',
    data: kategoriData,
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'right' }
      }
    }
  });

  new Chart(document.getElementById('penulisChart'), {
    type: 'bar',
    data: penulisData,
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });

  new Chart(document.getElementById('likeArtikelChart'), {
    type: 'bar',
    data: likeArtikelData,
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });

  new Chart(document.getElementById('likePenulisChart'), {
    type: 'bar',
    data: likePenulisData,
    options: {
      responsive: true,
      scales: { y: { beginAtZero: true } }
    }
  });
</script>

</body>
</html>
