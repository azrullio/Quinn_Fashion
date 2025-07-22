-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 04:06 PM
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
  `nama_barang` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(100) DEFAULT NULL,
  `link_shopee` varchar(255) DEFAULT NULL,
  `link_tokopedia` varchar(255) DEFAULT NULL,
  `link_lazada` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `kategori`, `harga`, `stok`, `deskripsi`, `gambar`, `link_shopee`, `link_tokopedia`, `link_lazada`) VALUES
(3, 'kaki', 'kaca', 300000, 300, 'gtau', '1win.png', 'https://shopee.co.id/', 'https://shopee.co.id/', 'https://shopee.co.id/'),
(4, 'kaki', 'kaca', 88000, 31, 'ajkjsk', 'Screenshot 2023-11-04 202707.png', 'https://shopee.co.id/', 'https://shopee.co.id/', 'https://shopee.co.id/'),
(5, 'Pensil 2B', 'Alat Tulis', 3500, 120, 'Pensil berkualitas untuk menggambar', 'pensil.jpg', 'https://shopee.co.id/pensil', NULL, NULL),
(6, 'Buku Tulis', 'Alat Tulis', 8000, 90, 'Buku tulis bergaris 40 lembar', 'buku.jpg', NULL, 'https://tokopedia.com/bukutulis', NULL),
(7, 'Penghapus Karet', 'Alat Tulis', 2000, 70, 'Penghapus kecil dan lembut', 'penghapus.jpg', NULL, NULL, 'https://lazada.co.id/penghapus'),
(8, 'Sapu Ijuk', 'Peralatan Rumah', 23000, 45, 'Sapu ijuk gagang panjang', 'sapu_ijuk.jpg', 'https://shopee.co.id/sapuijuk', NULL, NULL),
(9, 'Ember Plastik', 'Peralatan Rumah', 18000, 30, 'Ember plastik kapasitas 10 liter', 'ember.jpg', NULL, 'https://tokopedia.com/ember', NULL),
(10, 'Mouse Wireless', 'Elektronik', 125000, 25, 'Mouse wireless 2.4GHz dengan USB receiver', 'mouse.jpg', 'https://shopee.co.id/mouse', 'https://tokopedia.com/mouse', 'https://lazada.co.id/mouse'),
(11, 'Keyboard Gaming', 'Elektronik', 250000, 18, 'Keyboard mekanikal RGB untuk gaming', 'keyboard.jpg', 'https://shopee.co.id/keyboard', NULL, 'https://lazada.co.id/keyboard'),
(12, 'Tas Eiger', 'Kebutuhan Harian', 12000, 60, 'Sabun cair wangi lemon 250ml', 'sabun.jpg', '', '', 'https://lazada.co.id/sabun'),
(14, 'Cangkul', 'Peralatan Pertanian', 45000, 15, 'Cangkul besi gagang kayu', 'cangkul.jpg', NULL, NULL, NULL);

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
(18, 'Baju'),
(15, 'Jam Tangan'),
(19, 'Mukena'),
(16, 'Sepatu'),
(17, 'Sepatu Sandal'),
(14, 'Tas');

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `video_iklan`
--
ALTER TABLE `video_iklan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
