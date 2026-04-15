-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2026 at 09:35 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(225) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `email`, `password`) VALUES
(2, 'anis', 'anis@gmail.com', '12345'),
(3, 'farizi', 'farizi@email.com', 'farizi123.'),
(4, 'dafa', 'dafa@email.com', 'dafa123.');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kategori_id` int DEFAULT NULL,
  `kode_inventaris` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `merk` varchar(100) DEFAULT NULL,
  `tipe` varchar(100) DEFAULT NULL,
  `spesifikasi` text,
  `jumlah` int NOT NULL,
  `kondisi` enum('Baik','Cukup','Rusak') NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `tahun_perolehan` year DEFAULT NULL,
  `keterangan` text,
  `tersedia` tinyint(1) NOT NULL DEFAULT '1',
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kategori_id`, `kode_inventaris`, `nama_barang`, `merk`, `tipe`, `spesifikasi`, `jumlah`, `kondisi`, `lokasi`, `tahun_perolehan`, `keterangan`, `tersedia`, `create_at`) VALUES
(13, 1, 'KOM001', 'Monitor', 'LG', '', '', 12, 'Baik', 'LAB MM', '2024', 'Digunakan Mahasiswa', 1, '2026-03-14 02:58:20'),
(14, 1, 'KOM002', 'PC', 'SIMBADDA', '', 'Intel Core I5', 12, 'Baik', 'LAB MM', '2024', '', 1, '2026-03-14 02:59:17'),
(15, 1, 'KOM003', 'PC', 'ENLIGHT EN-220', '', '', 3, 'Baik', 'LAB MM', '2024', 'Digunakan Mahasiswa', 1, '2026-03-14 03:00:00'),
(16, 2, 'FURN001', 'Kursi', '', '', '', 21, 'Baik', 'LAB MM', '2024', 'Digunakan Mahasiswa', 1, '2026-03-14 03:00:44'),
(17, 2, 'FURN002', 'Meja', '', '', '', 24, 'Cukup', 'LAB MM', '2023', 'Digunakan Mahasiswa', 1, '2026-03-14 03:01:27'),
(18, 2, 'FURN003', 'Kursi', '', '', '', 1, 'Baik', 'LAB MM', '2023', 'Digunakan Dosen', 1, '2026-03-14 03:02:48'),
(19, 3, 'AUD001', 'Sound', 'Borneo', '', '', 2, 'Baik', 'LAB MM', '2023', '', 1, '2026-03-14 03:03:43'),
(20, 4, 'ELK001', 'CCTV', '', '', '', 1, 'Baik', 'LAB MM', '2023', '', 1, '2026-03-14 03:04:20'),
(21, 4, 'ELK002', 'CCTV', '', '', '', 2, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:04:43'),
(22, 5, 'AC001', 'AC', 'PowerSonic', '', '', 2, 'Baik', 'LAB MM', '2023', '', 1, '2026-03-14 03:05:27'),
(23, 5, 'AC002', 'AC', 'PowerSonic', '', '', 4, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:05:51'),
(24, 1, 'KOM004', 'Keyboard', 'Logitech', '', '', 12, 'Baik', 'LAB MM', '2023', 'Digunakan Mahasiawa', 1, '2026-03-14 03:07:22'),
(25, 1, 'KOM005', 'Mouse', 'Logitech', '', '', 12, 'Baik', 'LAB MM', '2023', 'Digunakan Mahasiswa', 1, '2026-03-14 03:07:51'),
(26, 1, 'KOM006', 'Mouse', 'Logitech', '', '', 8, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:09:10'),
(27, 1, 'KOM007', 'Keyboard', 'Logitech', '', '', 12, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:09:54'),
(28, 1, 'KOM008', 'Keyboard', 'Lenovo', '', '', 1, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:11:14'),
(29, 1, 'KOM009', 'Monitor', 'LG', '', '', 1, 'Baik', 'LAB JARKOM', '2024', '', 1, '2026-03-14 03:12:36'),
(30, 1, 'KOM010', 'PC', 'LNEIX', '', '', 1, 'Baik', 'LAB JARKOM', '2024', '', 1, '2026-03-14 03:14:05'),
(31, 1, 'KOM011', 'Printer', 'Epson', 'EpsonL3210', '', 1, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:15:03'),
(32, 1, 'KOM012', 'PC', 'No Merk', '', '', 1, 'Baik', 'LAB JARKOM', '2024', '', 1, '2026-03-14 03:15:39'),
(33, 1, 'KOM013', 'Web Cam', '', '', '', 2, 'Baik', 'LAB JARKOM', '2024', '', 1, '2026-03-14 03:16:10'),
(34, 3, 'AUD002', 'Headset', 'Rexus', '', '', 9, 'Baik', 'LAB JARKOM', '2023', '', 1, '2026-03-14 03:17:05'),
(35, 3, 'AUD003', 'Headset', 'Mars', '', '', 2, 'Baik', 'LAB JARKOM', '2026', '', 1, '2026-03-14 03:18:09'),
(36, 1, 'KOM015', 'UPS', 'APC ', '', '', 1, 'Baik', 'LAB JARKOM', '2024', '', 1, '2026-03-14 03:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'alat_komputer'),
(2, 'furniture'),
(3, 'perangkat_audio'),
(4, 'elektronik'),
(5, 'pendingin'),
(30, 'praktikum');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `keperluan` text,
  `jumlah` int DEFAULT NULL,
  `barang_id` int DEFAULT NULL,
  `tanggal_pinjam` datetime DEFAULT CURRENT_TIMESTAMP,
  `tanggal_kembali` datetime DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `nama_peminjam`, `keperluan`, `jumlah`, `barang_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(15, 'Anisa', 'mau duduk', 3, 17, '2026-04-11 21:33:17', '2026-04-11 21:44:07', 'dikembalikan'),
(16, 'risa', 'dingin', 1, 22, '2026-04-11 21:58:01', '2026-04-13 09:58:58', 'dikembalikan'),
(17, 'kurdii', 'hmm', 1, 15, '2026-04-11 22:04:46', '2026-04-12 08:33:41', 'dikembalikan'),
(18, 'anisssssssssss', '1akdjl;iwj', 1, 14, '2026-04-11 22:05:06', NULL, 'dipinjam'),
(19, 'yusdi', 'akaokwoi', 2, 23, '2026-04-11 22:21:47', '2026-04-12 08:18:27', 'dikembalikan'),
(20, 'asded', 'yjfnjynjr', 2, 14, '2026-04-13 09:57:32', '2026-04-13 10:59:25', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_lab`
--

CREATE TABLE `peminjaman_lab` (
  `id` int NOT NULL,
  `nama_peminjam` varchar(100) DEFAULT NULL,
  `lab` varchar(50) DEFAULT NULL,
  `keperluan` text,
  `tanggal` date DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman_lab`
--

INSERT INTO `peminjaman_lab` (`id`, `nama_peminjam`, `lab`, `keperluan`, `tanggal`, `status`) VALUES
(1, 'laili romadona', 'lab_mm', 'gabut', '2026-02-18', 'menunggu');

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
  ADD UNIQUE KEY `kode_inventaris` (`kode_inventaris`),
  ADD KEY `fk_barang_kategori` (`kategori_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `peminjaman_lab`
--
ALTER TABLE `peminjaman_lab`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `peminjaman_lab`
--
ALTER TABLE `peminjaman_lab`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
