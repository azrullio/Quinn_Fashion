-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2025 at 05:01 AM
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
  `kategori_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `harga`, `stok`, `gambar`, `deskripsi`, `link_shopee`, `link_lazada`, `link_tokopedia`, `created_at`, `kategori_id`) VALUES
(1, 'Gamis Brokat Premium', 250000, 20, 'gamis1.jpg', 'Gamis brokat dengan bahan premium, nyaman dipakai.', 'https://shopee.co.id/gamis123', 'https://www.lazada.co.id/gamis123', 'https://www.tokopedia.com/gamis123', '2025-07-24 03:49:25', 9),
(2, 'Hijab Segi Empat', 55000, 40, '2c390b7ee6c6a6858a81034dc0eed93b.jpg', 'Hijab segi empat motif elegan.', 'https://shopee.co.id/hijab123', 'https://www.lazada.co.id/hijab123', 'https://www.tokopedia.com/hijab123', '2025-07-24 03:58:31', 2),
(3, 'SoftCase Iphone 17 pro max', 600000, 200, 'papan_tulis.png', 'Ini adalah softcase baru', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', '2025-07-24 04:17:05', 3),
(4, 'Glasses', 23000, 56, 'papan_tulis.png', 'jskakzkankwdqknxkabxnja', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', 'https://shopee.co.id/Makanan-Minuman-cat.11043451', '2025-07-24 04:23:35', 5),
(5, 'Kacamata Trendy Wanita', 150000, 20, 'kacamata1.jpg', 'Kacamata wanita model kekinian UV protection', 'https://shopee.co.id/kacamata1', 'https://www.lazada.co.id/kacamata1', 'https://www.tokopedia.com/kacamata1', '2025-07-24 05:24:17', 5),
(6, 'Kacamata Hitam Pria', 175000, 18, 'kacamata2.jpg', 'Kacamata hitam dengan pelindung UV', 'https://shopee.co.id/kacamata2', 'https://www.lazada.co.id/kacamata2', 'https://www.tokopedia.com/kacamata2', '2025-07-24 05:24:17', 5),
(7, 'Kacamata Retro Wanita', 160000, 22, 'kacamata3.jpg', 'Desain retro cocok untuk gaya vintage', 'https://shopee.co.id/kacamata3', 'https://www.lazada.co.id/kacamata3', 'https://www.tokopedia.com/kacamata3', '2025-07-24 05:24:17', 5),
(8, 'Kacamata Sporty', 140000, 15, 'kacamata4.jpg', 'Kacamata untuk aktivitas luar ruangan', 'https://shopee.co.id/kacamata4', 'https://www.lazada.co.id/kacamata4', 'https://www.tokopedia.com/kacamata4', '2025-07-24 05:24:17', 5),
(9, 'Kacamata Frame Transparan', 120000, 25, 'kacamata5.jpg', 'Frame ringan dan stylish', 'https://shopee.co.id/kacamata5', 'https://www.lazada.co.id/kacamata5', 'https://www.tokopedia.com/kacamata5', '2025-07-24 05:24:17', 5),
(15, 'Hijab Segi Empat Polos', 45000, 30, '5f8ab2944a66493c938c7c6611ec4068.jpg', 'Hijab bahan voal polos adem nyaman', 'https://shopee.co.id/hijab1', 'https://www.lazada.co.id/hijab1', 'https://www.tokopedia.com/hijab1', '2025-07-24 05:24:17', 2),
(16, 'Hijab Instan Jersey', 50000, 35, 'a098b8861eae7152220fe1319cf96cf7.jpg', 'Hijab instan tanpa jarum dan mudah dipakai', 'https://shopee.co.id/hijab2', 'https://www.lazada.co.id/hijab2', 'https://www.tokopedia.com/hijab2', '2025-07-24 05:24:17', 2),
(17, 'Hijab Pashmina Ceruty', 55000, 28, 'c6ae9efa799708c6a8b050eb82944717.jpg', 'Pashmina bahan ceruty ringan dan jatuh', 'https://shopee.co.id/hijab3', 'https://www.lazada.co.id/hijab3', 'https://www.tokopedia.com/hijab3', '2025-07-24 05:24:17', 2),
(18, 'Hijab Polos Ultrafine', 47000, 32, 'e0d3b0ea8a1c01fadadc26f592618d5a.jpg', 'Hijab dengan bahan ultrafine premium', 'https://shopee.co.id/hijab4', 'https://www.lazada.co.id/hijab4', 'https://www.tokopedia.com/hijab4', '2025-07-24 05:24:17', 2),
(19, 'Hijab Motif Bunga', 60000, 25, 'hijab5.jpg', 'Hijab motif floral untuk gaya feminin', 'https://shopee.co.id/hijab5', 'https://www.lazada.co.id/hijab5', 'https://www.tokopedia.com/hijab5', '2025-07-24 05:24:17', 2),
(20, 'Dress Wanita Casual', 190000, 10, 'dress1.jpg', 'Dress wanita santai cocok untuk hangout', 'https://shopee.co.id/dress1', 'https://www.lazada.co.id/dress1', 'https://www.tokopedia.com/dress1', '2025-07-24 05:24:17', 4),
(21, 'Dress A-Line Motif', 225000, 14, 'dress2.jpg', 'Dress A-line dengan motif menarik', 'https://shopee.co.id/dress2', 'https://www.lazada.co.id/dress2', 'https://www.tokopedia.com/dress2', '2025-07-24 05:24:17', 4),
(22, 'Dress Satin Elegan', 260000, 8, 'dress3.jpg', 'Dress bahan satin untuk acara formal', 'https://shopee.co.id/dress3', 'https://www.lazada.co.id/dress3', 'https://www.tokopedia.com/dress3', '2025-07-24 05:24:17', 4),
(23, 'Dress Overall Denim', 180000, 13, 'dress4.jpg', 'Dress overall berbahan denim cocok dipadukan inner', 'https://shopee.co.id/dress4', 'https://www.lazada.co.id/dress4', 'https://www.tokopedia.com/dress4', '2025-07-24 05:24:17', 4),
(24, 'Dress Tunik Motif', 200000, 17, 'dress5.jpg', 'Dress tunik panjang motif etnik', 'https://shopee.co.id/dress5', 'https://www.lazada.co.id/dress5', 'https://www.tokopedia.com/dress5', '2025-07-24 05:24:17', 4),
(25, 'Anting Fashion Wanita', 25000, 50, 'aksesoris1.jpg', 'Anting lucu dan ringan, cocok untuk daily wear', 'https://shopee.co.id/aksesoris1', 'https://www.lazada.co.id/aksesoris1', 'https://www.tokopedia.com/aksesoris1', '2025-07-24 05:24:17', 3),
(26, 'Kalung Manik Etnik', 30000, 35, 'aksesoris2.jpg', 'Kalung handmade dengan desain etnik', 'https://shopee.co.id/aksesoris2', 'https://www.lazada.co.id/aksesoris2', 'https://www.tokopedia.com/aksesoris2', '2025-07-24 05:24:17', 3),
(27, 'Cincin Adjustable', 27000, 40, 'aksesoris3.jpg', 'Cincin model simple bisa disesuaikan ukuran', 'https://shopee.co.id/aksesoris3', 'https://www.lazada.co.id/aksesoris3', 'https://www.tokopedia.com/aksesoris3', '2025-07-24 05:24:17', 3),
(28, 'Bando Fashion Korea', 20000, 45, 'aksesoris4.jpg', 'Bando lucu ala Korea cocok untuk remaja', 'https://shopee.co.id/aksesoris4', 'https://www.lazada.co.id/aksesoris4', 'https://www.tokopedia.com/aksesoris4', '2025-07-24 05:24:17', 3),
(29, 'Gelang Handmade', 32000, 28, 'aksesoris5.jpg', 'Gelang cantik handmade dari manik dan tali', 'https://shopee.co.id/aksesoris5', 'https://www.lazada.co.id/aksesoris5', 'https://www.tokopedia.com/aksesoris5', '2025-07-24 05:24:17', 3);

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
(2, 'Hijab'),
(3, 'Aksesoris'),
(4, 'Dress'),
(5, 'Kacamata'),
(8, 'tas'),
(9, 'Gamis');

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
-- Table structure for table `video_iklan`
--

CREATE TABLE `video_iklan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `file_video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `video_iklan`
--
ALTER TABLE `video_iklan`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `video_iklan`
--
ALTER TABLE `video_iklan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
