-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 01:57 PM
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
-- Database: `rentalmobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `psw` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `email`, `psw`) VALUES
(1, 'anisa', 'anisacantik', 'anisaaaftri@gmail.com', 'anisa123'),
(2, 'arum', 'arumcomel', 'arum0312@gmail.com', 'arum123'),
(3, 'carmen', 'carmennita', 'carmennita@gmail.com', 'carmen123'),
(4, 'jamal', 'jamal1', 'jamalludin@gmail.com', 'jamal123'),
(5, 'ad', 'ad', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$8x8TpzcCrszqpK9qluLmEOzgHRM3WHMZ5PQBj/QWEXT4dpuUvhBt6'),
(6, 'p', 'p', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$hsoIdYI9H7eGS.83Yoj/4uH25RtPjpDadNLx0XX3XmQUjmPty0kEO'),
(7, 'c', 'c', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$bLfewX41S1IjrMBSXiukDeAyhR2MGdZDv8kzq7w.Yp5BTjU9NaOm2');

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `psw` varchar(255) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `fotoktp` varchar(255) DEFAULT NULL,
  `fotosim` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `nama`, `username`, `email`, `psw`, `alamat`, `telepon`, `fotoktp`, `fotosim`) VALUES
(3, 'adit', 'adit', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$8BIhQRLZeIVshZ7MCWttQ.liczlo/l4Cxu1ZVOmxIVyU0snBWxI3y', NULL, NULL, NULL, NULL),
(5, 'Aditya Aulia Subhan', 'ad', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$PMPSe6F1Ik975EpCEFBhvuD4YXIoIYu5LQV2TN43hlcnOTYH3cidq', 'riau', NULL, 'bacground-login.png', 'bacground-login.jpg'),
(6, 'naufal', 'asd', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$ipOkr9W.2cPO4SJkBmdYgeNAb73e945MVHCnHnFiiNPbUST3I2riq', 'asd', '08', 'bacground-login.png', 'bacground-login.jpg'),
(7, 'abil', 'abil', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$e41n.e/R5cE0CsSMVtH1buf/fdtyVphx/ox843d3mrzqz8jYWxy46', 'asd', 'asd', 'Free fire (1).png', 'ling_ai-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transmisi` varchar(255) NOT NULL,
  `status` enum('tersedia','tidak_tersedia') NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`id`, `foto`, `name`, `harga`, `transmisi`, `status`) VALUES
(1, 'vehicle-1.png', 'Avanza', 476000.00, 'MANUAL', 'tersedia'),
(2, 'inova.jpg', 'KIJANG INOVA', 150000.00, 'MATIC', 'tersedia'),
(3, 'ayla.jpg', 'AYLA', 200000.00, 'MATIC', 'tersedia'),
(6, 'Benedetta•[Street Blow].jpg', 'ASDA', 120000.00, 'DSFDSF', 'tersedia'),
(7, 'Benttley.jpg', 'Bentley', 250000.00, 'MANUAL', 'tersedia'),
(8, 'ferrari.jpg', 'ferrari', 300000.00, 'otomatis', 'tersedia'),
(9, 'Lamborgini.jpg', 'Lamborghini', 200000.00, 'manual', 'tersedia'),
(10, 'Porsche.jpeg', 'Porsche', 175000.00, 'otomatis', 'tersedia'),
(11, 'bugatti.jpeg', 'Bugatti', 375000.00, 'MATIC', 'tidak_tersedia');

-- --------------------------------------------------------

--
-- Stand-in structure for view `datasewa`
-- (See below for the actual view)
--
CREATE TABLE `datasewa` (
`id` int(255)
,`jumlah_hari` int(11)
,`total_harga` decimal(10,2)
,`time` varchar(255)
,`nama_penyewa` varchar(255)
,`alamat_penyewa` varchar(255)
,`telpon_penyewa` varchar(255)
,`fotoktp_penyewa` varchar(255)
,`fotosim_penyewa` varchar(255)
,`nama_mobil` varchar(255)
,`transmisi_mobil` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `rentcar`
--

CREATE TABLE `rentcar` (
  `id` int(255) NOT NULL,
  `id_penyewa` int(11) NOT NULL,
  `id_mobil` int(11) NOT NULL,
  `jumlah_hari` int(11) NOT NULL DEFAULT 1,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentcar`
--

INSERT INTO `rentcar` (`id`, `id_penyewa`, `id_mobil`, `jumlah_hari`, `total_harga`, `time`) VALUES
(36, 6, 10, 9, 1575000.00, '26-06-2025 09:00:40pm'),
(37, 5, 1, 1, 550000.00, '26-06-2025 09:04:07pm'),
(38, 5, 11, 1, 375000.00, '30-06-2025 07:01:37pm');

-- --------------------------------------------------------

--
-- Structure for view `datasewa`
--
DROP TABLE IF EXISTS `datasewa`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `datasewa`  AS SELECT `r`.`id` AS `id`, `r`.`jumlah_hari` AS `jumlah_hari`, `r`.`total_harga` AS `total_harga`, `r`.`time` AS `time`, `a`.`nama` AS `nama_penyewa`, `a`.`alamat` AS `alamat_penyewa`, `a`.`telepon` AS `telpon_penyewa`, `a`.`fotoktp` AS `fotoktp_penyewa`, `a`.`fotosim` AS `fotosim_penyewa`, `c`.`name` AS `nama_mobil`, `c`.`transmisi` AS `transmisi_mobil` FROM ((`rentcar` `r` join `akun` `a` on(`r`.`id_penyewa` = `a`.`id`)) join `car` `c` on(`r`.`id_mobil` = `c`.`id`)) ORDER BY `r`.`id` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rentcar`
--
ALTER TABLE `rentcar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_penyewa` (`id_penyewa`),
  ADD KEY `fk_id_mobil` (`id_mobil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `rentcar`
--
ALTER TABLE `rentcar`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rentcar`
--
ALTER TABLE `rentcar`
  ADD CONSTRAINT `fk_id_mobil` FOREIGN KEY (`id_mobil`) REFERENCES `car` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_penyewa` FOREIGN KEY (`id_penyewa`) REFERENCES `akun` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
