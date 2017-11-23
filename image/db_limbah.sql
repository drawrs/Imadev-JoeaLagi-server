-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2017 at 03:24 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_limbah`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_booking`
--

CREATE TABLE `tb_booking` (
  `booking_id` int(11) NOT NULL,
  `booking_tanggal` datetime NOT NULL,
  `booking_limbah` int(11) NOT NULL,
  `booking_harga` double NOT NULL,
  `booking_jumlah` int(11) NOT NULL,
  `booking_total` double NOT NULL,
  `booking_user` int(11) NOT NULL,
  `booking_penjual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_limbah`
--

CREATE TABLE `tb_limbah` (
  `limbah_id` int(11) NOT NULL,
  `limbah_nama` varchar(25) NOT NULL,
  `limbah_harga` varchar(25) NOT NULL,
  `limbah_lokasi` text NOT NULL,
  `limbah_provinsi` text NOT NULL,
  `limbah_user` int(11) NOT NULL,
  `limbah_photo` text NOT NULL,
  `limbah_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_limbah`
--

INSERT INTO `tb_limbah` (`limbah_id`, `limbah_nama`, `limbah_harga`, `limbah_lokasi`, `limbah_provinsi`, `limbah_user`, `limbah_photo`, `limbah_type`) VALUES
(1, 'plastik', '5000', 'purwakarta', 'Jawa Barat', 1, 'pabrik1.jpg', 2),
(2, 'Kantong Plastik', '2000', 'Jonggol', 'Jawa Barat', 2, 'kantong.jpg', 1),
(3, 'Botol Plastik ', '1500', 'Cileungsi', 'Jawa Barat', 3, 'botol.jpg', 1),
(4, 'Gelas Plastik', '1000', 'Cibubur', 'Jawa Barat', 4, 'gelas.jpg', 1),
(5, 'Kaleng', '1000', 'Bojong', 'Jawa Tengah', 5, 'kaleng1.jpg', 1),
(6, 'kertas', '1000', 'Slipi', 'Jakarta Barat', 6, 'kertas1.jpg', 2),
(7, 'Kain Sisa', '2000', 'Semarang', 'Jawa Tengah', 7, 'kain1.jpg', 2),
(8, 'Kaleng Alumunium', '3000', 'Yogyakarta', 'Jawa Tengah', 8, 'alumunium2.jpg', 2),
(9, 'Mother Board TV', '20000', 'bojong', 'Jawa Tengah', 9, 'tv1.jpg', 3),
(10, 'Casing Komputer', '12000', 'Malang', 'Jawa Timur', 10, 'casing122.jpg', 3),
(11, 'keyboard', '25000', 'Kediri', 'Jawa Timur', 11, 'keyboard.jpg', 3),
(12, 'Speaker Radio', '9000', 'Demak', 'Jawa Tengah', 12, 'monitor.jpg', 3),
(13, 'Limbah Kayu', '20000', 'Cirebon', 'Jawa Barat', 13, 'kayu12.jpg', 4),
(14, 'Rangka Motor', '50000', 'Cikarang', 'Jawa Barat', 12, 'rangka111.jpg', 4),
(15, 'Spanduk Bekas', '2000', 'Jakarta', 'Jakarta Barat', 15, 'spanduk19.jpg', 4),
(16, 'Plastik Sampoh', '5000', 'Bekasi', 'Jawa Barat', 17, 'sampah.jpg', 4),
(17, 'Karung Bekas', '6000', 'Kampung Melayu', 'DKI Jakarta', 19, 'karung.jpg', 4),
(18, 'Aqua Gelas', '1000', 'Semarang', 'Jawa Timur', 3, 'aquagelas.jpg', 4),
(19, 'Kardus Bekas', '500', 'Bogor', 'Jawa Barat', 20, 'kardus.jpg', 1),
(20, 'Kertas Buku', '2000', 'Cimahi', 'Jawa Barat', 21, 'kertasbuku.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_nama` varchar(50) NOT NULL,
  `user_email` varchar(25) NOT NULL,
  `user_password` text NOT NULL,
  `user_status` int(11) NOT NULL DEFAULT '1',
  `user_level` int(11) NOT NULL,
  `user_kebutuhan` int(11) NOT NULL,
  `user_hp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_nama`, `user_email`, `user_password`, `user_status`, `user_level`, `user_kebutuhan`, `user_hp`) VALUES
(1, 'nando', 'nando@imastudio.co.id', 'd41d8cd98f00b204e9800998ecf8427e', 1, 3, 1, '085289587710'),
(2, 'septian', 'septian@gmail.com', '5b3bb3e5458e02aa356f2fc671ae08d9', 1, 3, 1, '085289587710');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_booking`
--
ALTER TABLE `tb_booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `tb_limbah`
--
ALTER TABLE `tb_limbah`
  ADD PRIMARY KEY (`limbah_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_booking`
--
ALTER TABLE `tb_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_limbah`
--
ALTER TABLE `tb_limbah`
  MODIFY `limbah_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
