<?php
session_start();
include 'koneksi.php';

if (isset($_GET['post_id'])) {
    $article_id = intval($_GET['post_id']);

    // Ambil artikel aktif berdasarkan ID
    $query = "SELECT * FROM post_article WHERE post_id = $article_id AND status = 'active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $article = mysqli_fetch_assoc($result);

        // Inisialisasi session untuk like
        if (!isset($_SESSION['liked_articles'])) {
            $_SESSION['liked_articles'] = [];
        }

        // Jika datang dari index atau kategori dan belum like
        if (isset($_GET['from']) && in_array($_GET['from'], ['index', 'sport', 'health', 'politics', 'entertainment', 'business', 'favorite'])) {
            if (!in_array($article_id, $_SESSION['liked_articles'])) {
                $query_like = "UPDATE post_article SET total_like = total_like + 1 WHERE post_id = $article_id";
                mysqli_query($conn, $query_like);
                $_SESSION['liked_articles'][] = $article_id;

                // Ambil ulang artikel setelah update like
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
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link rel="stylesheet" href="full_article.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>

<?php include 'header.php'; ?>

<main class="article-container">
    <div class="article-header">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <div class="article-meta">
            <span>💊 <?= htmlspecialchars($article['username']) ?></span>
            <span>🗓️ <?= date("d M Y", strtotime($article['post_date'])) ?></span>
            <span>👍 <?= (int)$article['total_like'] ?> Likes</span>
        </div>
    </div>

    <?php
        $image_path = "http://localhost/assessment-master/uploads/" .
                      str_replace("-", "/", explode(" ", $article['post_date'])[0]) .
                      "/" . $article['post_img'];
    ?>
    <div class="article-image">
        <img src="<?= $image_path ?>" alt="Gambar Artikel">
    </div>

    <div class="article-body">
        <?= $article['description'] // jangan htmlspecialchars agar HTML muncul ?>
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
