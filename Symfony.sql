-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 12, 2017 at 06:47 PM
-- Server version: 5.5.56-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Symfony`
--
CREATE DATABASE IF NOT EXISTS `Symfony` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `Symfony`;

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Groups`
--

INSERT INTO `Groups` (`Id`, `Name`) VALUES
(1, 'Administrators'),
(2, 'Super Users'),
(3, 'Developers'),
(4, 'Project Managers'),
(7, 'Quality'),
(8, 'Human Resources'),
(13, 'New Group');

-- --------------------------------------------------------

--
-- Table structure for table `Roles`
--

CREATE TABLE `Roles` (
  `id` int(11) NOT NULL,
  `RoleName` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `Id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `FirstName` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `LastName` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`Id`, `username`, `password`, `FirstName`, `LastName`, `Email`) VALUES
(1, 'melharty', '5f4dcc3b5aa765d61d8327deb882cf99', 'Mohamed', 'El Harty', 'melharty@gmail.com'),
(9, 'User1', '5f4dcc3b5aa765d61d8327deb882cf99', 'user', 'test', 'user.test@testuser.com'),
(10, 'User2', '5f4dcc3b5aa765d61d8327deb882cf99', 'user', 'test', 'user.test@testuser.com'),
(11, 'Kevin', '5f4dcc3b5aa765d61d8327deb882cf99', 'Kevin', 'Smith', 'kevin.smith@microsoft.com'),
(12, 'user3', 'e16b2ab8d12314bf4efbd6203906ea6c', 'User 3', 'Test', 'test.user3@test.com'),
(13, 'user4', 'e16b2ab8d12314bf4efbd6203906ea6c', 'User 3', 'Test', 'test.user3@test.com'),
(18, 'usertestnew', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(19, 'usertestnew1', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(20, 'usertestnew2', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(21, 'usertestnew23', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(22, 'usertestnew234', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(23, 'usertestnew2345', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(24, 'usertestnew23456', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(25, 'usertestnew234567', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(27, 'usertestnew23456789', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New', 'Last', 'test@test.com'),
(28, 'usertestnew234567890', '5f4dcc3b5aa765d61d8327deb882cf99', 'User Test New 1', 'Last', 'test@test.com');

-- --------------------------------------------------------

--
-- Table structure for table `UsersGroups`
--

CREATE TABLE `UsersGroups` (
  `UserId` int(11) NOT NULL,
  `GroupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `UsersGroups`
--

INSERT INTO `UsersGroups` (`UserId`, `GroupId`) VALUES
(1, 1),
(11, 1),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(28, 3),
(28, 4);

-- --------------------------------------------------------

--
-- Table structure for table `UsersRoles`
--

CREATE TABLE `UsersRoles` (
  `UserId` int(11) NOT NULL,
  `RoleId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `UsersGroups`
--
ALTER TABLE `UsersGroups`
  ADD PRIMARY KEY (`UserId`,`GroupId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `GroupId` (`GroupId`);

--
-- Indexes for table `UsersRoles`
--
ALTER TABLE `UsersRoles`
  ADD PRIMARY KEY (`UserId`,`RoleId`),
  ADD KEY `UserId` (`UserId`),
  ADD KEY `RoleId` (`RoleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `UsersGroups`
--
ALTER TABLE `UsersGroups`
  ADD CONSTRAINT `UsersGroups_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `Users` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UsersGroups_ibfk_2` FOREIGN KEY (`GroupId`) REFERENCES `Groups` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UsersRoles`
--
ALTER TABLE `UsersRoles`
  ADD CONSTRAINT `UsersRoles_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `Users` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UsersRoles_ibfk_2` FOREIGN KEY (`RoleId`) REFERENCES `Roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
