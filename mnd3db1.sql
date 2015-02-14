-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 14, 2015 at 04:55 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mnd3db1`
--
CREATE DATABASE IF NOT EXISTS `mnd3db1` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mnd3db1`;

-- --------------------------------------------------------

--
-- Table structure for table `grupv`
--

CREATE TABLE IF NOT EXISTS `grupv` (
  `codgrupv` smallint(6) NOT NULL,
  `dengrupv` varchar(40) NOT NULL,
  PRIMARY KEY (`codgrupv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grupv`
--

INSERT INTO `grupv` (`codgrupv`, `dengrupv`) VALUES
(1, 'Varsta intre 10 si 19 ani'),
(2, 'Varsta intre 20 si 29 de ani'),
(3, 'Varsta intre 30 si 39 de ani'),
(4, 'Varsta intre 40 si 49 de ani'),
(5, 'Varsta intre 50 si 59 de ani'),
(6, 'Varsta intre 60 si 69 de ani'),
(7, 'Varsta peste 70 de ani');

-- --------------------------------------------------------

--
-- Table structure for table `tipuser`
--

CREATE TABLE IF NOT EXISTS `tipuser` (
  `codtipu` smallint(6) NOT NULL,
  `dentipu` varchar(20) NOT NULL,
  PRIMARY KEY (`codtipu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tipuser`
--

INSERT INTO `tipuser` (`codtipu`, `dentipu`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(20) NOT NULL,
  `codtipu` smallint(6) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` char(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `codgrupv` smallint(6) NOT NULL,
  PRIMARY KEY (`username`),
  KEY `codtipu` (`codtipu`),
  KEY `codgrupv` (`codgrupv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `codtipu`, `password`, `name`, `email`, `phone`, `description`, `codgrupv`) VALUES
('arba_g', 1, 'b0eaaa4ad7946a4e8b9314f36ca6d8f9', 'Arba George Mihai', 'arba_george@yahoo.com', '9744177125', 'baiat bun', 5),
('cristipop', 2, 'd1ee191db26f6223ce6fc3b68601f11f', 'Pop Cristian', 'c.pop@yahoo.com', '5127497281', 'pop cristi', 1),
('moldovan_d', 2, 'aeb03b4ce08ee83ac8c7d4d68c67512a', 'Moldovan Dana Cristiana', 'dana_crys@yahoo.com', '7745113662', 'tanara speranta da si nu', 4),
('pop.ion', 2, 'f06ea77f18d02f66ce40253d56ad7e82', 'Pop Ion', 'ion.pop@gmail.com', '3851231234', 'descriere pop ion', 2),
('stanciu_i', 1, 'ee13363677d617b51e5ec92413516351', 'Stanciu Ioan', 'stanciu_ioan@yahoo.com', '8755980121', 'gazda mare', 5),
('tasnady_e', 2, 'f06ea77f18d02f66ce40253d56ad7e82', 'Tasnady Erzsebet', 'tasnady_e@yahoo.com', '4127919900', 'out of country', 3),
('tica', 2, 'f06ea77f18d02f66ce40253d56ad7e82', 'Tica Ana', 'mtica@gmail.com', '3333334444', 'Tica Ana Maria', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `grupv_fk` FOREIGN KEY (`codgrupv`) REFERENCES `grupv` (`codgrupv`),
  ADD CONSTRAINT `tipu_fk` FOREIGN KEY (`codtipu`) REFERENCES `tipuser` (`codtipu`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
