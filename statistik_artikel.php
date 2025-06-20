<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login_user.php");
    exit();
}

// Statistik: Artikel per kategori
$kategoriLabels = $kategoriCounts = [];
$stmt1 = mysqli_prepare($conn, "
    SELECT c.category_description, COUNT(p.post_id) AS total 
    FROM post_article p
    JOIN category_post c ON p.category_id = c.category_id
    GROUP BY c.category_description
");
mysqli_stmt_execute($stmt1);
$result1 = mysqli_stmt_get_result($stmt1);
while ($row = mysqli_fetch_assoc($result1)) {
    $kategoriLabels[] = $row['category_description'];
    $kategoriCounts[] = $row['total'];
}
mysqli_stmt_close($stmt1);

// Statistik: 10 Penulis terbanyak
$penulisLabels = $penulisCounts = [];
$stmt2 = mysqli_prepare($conn, "
    SELECT username, COUNT(*) AS total 
    FROM post_article 
    GROUP BY username 
    ORDER BY total DESC 
    LIMIT 10
");
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
while ($row = mysqli_fetch_assoc($result2)) {
    $penulisLabels[] = $row['username'];
    $penulisCounts[] = $row['total'];
}
mysqli_stmt_close($stmt2);

// Statistik: Artikel dengan Like terbanyak
$likeArtikelLabels = $likeArtikelCounts = [];
$stmt3 = mysqli_prepare($conn, "
    SELECT title, total_like 
    FROM post_article 
    WHERE total_like > 0 
    ORDER BY total_like DESC 
    LIMIT 10
");
mysqli_stmt_execute($stmt3);
$result3 = mysqli_stmt_get_result($stmt3);
while ($row = mysqli_fetch_assoc($result3)) {
    $likeArtikelLabels[] = $row['title'];
    $likeArtikelCounts[] = $row['total_like'];
}
mysqli_stmt_close($stmt3);

// Statistik: Penulis dengan Like terbanyak
$likePenulisLabels = $likePenulisCounts = [];
$stmt4 = mysqli_prepare($conn, "
    SELECT username, SUM(total_like) as total_likes
    FROM post_article
    GROUP BY username
    ORDER BY total_likes DESC
    LIMIT 10
");
mysqli_stmt_execute($stmt4);
$result4 = mysqli_stmt_get_result($stmt4);
while ($row = mysqli_fetch_assoc($result4)) {
    $likePenulisLabels[] = $row['username'];
    $likePenulisCounts[] = $row['total_likes'];
}
mysqli_stmt_close($stmt4);

// Statistik: Artikel per bulan
$bulanLabels = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
$bulanCounts = array_fill(0, 12, 0);
$currentYear = date('Y');

$stmt5 = mysqli_prepare($conn, "
    SELECT MONTH(post_date) AS bulan, COUNT(*) AS total
    FROM post_article
    WHERE YEAR(post_date) = ?
    GROUP BY bulan
");
mysqli_stmt_bind_param($stmt5, "i", $currentYear);
mysqli_stmt_execute($stmt5);
$result5 = mysqli_stmt_get_result($stmt5);

while ($row = mysqli_fetch_assoc($result5)) {
    $bulanIndex = (int)$row['bulan'] - 1;
    $bulanCounts[$bulanIndex] = (int)$row['total'];
}
mysqli_stmt_close($stmt5);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Statistik Portal Berita</title>
  <link rel="stylesheet" href="manage_user_style.css">
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="container.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
  <style>
    body { font-family: Arial, sans-serif; padding: 2rem; background: #f5f5f5; }
    h2 { text-align: center; margin-bottom: 2rem; }
    .chart-container {
      width: 90%; max-width: 900px; margin: 2rem auto;
      background: white; padding: 2rem; border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    canvas { margin-top: 20px; }
  </style>
</head>
<body>

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

<div class="chart-container">
  <h3>Jumlah Artikel per Bulan (<?php echo $currentYear; ?>)</h3>
  <canvas id="artikelBulanChart"></canvas>
</div>

<script>
Chart.register(ChartDataLabels);

const kategoriData = {
  labels: <?php echo json_encode($kategoriLabels); ?>,
  datasets: [{
    label: 'Jumlah Artikel',
    data: <?php echo json_encode($kategoriCounts); ?>,
    backgroundColor: [
      '#4e79a7', '#f28e2b', '#e15759', '#76b7b2', '#59a14f',
      '#edc949', '#af7aa1', '#ff9da7', '#9c755f', '#bab0ab'
    ]
  }]
};

const penulisData = {
  labels: <?php echo json_encode($penulisLabels); ?>,
  datasets: [{
    label: 'Total Artikel',
    data: <?php echo json_encode($penulisCounts); ?>,
    backgroundColor: '#59a14f'
  }]
};

const likeArtikelData = {
  labels: <?php echo json_encode($likeArtikelLabels); ?>,
  datasets: [{
    label: 'Jumlah Like',
    data: <?php echo json_encode($likeArtikelCounts); ?>,
    backgroundColor: [
      '#f28e2b', '#e15759', '#76b7b2', '#59a14f', '#af7aa1',
      '#ff9da7', '#4e79a7', '#edc949', '#9c755f', '#bab0ab'
    ]
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

const artikelBulanData = {
  labels: <?php echo json_encode($bulanLabels); ?>,
  datasets: [{
    label: 'Jumlah Artikel per Bulan',
    data: <?php echo json_encode($bulanCounts); ?>,
    backgroundColor: '#76b7b2'
  }]
};

const defaultBarOptions = {
  responsive: true,
  plugins: {
    legend: { display: false },
    datalabels: {
      anchor: 'end',
      align: 'end',
      formatter: Math.round,
      font: { weight: 'bold' }
    }
  },
  scales: { y: { beginAtZero: true } }
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
  options: defaultBarOptions,
  plugins: [ChartDataLabels]
});

new Chart(document.getElementById('likeArtikelChart'), {
  type: 'doughnut',
  data: likeArtikelData,
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'right' },
      datalabels: {
        formatter: Math.round,
        color: '#000',
        font: { weight: 'bold' }
      }
    }
  },
  plugins: [ChartDataLabels]
});

new Chart(document.getElementById('likePenulisChart'), {
  type: 'bar',
  data: likePenulisData,
  options: defaultBarOptions,
  plugins: [ChartDataLabels]
});

new Chart(document.getElementById('artikelBulanChart'), {
  type: 'bar',
  data: artikelBulanData,
  options: defaultBarOptions,
  plugins: [ChartDataLabels]
});
</script>

</body>
</html>
