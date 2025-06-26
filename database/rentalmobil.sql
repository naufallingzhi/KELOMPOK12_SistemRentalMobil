-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 02:19 AM
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
(5, 'ad', 'ad', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$8x8TpzcCrszqpK9qluLmEOzgHRM3WHMZ5PQBj/QWEXT4dpuUvhBt6');

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `psw` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`id`, `nama`, `username`, `email`, `psw`) VALUES
(3, 'adit', 'adit', 'aditya24ti@mahasiswa.pcr.ac.id', '$2y$10$8BIhQRLZeIVshZ7MCWttQ.liczlo/l4Cxu1ZVOmxIVyU0snBWxI3y');

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `id` int(255) NOT NULL,
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
(1, 'vehicle-1.png', 'Avanza', 550000.00, 'MANUAL', 'tersedia'),
(2, 'inova.jpg', 'KIJANG INOVA', 150000.00, 'MATIC', 'tersedia'),
(3, 'ayla.jpg', 'AYLA', 200000.00, 'MATIC', 'tersedia'),
(6, 'Benedetta•[Street Blow].jpg', 'ASDA', 120000.00, 'DSFDSF', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `rentcar`
--

CREATE TABLE `rentcar` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `jumlah_hari` int(11) NOT NULL DEFAULT 1,
  `alamat` varchar(255) NOT NULL,
  `telpon` varchar(255) NOT NULL,
  `fotoktp` varchar(255) NOT NULL,
  `fotosim` varchar(255) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentcar`
--

INSERT INTO `rentcar` (`id`, `name`, `pesan`, `jumlah_hari`, `alamat`, `telpon`, `fotoktp`, `fotosim`, `total_harga`, `time`) VALUES
(1, 'rehan', 'Suzuki', 1, 'suwandi', '12345678', 'car-3.png', 'car-1.png', 0.00, '25-06-2025 08:29:56pm'),
(2, 'korin', 'PAJERO', 1, 'kemuning', '98765432', 'car-7.png', 'car-5.png', 0.00, '142-11-2022 07:26:09pm'),
(9, 'sad', 'sadasd', 1, 'sadsa', 'sadsa', 'bacground-login.jpg', 'Free fire (1).png', 0.00, '24-06-2025 11:58:08pm'),
(10, 'jknjkkj', 'Avanza', 1, 'asdsad', '0897867867', 'bacground-login.jpg', 'bacground-login.jpg', 0.00, '25-06-2025 12:20:18am'),
(11, 'jknjkkj', 'Avanza', 1, 'asdsad', '0897867867', 'bacground-login.jpg', 'bacground-login.jpg', 0.00, '25-06-2025 12:22:59am'),
(13, 'lasdasd', 'Avanza', 1, 'asdsa', '123213', 'bacground-login.jpg', 'mobile-legends-gusion-k-q0t300299u34h3sp (1).jpg', 900.00, '25-06-2025 12:35:29am'),
(14, 'asdsadsa', 'bla', 1, 'sdasdsad', '3243224', 'bacground-login.png', 'mobile-legends-gusion-k-q0t300299u34h3sp (1).jpg', 12000000.00, '25-06-2025 12:46:33am'),
(15, 'benedeta', 'KIJANG INOVA', 1, 'asdsad', '09978998', 'bacground-login.jpg', 'bacground-login (1).png', 550.00, '25-06-2025 08:19:12pm'),
(16, 'rehan', 'Avanza', 1, 'suwandi', '090879', 'bacground-login (1).png', 'Benedetta•[Street Blow].jpg', 550000.00, '25-06-2025 09:58:31pm'),
(17, 'rehan', 'Avanza', 1, 'jk', '43232432', 'Benedetta•[Street Blow].png', 'mobile-legends-gusion-k-q0t300299u34h3sp (1).jpg', 550000.00, '25-06-2025 10:00:28pm'),
(18, 'asdsad', 'ASDA', 1, 'dsdfds', '09438435', 'bacground-login.png', 'mobile-legends-gusion-k-q0t300299u34h3sp (1).jpg', 120000.00, '25-06-2025 10:02:50pm'),
(19, 'ghjghj', 'Avanza', 5, 'erteroitert', '656456456', 'bacground-login.jpg', 'bacground-login.png', 2750000.00, '25-06-2025 10:03:36pm');

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rentcar`
--
ALTER TABLE `rentcar`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
