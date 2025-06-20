-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 20, 2025 at 12:14 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_berita`
--

-- --------------------------------------------------------

--
-- Table structure for table `act_users`
--

CREATE TABLE `act_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status_acc` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `act_users`
--

INSERT INTO `act_users` (`user_id`, `first_name`, `last_name`, `username`, `tanggal_lahir`, `password`, `role_id`, `email`, `status_acc`) VALUES
(2, 'author', 'satu', 'author1', '1999-01-11', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author1@gmail.com', 1),
(3, 'author', 'dua', 'autho2', '1998-01-11', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author2@gmail.com', 1),
(4, 'admin', 'satu', 'admin1', '1997-01-24', 'admin1', 1, 'admin1@gmail.com', 1),
(5, 'user', 'test', 'user_test', '1998-10-13', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'user_test@gmail.com', 1),
(6, 'author', 'tiga', 'author3', '1998-06-01', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author3@gmail.com', 1),
(7, 'Dece Ganteng', 'empat', 'penulis12', '1997-12-22', 'ee11cbb19052e40b07aac0ca060c23ee', 1, 'author4@gmail.com', 1),
(9, 'Dwi', 'Wijaya', 'admin', '2002-02-19', 'ef9db03035f168fd74971fb61060b295', 2, 'candraexprore@gmail.com', 1),
(10, 'Dwi', 'Wijaya', 'adminaa', '2025-05-13', '3dfc4f433cc4665fc3461ee7ac1146c3', 2, 'candraexprore@gmail.com', 1),
(11, 'Dwi', 'Wijaya', 'dece1234', '2025-05-06', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'candraexprore@gmail.com', 1),
(12, 'Dwi', 'Wijaya', 'aqiilah', '2025-05-21', 'ef9db03035f168fd74971fb61060b295', 1, 'candraexprore@gmail.com', 1),
(13, 'Dwi', 'Wijaya', 'dece1', '2025-05-01', '0258e9ad9aa95b67725222e452c4c64a', 2, 'candraexprore@gmail.com', 1),
(15, 'Dwi', 'Wijaya', 'admin24', '2025-06-05', 'ef9db03035f168fd74971fb61060b295', 2, 'candraexprore@gmail.com', 1),
(16, 'Dwi', 'Wijaya', 'Stephen Dece', '2025-06-05', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'candraexprore2@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category_post`
--

CREATE TABLE `category_post` (
  `category_id` int(11) NOT NULL,
  `category_description` varchar(50) NOT NULL,
  `total_post` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category_post`
--

INSERT INTO `category_post` (`category_id`, `category_description`, `total_post`) VALUES
(11, 'Sports', 0),
(12, 'Health', 0),
(13, 'Politics', 0),
(14, 'Entertainment', 0),
(15, 'Business', 0),
(16, 'Favorite', 0);

-- --------------------------------------------------------

--
-- Table structure for table `post_article`
--

CREATE TABLE `post_article` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) DEFAULT NULL,
  `post_img` varchar(255) DEFAULT NULL,
  `total_like` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `status` enum('active','takedown') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `post_article`
--

INSERT INTO `post_article` (`post_id`, `title`, `description`, `category_id`, `post_date`, `username`, `post_img`, `total_like`, `user_id`, `status`) VALUES
(36, 'Terungkap! Segini Iuran RI ke OECD Bila Diterima Jadi Anggota', 'Jakarta, CNBC Indonesia -&nbsp;Indonesia tengah berusaha keras untuk bisa cepat menjadi anggota kelompok negara-negara maju, yakni Organisation Economic Cooperation and Development (OECD). Namun, bila telah menjadi anggota, penting dicatat, Indonesia harus membayar iuran yang disebut budget contributions. Menteri Koordinator Bidang Perekonomian Airlangga Hartarto mengatakan, perhitungan terkait dengan porsi budget contributions di OECD akan didasari dari dua aspek, yakni kapasitas perekonomian suatu negara yang tercermin dari produk domestik bruto (PDB) nya serta besaran populasi penduduknya, \"Terkait dengan budget contribution nanti akan dihitung berdasarkan GDP dan populasi sesudah kita menjadi anggota penuh,\" kata Airlangga saat konferensi pers secara daring, Rabu (4/6/2025). Saat ini, Indonesia masih menjalani proses aksesi untuk menjadi anggota penuh OECD. Tahapan terakhir yang sudah diselesaikan ialah penyerahan Initial Memorandum (IM) oleh Menteri Koordinator Bidang Perekonomian RI Airlangga Hartarto kepada Sekretaris Jenderal OECD Mathias Cormann dalam pertemuan bilateral di sela-sela Pertemuan Tingkat Menteri (PTM) Dewan OECD 2025 yang berlangsung di Paris, Prancis, Selasa (3/6/2025). Proses aksesi ini Airlangga targetkan selesai dalam waktu 4 tahun. Namun, untuk bisa menjadi anggota penuh, biasanya menurut dia membutuhkan waktu yang cukup panjang, berkisar di atas 5 tahun sampai dengan 10 tahun. Maka, ketika Indonesia telah menyelesaikan seluruh proses aksesi, iuran wajib harus dipenuhi, sebagaimana penjelasan yang tertuang dalam OECD.org. Semua negara anggota disebutkan harus memberikan kontribusi pendanaan untuk anggaran OECD yang terbagi dalam dua aspek, yakni Part I Budget dan Part II Budget. Anggaran Bagian I untuk 2025 adalah EUR 235 juta dan Anggaran Bagian II berjumlah EUR 126,1 juta. Anggaran Bagian I dan Bagian II bila digabungkan berjumlah EUR 361,1 juta atau setara Rp 6,70 triliun. Adapun persentase bagian budget contribution per negara untuk pemenuhan Part I Budget pada 2025 sebagai berikut: Amerika Serikat - 18.3% Jepang - 7.9% Jerman - 7.6% Inggris - 5.5% Perancis - 5.1% Italia - 4.0% Kanada - 3.9% Korea - 3.6% Australia - 3.2% Spanyol - 3.0% Meksiko - 2.9% Belanda - 2.4% Swiss - 2.1% Turki - 2.1% Polandia - 1.8% Belgia - 1.7% Norwegia - 1.6% Swedia - 1.6% Austria - 1.5% Israel - 1.5% Denmark - 1.4% Irlandia - 1.4% Chili - 1.2% Kolombia - 1.2% Ceko - 1.2% Finlandia - 1.2% Portugal - 1.2% Yunani - 1.1% Selandia Baru - 1.1% Hongaria - 1.0% Republik Slovakia - 1.0% Costa Rika - 0.9% Lithuania - 0.9% Bahasa Slovenia - 0.9% Estonia - 0.8% Latvia - 0.8% Luksemburg - 0.8% Islandia - 0.6%', 15, '2025-06-05 04:43:53', 'admin', '9_20250605_kpufFulPnq.jpeg', 30, 9, 'active'),
(37, 'Mengintip Latihan Terakhir Timnas Indonesia Jelang Lawan China: Siap Menang!', 'Bola.net - Timnas Indonesia&nbsp;menggelar sesi latihan terakhir pada Rabu malam (4/6/2025) di Stadion Utama Gelora Bung Karno (SUGBK), Jakarta. Latihan ini menjadi bagian penting dari persiapan jelang laga krusial melawan China dalam lanjutan Kualifikasi Piala Dunia 2026 zona Asia.\r\nSesi yang dimulai pukul 20.15 WIB ini digelar setelah tim tamu menyelesaikan jadwal latihan mereka. Selain fokus pada penyempurnaan taktik, Timnas Indonesia juga mengadakan konferensi pers pra-pertandingan dengan kehadiran pelatih Patrick Kluivert dan kiper Emil Audero yang diprediksi akan membuat debutnya.\r\nLatihan malam ini bukan sekadar rutinitas biasa, melainkan momen krusial untuk menyesuaikan diri dengan kondisi lapangan SUGBK. Faktor seperti kekerasan permukaan dan tingkat kelicinan rumput menjadi perhatian serius karena akan mempengaruhi kontrol bola dan mobilitas pemain saat menghadapi China.\r\n\r\n\r\nMengintip Latihan Terakhir Timnas Indonesia Jelang Lawan China: Siap Menang!\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\nBola.net -&nbsp;Timnas Indonesia&nbsp;menggelar sesi latihan terakhir pada Rabu malam (4/6/2025) di Stadion Utama Gelora Bung Karno (SUGBK), Jakarta. Latihan ini menjadi bagian penting dari persiapan jelang laga krusial melawan China dalam lanjutan Kualifikasi Piala Dunia 2026 zona Asia.\r\nSesi yang dimulai pukul 20.15 WIB ini digelar setelah tim tamu menyelesaikan jadwal latihan mereka. Selain fokus pada penyempurnaan taktik, Timnas Indonesia juga mengadakan konferensi pers pra-pertandingan dengan kehadiran pelatih Patrick Kluivert dan kiper Emil Audero yang diprediksi akan membuat debutnya.\r\nLatihan malam ini bukan sekadar rutinitas biasa, melainkan momen krusial untuk menyesuaikan diri dengan kondisi lapangan SUGBK. Faktor seperti kekerasan permukaan dan tingkat kelicinan rumput menjadi perhatian serius karena akan mempengaruhi kontrol bola dan mobilitas pemain saat menghadapi China.\r\nTimnas Indonesia datang dengan tekad bulat untuk membalas kekalahan 1-2 dari China di pertemuan sebelumnya. Hasil positif di kandang sendiri sangat dibutuhkan untuk menjaga peluang lolos ke fase berikutnya Kualifikasi Piala Dunia 2026. Semangat juang skuad Garuda tampak menyala-nyala dalam latihan penutup ini.\r\nDengan persiapan matang dan dukungan suporter di SUGBK, Timnas Indonesia siap memberikan perlawanan sengit melawan China. Pertandingan ini tidak hanya tentang tiga poin, tapi juga harga diri dan ambisi untuk terus bertahan di jalur menuju Piala Dunia 2026.\r\n\r\n\r\n\r\n', 11, '2025-06-05 06:36:28', 'admin', '9_20250605_GsHSOOW4EE.jpeg', 14, 9, 'active'),
(39, 'Cerita Terciptanya Lagu Nuansa Bening: Addie MS Isi Piano saat Masih SMA', 'Lagu Nuansa Bening saat ini tengah menjadi bahan perbincangan. Keenan Nasution dan Rudi Pekerti, sebagai pencipta asli lagu tersebut, menggugat Vidi Aldiano yang menyanyikan aransemen ulangnya pada 2008. Kepada kumparan, Keenan menyebut lagu itu pertama kali diciptakan pada 1977. Saat itu, Keenan memantau perkembangan musik Indonesia di era 1970-1980an. Gairah bermusik yang masih meluap-luap membuat Keenan ingin menciptakan satu lagu dengan nuansa yang berbeda daripada lagu lainnya. &nbsp; \"Saya tahu musik Indonesia kayak ini dan itu. Saya ikut di Badai Pasti Berlalu, Guruh Gipsy, saya tahu karakter musiknya gimana, liriknya gimana. Tapi saya ingin bikin yang beda,\" jelas Keenan. Lirik yang Punya Makna Luas Saat itu, Keenan meminta Rudi menciptakan lirik yang punya makna luas. Bisa diartikan apa saja sesuai keinginan pendengar. &nbsp; \"Kami bikin Nuansa Bening akhirnya. Dengan perjuangan bahwa, sebenarnya lirik itu, saya berembuk sama Rudi, gimana caranya kata-katanya itu biasa tapi double meaning,\" ujar Keenan. Setelah lagu itu jadi, Keenan merasa masih ada yang kurang dari segi aransemen. Ada bunyi piano yang ternyata sebenarnya bisa memperindah lagu Nuansa Bening. Keenan pun bertemu Addie MS, yang saat itu masih duduk di bangku SMA. Keenan dikenalkan oleh teman-teman Addie yang ikut dalam Lomba Cipta Lagu Remaja (1977). \"Saya bertemu sama teman-temannya Addie. Mereka bilang asyik lagu ini, tapi jangan sama gitar aja. Harus piano juga,\" tutur Keenan. &nbsp; \"Akhirnya saya bertemu Addie. Mereka masih duduk di SMA 3. Saya sering bawakan lagu mereka yang menang di ajang lomba itu. Akhirnya saya bertemu Addie di situ. Saya ajak akhirnya,\" ujar Keenan.', 14, '2025-06-12 02:04:06', 'penulis12', '7_20250612_awxz2L27vN.jpg', 16, 7, 'active'),
(48, 'Peringatan Keras Rusia: Israel Harus Hentikan Serangan ke Situs Nuklir Iran, AS Jangan Ikut Campur', 'REPUBLIKA.CO.ID, PETERSBURG - Rusia menganggap serangan Israel terhadap fasilitas nuklir di Iran sebagai hal yang tidak dapat diterima. Juru bicara Kementerian Luar Negeri Rusia, Maria Zakharova mengatakan, Rusia juga mendesak Tel Aviv untuk segera menghentikan serangannya. \"Rusia meminta para pemimpin Israel untuk segera menghentikan serangan terhadap instalasi dan lokasi nuklir yang berada di bawah perlindungan dan yang menjadi subjek kegiatan verifikasi IAEA,\" kata Zakharova di sela-sela Forum Ekonomi Internasional St. Petersburg \"Kami khususnya prihatin dengan keamanan pembangkit listrik tenaga nuklir Bushehr, lokasi para ahli Rusia bekerja,\" tambahnya. Rusia juga memperingatkan Amerika Serikat (AS) untuk tidak melakukan intervensi militer dalam konflik Iran-Israel, karena dapat menjadi sebuah langkah yang sangat berbahaya. \"Kami berharap pemahaman bahwa tidak ada alternatif selain menemukan solusi negosiasi yang dapat diterima bersama untuk masalah yang ada akan muncul,\" katanya. Saat ini Kota St. Petersburg Rusia menjadi tuan rumah SPIEF ke-28 yang berlangsung pada 18-21 Juni, dengan mengusung tema&nbsp;Shared Values: The Foundation of Growth in a Multipolar World. Perusahaan induk RIA Novosti, Grup media internasional Rossiya Segodnya, menjadi mitra informasi forum tersebut.', 12, '2025-06-19 15:38:37', 'admin', '9_20250619_zIyw9IstbP.jpg', 11, 9, 'active'),
(49, 'Saling Serang Israel-Iran Kamis 19 Juni: Fasilitas Nuklir dan RS Kena Hantam', 'Pada Kamis, 19 Juni 2025, Israel menyerang fasilitas nuklir Arak, sedangkan serangan Iran menghantam gedung bursa efek dan rumah sakit.\r\n\r\nTEMPO.CO,&nbsp;Jakarta&nbsp;- Pada hari ketujuh, Kamis, 19 Juni 2025, konflik antara&nbsp;Israel&nbsp;dan&nbsp;Iran&nbsp;tidak mereda. Tensi antara kedua negara bahkan semakin memanas dengan serangkaian aksi militer besar-besaran dari kedua belah pihak.&nbsp;\r\n\r\n\r\nSepanjang tujuh hari berkonflik terbuka, pertukaran serangan udara dan rudal balistik terus terjadi. Pada Rabu, intensitas serangan dari Tel Aviv maupun Teheran sempat mereda. Namun, pada Kamis, 19 Juni, konflik mereka seperti memasuki intensitas baru, dengan target strategis semakin diperluas dan korban sipil meningkat.\r\nIsrael melancarkan serangan udara ke fasilitas nuklir Arak di Iran. Iran membalas dengan peluncuran rudal balistik yang berhasil mengenai sejumlah gedung termasuk rumah sakit di selatan Israel dan gedung bursa efek Tel Aviv.\r\nSerangan Israel&nbsp;\r\nPada Kamis hari, 19 Juni 2025, Israel melancarkan serangan udara ke reaktor air berat di Arak, salah satu pusat penelitian nuklir utama Iran. Dalam pernyataannya, Israel mengklaim bahwa serangan ini adalah bagian dari operasi lebih luas untuk melemahkan ambisi nuklir Iran, yang mereka anggap sebagai ancaman eksistensial bagi keberadaan negara Yahudi tersebut.\r\n\r\nMenurut laporan&nbsp;Times of Israel&nbsp;yang mengutip keterangan Pasukan Pertahanan Israel (IDF), gelombang serangan semalam melibatkan 40 pesawat tempur yang menjatuhkan 100 amunisi. Selain reaktor Arak, serangan juga ditujukan&nbsp;ke puluhan fasilitas militer Iran di Teheran dan wilayah lain di Iran.\r\n\r\n\r\nIDF mengeluarkan peringatan sebelum serangan terhadap reaktor Arak dan sudah mendesak penduduk di daerah sekitarnya untuk mengungsi. &ldquo;Serangan tersebut menargetkan komponen yang dimaksudkan untuk produksi plutonium, guna mencegah reaktor tersebut dipulihkan dan digunakan untuk pengembangan senjata nuklir,&rdquo; demikian media itu mengutip keterangan dari pejabat militer Israel. &nbsp;\r\n\r\n', 13, '2025-06-19 18:18:22', 'dece1', '13_20250620_AoUUF6AScL.jpg', 6, 13, 'active'),
(50, 'Piala Dunia Klub 2025 - Dibuka Lionel Messi dan Diikuti Pemain Berdarah Indonesia', 'BOLASPORT.COM -&nbsp;Piala Dunia Klub 2025&nbsp;akan digelar hanya dalam hitungan kurang dari dua pekan.\r\nTurnamen revolusioner garapan FIFA tersebut akan menerapkan format baru sebagai prolog untuk Piala Dunia 2026.&nbsp;Pentas bernama resmi FIFA Club World Cup 2025 dijadwalkan berlangsung pada 14 Juni-13 Juli mendatang.\r\n\r\nPesertanya terdiri atas 32 klub yang mewakili semua zona atau konfederasi FIFA.&nbsp;Para kontestan tersebut lolos berdasarkan kualifikasi masing-masing yang terbagi menjadi empat kelompok besar.\r\nKelompok pertama ialah para pemenang kompetisi antarklub terelite di zona masing-masing dalam 2-4 musim terakhir.&nbsp;Rombongan kedua meraih tiket berkat performa kumulatif di zona masing-masing dalam lingkup 4 tahun.\r\nSelanjutnya golongan ketiga dan keempat ialah pemilik privilese dari pertimbangan FIFA.&nbsp;Mereka ialah&nbsp;Inter Miami, yang lolos sebagai perwakilan tuan rumah sekaligus peraih gelar Supporters\' Shield MLS 2024, serta Los Angeles FC si pemenang play-off.', 11, '2025-06-19 18:22:13', 'dece1', '13_20250620_i1LJHnw6bk.jpg', 6, 13, 'active'),
(57, 'Hasil Drawing Piala Dunia U-17 2025: Siapa Lawan Timnas Indonesia?', 'Bola.net - Timnas Indonesia menggelar sesi latihan terakhir pada Rabu malam (4/6/2025) di Stadion Utama Gelora Bung Karno (SUGBK), Jakarta. Latihan ini menjadi bagian penting dari persiapan jelang laga krusial melawan China dalam lanjutan Kualifikasi Piala Dunia 2026 zona Asia. Sesi yang dimulai pukul 20.15 WIB ini digelar setelah tim tamu menyelesaikan jadwal latihan mereka. Selain fokus pada penyempurnaan taktik, Timnas Indonesia juga mengadakan konferensi pers pra-pertandingan dengan kehadiran pelatih Patrick Kluivert dan kiper Emil Audero yang diprediksi akan membuat debutnya. Latihan malam ini bukan sekadar rutinitas biasa, melainkan momen krusial untuk menyesuaikan diri dengan kondisi lapangan SUGBK. Faktor seperti kekerasan permukaan dan tingkat kelicinan rumput menjadi perhatian serius karena akan mempengaruhi kontrol bola dan mobilitas pemain saat menghadapi China. Mengintip Latihan Terakhir Timnas Indonesia Jelang Lawan China: Siap Menang! Bola.net - Timnas Indonesia menggelar sesi latihan terakhir pada Rabu malam (4/6/2025) di Stadion Utama Gelora Bung Karno (SUGBK), Jakarta. Latihan ini menjadi bagian penting dari persiapan jelang laga krusial melawan China dalam lanjutan Kualifikasi Piala Dunia 2026 zona Asia. Sesi yang dimulai pukul 20.15 WIB ini digelar setelah tim tamu menyelesaikan jadwal latihan mereka. Selain fokus pada penyempurnaan taktik, Timnas Indonesia juga mengadakan konferensi pers pra-pertandingan dengan kehadiran pelatih Patrick Kluivert dan kiper Emil Audero yang diprediksi akan membuat debutnya. Latihan malam ini bukan sekadar rutinitas biasa, melainkan momen krusial untuk menyesuaikan diri dengan kondisi lapangan SUGBK. Faktor seperti kekerasan permukaan dan tingkat kelicinan rumput menjadi perhatian serius karena akan mempengaruhi kontrol bola dan mobilitas pemain saat menghadapi China. Timnas Indonesia datang dengan tekad bulat untuk membalas kekalahan 1-2 dari China di pertemuan sebelumnya. Hasil positif di kandang sendiri sangat dibutuhkan untuk menjaga peluang lolos ke fase berikutnya Kualifikasi Piala Dunia 2026. Semangat juang skuad Garuda tampak menyala-nyala dalam latihan penutup ini. Dengan persiapan matang dan dukungan suporter di SUGBK, Timnas Indonesia siap memberikan perlawanan sengit melawan China. Pertandingan ini tidak hanya tentang tiga poin, tapi juga harga diri dan ambisi untuk terus bertahan di jalur menuju Piala Dunia 2026.', 11, '2025-06-20 06:47:46', 'Stephen Dece', '16_20250620_BMoEUWuWE6.jpg', 7, 16, 'active'),
(58, 'Timnas Indonesia U-17 Bakal Tambah Amunisi Pemain Keturunan Sebelum Terjun ke Piala Dunia U-17 2025', '\r\nBola.net -&nbsp;Pelatih&nbsp;Timnas Indonesia U-17,&nbsp;Nova Arianto, mengatakan bakal memperkuat tim asuhannya dengan penambahan pemain keturunan sebelum terjun ke pentas&nbsp;Piala Dunia U-17 2025.\r\nTimnas Indonesia U-17 mendapatkan tiket untuk berlaga di pentas dunia selepas melaju ke babak perempat final Piala Asia U-17 2025. Nova Arianto mulai berbicara mengenai persiapan yang akan dilakukan tim asuhannya berlaga di Piala Dunia U-17 2025.\r\nNova Arianto menyebut persiapan menuju Piala Dunia U-17 2025 tentu akan berbeda dengan persiapan yang dijalani oleh tim asuhannya sebelum berlaga di Piala AFF dan Piala Asia.\r\n\r\n&nbsp;\r\n\r\n\"Yang pasti di jalannya persiapan nanti akan berbeda, karena kalau di AFF, di kualifikasi atau di Piala Asia mungkin dengan pemain yang ada saat ini kita sangat-sangat mampu lah untuk bersaing di sana,\" kata Nova Arianto.\r\n\"Tetapi dengan level Piala Dunia yang pasti akan lebih di atas yang di Piala Asia pastinya persiapannya kita akan lebih fokus lagi terutama dalam kita melihat posisi mana saja yang perlu ada tambahan pemain. Dan kita akan cari opsinya nanti setelah kita lihat apa yang harus kita evaluasi,\" ungkapnya.\r\nTimnas Indonesia U-17 Masuk Pot 3 Drawing Piala Dunia U-17 2025, Ini Reaksi Nova Arianto\r\nNova Arianto Liburkan Timnas Indonesia U-17 Selama 2 Bulan, Kumpul Lagi pada Juli 2025 untuk Piala Dunia U-17 2025\r\n\r\nSelebrasi skuad Timnas Indonesia U-17 dalam laga Piala Asia U-17 2025 versus Afghanistan, Jumat (11/4/2025). (c) AFC\r\nNova Arianto pun menyebut dalam waktu dekat Timnas Indonesia U-17 akan menggelar proses seleksi menuju Piala Dunia U-17 2025.\r\nSeleksi itu tidak hanya menyasar pemain yang saat ini bermain di Indonesia. Pemain diaspora yang sedang memulai karier di luar negeri pun akan mendapatkan kesempatan yang sama.\r\n\"Seleksi pemain ada dan tidak terlepas dengan opsi-opsi pemain diaspora yang mungkin akan kita coba dan kita lihat semuanya.\r\n', 11, '2025-06-20 06:49:27', 'Stephen Dece', '16_20250620_8zYcOT0maj.jpg', 7, 16, 'active'),
(61, 'Nova Arianto Fokus ke Piala Dunia U-17 2025 Meski Disebut Berpeluang Gabung Timnas Indonesia U-23 di SEA Games 2025', 'Bola.net -&nbsp;Nova Arianto&nbsp;mengaku masih akan fokus mempersiapkan&nbsp;Timnas Indonesia U-17&nbsp;ke&nbsp;Piala Dunia U-17 2025&nbsp;meski namanya disebut berpeluang gabung tim kepelatihan&nbsp;Timnas Indonesia U-23&nbsp;untuk&nbsp;SEA Games 2025. Ketua umum PSSI, Erick Thohir, sebelumnya mengatakan dirinya tidak menutup kemungkinan untuk terus melengkapi komposisi tim kepelatihan Timnas Indonesia U-23 asuhan Gerald Vanenburg. Tim Garuda Muda tengah disiapkan untuk berlaga di Piala AFF U-23 2025 dan SEA Games pada tahun yang sama. Belakangan Gerald Vanenburg tidak bekerja sendirian. Pelatih asal Belanda itu terlihat memantau beberapa pertandingan BRI Liga 1 2024/2025 didampingi Indra Sjafri dan Sjoerd Woudenberg. &nbsp; \"Yang untuk SEA Games kami masih melakukan&nbsp;review. Kami akan lihat juga prestasi&nbsp;Coach&nbsp;Nova Arianto, dia juga harus dipertimbangkan. Kalau tiba-tiba&nbsp;Coach&nbsp;Nova bikin ledakan,\" ujar Erick Thohir di Jakarta belum lama ini. Fix! Oxford United dan Port FC Dipastikan Ikut Meramaikan Piala Presiden 2025 Arab Saudi &amp; Qatar Jadi Tuan Rumah Ronde 4 Kualifikasi Piala Dunia 2026 Zona Azia, Ini Reaksi Erick Thohir Starting XI Timnas Indonesia U-17 dalam laga perempat final Piala Asia U-17 2025 versus Korea Utara, Senin (14/4/2025). (c) AFC Nova Arianto mengaku belum mengetahui rencana PSSI memasukannya ke tim kepelatihan Timnas Indonesia U-23. Eks pemain Persib Bandung itu ingin fokus mempersiapkan Timnas Indonesia U-17 untuk berlaga di Piala Dunia U-17 2025. Diketahui, Timnas Indonesia U-17 dipastikan akan berlaga di Piala Dunia U-17 2025. Turnamen usia muda itu akan digelar di Qatar pada November mendatang. \"Saya belum terpikir ke sana, karena PSSI juga belum sampaikan hal itu kepada saya. Tetapi saya ingin fokus ke Piala Dunia, dan kita lihat seperti apa PSSI ke depannya, dan pasti saya akan selalu ikut arahan,\" tegas Nova Arianto.', 11, '2025-06-20 08:20:56', 'Stephen Dece', '16_20250620_TCwVMQ7bjY.jpg', 5, 16, 'active'),
(65, 'Dihajar Korea Utara U-17 di Piala Asia U-17 2025', '<p>Bola.net - Pelatih Timnas Indonesia U-17, Nova Arianto, membeberkan apa hasil evaluasi pada anak-anak asuhnya usai mereka dihajar Timnas Korea Utara U-17 di Piala Asia U-17 2025. Timnas Indonesia U-17 memang tidak berdaya saat menghadapi Timnas Korea Utara U-17 di perempat final Piala Asia U-17 2025. Laga yang berlangsung di King Abdullah Sports City Hall benar-benar bukan milik Garuda Asia. Meski demikian, Timnas Indonesia U-17 tidak perlu terlalu berkecil hati. Dengan lolos ke perempat final Piala Asia U-17 2025, Daniel Alfrido dan kawan-kawan sudah dipastikan akan ambil bagian di Piala Dunia U-17 2025. &nbsp; Pelatih Timnas Indonesia U-17, Nova Arianto, mengaku memetik pelajaran yang bagus dari kekalahan telak tersebut. Nova merasa fokus para pemainnya harus diperbaiki, terutama saat tampil di Piala Dunia U-17 nanti. \"Evualuasi terbesarnya saat melawan Korut. Masalah fokus pemain, setelah kita lolos Piala Dunia, fokus pemain jadi sedikit lepas, secara mental pemain juga sedikit lepas, setelah memastikan ke Piala Dunia,\" jelas Nova Arianto. Hasil Drawing Piala Dunia U-17 2025: Siapa Lawan Timnas Indonesia? Timnas Indonesia U-17 Masuk Pot 3 Drawing Piala Dunia U-17 2025, Ini Reaksi Nova Arianto Starting XI Timnas Indonesia U-17 dalam laga perempat final Piala Asia U-17 2025 versus Korea Utara, Senin (14/4/2025). (c) AFC Nova Arianto kemudian menyebut kualitas individu para pemain di Timnas Indonesia U-17 sudah sangat baik. Mereka juga menunjukkan potensi berkembang yang sangat bagus. Namun, kondisi itu masih belum membuat Nova Arianto berpuas diri. Nova masih ingin terus memperbaiki kualitas permainan tim asuhannya sebelum terjun di ajang Piala Dunia U-17 2025. \"Termasuk soal masalah skill individu yang masih kalah bersaing untuk di Piala Dunia, ke depannya itu yang akan kami perbaiki, semoga bisa meraih hasil terbaik,\" tegas Nova. Jadwal Siaran Langsung Piala Asia U-17 2025 di RCTI, GTV, dan iNews, 3-20 April 2025 Nova Arianto Liburkan Timnas Indonesia U-17 Selama 2 Bulan, Kumpul Lagi pada Juli 2025 untuk Piala Dunia U-17 2025 &nbsp; Aksi Nova Arianto ketika Timnas Indonesia bermain lawan Australia pada Kualifikasi Piala Asia U-17 2024 (c) Media PSSI Lebih lanjut, Nova Arianto mengucapkan terima kasih kepada pihak terkait. Terutama kepada PSSI dan masyarakat yang memberikan dukungan tak terhinga untuk Timnas Indonesia U-17. \"Terima kasih kepada PSSI yang telh memberikan suppotr kepada kami, kami bisa jauh sampai sekarang, bukan karena saya atau apa pun, ini karena semuanya, pemain, staff, ofisial, dan masyarakat sepak bola, kami bisa meraih hasil terbaik, saya sangat bersyukur, semoga kamijuga meraih hasil terbaik di Piala Dunia,\" tandas Nova Arianto. Skuad Timnas Indonesia U-17 tiba di Jakarta, Kamis (17/4/2025) malam. Untuk sementara para pemain akan dikembalikan ke klub masing-masing.</p>', 11, '2025-06-20 08:40:09', 'Stephen Dece', '16_20250620_u8Gs5OfzG1.jpg', 9, 16, 'active'),
(66, 'Dihajar Korea Utara U-17 di Piala Asia U-17 2025', 'aaaaaaaazzzaaa', 11, '2025-06-20 09:49:31', 'Stephen Dece', '16_20250620_87rnQYFoLq.jpeg', 1, 16, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(11) NOT NULL,
  `role_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`role_id`, `role_description`) VALUES
(1, 'admin'),
(2, 'author');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `act_users`
--
ALTER TABLE `act_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `category_post`
--
ALTER TABLE `category_post`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `post_article`
--
ALTER TABLE `post_article`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `act_users`
--
ALTER TABLE `act_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `category_post`
--
ALTER TABLE `category_post`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `post_article`
--
ALTER TABLE `post_article`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `act_users`
--
ALTER TABLE `act_users`
  ADD CONSTRAINT `act_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role_user` (`role_id`);

--
-- Constraints for table `post_article`
--
ALTER TABLE `post_article`
  ADD CONSTRAINT `post_article_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_post` (`category_id`),
  ADD CONSTRAINT `post_article_ibfk_2` FOREIGN KEY (`username`) REFERENCES `act_users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
