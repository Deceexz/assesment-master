<?php
session_start();
include 'koneksi.php';

if (isset($_GET['post_id'])) {
    $article_id = intval($_GET['post_id']);

    // Ambil artikel dengan status aktif
    $query = "SELECT * FROM post_article WHERE post_id = $article_id AND status = 'active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);

        // Inisialisasi session liked_articles jika belum ada
        if (!isset($_SESSION['liked_articles'])) {
            $_SESSION['liked_articles'] = [];
        }

        // Tambah like hanya jika datang dari index dan belum pernah like
        if (isset($_GET['from']) && $_GET['from'] === 'index') {
            if (!in_array($article_id, $_SESSION['liked_articles'])) {
                $query_increment_like = "UPDATE post_article SET total_like = total_like + 1 WHERE post_id = $article_id";
                mysqli_query($conn, $query_increment_like);
                $_SESSION['liked_articles'][] = $article_id;

                // Ambil ulang data artikel setelah update
                $result = mysqli_query($conn, $query);
                $article = mysqli_fetch_assoc($result);
            }
        }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="full_article.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="footer.css">

</head>
<body>

<?php include 'header.php'; ?>

<main class="article-container">
    <div class="article-header">
        <h1><?= htmlspecialchars($article['title']); ?></h1>
        <div class="article-meta">
            <span>🖊️ <?= htmlspecialchars($article['username']); ?></span>
            <span>📅 <?= date("d M Y", strtotime($article['post_date'])); ?></span>
            <span>👍 <?= $article['total_like']; ?> Likes</span>
        </div>
    </div>

    <div class="article-image">
        <img src="http://localhost/assessment-master/uploads/<?= str_replace("-", "/", explode(" ", $article['post_date'])[0]) . "/" . $article['post_img']; ?>" alt="Gambar Artikel">
    </div>

    <div class="article-body">
        <?= $article['description']; ?>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
<?php
    } else {
        echo "<p class='not-found'>❌ Artikel tidak ditemukan atau telah ditakedown.</p>";
    }
} else {
    echo "<p class='not-found'>❌ Parameter post_id tidak ditemukan.</p>";
}
?>
