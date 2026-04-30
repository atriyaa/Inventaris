-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 30, 2026 at 05:37 AM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id_barang` int NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `spesifikasi` text,
  `tersedia` enum('Iya','Tidak') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `id_kategori`, `nama_barang`, `merk`, `tipe`, `spesifikasi`, `tersedia`) VALUES
(1, 1, 'MONITOR-001', 'LG', 'tipe', 'keren', 'Iya'),
(2, 1, 'MONITOR-002', 'LG', 'i3', 'keren juga', 'Iya'),
(3, 2, 'CCTV-MM-001', 'Elvis', 'Apa aja', 'iya sama', 'Iya'),
(4, 2, 'CCTV-JARKOM-001', 'Elvis', 'Apa aja', 'iya sama', 'Iya'),
(5, 2, 'CCTV-JARKOM-001', 'Elvis', 'Apa aja', 'iya sama', 'Iya'),
(6, 3, 'AC-JARKOM-001', 'Lupa', 'Apa aja', 'iya sama', 'Iya'),
(7, 3, 'AC-MM-001', 'Lupa', 'Apa aja', 'iya sama', 'Iya');

-- --------------------------------------------------------

--
-- Table structure for table `barang_detail`
--

CREATE TABLE `barang_detail` (
  `id_detail` int NOT NULL,
  `id_barang` int DEFAULT NULL,
  `kode_unit` varchar(50) NOT NULL,
  `kondisi` enum('Baik','Rusak','Perbaikan') DEFAULT 'Baik',
  `status` enum('Tersedia','Tidak Tersedia') DEFAULT 'Tersedia',
  `lokasi_meja` varchar(50) DEFAULT NULL,
  `lokasi_ruang` enum('LAB MM','LAB JARKOM') DEFAULT 'LAB MM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang_detail`
--

INSERT INTO `barang_detail` (`id_detail`, `id_barang`, `kode_unit`, `kondisi`, `status`, `lokasi_meja`, `lokasi_ruang`) VALUES
(5, 1, 'KOMP-MM-001', 'Baik', 'Tersedia', 'MJ-MM-01', 'LAB MM'),
(6, 1, 'KOMP-MM-002', 'Baik', 'Tersedia', 'MJ-MM-02', 'LAB MM'),
(7, 1, 'KOMP-MM-003', 'Baik', 'Tersedia', 'MJ-MM-03', 'LAB MM');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Alat Komputer'),
(2, 'Alat Elektronik'),
(3, 'Pendingin Ruangan'),
(4, 'Alat Audio'),
(5, 'Alat Praktikum');

-- --------------------------------------------------------

--
-- Table structure for table `kerusakan`
--

CREATE TABLE `kerusakan` (
  `id_kerusakan` int NOT NULL,
  `id_detail` int DEFAULT NULL,
  `tanggal_lapor` date DEFAULT NULL,
  `deskripsi_kerusakan` text,
  `status_perbaikan` enum('Menunggu','Sedang Diperbaiki','Selesai') DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `nama_peminjam` varchar(200) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status_pinjam` enum('Proses','Selesai') DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_detail`
--

CREATE TABLE `peminjaman_detail` (
  `id_pinjam_detail` int NOT NULL,
  `id_peminjaman` int DEFAULT NULL,
  `id_detail` int DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `kondisi_saat_kembali` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perawatan`
--

CREATE TABLE `perawatan` (
  `id_perawatan` int NOT NULL,
  `id_detail` int DEFAULT NULL,
  `tgl_perawatan` date DEFAULT NULL,
  `biaya` decimal(10,2) DEFAULT NULL,
  `keterangan_perawatan` text,
  `petugas` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `software_license`
--

CREATE TABLE `software_license` (
  `id_license` int NOT NULL,
  `nama_software` varchar(100) NOT NULL,
  `kode_lisensi` varchar(100) DEFAULT NULL,
  `tanggal_aktivasi` date DEFAULT NULL,
  `tgl_kadaluarsa` date DEFAULT NULL,
  `id_detail` int DEFAULT NULL
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
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `barang_detail`
--
ALTER TABLE `barang_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD UNIQUE KEY `kode_unit` (`kode_unit`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD PRIMARY KEY (`id_kerusakan`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- Indexes for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD PRIMARY KEY (`id_pinjam_detail`),
  ADD KEY `id_peminjaman` (`id_peminjaman`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `perawatan`
--
ALTER TABLE `perawatan`
  ADD PRIMARY KEY (`id_perawatan`),
  ADD KEY `id_detail` (`id_detail`);

--
-- Indexes for table `software_license`
--
ALTER TABLE `software_license`
  ADD PRIMARY KEY (`id_license`),
  ADD KEY `id_detail` (`id_detail`);

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
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `barang_detail`
--
ALTER TABLE `barang_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kerusakan`
--
ALTER TABLE `kerusakan`
  MODIFY `id_kerusakan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  MODIFY `id_pinjam_detail` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perawatan`
--
ALTER TABLE `perawatan`
  MODIFY `id_perawatan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `software_license`
--
ALTER TABLE `software_license`
  MODIFY `id_license` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL;

--
-- Constraints for table `barang_detail`
--
ALTER TABLE `barang_detail`
  ADD CONSTRAINT `barang_detail_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;

--
-- Constraints for table `kerusakan`
--
ALTER TABLE `kerusakan`
  ADD CONSTRAINT `kerusakan_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `barang_detail` (`id_detail`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman_detail`
--
ALTER TABLE `peminjaman_detail`
  ADD CONSTRAINT `peminjaman_detail_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_detail_ibfk_2` FOREIGN KEY (`id_detail`) REFERENCES `barang_detail` (`id_detail`);

--
-- Constraints for table `perawatan`
--
ALTER TABLE `perawatan`
  ADD CONSTRAINT `perawatan_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `barang_detail` (`id_detail`) ON DELETE CASCADE;

--
-- Constraints for table `software_license`
--
ALTER TABLE `software_license`
  ADD CONSTRAINT `software_license_ibfk_1` FOREIGN KEY (`id_detail`) REFERENCES `barang_detail` (`id_detail`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
