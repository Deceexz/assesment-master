-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2025 at 07:00 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `act_users`
--

INSERT INTO `act_users` (`user_id`, `first_name`, `last_name`, `username`, `tanggal_lahir`, `password`, `role_id`, `email`, `status_acc`) VALUES
(2, 'author', 'satu', 'author1', '1999-01-11', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author1@gmail.com', 1),
(3, 'author', 'dua', 'autho2', '1998-01-11', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author2@gmail.com', 1),
(4, 'admin', 'satu', 'admin1', '1997-01-24', 'admin1', 1, 'admin1@gmail.com', 1),
(5, 'user', 'test', 'user_test', '1998-10-13', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'user_test@gmail.com', 1),
(6, 'author', 'tiga', 'author3', '1998-06-01', 'ee11cbb19052e40b07aac0ca060c23ee', 2, 'author3@gmail.com', 1),
(7, 'author', 'empat', 'author4', '1997-12-22', 'ee11cbb19052e40b07aac0ca060c23ee', 1, 'author4@gmail.com', 1),

-- --------------------------------------------------------

--
-- Table structure for table `category_post`
--

CREATE TABLE `category_post` (
  `category_id` int(11) NOT NULL,
  `category_description` varchar(50) NOT NULL,
  `total_post` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_post`
--

INSERT INTO `category_post` (`category_id`, `category_description`, `total_post`) VALUES
(11, 'Sports', 0),
(12, 'Health', 0),
(13, 'Politics', 0),
(14, 'Entertainment', 0),
(15, 'Business', 0),
(16, 'Technology', 0);

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
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_article`
--

INSERT INTO `post_article` (`post_id`, `title`, `description`, `category_id`, `post_date`, `username`, `post_img`, `total_like`, `user_id`) VALUES
(20, 'Telkom Kembali Hadirkan Tayangan Olahraga Bergengsi melalui Channel Usee Sports di IndiHome TV', '18 May 2025 (PT Telkom Indonesia (Persero) Tbk (Telkom) kembali menghadirkan tayangan yang paling ditunggu oleh pecinta olahraga tanah air. Kali ini IndiHome menghadirkan berbagai program tayangan olahraga di Channel Usee Sports 1 dan Usee Sports 2 yang dapat dinikmati kapan saja dan di mana saja.\r\n\r\nâ€œOlahraga sudah menjadi bagian tak terpisahkan bagi masyarakat Indonesia. Oleh karena itu, mulai Januari 2021 kami menghadirkan kembali berbagai tayangan olahraga pada Channel Usee Sports 1 dan Usee Sports 2 di IndiHome TV sehingga masyarakat yang menyukai olahraga bisa menikmati dan mendapatkan informasi terkait dunia olahraga dengan mudah, aman, dan nyaman di rumah.â€ ujar E. Kurniawan selaku Vice President Marketing Management Telkom.\r\n\r\nKurniawan melanjutkan, melalui kedua channel olahraga tersebut, pecinta olahraga bisa menyaksikan secara langsung tayangan kompetisi bergengsi sepak bola Indonesia, seperti Liga 1 dan Liga 2. Cabang olahraga bergengsi basket seperti Indonesian Basketball League (IBL) juga dapat dinikmati melalui IndiHome TV. Tidak hanya itu, pelanggan juga dapat menikmati tayangan olahraga luar negeri seperti turnamen bulu tangkis Badminton World Federation (BWF), kompetisi sepakbola bergengsi dunia Liga Champion, Liga Europa dan Coppa Italia di channel Usee Sports 1 dan Usee Sports 2.\r\n\r\nPelanggan bisa menikmati berbagai macam tayangan olahraga terbaik tersebut mulai bulan Januari 2021, dengan beberapa jadwal pertandingan perdana di antaranya turnamen BWF yang terdiri dari Yonex Thailand Open 2020, Toyota Thailand Open 2021 dan HSBC BWF World Tour Final 2020, serta Coppa Italia yang tayang di bulan Januari 2021. Sedangkan, Liga Champions dan Liga Europa akan mulai tayang di bulan Februari 2021 serta liga basket tertinggi di Indonesia, yaitu IBL dijadwalkan kembali hadir di bulan Maret 2021.\r\n\r\nTidak hanya pertandingan olahraga unggulan, channel Usee Sports dan Usee Sports 2 juga memproduksi berbagai macam program olahraga berkualitas seperti Rivalitas yang menampilkan flashback persaingan sengit antar club yang terjadi di kancah sepak bola Indonesia, Ricky Smash yaitu program magazine yang membahas seputar olahraga bulu tangkis di tanah air yang dibawakan oleh legenda bulutangkis Indonesia Ricky Soebagja, Soccer Tonight yang berisi program berita perkembangan sepak bola mancanegara, berita tentang atlet hingga cuplikan pertandingan terbaik serta berbagai program menarik lainnya.\r\n\r\nTayangan olahraga unggulan tersebut dapat dinikmati oleh pelanggan IndiHome hanya dengan berlangganan minipack IndiSport 2 yang dapat diaktivasi dengan mudah melalui layar IndiHome TV atau aplikasi myIndiHome. Biaya berlangganan IndiSport 2 cukup terjangkau dan dapat dibayar dengan dua metode pembayaran melalui pre-paid atau post-paid lewat tagihan IndiHome.)', 11, '2025-05-18 05:41:28', 'author1', 'L1VpANNaFS.jpg', 0, 2),
(21, 'Usee Sports Futsal Challenge 2022 Kini Hadir di Semarang!', '18 May 2025 (Setelah sukses terlaksana di Bandung pada Maret 2022 lalu, kali ini Usee Sports kembali menyelenggarakan Usee Sports Futsal Challenge yang akan diadakan di Semarang. Masih sama seperti Futsal Challenge sebelumnya, turnamen ini akan melibatkan 2 kategori, yaitu Umum dan U-20, dimana masing-masing kategori terdiri dari 10 tim.\r\n\r\nEGM Divisi TV Video PT. Telkom Indonesia (Persero) Tbk (Telkom), Dedi Suherman menyampaikan, \"TelkomGroup selalu mendukung perkembangan olahraga di tanah air. Konten olahraga merupakan salah satu konten terpopuler yang kami miliki di IndiHome TV. Dengan tingginya antusiasme masyarakat terhadap berbagai macam kegiatan olahraga, di mana salah satunya adalah olahraga futsal, kami ingin memberikan sebuah wadah bagi mereka untuk bisa merasakan kompetisi futsal yang akan meningkatkan potensi bakat yang mereka miliki sehingga keinginan mereka akan tercapai. Dengan hadirnya Usee Sports Futsal Challenge Regional Semarang ini, para pecinta olahraga futsal bisa bertanding untuk membuktikan bahwa tim mereka adalah tim terbaik dalam olahraga ini.\"\r\n\r\nYang spesial dari Usee Sports Futsal Challenge 2022 di Semarang ini adalah para pecinta futsal bisa hadir untuk menyaksikan secara langsung di lokasi. Selain itu, pertandingan akan disiarkan juga secara langsung melalui akun Youtube Usee Sports untuk babak penyisihan, dan untuk babak Semi Final dan Final dapat disaksikan melalui channel Usee Sports di IndiHome TV, maupun aplikasi UseeTV GO, bahkan khusus di aplikasi ini, seluruh rangkaian pertandingan juga tersedia.\r\n\r\nASPROV PSSI Jawa Tengah dan Asosiasi Futsal Provinsi Jawa Tengah juga turut antusias dalam menyambut Usee Sports dan Futsalation selaku pelaksana pertandingan futsal ini berharap event ini dapat menjadi wadah bagi atlet futsal untuk meningkatkan skill, meraih prestasi dan berperan dalam pembinaan futsal di Indonesia.\r\n\r\nâ€œPandemi sejak tahun 2020 kemarin memang menjadi sebuah halangan terbesar untuk menjalankan agenda di luar rumah, terutama Futsal. Dan kita harus bersyukur saat ini kondisi sudah mulai pulih dan membaik, sehingga turnamen Usee Sports Futsal Challenge di Semarang akan bisa kita laksanakan, apalagi masyarakat dapat menyaksikan langsung aksi-aksi tim futsal terbaik saat bertanding, tentu akan membangkitkan memori kita akan suasana menonton pertandingan secara langsung.â€ ujar Muchamad C. Maretan, Ketua Asosiasi Futsal Provinsi Jawa Tengah.\r\n\r\nKomitmen Usee Sports sebagai channel TV di bawah naungan IndiHome dan TelkomGroup ditunjukkan dengan menyelenggarakan turnamen-turnamen futsal,  guna meningkatkan gairah serta minat olah raga futsal, terlebih situasi olah raga sempat mati suri akibat pandemi Covid-19.)', 11, '2025-05-18 05:42:19', 'author1', 'euNXPZcsM8.jpg', 0, 2),
(22, 'SATUNADI: Terobosan Digital Telkom untuk Layanan Kesehatan Masa Depan', '18 May 2025 (PT Telkom Indonesia (Persero) Tbk, melalui umbrella brand Leap, menghadirkan solusi digitalisasi terdepan bagi rumah sakit bernama SATUNADI. Platform ini mentransformasi pengelolaan data dan informasi di sektor kesehatan, menghadirkan efektivitas layanan, efisiensi biaya, dan transparansi pada sektor kesehatan.\r\nEVP Digital Business Telkom, Komang Budi Aryasa, menyampaikan melalui platform SATUNADI, Telkom mengambil peran dalam mendigitalkan sektor kesehatan agar kualitas hidup masyarakat meningkat.\r\n\r\n\"Transformasi digital dengan SATUNADI membantu manajemen di rumah sakit semakin baik dan pelayanan yang diberikan kepada masyarakat lebih maksimal,\" ujarnya dalam keterangan tertulis, Selasa (6/2).\r\n)', 12, '2025-05-18 05:44:46', 'autho2', 'pT4O4PwaE5.jpg', 3, 3),
(23, 'Telkom Jadi BUMN Terbaik dalam Penanganan Krisis & Pengelolaan Media pada BCOMMS 2025', '18 May 2025 (Sebagai bentuk pengakuan atas kinerjanya terkait pengelolaan komunikasi dan program keberlanjutan, PT Telkom Indonesia (Persero) Tbk dianugerahi empat penghargaan oleh Kementerian BUMN dalam ajang BUMN Corporate Communication and Sustainability Summit (BCOMSS) 2025. Telkom meraih predikat Juara 1 Best Crisis Handling BUMN, Juara 1 SME Development, Juara 2 Media Relationship Management, dan Facilitator Rumah BUMN of The Year. Penghargaan ini diserahkan langsung oleh Menteri BUMN Erick Thohir kepada Direktur Digital Business Telkom, Muhamad Fajrin Rasyid dan VP Corporate Communication Telkom, Andri Herawan Sasoko di Tennis Indoor Senayan, Jakarta pada Kamis pekan lalu.\r\n\r\nDirektur Digital Business Telkom, Muhamad Fajrin Rasyid Telkom mengucapkan terima kasih atas apresiasi yang diberikan Kementerian BUMN kepada Telkom. â€œPenghargaan ini tentunya tidak hanya sebagai wujud kalibrasi atas apa yang sudah Telkom lakukan saat ini, tapi juga motivasi untuk terus memberikan yang terbaik ke depannya.\r\n\r\nTerima kasih juga kami ucapkan kepada tim Corporate Communication, Community Development Center, dan seluruh karyawan Telkom yang telah menggaungkan program dan pencapaian  perusahaan sehingga dapat diterima dengan baik oleh masyarakat. Semoga Telkom dapat terus menjalin hubungan baik dengan masyarakat, media, dan seluruh stakeholder,â€ ungkap Fajrin.)', 15, '2025-05-18 05:47:00', 'autho2', 'cgTzd6PJRc.jpg', 0, 3),
(25, 'Wacana Jokowi Pimpin Koalisi Partai Pengusung Prabowo hanya Dinamika Politik', '18 May 2025 (18 May 2025 (Ketua Umum (Ketum) Relawan Pro Jokowi (Projo) Budi Arie Setiadi mengatakan wacana Presiden Joko Widodo memimpin koalisi partai politik pengusung calon presiden Prabowo Subianto masih sebatas dinamika. Ia mengatakan proses pemilu 2025 belum selesai. Masih banyak hal yang akan terjadi hingga nantinya presiden terpilih dilantik. \"Yang namanya aspirasi, yang namanya pendapat, untuk hal-hal tertentu seperti tadi ya tidak apa-apa. Itu dinamika saja,\" ujarnya di Kompleks Istana Kepresidenan, Jakarta, Rabu (13/3) sore.Sebagaimana diberitakan sebelumnya, wacana Presiden Jokowi memimpin koalisi partai pengusung Prabowo Subianto-Gibran Rakabuming Raka diusulkan oleh Partai Solidaritas Indonesia (PSI).))', 13, '2025-05-18 05:53:52', 'autho2', 'jwtxK3g12j.jpg', 0, 3),
(26, 'Kabar Gembira untuk Pelanggan! Disney+ Hotstar Kini dapat Diakses di STB IndiHome TV', '18 May 2025 (IndiHome, layanan internet cepat milik PT Telkom Indonesia (Persero) Tbk (Telkom) berkomitmen untuk terus menghadirkan program hiburan berkualitas dengan pelayanan terbaik melalui TV Interaktif. Demi menjadi window of entertainment dengan beragam konten terbaik, usai berkolaborasi dengan The Walt Disney Company September 2021 lalu, kini pelanggan IndiHome dapat mengakses platform streaming terkemuka Disney+ Hotstar melalui Set Top Box (STB) IndiHome TV terpilih secara langsung. Dengan demikian, layanan Disney+ Hotstar resmi ada di IndiHome TV.\r\n\r\nDedi Suherman selaku Executive General Manager Divisi TV and Video Telkom menyampaikan, â€œKerjasama Telkom dengan The Walt Disney Company dalam menghadirkan Disney+ Hotstar di IndiHome TV merupakan wujud komitmen kami dalam meningkatkan layanan bagi pelanggan melalui konten hiburan berkualitas yang dapat dinikmati dengan lebih mudah. Kami berharap inisiatif ini akan meningkatkan pengalaman digital terbaik bagi pelanggan sekaligus memperkuat langkah kami untuk posisi menjadi window of entertainment terlengkap di Indonesia.â€\r\n\r\nPada Disney+ Hotstar, pelanggan IndiHome dapat menikmati berbagai tayangan hits global, regional, dan Indonesia. Film-film blockbuster Hollywood dan berbagai film yang meraih penghargaan milik Disney, Marvel, Star Wars, Pixar, National Geographic, konten dari studio terpilih di Indonesia, serta portofolio cerita berbahasa lokal yang berkembang dari Asia Pasifik, hadir untuk memanjakan para pelanggan.\r\n\r\nDalam beberapa bulan terakhir, Disney+ Hotstar pun telah meluncurkan â€œSusah Sinyal The Seriesâ€, â€œVirgin The Seriesâ€, dan â€œWedding Agreement The Seriesâ€. Serial kenamaan dari Korea Selatan seperti â€œSoundtrack #1â€, â€œGridâ€, dan â€œSnowdropâ€ juga telah menjadi tiga judul teratas yang paling banyak ditonton di Indonesia sejak penayangan perdananya.\r\n\r\nPelanggan Disney+ Hotstar juga dapat menyaksikan film-film terbaru, termasuk dari Marvel Studios â€œMoon Knightâ€, â€œLove All Playâ€, film animasi untuk keluarga seperti â€œEncantoâ€ dan â€œTurning Redâ€, serial lokal â€œJurnal Risaâ€, â€œKeluarga Cemara The Seriesâ€, â€œTeluh Darahâ€, hingga serial Korea â€œBig Mouthâ€, dan masih banyak lagi. Disney+ Hotstar menawarkan program bebas iklan dengan daftar yang lengkap untuk film panjang original, serial live action dan animasi, konten film pendek dan film dokumenter dengan subtitle multi-bahasa yang tersedia.\r\n\r\nâ€œKami sangat senang untuk membawa cerita Disney yang tak tertandingi, dengan keunggulan kreatif, dan hiburan terlengkap lebih dekat kepada para konsumen di Indonesia. Kami berharap kerjasama dengan IndiHome ini memberikan konsumen keleluasaan untuk menikmati hits terbaru dan cerita terbaik dunia kapan saja, di mana saja, dengan cara yang lebih nyaman,â€ kata Vincent Puri, Indonesia, General Manager The Walt Disney Company.\r\n\r\nPelanggan IndiHome dapat mengaktifkan paket Disney+ Hotstar secara langsung di perangkat STB. Dengan biaya berlangganan Rp29.000, pelanggan dapat menikmati berbagai program hiburan di Disney+ Hotstar langsung dari STB TV IndiHome. Khusus untuk pelanggan baru, IndiHome juga menawarkan paket spesial berupa akses Disney+ Hotstar yang tersedia dalam tiga pilihan, yaitu paket 3P (Internet + TV + Phone), 2P (Internet + Phone), dan 2P (Internet + TV) dengan berbagai kecepatan internet yang tersedia sesuai dengan permintaan. Untuk berlangganan, pelanggan dapat membuka aplikasi myIndiHome, website www.indihome.co.id, media sosial @indihome atau @indihomecare, call center 147 atau mengunjungi Telkom Plaza terdekat.)', 14, '2025-05-18 05:56:38', 'autho2', 'tbdc85JNQg.jpg', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(11) NOT NULL,
  `role_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category_post`
--
ALTER TABLE `category_post`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `post_article`
--
ALTER TABLE `post_article`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
