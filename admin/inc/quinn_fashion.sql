-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 26, 2025 at 04:31 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quinn_fashion`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `link_shopee` text DEFAULT NULL,
  `link_lazada` text DEFAULT NULL,
  `link_tokopedia` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `kategori_id` int(11) DEFAULT NULL,
  `promo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `harga`, `stok`, `gambar`, `deskripsi`, `link_shopee`, `link_lazada`, `link_tokopedia`, `created_at`, `kategori_id`, `promo`) VALUES
(31, 'Tas Selempang', 85000, 231, 'Tas.jpg', 'B891 MATERIAL PU SIZE L29XH22XW13CM WEIGHT 850GR COLOR PINK', 'https://shopee.co.id/search?keyword=tas%20selempang%20wanita', 'https://www.lazada.co.id/tag/tas-selempang-wanita-2025/?spm=a2o4j.homepage.search.2.4d5a600dV6ittP&q=tas%20selempang%20wanita%202025&_keyori=ss&clickTrackInfo=abId--378451__textId--6264101581294839793__score--2.7712357__pvid--9cb024eb-4f56-4bb8-888f-8c0353b9e724__matchType--1__matchList--1-3__listNo--0__inputQuery--tas%2Bselempa__srcQuery--tas%20selempang%20wanita%202025__spellQuery--tas%20selempang%20wanita%202025__ctrScore--0.48348993__cvrScore--0.039339364&from=suggest_normal&sugg=tas%20selempang%20wanita%202025_0_1&catalog_redirect_tag=true', 'https://www.tokopedia.com/search?navsource=home&q=tas+selempang+wanita&source=universe&st=product', '2025-07-25 13:08:27', 10, 'Promo'),
(32, 'Tas Selempang Wanita Import', 112000, 28, 'brd-69012_tas-selempang-wanita-import-px-145-doctors-bag-tas-wanita-import-berkualitas-terbaik_full01.jpg', 'Model : SAS 145\r\nSize : 24,5 x 13 x 17\r\nBahan : PU\r\nBerat : 650 g\r\nAksesoris : TALI PANJANG & GANTUNGANG I LOVE CONY\r\n\r\n⭐ Jual tas selempang, tas pesta, tas wanita, tas kantor\r\n⭐ 100% Import berkualitas\r\n⭐ Semua tas melalui proses pengecekan sebelum dipacking\r\n\r\n- Cocok digunakan untuk berpergian', 'https://shopee.co.id/search?keyword=tas%20selempang%20wanita', 'https://www.lazada.co.id/tag/tas-selempang-wanita-2025/?spm=a2o4j.homepage.search.2.4d5a600dV6ittP&q=tas%20selempang%20wanita%202025&_keyori=ss&clickTrackInfo=abId--378451__textId--6264101581294839793__score--2.7712357__pvid--9cb024eb-4f56-4bb8-888f-8c0353b9e724__matchType--1__matchList--1-3__listNo--0__inputQuery--tas%2Bselempa__srcQuery--tas%20selempang%20wanita%202025__spellQuery--tas%20selempang%20wanita%202025__ctrScore--0.48348993__cvrScore--0.039339364&from=suggest_normal&sugg=tas%20selempang%20wanita%202025_0_1&catalog_redirect_tag=true', 'https://www.tokopedia.com/search?navsource=home&q=tas+selempang+wanita&source=universe&st=product', '2025-07-25 13:13:27', 10, NULL),
(33, 'Tas Pesta Import', 125000, 190, 'B2946-IDR.156.000-MATERIAL-PU-SIZE-L19XH18XW9CM-WEIGHT-650GR-COLOR-BLUE.jpg', 'B2946 MATERIAL PU SIZE L19XH18XW9CM WEIGHT 650GR COLOR BLUE', 'https://shopee.co.id/search?keyword=tas%20selempang%20wanita', 'https://www.lazada.co.id/tag/tas-selempang-wanita-2025/?spm=a2o4j.homepage.search.2.4d5a600dV6ittP&q=tas%20selempang%20wanita%202025&_keyori=ss&clickTrackInfo=abId--378451__textId--6264101581294839793__score--2.7712357__pvid--9cb024eb-4f56-4bb8-888f-8c0353b9e724__matchType--1__matchList--1-3__listNo--0__inputQuery--tas%2Bselempa__srcQuery--tas%20selempang%20wanita%202025__spellQuery--tas%20selempang%20wanita%202025__ctrScore--0.48348993__cvrScore--0.039339364&from=suggest_normal&sugg=tas%20selempang%20wanita%202025_0_1&catalog_redirect_tag=true', 'https://www.tokopedia.com/search?navsource=home&q=tas+selempang+wanita&source=universe&st=product', '2025-07-25 14:52:10', 10, NULL),
(34, 'SlingBag', 150000, 50, 'no_brands_tas_selempang_wanita_vb3480_import_slingbag_pesta_bahu_jinjing_handbag_perempuan_tas_kerja_bahan_kulit_croco_kondangan_jakarta_kantor_fashion_cewek_el_full07_pe0hqlo.jpg', '• Kami menyediakan berbagai macam tas import dengan model yang kekinian dan lebih trendy.\r\n• Produk kami berkualitas bagus dengan strandar impor.\r\n• Bahan tas kami terbuat dari bahan asli import dimana bahan tebal dan tidak mudah rusak, jangan mudah tertipu dg tas yg lain foto sama tapi hrg jauh lbh murah karena blm tentu asli import.\r\n• Semua Produk yang dijual Import China & Taiwan (LEBIH AWET)', 'https://shopee.co.id/search?keyword=tas%20selempang%20wanita', 'https://www.lazada.co.id/tag/tas-selempang-wanita-2025/?spm=a2o4j.homepage.search.2.4d5a600dV6ittP&q=tas%20selempang%20wanita%202025&_keyori=ss&clickTrackInfo=abId--378451__textId--6264101581294839793__score--2.7712357__pvid--9cb024eb-4f56-4bb8-888f-8c0353b9e724__matchType--1__matchList--1-3__listNo--0__inputQuery--tas%2Bselempa__srcQuery--tas%20selempang%20wanita%202025__spellQuery--tas%20selempang%20wanita%202025__ctrScore--0.48348993__cvrScore--0.039339364&from=suggest_normal&sugg=tas%20selempang%20wanita%202025_0_1&catalog_redirect_tag=true', 'https://www.tokopedia.com/search?navsource=home&q=tas+selempang+wanita&source=universe&st=product', '2025-07-25 14:55:33', 10, NULL),
(35, 'Hand Bag', 125000, 23, 'brd-69012_tas-wanita-import-jx-256-lt-1256-handbag-wanita-elegan-tas-wanita-terbaru-berkualitas-terbaik_full01.jpg', 'Kode : LT1256Bahan : PUUkuran : P23XL12XT17CMBerat : 0.6kg Ada Talpan+Gantungan\r\n---------PENTING----------\r\npembelanjaan di atas 1 juta selama 1 bulan di toko kami akan mendapatkan hadiah dan berlaku kelipatan. Ditunggu orderannya ya :)\r\n\r\n----------------------------------------------------------------\r\n- Produk selalu READY STOCK & paling UPDATE :D\r\n- Kami adalah distributor FIRST HAND dengan harga TERMURAH\r\n\r\n----------------------------------------------------------------\r\n\r\nJadwal Pengiriman:\r\nSenin - Sabtu\r\nMinggu dan tanggal merah = LIBUR', 'https://shopee.co.id/search?keyword=tas%20selempang%20wanita', 'https://www.lazada.co.id/tag/tas-selempang-wanita-2025/?spm=a2o4j.homepage.search.2.4d5a600dV6ittP&q=tas%20selempang%20wanita%202025&_keyori=ss&clickTrackInfo=abId--378451__textId--6264101581294839793__score--2.7712357__pvid--9cb024eb-4f56-4bb8-888f-8c0353b9e724__matchType--1__matchList--1-3__listNo--0__inputQuery--tas%2Bselempa__srcQuery--tas%20selempang%20wanita%202025__spellQuery--tas%20selempang%20wanita%202025__ctrScore--0.48348993__cvrScore--0.039339364&from=suggest_normal&sugg=tas%20selempang%20wanita%202025_0_1&catalog_redirect_tag=true', 'https://www.tokopedia.com/search?navsource=home&q=tas+selempang+wanita&source=universe&st=product', '2025-07-25 14:59:54', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(10, 'Tas'),
(11, 'Jam Tangan'),
(12, 'Sepatu'),
(13, 'Sepatu Sandal'),
(14, 'Baju'),
(15, 'Mukena');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `nominal` double NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keuangan`
--

INSERT INTO `keuangan` (`id`, `tanggal`, `keterangan`, `nominal`, `jenis`) VALUES
(1, '2025-07-01', 'gaji', 200000, 'pemasukan'),
(2, '2025-07-01', 'beli sapu', 10000, 'pengeluaran'),
(3, '2025-07-25', 'gaji', 2000000, 'pemasukan');

-- --------------------------------------------------------

--
-- Table structure for table `slider_iklan`
--

CREATE TABLE `slider_iklan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `file_gambar` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider_iklan`
--

INSERT INTO `slider_iklan` (`id`, `judul`, `file_gambar`, `link`, `created_at`) VALUES
(1, 'Tas Selempang', 'Tas.jpg', NULL, '2025-07-25 13:40:51'),
(2, 'Tas Selempang Import', 'brd-69012_tas-selempang-wanita-import-px-145-doctors-bag-tas-wanita-import-berkualitas-terbaik_full01.jpg', NULL, '2025-07-25 13:44:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kategori_barang` (`kategori_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_iklan`
--
ALTER TABLE `slider_iklan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `slider_iklan`
--
ALTER TABLE `slider_iklan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_kategori_barang` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
