-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 03, 2026 at 05:23 AM
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
(1, 'ivan ganteng', 'anis@gmail.com', '12345'),
(2, 'anis', 'anis@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_inventaris` varchar(50) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
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

INSERT INTO `barang` (`id`, `kode_inventaris`, `nama_barang`, `kategori`, `merk`, `tipe`, `spesifikasi`, `jumlah`, `kondisi`, `lokasi`, `tahun_perolehan`, `keterangan`, `tersedia`, `create_at`) VALUES
(10, 'KOM001', 'Monitor', 'Alat Komputer', 'LG', '', '', 12, 'Baik', 'LAB MM', '2026', 'Digunakan Mahasiswa', 1, '2026-02-28 15:39:25'),
(11, 'KOM002', 'PC', 'Alat Komputer', 'Simbadda', '', 'Intel Core i5', 10, 'Baik', 'LAB MM', '2026', 'Digunakan Mahasiswa', 1, '2026-02-28 15:40:42'),
(12, 'KOM003', 'PC', 'Alat Komputer', 'Enlight En-220', '', '', 2, 'Baik', 'LAB MM', '2026', 'Digunakan Mahasiswa', 1, '2026-02-28 15:41:56'),
(13, 'FURN001', 'Kursi', 'Furniture', '', '', '', 21, 'Baik', 'LAB MM', '2018', 'Digunakan Mahasiswa', 1, '2026-02-28 15:43:18'),
(14, 'FURN002', 'Meja', 'Furniture', '', '', '', 21, 'Cukup', 'LAB MM', '2018', 'Digunakan Mahasiswa', 1, '2026-02-28 15:43:53'),
(15, 'FURN003', 'Kursi', 'Furniture', '', '', '', 0, 'Baik', 'LAB MM', '2019', 'Digunakan Dosen', 1, '2026-02-28 15:44:40'),
(16, 'FURN004', 'Kursi', 'Furniture', '', '', '', 0, 'Baik', 'LAB Jarkom', '2019', 'Digunakan Dosen', 1, '2026-02-28 15:45:06'),
(17, 'AUD001', 'Sound', 'Perangkat Audio', 'Borneo', '', '', 3, 'Baik', 'LAB MM', '2020', '', 1, '2026-02-28 15:46:12'),
(18, 'KOMP004', 'Keyboard', 'Alat Komputer', 'Logitech', '', '', 15, 'Baik', 'LAB Jarkom', '2024', '', 1, '2026-03-01 04:10:21'),
(19, 'KOMP0005', 'Keyboard', 'Alat Komputer', 'Logitech', '', '', 12, 'Baik', 'LAB MM', '2023', '', 1, '2026-03-01 04:11:10'),
(20, 'KOMP0006', 'Mouse', 'Alat Komputer', 'Logitech', '', '', 12, 'Baik', 'LAB MM', '2024', '', 1, '2026-03-01 04:11:55'),
(21, 'KOM007', 'Keyboard', 'Alat Komputer', 'Lenovo', '', '', 1, 'Baik', 'LAB Jarkom', '2023', '', 1, '2026-03-01 04:12:54'),
(22, 'KOM008', 'Mouse', 'Alat Komputer', 'Logitech', '', '', 9, 'Baik', 'LAB Jarkom', '2024', '', 1, '2026-03-01 04:13:46'),
(23, 'FURN005', 'Kursi', 'Furniture', '', '', '', 1, 'Baik', 'LAB Jarkom', '2023', 'Digunakan Aslab', 1, '2026-03-01 04:21:03'),
(24, 'AUD002', 'HeadSet', 'Perangkat Audio', 'Rexus', '', '', 7, 'Baik', 'LAB Jarkom', '2023', '', 1, '2026-03-01 04:31:23'),
(25, 'AUD003', 'HeadSet', 'Perangkat Audio', 'Mars', '', '', 2, 'Baik', 'LAB Jarkom', '2026', '', 1, '2026-03-01 04:36:07'),
(26, 'KOMP008', 'Web Cam', 'Alat Komputer', 'Skype', '', '', 3, 'Baik', 'LAB Jarkom', '2023', '', 1, '2026-03-01 04:39:04'),
(27, 'KOM009', 'Monitor', 'Alat Komputer', 'LG', '', '', 1, 'Baik', 'LAB Jarkom', '2023', 'Dipakai Dosen', 1, '2026-03-01 04:39:34'),
(28, 'KOM10', 'Monitor', 'Alat Komputer', 'LG', '', '', 1, 'Baik', 'LAB Jarkom', '2024', 'Dipakai Aslab', 1, '2026-03-01 04:40:14'),
(29, 'KOM11', 'Monitor', 'Alat Komputer', 'Qwerty', '', '', 1, 'Baik', 'LAB Jarkom', '2024', 'Dipakai Aslab', 1, '2026-03-01 04:40:48'),
(30, 'KOM12', 'PC', 'Alat Komputer', 'VenomRX', '', '', 1, 'Baik', 'LAB Jarkom', '2024', 'Dipakai Dosen', 1, '2026-03-01 04:42:23'),
(31, 'KOM13', 'PC', 'Alat Komputer', 'NEOX', '', '', 0, 'Baik', 'LAB Jarkom', '2024', '', 1, '2026-03-01 04:45:16'),
(33, 'KOM14', 'Printer', 'Alat Komputer', 'Epson L3210', '', '', 0, 'Baik', 'LAB Jarkom', '2024', 'Dipakai anis', 1, '2026-03-01 04:48:00');

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
(1, 'User', 'Peminjaman', 1, 33, '2026-03-02 12:20:02', NULL, 'dipinjam'),
(2, 'User', 'Peminjaman', 1, 23, '2026-03-02 12:25:55', NULL, 'dipinjam'),
(3, 'User', 'Peminjaman', 1, 16, '2026-03-02 12:27:02', NULL, 'dipinjam'),
(4, 'User', 'Peminjaman', 1, 15, '2026-03-02 12:27:12', NULL, 'dipinjam'),
(5, 'User', 'Peminjaman', 1, 33, '2026-03-02 14:31:42', NULL, 'dipinjam'),
(6, 'User', 'Peminjaman', 1, 31, '2026-03-03 11:33:56', NULL, 'dipinjam');

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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'anisaa', 'okieys@gmail.com', '1234643', '2026-02-24 12:06:21', 'user'),
(5, 'ivan', 'ivanntrya@gmail.com', '20404', '2026-02-24 12:20:09', 'user'),
(8, 'kila', 'ksjlie@gmail.com', '123', '2026-02-24 13:05:18', 'user');

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
  ADD UNIQUE KEY `kode_inventaris` (`kode_inventaris`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peminjaman_lab`
--
ALTER TABLE `peminjaman_lab`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
