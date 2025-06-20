<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit();
}

if (!isset($_GET['post_id'])) {
    header("Location: dashboard_user.php");
    exit();
}

$post_id = $_GET['post_id'];
$query = "SELECT * FROM post_article WHERE post_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: dashboard_user.php");
    exit();
}

$article = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

function escape($html) {
    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Artikel - Portal Berita Neuron</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="container.css">

    <script src="https://unpkg.com/wanakana"></script>
    <script src="https://cdn.tiny.cloud/1/o4ima19zq8rcl1vbfpzh1rougma5bjaqmeu94vy0a8d3gh6m/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
      let useHiragana = false;

      tinymce.init({
        selector: '#isi_artikel',
        plugins: 'lists link image table code help wordcount preview anchor autolink charmap',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview code | hiraganaToggle',
        menubar: false,
        height: 400,

        setup: function(editor) {
          editor.ui.registry.addToggleButton('hiraganaToggle', {
            text: 'Hiragana Mode',
            icon: 'language',
            onAction: function() {
              useHiragana = !useHiragana;
              editor.notificationManager.open({
                text: useHiragana ? 'Mode Hiragana Aktif' : 'Mode Hiragana Nonaktif',
                type: 'info',
                timeout: 2000
              });
            },
            onSetup: function(api) {
              api.setActive(useHiragana);
              return function() {};
            }
          });
        }
      });

      function convertToHiragana() {
        const content = tinymce.activeEditor.getContent({ format: 'text' });
        const converted = wanakana.toHiragana(content);
        tinymce.activeEditor.setContent('<p>' + converted + '</p>');
      }
    </script>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
  <aside id="sidebar">
    <div class="sidebar">
      <h2>Menu</h2>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="dashboard_user.php">Dashboard</a></li>
        <li><a href="membuat_berita.php">Membuat Berita</a></li>
      </ul>
    </div>
  </aside>

  <section class="content form-membuat-artikel_mb">
    <h2 class="text-center">Edit Artikel</h2>
    <form action="proses_edit_artikel.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="post_id" value="<?php echo escape($article['post_id']); ?>">

      <div class="input-group_mb">
        <label for="nama_penulis">Nama Penulis:</label>
        <input type="text" id="nama_penulis" name="nama_penulis" value="<?php echo escape($article['username']); ?>" readonly required>
      </div>

      <div class="input-group_mb">
        <label for="judul_artikel">Judul Artikel:</label>
        <input type="text" id="judul_artikel" name="judul_artikel" value="<?php echo escape($article['title']); ?>" required>
      </div>

      <div class="input-group_mb">
        <label for="isi_artikel">Isi Artikel:</label>
        <textarea id="isi_artikel" name="isi_artikel"><?php echo $article['description']; ?></textarea>
        <button type="button" onclick="convertToHiragana()">Konversi ke Hiragana</button>
      </div>

      <div class="upload-form">
        <label for="gambar">Gambar Lama:</label><br>
        <img src="http://localhost/assessment-master/uploads/<?php 
            echo str_replace("-", "/", explode(" ", $article['post_date'])[0]) . "/" . $article['post_img']; 
        ?>" alt="Article Image" style="max-width: 100px;"><br><br>

        <label for="gambar_baru">Unggah Gambar Baru:</label>
        <input type="file" id="gambar_baru" name="gambar_baru">
      </div>

      <input type="hidden" name="category_id" value="<?php echo escape($article['category_id']); ?>">
      <button type="submit" class="button_mb">Simpan Perubahan</button>
    </form>
  </section>
</div>

</body>
</html>
