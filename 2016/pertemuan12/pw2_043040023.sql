-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2015 at 02:54 PM
-- Server version: 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pw2_043040023`
--

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `universitas` varchar(50) NOT NULL,
  `gambar` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nama`, `email`, `jurusan`, `universitas`, `gambar`) VALUES
(1, 'Sandhika Galih', 'sandhikagalih@unpas.ac.id', 'Teknik Informatika', 'Universitas Pasundan', 'sandhika.jpg'),
(2, 'Nofariza Handayani', 'nofa@gmail.com', 'Ilmu Ekonomi', 'Universitas Padjadjaran', 'nofa.jpg'),
(3, 'Erik', 'eikic1@unpas.ac.id', 'Teknik Mesin', 'Universitas Pasundan', 'erik.jpg'),
(4, 'Anggoro Ari', 'anggoro.ari@gmail.com', 'Teknik Industri', 'Universitas Pasundan', 'anggoro.jpg'),
(5, 'Doddy Ferdiansyah', 'doy@hotmail.com', 'Kemanan Jaringan', 'Universitas Langlangbuana', 'doddy.jpg'),
(6, 'Mellia Liyanthi', 'mellia@unpas.ac.id', 'Teknik Informatika', 'Universitas Pasundan', 'mellia.jpg'),
(7, 'Fajar Darmawan', 'fajar_if@yahoo.com', 'Teknik Informatika', 'STMIK AMIKOM', 'fajar.jpg'),
(8, 'Rommy Fauziantarto', 'rommy@gmail.com', 'Desain Komunikasi Visual', 'Institut Teknologi Nasional', 'rommy.jpg'),
(9, 'Shabrina Gea', 'geageagea@yahoo.com', 'Ilmu Psikologi', 'Universitas Islam Bandung', 'shabrinagea.jpg'),
(10, 'Hendra Komara', 'hendra_k@gmail.com', 'Rekayasa Perangkat Lunak', 'Institut Teknologi Bandung', 'nophoto.jpg');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
