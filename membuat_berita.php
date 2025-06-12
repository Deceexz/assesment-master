<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_penulis = $_SESSION['username'] ?? '';

// Ambil data artikel
$article = [];
$query = "SELECT * FROM post_article WHERE username = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $article = mysqli_fetch_assoc($result);
}

// Ambil kategori
$query_kategori = "SELECT category_id, category_description FROM category_post";
$result_kategori = mysqli_query($conn, $query_kategori);
$kategori_options = [];

if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori_options[$row['category_id']] = $row['category_description'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Membuat Artikel - Portal Berita Neuron</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="container.css">

  <!-- WanaKana untuk konversi Hiragana -->
  <script src="https://unpkg.com/wanakana"></script>

  <!-- TinyMCE CDN -->
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

        editor.on('input', function() {
          if (useHiragana) {
            const plainText = editor.getContent({ format: 'text' });
            const converted = wanakana.toHiragana(plainText);
            if (plainText !== converted) {
              editor.setContent('<p style="font-size:14px;">' + converted + '</p>');
            }
          }
        });
      }
    });
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
    <h2 class="text-center">Membuat Artikel Baru</h2>
    <form action="proses_pembuatan_artikel.php" method="POST" enctype="multipart/form-data">
      <div class="input-group_mb">
        <label for="nama_penulis">Nama Penulis:</label>
        <input type="text" id="nama_penulis" name="nama_penulis" value="<?php echo htmlspecialchars($nama_penulis); ?>" readonly required>
      </div>

      <div class="input-group_mb">
        <label for="judul_artikel">Judul Artikel:</label>
        <input type="text" id="judul_artikel" name="judul_artikel" value="<?php echo isset($article['title']) ? htmlspecialchars($article['title']) : ''; ?>" required>
      </div>

      <div class="input-group_mb">
        <label for="isi_artikel">Isi Artikel:</label>
        <textarea id="isi_artikel" name="isi_artikel"><?php echo isset($article['description']) ? htmlspecialchars($article['description']) : ''; ?></textarea>
      </div>

      <div class="select-category">
        <label for="kategori">Kategori:</label>
        <select id="kategori" name="kategori" required>
          <?php
          foreach ($kategori_options as $id => $desc) {
            $selected = (isset($article['category_id']) && $article['category_id'] == $id) ? 'selected' : '';
            echo "<option value=\"$id\" $selected>$desc</option>";
          }
          ?>
        </select>
      </div>

      <div class="upload-form">
        <label for="gambar">Unggah Gambar:</label>
        <input type="file" id="gambar" name="gambar">
      </div>

      <button type="submit" class="button_mb">Submit</button>
    </form>
  </section>
</div>

</body>
</html>
