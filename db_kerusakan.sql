-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 05:02 AM
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
-- Database: `db_kerusakan`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(2) NOT NULL,
  `nim_user` varchar(16) NOT NULL,
  `nama_user` varchar(32) NOT NULL,
  `no_telpon` int(12) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `nim_user`, `nama_user`, `no_telpon`, `email`, `password`) VALUES
(1, '246661032', 'christian', 83725326, 'christian@student.polnes.ac.id', '12345'),
(3, '2466661031', 'Bentol', 2147483647, 'bentol@polnes.ac.id', '121212'),
(4, '246661001', 'War', 2147483647, 'war@polnes.ac.id', '11111'),
(5, '246661043', 'Dapin', 2147483647, 'dapin@polnes.ac.id', '909090');

-- --------------------------------------------------------

--
-- Table structure for table `disposisi`
--

CREATE TABLE `disposisi` (
  `id` int(2) NOT NULL,
  `id_disposisi` varchar(16) NOT NULL,
  `tahap` int(11) NOT NULL,
  `keputusan` text NOT NULL,
  `catatan` int(32) NOT NULL,
  `tgl_disposisi` date NOT NULL,
  `tgl_laporan` date NOT NULL,
  `id_pegawai` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id_laporan` int(16) NOT NULL,
  `tgl_laporan` timestamp NOT NULL DEFAULT current_timestamp(),
  `lokasi_gedung` text NOT NULL,
  `lokasi_ruang` text NOT NULL,
  `jenis_kerusakan` text NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` blob NOT NULL,
  `status_ketua_unit` varchar(16) NOT NULL,
  `status_wadir2` varchar(16) NOT NULL,
  `status_kesubbag` varchar(16) NOT NULL,
  `id_teknisi` varchar(8) NOT NULL,
  `status_perbaikan` text NOT NULL,
  `arahan_wadir` text DEFAULT NULL,
  `catatan_teknisi` text NOT NULL,
  `foto_sebelum` blob NOT NULL,
  `foto_sesudah` blob NOT NULL,
  `nim` varchar(32) NOT NULL,
  `id_unit` varchar(64) NOT NULL,
  `Jurusan` text NOT NULL,
  `Lantai` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id_laporan`, `tgl_laporan`, `lokasi_gedung`, `lokasi_ruang`, `jenis_kerusakan`, `deskripsi`, `foto`, `status_ketua_unit`, `status_wadir2`, `status_kesubbag`, `id_teknisi`, `status_perbaikan`, `arahan_wadir`, `catatan_teknisi`, `foto_sebelum`, `foto_sesudah`, `nim`, `id_unit`, `Jurusan`, `Lantai`) VALUES
(37, '2026-04-30 08:58:42', 'gedung baru', 'lab', 'Fasilitas Umum', 'ucak', '', '', '', '', '', 'Menunggu Verifikasi', NULL, '', '', '', '246661001', '2', 'Arsitektur', 'Lantai 1'),
(38, '2026-04-30 09:01:48', 'gedung baru', 'lab', 'Elektronik', 'ucak', 0x494d475f313737373533393730385f3234363636313030312e6a7067, '', '', '', '', 'Selesai', 'kerjain', 'udah selesai bang\r\n', '', 0x61667465725f33385f313737373534303834302e6a7067, '246661001', '1', 'Akuntansi', 'Lantai 1'),
(39, '2026-05-04 02:44:13', 'gedung lama', 'lab', 'Fasilitas Umum', 'ucak\r\n', '', '', '', '', '', 'Selesai', 'baiki dong', 'udah nih', '', '', '246661043', '1', 'Pariwisata', 'Lantai 1');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(2) NOT NULL,
  `id_pegawai` varchar(16) NOT NULL,
  `nama_pegawai` text NOT NULL,
  `nip` varchar(12) NOT NULL,
  `no_telepon` varchar(12) NOT NULL,
  `jabatan` text NOT NULL,
  `email` text NOT NULL,
  `password` varchar(12) NOT NULL,
  `id_unit` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `id_pegawai`, `nama_pegawai`, `nip`, `no_telepon`, `jabatan`, `email`, `password`, `id_unit`) VALUES
(1, 'budi_sentoso', 'budi sentoso', '1234', '081234567891', 'wadir\r\n', 'budisentoso@gmail.com', '4321', '03'),
(2, '5432', 'piping', '091712731', '08971627136', 'ibu kantin', 'piping@gmail.com', '1111', ''),
(3, '88888', 'roben', '101010', '081234567899', 'stafwadir', 'roben@polnes.ac.id\r\n', '00000', '04');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(2) NOT NULL,
  `id_unit` varchar(16) NOT NULL,
  `nama_unit` text NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disposisi`
--
ALTER TABLE `disposisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id_laporan`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `disposisi`
--
ALTER TABLE `disposisi`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id_laporan` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
