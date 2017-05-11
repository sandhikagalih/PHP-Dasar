-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 01, 2013 at 10:41 AM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `coba`
--

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gambar` varchar(32) NOT NULL,
  `caption` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gambar` (`gambar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `gambar`, `caption`) VALUES
(9, '1385828263.jpg', 'Cherry'),
(10, '1385828280.jpg', 'Shabrina Gea');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `universitas` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nama` (`nama`),
  KEY `universitas` (`universitas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nama`, `universitas`) VALUES
(1, 'Fajar Darmawan', 'Universitas Pasundan'),
(2, 'Nofariza Handayani', 'Universitas Padjadjaran'),
(3, 'Doddy Ferdiansyah', 'Universitas Pasundan'),
(4, 'Anggoro Ari Nurcahyo', 'Universitas Pasundan'),
(5, 'Mellia Liyanthi', 'Universitas Pasundan'),
(6, 'Shabrina Gea', 'Universitas Islam'),
(8, 'Erik', 'Universitas Pasundan'),
(9, 'Acep Hendra', 'Universitas Pasundan'),
(19, 'aaaaaaa', 'aaa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
