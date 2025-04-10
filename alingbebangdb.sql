-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3377
-- Generation Time: Apr 10, 2025 at 02:59 PM
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
-- Database: `alingbebangdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comboitemtbl`
--

CREATE TABLE `comboitemtbl` (
  `ComboItemID` int(11) NOT NULL,
  `ComboID` int(11) DEFAULT NULL,
  `MenuID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comboitemtbl`
--

INSERT INTO `comboitemtbl` (`ComboItemID`, `ComboID`, `MenuID`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 1, 6),
(4, 2, 2),
(5, 2, 3),
(6, 2, 5),
(7, 3, 1),
(8, 3, 3),
(9, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `combotbl`
--

CREATE TABLE `combotbl` (
  `ComboID` int(11) NOT NULL,
  `ComboName` varchar(50) DEFAULT NULL,
  `Discount` int(11) DEFAULT NULL,
  `TimesUsed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `combotbl`
--

INSERT INTO `combotbl` (`ComboID`, `ComboName`, `Discount`, `TimesUsed`) VALUES
(1, 'sodium overdose', 30, 2),
(2, 'otentik pinoy', 20, 1),
(3, 'sige sisig', 40, 3);

-- --------------------------------------------------------

--
-- Table structure for table `menutbl`
--

CREATE TABLE `menutbl` (
  `MenuID` int(11) NOT NULL,
  `ItemName` varchar(50) DEFAULT NULL,
  `ItemPic` varchar(300) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `ItemDescription` varchar(400) DEFAULT NULL,
  `Allergen` varchar(50) DEFAULT NULL,
  `AllergenPic` varchar(300) DEFAULT NULL,
  `Spicy` tinyint(1) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT NULL,
  `IsMeal` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menutbl`
--

INSERT INTO `menutbl` (`MenuID`, `ItemName`, `ItemPic`, `Price`, `ItemDescription`, `Allergen`, `AllergenPic`, `Spicy`, `IsActive`, `IsMeal`) VALUES
(1, 'Sisig', 'images/sisig.png', 120, 'I love sisig, masrap yann!!', '', NULL, 1, 1, 1),
(2, 'Bisteak Tagalog', 'images/bisteak.png', 100, 'eto dagdagan ko ulam mo dahil gwapo ka!', NULL, 'images/nut.png', 0, 1, 1),
(3, 'Iced Tea', 'images/iced_tea.png', 30, 'eto pampalamig oh', NULL, NULL, 0, 1, 0),
(4, 'Lumpiang Shanghai', 'images/lumpiang_shanghai.png', 50, 'Crunchy yaaan!', NULL, NULL, 1, 1, 2),
(5, 'garlic_rice', 'images/garlic_rice.png', 20, 'Gusto mo ng extra garlic toi?', NULL, NULL, 1, 1, 2),
(6, 'Kape', 'images/kape.png', 30, 'Eto sugar tsaka creamer para di masyadong mapait!', NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ordertbl`
--

CREATE TABLE `ordertbl` (
  `OrderID` int(11) NOT NULL,
  `SalesID` int(11) DEFAULT NULL,
  `MenuID` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordertbl`
--

INSERT INTO `ordertbl` (`OrderID`, `SalesID`, `MenuID`, `qty`) VALUES
(1, 1, 1, 2),
(2, 1, 4, 2),
(3, 1, 6, 3),
(4, 2, 2, 1),
(5, 2, 3, 1),
(6, 2, 5, 10),
(7, 3, 1, 2),
(8, 3, 3, 3),
(19, 30, 2, 1),
(20, 30, 3, 1),
(21, 31, 4, 1),
(22, 31, 5, 1),
(23, 32, 3, 1),
(24, 32, 1, 2),
(25, 32, 4, 1),
(26, 32, 6, 1),
(27, 32, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ratingtbl`
--

CREATE TABLE `ratingtbl` (
  `RatingID` int(11) NOT NULL,
  `Stars` int(11) DEFAULT NULL,
  `Comment` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratingtbl`
--

INSERT INTO `ratingtbl` (`RatingID`, `Stars`, `Comment`) VALUES
(1, 4, 'No'),
(5, 5, 'gamer'),
(6, 5, 'gamer'),
(7, 5, 'das'),
(8, 5, 'das'),
(9, 5, 'das'),
(10, 5, 'sda'),
(11, 0, 'NULL'),
(12, 0, 'NULL'),
(13, 5, 'dsa'),
(14, 5, 'dasda'),
(15, 5, 'dsa'),
(16, 5, 'das'),
(17, 5, 'dsa'),
(18, 5, 'dasda'),
(19, 0, 'NULL'),
(20, 0, 'NULL'),
(21, 5, 'DSADAS'),
(22, 4, 'dasda'),
(23, 0, 'NULL'),
(24, 0, 'NULL'),
(25, 0, 'NULL'),
(26, 0, 'NULL'),
(27, 5, 'dasda'),
(28, 5, 'dsada'),
(29, 5, 'dat was amazing'),
(30, 5, 'dasda');

-- --------------------------------------------------------

--
-- Table structure for table `salestbl`
--

CREATE TABLE `salestbl` (
  `SalesID` int(11) NOT NULL,
  `TotalRaw` float DEFAULT NULL,
  `TotalDiscounted` float DEFAULT NULL,
  `PaymentMethod` varchar(50) DEFAULT NULL,
  `DiningMethod` varchar(50) DEFAULT NULL,
  `SaleDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salestbl`
--

INSERT INTO `salestbl` (`SalesID`, `TotalRaw`, `TotalDiscounted`, `PaymentMethod`, `DiningMethod`, `SaleDate`) VALUES
(1, 430, 370, 'card', 'dine in', '2025-03-10'),
(2, 150, 130, 'e-wallet', 'take out', '2025-03-15'),
(3, 330, 330, 'card', 'dine in', '2025-04-12'),
(30, 130, 130, 'E-Wallet', 'Dine In', '2025-04-10'),
(31, 70, 70, 'Card', 'Take Out', '2025-04-10'),
(32, 370, 300, 'E-Wallet', 'Dine In', '2025-04-10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comboitemtbl`
--
ALTER TABLE `comboitemtbl`
  ADD PRIMARY KEY (`ComboItemID`),
  ADD KEY `ComboID` (`ComboID`),
  ADD KEY `MenuID` (`MenuID`);

--
-- Indexes for table `combotbl`
--
ALTER TABLE `combotbl`
  ADD PRIMARY KEY (`ComboID`);

--
-- Indexes for table `menutbl`
--
ALTER TABLE `menutbl`
  ADD PRIMARY KEY (`MenuID`);

--
-- Indexes for table `ordertbl`
--
ALTER TABLE `ordertbl`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `SalesID` (`SalesID`),
  ADD KEY `MenuID` (`MenuID`);

--
-- Indexes for table `ratingtbl`
--
ALTER TABLE `ratingtbl`
  ADD PRIMARY KEY (`RatingID`);

--
-- Indexes for table `salestbl`
--
ALTER TABLE `salestbl`
  ADD PRIMARY KEY (`SalesID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comboitemtbl`
--
ALTER TABLE `comboitemtbl`
  MODIFY `ComboItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `combotbl`
--
ALTER TABLE `combotbl`
  MODIFY `ComboID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menutbl`
--
ALTER TABLE `menutbl`
  MODIFY `MenuID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ordertbl`
--
ALTER TABLE `ordertbl`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ratingtbl`
--
ALTER TABLE `ratingtbl`
  MODIFY `RatingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `salestbl`
--
ALTER TABLE `salestbl`
  MODIFY `SalesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comboitemtbl`
--
ALTER TABLE `comboitemtbl`
  ADD CONSTRAINT `comboitemtbl_ibfk_1` FOREIGN KEY (`ComboID`) REFERENCES `combotbl` (`ComboID`),
  ADD CONSTRAINT `comboitemtbl_ibfk_2` FOREIGN KEY (`MenuID`) REFERENCES `menutbl` (`MenuID`);

--
-- Constraints for table `ordertbl`
--
ALTER TABLE `ordertbl`
  ADD CONSTRAINT `ordertbl_ibfk_1` FOREIGN KEY (`SalesID`) REFERENCES `salestbl` (`SalesID`),
  ADD CONSTRAINT `ordertbl_ibfk_2` FOREIGN KEY (`MenuID`) REFERENCES `menutbl` (`MenuID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
