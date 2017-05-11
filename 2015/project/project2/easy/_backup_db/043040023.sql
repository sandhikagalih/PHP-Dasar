-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 14, 2013 at 08:44 PM
-- Server version: 5.5.25
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `043040023`
--

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama`) VALUES
(1, 'Fakultas Teknik'),
(2, 'Fakultas Ekonomi'),
(3, 'Fakultas Psikologi'),
(4, 'Fakultas Desain');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `id_fakultas` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_fakultas` (`id_fakultas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`id`, `nama`, `id_fakultas`) VALUES
(1, 'Teknik Informatika', 1),
(2, 'Teknik Mesin', 1),
(3, 'Teknik Pangan', 1),
(4, 'Ilmu Psikologi', 3),
(5, 'Perpajakan', 2),
(6, 'Desain Komunikasi Visual', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `universitas` varchar(30) NOT NULL,
  `kota` varchar(30) NOT NULL,
  `fakultas` int(5) NOT NULL,
  `jurusan` int(5) NOT NULL,
  `foto` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fakultas` (`fakultas`),
  KEY `jurusan` (`jurusan`),
  KEY `nama` (`nama`),
  KEY `universitas` (`universitas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nama`, `universitas`, `kota`, `fakultas`, `jurusan`, `foto`) VALUES
(1, 'Fajar Darmawan', 'Universitas Pasundan', 'Bandung', 1, 1, 'fajar.jpg'),
(2, 'Nofariza Handayani', 'Universitas Padjadjaran', 'Bandung', 2, 5, 'nofariza.jpg'),
(3, 'Doddy Ferdiansyah', 'Universitas Pasundan', 'Bandung', 1, 1, 'doddy.jpg'),
(4, 'Anggoro Ari Nurcahyo', 'Universitas Pasundan', 'Bandung', 1, 1, 'anggoro.jpg'),
(5, 'Mellia Liyanthi', 'Universitas Pasundan', 'Bandung', 1, 1, 'mellia.jpg'),
(6, 'Shabrina Gea', 'Universitas Islam', 'Jakarta', 3, 4, 'shabrinagea.jpg'),
(7, 'Rommy Fauziantarto', 'Institut Teknologi Nasional', 'Bandung', 4, 6, 'rommy.jpg'),
(8, 'Erik', 'Universitas Pasundan', 'Bandung', 1, 3, 'erik.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', 'motekar'),
(2, 'sandhika', '123');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD CONSTRAINT `jurusan_ibfk_1` FOREIGN KEY (`id_fakultas`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`fakultas`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`jurusan`) REFERENCES `jurusan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
