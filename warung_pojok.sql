-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 10, 2025 at 01:01 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warung_pojok`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_owner`
--

CREATE TABLE `admin_owner` (
  `id_admin` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin_owner`
--

INSERT INTO `admin_owner` (`id_admin`, `nama`, `email`, `password`, `no_telp`) VALUES
(1, 'Huda_Owner', 'huda@resto.com', 'admin123', '08123456789'),
(2, 'hudaabdulmajid', 'admin@gmail.com', '$2y$10$8agiNQFx.Po4im/r.5mJKu9aip.V7VvZgtR1tZBCKDYTYw9ET8P7G', NULL),
(3, 'hudaaja', 'abcd123@gamil.com', '$2y$10$Bz6t2Mo20F5ePMLvKyyCReRmgxxPDXWUJmIlb5FN4yVZj4D24AlTa', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `id_menu` int DEFAULT NULL,
  `jumlah_item` int NOT NULL,
  `harga_satuan` decimal(10,2) DEFAULT NULL,
  `subtotal_item` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_menu`, `jumlah_item`, `harga_satuan`, `subtotal_item`) VALUES
(8, 4, 14, 2, '27000.00', '54000.00'),
(9, 4, 1, 1, '5000.00', '5000.00');

-- --------------------------------------------------------

--
-- Table structure for table `kasir`
--

CREATE TABLE `kasir` (
  `id_kasir` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `shift_kerja` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kasir`
--

INSERT INTO `kasir` (`id_kasir`, `nama`, `shift_kerja`, `password`) VALUES
(1, 'Budi Kasir', 'Pagi', 'budigaming12'),
(2, 'boluuuuuuuu', NULL, '$2y$10$xUD3oyVnqQ.vjZyvSU9fbeEtmcSjLiQ3tDDxVl/esT99zmobPJGA2'),
(3, 'hudaa', NULL, '$2y$10$GQW7okqXfm7LETyoHSchjucQnJQgX.BDo5lIuPvq8.M3ycoVxeAuq'),
(4, 'abcd1234@gamil.com', NULL, '$2y$10$z7PFQT0UZLeAsWIIP5bqpuHhv54ZBO2jxQo0WXgdFFnnapKGZ2XPm');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id_meja` int NOT NULL,
  `nomor_meja` varchar(10) NOT NULL,
  `status_meja` varchar(50) DEFAULT 'Kosong',
  `id_pesanan_aktif` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id_meja`, `nomor_meja`, `status_meja`, `id_pesanan_aktif`) VALUES
(1, '1', 'Kosong', NULL),
(2, '2', 'Kosong', NULL),
(3, '3', 'Kosong', NULL),
(4, '4', 'Kosong', NULL),
(5, '5', 'Kosong', NULL),
(6, '6', 'Kosong', NULL),
(7, '7', 'Kosong', NULL),
(8, '8', 'Kosong', NULL),
(9, '9', 'Kosong', NULL),
(10, '10', 'Kosong', NULL),
(11, '11', 'Kosong', NULL),
(12, '12', 'Kosong', NULL),
(13, '13', 'Kosong', NULL),
(14, '14', 'Kosong', NULL),
(15, '15', 'Kosong', NULL),
(16, '16', 'Kosong', NULL),
(17, '17', 'Kosong', NULL),
(18, '18', 'Kosong', NULL),
(19, '19', 'Kosong', NULL),
(20, '20', 'Kosong', NULL),
(21, '21', 'Kosong', NULL),
(22, '22', 'Kosong', NULL),
(23, '23', 'Kosong', NULL),
(24, '24', 'Kosong', NULL),
(25, '25', 'Kosong', NULL),
(26, '26', 'Kosong', NULL),
(27, '27', 'Kosong', NULL),
(28, '28', 'Kosong', NULL),
(29, '29', 'Kosong', NULL),
(30, '30', 'Kosong', NULL),
(31, '31', 'Kosong', NULL),
(32, '32', 'Kosong', NULL),
(33, '33', 'Kosong', NULL),
(34, '34', 'Kosong', NULL),
(35, '35', 'Kosong', NULL),
(36, '36', 'Kosong', NULL),
(37, '37', 'Kosong', NULL),
(38, '38', 'Kosong', NULL),
(39, '39', 'Kosong', NULL),
(40, '40', 'Kosong', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text,
  `harga` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `id_admin` int DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `status`, `kategori`, `id_admin`, `gambar`) VALUES
(1, 'Kopi Arabika', 'Kopi arabika pilihan dengan rasa yang khas', '5000.00', '1', 'Minuman', 1, 'arabika.jpg'),
(2, 'Kopi Americano', 'Kopi americano dengan rasa yang kuat', '5000.00', '1', 'Minuman', 1, 'americano.jpg'),
(3, 'Cappuccino', 'Cappuccino dengan busa susu yang lembut', '5000.00', '1', 'Minuman', 1, 'capucino.jpg'),
(4, 'Cafe Latte', 'Cafe latte dengan rasa yang creamy', '7000.00', '1', 'Minuman', 1, 'cafe latte.jpg'),
(5, 'Espresso', 'Espresso dengan kekentalan yang pas', '5000.00', '1', 'Minuman', 1, 'expresso.jpg'),
(6, 'Long Black', 'Long black dengan aroma kopi yang kuat', '6000.00', '1', 'Minuman', 1, 'long back.jpg'),
(7, 'Ice Coffee', 'Ice coffee segar untuk hari yang panas', '5000.00', '1', 'Minuman', 1, 'icecoffe.jpg'),
(8, 'Kopi Susu', 'Kopi susu dengan perpaduan rasa yang pas', '8000.00', '1', 'Minuman', 1, 'kopisusu.jpg'),
(9, 'Mocca Latte', 'Mocca latte dengan coklat dan kopi', '9000.00', '1', 'Minuman', 1, 'mocca latte.jpg'),
(10, 'Donat', 'Donat lembut dengan berbagai topping', '7000.00', '1', 'Makanan', 1, 'donat.jpg'),
(11, 'Roti Gandum', 'Roti gandum sehat dan bergizi', '7000.00', '1', 'Makanan', 1, 'roti gandum.jpg'),
(12, 'Croissant', 'Croissant renyah dan lembut', '7000.00', '1', 'Makanan', 1, 'croise.jpg'),
(13, 'Ayam Geprek + Es Teh', 'Ayam geprek dengan es teh gratis', '25000.00', '1', 'Makanan', 1, 'ayamgeprekfreeesteh.jpg'),
(14, 'Ayam Geprek Mozarella', 'Ayam geprek dengan mozarella leleh', '27000.00', '1', 'Makanan', 1, 'ayamgeprekmozarella.jpg'),
(15, 'Ayam Geprek Original', 'Ayam geprek original dengan sambal', '23000.00', '1', 'Makanan', 1, 'ayamgeprekkori.jpg'),
(16, 'Ayam Geprek Sambal Hijau', 'Ayam geprek dengan sambal hijau', '26000.00', '1', 'Makanan', 1, 'ayamgepreksambalhijau.jpg'),
(17, 'Ayam Geprek Sambal Matah', 'Ayam geprek dengan sambal matah', '26000.00', '1', 'Makanan', 1, 'ayamgepreksambalmatah.jpg'),
(18, 'Ayam Geprek Sambal Setan', 'Ayam geprek dengan sambal super pedas', '26000.00', '1', 'Makanan', 1, 'ayamgepreksambalsetan.jpg'),
(19, 'Ayam Geprek Sambal Terasi', 'Ayam geprek dengan sambal terasi', '26000.00', '1', 'Makanan', 1, 'ayamgepreksambalterasi.jpg'),
(20, 'French Fries', 'Kentang goreng renyah', '12000.00', '1', 'Makanan', 1, 'frenchfries.jpg'),
(21, 'Hamburger', 'Hamburger dengan daging juicy', '20000.00', '1', 'Makanan', 1, 'hamburger.jpg'),
(22, 'Kebab', 'Kebab dengan daging dan sayuran segar', '12000.00', '1', 'Makanan', 1, 'kebab.jpg'),
(24, 'Eskrim', 'Eskrim berbagai rasa', '8000.00', '1', 'Minuman', 1, 'eskrim.jpg'),
(25, 'Es Jeruk', 'Es jeruk segar', '5000.00', '1', 'Minuman', 1, 'esjeruk.jpg'),
(26, 'Jus Mangga', 'Jus mangga alami', '8000.00', '1', 'Minuman', 1, 'jusmangga.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `no_telp`) VALUES
(1, 'huda', NULL),
(2, 'iya', NULL),
(3, 'hud gantengg', NULL),
(4, 'hak e', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `tanggal_pesanan` datetime DEFAULT CURRENT_TIMESTAMP,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status_pesanan` varchar(50) DEFAULT NULL,
  `nomor_meja` varchar(10) DEFAULT NULL,
  `id_pelanggan` int DEFAULT NULL,
  `id_kasir` int DEFAULT NULL,
  `id_meja` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `tanggal_pesanan`, `total_harga`, `status_pesanan`, `nomor_meja`, `id_pelanggan`, `id_kasir`, `id_meja`) VALUES
(4, '2025-12-08 13:34:02', '59000.00', 'Selesai', NULL, 4, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_owner`
--
ALTER TABLE `admin_owner`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`id_kasir`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id_meja`),
  ADD UNIQUE KEY `nomor_meja` (`nomor_meja`),
  ADD KEY `id_pesanan_aktif` (`id_pesanan_aktif`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_kasir` (`id_kasir`),
  ADD KEY `id_meja` (`id_meja`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_owner`
--
ALTER TABLE `admin_owner`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kasir`
--
ALTER TABLE `kasir`
  MODIFY `id_kasir` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id_meja` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `meja`
--
ALTER TABLE `meja`
  ADD CONSTRAINT `meja_ibfk_1` FOREIGN KEY (`id_pesanan_aktif`) REFERENCES `pesanan` (`id_pesanan`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin_owner` (`id_admin`) ON DELETE SET NULL;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`),
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_kasir`) REFERENCES `kasir` (`id_kasir`),
  ADD CONSTRAINT `pesanan_ibfk_3` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
