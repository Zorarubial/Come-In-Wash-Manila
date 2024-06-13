-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2024 at 01:33 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ciw_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `User_id` int(5) NOT NULL,
  `Ufname` varchar(40) NOT NULL,
  `Usname` varchar(40) NOT NULL,
  `Umobile` varchar(13) NOT NULL,
  `Uemail` varchar(50) NOT NULL,
  `Upass` varchar(256) NOT NULL,
  `Ucity` varchar(50) NOT NULL,
  `Uprovince` varchar(50) NOT NULL,
  `Ucreation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Utype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`User_id`, `Ufname`, `Usname`, `Umobile`, `Uemail`, `Upass`, `Ucity`, `Uprovince`, `Ucreation`, `Utype`) VALUES
(1, 'Jack', 'Antonoff', '09168856643', 'jackantonoff@gmail.com', '$2y$10$lnPAcikVDLKa.JZptL2kd.mhSfq4o307nJs97c6eGZAwQbUh48yT6', 'Baliwag', 'Bulacan', '2024-04-18 16:30:19', '1'),
(2, 'Clara', 'Bow', '09187716312', 'clarabow@gmail.com', '$2y$10$vk/V6ZoocBpbd3sWcB2VUepENayoPuCMkLdGJw04z9a3INMHfV292', 'Baliwag', 'Bulacan', '2024-04-18 16:31:09', '1'),
(3, 'Charlie', 'Puth', '09156684431', 'charlieputh@gmail.com', '$2y$10$FSSh6WPBNnF9uSe4MW7SeOJxeFpvpXgxJTXFOvXZvb0VJIx.GeH.i', 'Baliwag', 'Bulacan', '2024-04-18 16:31:52', '1'),
(4, 'Sarah', 'Hannah', '09631723641', 'sarahh@gmail.com', '$2y$10$n9iXAXnkXqO342Kmm6lRLOFrMv57GGIp1tE0xCp8PrlPtXzqNJKym', 'Baliwag', 'Bulacan', '2024-04-29 10:36:23', '1');

-- --------------------------------------------------------

--
-- Table structure for table `washing`
--

CREATE TABLE `washing` (
  `Wash_id` int(11) NOT NULL,
  `User_id` int(5) NOT NULL,
  `Wcategory` varchar(30) NOT NULL,
  `Wcost` double(10,2) NOT NULL,
  `Wcarsize` varchar(10) NOT NULL,
  `Wdate` date NOT NULL,
  `Wtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `Wnotesbefore` varchar(300) DEFAULT NULL,
  `Wnotesafter` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `washing`
--

INSERT INTO `washing` (`Wash_id`, `User_id`, `Wcategory`, `Wcost`, `Wcarsize`, `Wdate`, `Wtime`, `Wnotesbefore`, `Wnotesafter`) VALUES
(1, 2, 'premium', 200.00, 'regular', '2024-04-19', '2024-04-29 08:36:53', 'good', 'good'),
(2, 3, 'ultimate', 250.00, 'regular', '2024-04-19', '2024-04-29 08:39:02', 'okay', 'not okay'),
(3, 3, 'ultimate', 250.00, 'regular', '2024-04-19', '2024-04-29 08:39:32', 'good', 'better'),
(4, 4, 'deluxe', 150.00, 'small', '2024-04-22', '2024-04-29 09:20:25', 'dirt on chain', 'clean');

-- --------------------------------------------------------

--
-- Table structure for table `xpns_tbl`
--

CREATE TABLE `xpns_tbl` (
  `xpns_id` int(11) NOT NULL,
  `User_id` int(5) NOT NULL,
  `Eamount` double(6,2) NOT NULL,
  `Edate` date NOT NULL,
  `Etime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`User_id`);

--
-- Indexes for table `washing`
--
ALTER TABLE `washing`
  ADD PRIMARY KEY (`Wash_id`);

--
-- Indexes for table `xpns_tbl`
--
ALTER TABLE `xpns_tbl`
  ADD PRIMARY KEY (`xpns_id`),
  ADD KEY `User_id` (`User_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `User_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `washing`
--
ALTER TABLE `washing`
  MODIFY `Wash_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `xpns_tbl`
--
ALTER TABLE `xpns_tbl`
  MODIFY `xpns_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `xpns_tbl`
--
ALTER TABLE `xpns_tbl`
  ADD CONSTRAINT `xpns_tbl_ibfk_1` FOREIGN KEY (`User_id`) REFERENCES `tbl_user` (`User_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
