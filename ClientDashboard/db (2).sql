-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2020 at 11:10 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `CODE` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `BUSINESSID` int(11) NOT NULL,
  `DESCRIPTION` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `STATUS` int(1) NOT NULL,
  `INSERT_DATE` date NOT NULL,
  `UPDATE_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`CODE`, `BUSINESSID`, `DESCRIPTION`, `STATUS`, `INSERT_DATE`, `UPDATE_DATE`) VALUES
('ADMIN', 1, 'ADMIN', 1, '2020-04-21', '0000-00-00'),
('SOFTEXPRES', 6, '', 1, '2020-04-21', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `cids`
--

CREATE TABLE `cids` (
  `CID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `BUSINESSID` int(11) NOT NULL,
  `ALLOW_SYNC` int(1) NOT NULL,
  `STATUS` int(1) NOT NULL,
  `START_DATE` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cids`
--

INSERT INTO `cids` (`CID`, `BUSINESSID`, `ALLOW_SYNC`, `STATUS`, `START_DATE`) VALUES
('SE', 6, 1, 1, '2020-04-21');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `ID` int(11) NOT NULL,
  `BUSINESSID` int(11) NOT NULL,
  `KUNDERID` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `STATUS` int(1) NOT NULL,
  `INSERT_DATE` datetime NOT NULL,
  `VALIDATION_DATE` datetime NOT NULL,
  `EXPIRE_DATE` datetime NOT NULL,
  `DESCRIPTION` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`ID`, `BUSINESSID`, `KUNDERID`, `STATUS`, `INSERT_DATE`, `VALIDATION_DATE`, `EXPIRE_DATE`, `DESCRIPTION`) VALUES
(1, 1, '123', 1, '2020-04-21 00:00:00', '2020-04-21 11:00:00', '2021-04-21 11:00:00', 'PAJISJE ADMIN'),
(8, 6, '7e8873dc663cd1c5', 1, '2020-04-21 11:06:00', '2020-04-21 11:11:00', '2021-04-21 11:11:00', 'sedevice1');

-- --------------------------------------------------------

--
-- Table structure for table `fatured`
--

CREATE TABLE `fatured` (
  `FATUREDID` int(11) NOT NULL,
  `TERMINALID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `KOD_FATURE` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `DATE_FAT` date NOT NULL,
  `VLEFTA` decimal(18,4) NOT NULL,
  `VLEFTATVSH` decimal(18,4) NOT NULL,
  `TVSH` decimal(18,2) NOT NULL,
  `SKONTO` float NOT NULL,
  `KLIENTI` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `NR_SERIAL` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `MAGAZINA` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `MONEDHA` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TIP_FATURE` varchar(10) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `IS_HYRJE_DALJE` tinyint(1) NOT NULL,
  `INSERT_DATE` datetime NOT NULL,
  `DATA_REGJ` datetime NOT NULL,
  `SEKSIONID` int(11) DEFAULT NULL,
  `USERID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `STATUS` tinyint(1) NOT NULL,
  `BUSINESSID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fatured`
--

INSERT INTO `fatured` (`FATUREDID`, `TERMINALID`, `KOD_FATURE`, `DATE_FAT`, `VLEFTA`, `VLEFTATVSH`, `TVSH`, `SKONTO`, `KLIENTI`, `NR_SERIAL`, `MAGAZINA`, `MONEDHA`, `TIP_FATURE`, `IS_HYRJE_DALJE`, `INSERT_DATE`, `DATA_REGJ`, `SEKSIONID`, `USERID`, `STATUS`, `BUSINESSID`, `CID`) VALUES
(1, 'Server', '1', '2020-04-21', '208.0000', '250.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 11:13:03', '2020-04-21 11:13:11', 49964, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(2, 'Server', '2', '2020-04-21', '133.0000', '150.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 11:22:53', '2020-04-21 11:22:56', 49965, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(3, 'Server', '3', '2020-04-21', '100.0000', '100.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 11:22:58', '2020-04-21 11:24:13', 49965, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(4, 'Server', '4', '2020-04-21', '83.0000', '100.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 13:12:51', '2020-04-21 13:12:55', 49965, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(5, 'Server', '5', '2020-04-21', '208.0000', '250.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 13:12:58', '2020-04-21 13:13:01', 49966, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(7, 'Server', '6', '2020-04-21', '100.0000', '100.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', '', 0, '2020-04-21 13:15:18', '2020-04-21 13:16:28', 49966, 'menaxher_bari', 0, 'SOFTEXPRES', 'SE'),
(8, 'Server', '7', '2020-04-21', '125.0000', '150.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', 'FDS(BAR)', 0, '2020-04-21 18:04:12', '2020-04-21 18:04:14', 49966, 'menaxher_bari', 1, 'SOFTEXPRES', 'SE'),
(10, 'Server', '9', '2020-04-22', '258.0000', '300.0000', '20.00', 0, 'Menaxher Bari', '', 'BAR', 'LEK', '', 0, '2020-04-22 10:04:38', '2020-04-22 10:07:34', 49967, 'menaxher_bari', 0, 'SOFTEXPRES', 'SE');

-- --------------------------------------------------------

--
-- Table structure for table `faturedrel`
--

CREATE TABLE `faturedrel` (
  `FATUREDRELID` int(11) NOT NULL,
  `TERMINALID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `FATUREDID` int(11) NOT NULL,
  `SASIA` float NOT NULL,
  `SKONTO` float NOT NULL,
  `CMIMI` decimal(18,4) NOT NULL,
  `CMIMISHITJES` decimal(18,4) NOT NULL,
  `CMIMSHITJESPATVSH` decimal(18,4) NOT NULL,
  `CMIMSHITJESTVSH` decimal(18,4) NOT NULL,
  `TVSH` double NOT NULL,
  `ARTIKULL` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `BUSINESSID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faturedrel`
--

INSERT INTO `faturedrel` (`FATUREDRELID`, `TERMINALID`, `FATUREDID`, `SASIA`, `SKONTO`, `CMIMI`, `CMIMISHITJES`, `CMIMSHITJESPATVSH`, `CMIMSHITJESTVSH`, `TVSH`, `ARTIKULL`, `BUSINESSID`, `CID`) VALUES
(84770, 'Server', 1, 1, 0, '100.0000', '100.0000', '83.0000', '100.0000', 20, 'art2', 'SOFTEXPRES', 'SE'),
(84771, 'Server', 1, 1, 0, '150.0000', '150.0000', '125.0000', '150.0000', 20, 'Artikull Llogari', 'SOFTEXPRES', 'SE'),
(84772, 'Server', 2, 1, 0, '100.0000', '100.0000', '83.0000', '100.0000', 20, 'art2', 'SOFTEXPRES', 'SE'),
(84773, 'Server', 2, 1, 0, '50.0000', '50.0000', '50.0000', '50.0000', 0, 'Artikull Shembull', 'SOFTEXPRES', 'SE'),
(84774, 'Server', 3, 2, 0, '50.0000', '50.0000', '50.0000', '50.0000', 0, 'Artikull Shembull', 'SOFTEXPRES', 'SE'),
(84775, 'Server', 4, 1, 0, '100.0000', '100.0000', '83.0000', '100.0000', 20, 'art2', 'SOFTEXPRES', 'SE'),
(84776, 'Server', 5, 1, 0, '100.0000', '100.0000', '83.0000', '100.0000', 20, 'art2', 'SOFTEXPRES', 'SE'),
(84777, 'Server', 5, 1, 0, '150.0000', '150.0000', '125.0000', '150.0000', 20, 'Artikull Llogari', 'SOFTEXPRES', 'SE'),
(84779, 'Server', 7, 2, 0, '50.0000', '50.0000', '50.0000', '50.0000', 0, 'Artikull Shembull', 'SOFTEXPRES', 'SE'),
(84780, 'Server', 8, 1, 0, '150.0000', '150.0000', '125.0000', '150.0000', 20, 'Artikull Llogari', 'SOFTEXPRES', 'SE'),
(84784, 'Server', 10, 1, 0, '100.0000', '100.0000', '83.0000', '100.0000', 20, 'art2', 'SOFTEXPRES', 'SE'),
(84785, 'Server', 10, 1, 0, '150.0000', '150.0000', '125.0000', '150.0000', 20, 'Artikull Llogari', 'SOFTEXPRES', 'SE'),
(84786, 'Server', 10, 1, 0, '50.0000', '50.0000', '50.0000', '50.0000', 0, 'Artikull Shembull', 'SOFTEXPRES', 'SE');

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `LOGID` int(11) NOT NULL,
  `TYPE` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TRANSACTION_ID` int(11) NOT NULL,
  `CID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `STATUS` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `RESPOND_MSG` varchar(2000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `REQUEST_XML` varchar(10000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `REG_DATE` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seksion`
--

CREATE TABLE `seksion` (
  `SEKSIONID` int(11) NOT NULL,
  `TERMINALID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TAVOLINA` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `TAVOLINA_EMERTIM` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `HAPJA` datetime NOT NULL,
  `MBYLLJA` datetime DEFAULT NULL,
  `STATUS` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `USERID` int(11) NOT NULL,
  `BUSINESSID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `CID` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `seksion`
--

INSERT INTO `seksion` (`SEKSIONID`, `TERMINALID`, `TAVOLINA`, `TAVOLINA_EMERTIM`, `HAPJA`, `MBYLLJA`, `STATUS`, `USERID`, `BUSINESSID`, `CID`) VALUES
(49964, 'Server', '176', '1', '2020-04-21 11:13:11', '2020-04-21 11:24:24', 'MBY', 11, 'SOFTEXPRES', 'SE'),
(49965, 'Server', '179', '4', '2020-04-21 11:22:56', '2020-04-21 13:15:14', 'MBY', 11, 'SOFTEXPRES', 'SE'),
(49966, 'Server', '183', '8', '2020-04-21 13:13:01', '1970-01-01 01:00:00', 'HAP', 11, 'SOFTEXPRES', 'SE'),
(49967, 'Server', '176', '1', '2020-04-21 18:12:16', '1970-01-01 01:00:00', 'HAP', 11, 'SOFTEXPRES', 'SE');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `USERID` int(11) NOT NULL,
  `USERNAMED` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `FJALEKALIM` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `BUSINESSID` int(11) NOT NULL,
  `STATUS` tinyint(1) NOT NULL,
  `TIPI` varchar(1) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`USERID`, `USERNAMED`, `FJALEKALIM`, `BUSINESSID`, `STATUS`, `TIPI`) VALUES
(1, 'ad', '1', 1, 1, 'A'),
(7, 'seuser1', '1', 6, 1, 'B');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`BUSINESSID`) USING BTREE;

--
-- Indexes for table `cids`
--
ALTER TABLE `cids`
  ADD PRIMARY KEY (`CID`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`ID`) USING BTREE;

--
-- Indexes for table `fatured`
--
ALTER TABLE `fatured`
  ADD PRIMARY KEY (`FATUREDID`,`TERMINALID`),
  ADD KEY `SEKSION_FK` (`SEKSIONID`),
  ADD KEY `CID_FK` (`CID`);

--
-- Indexes for table `faturedrel`
--
ALTER TABLE `faturedrel`
  ADD PRIMARY KEY (`FATUREDRELID`,`TERMINALID`),
  ADD KEY `FATUREDID_FK` (`FATUREDID`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`LOGID`);

--
-- Indexes for table `seksion`
--
ALTER TABLE `seksion`
  ADD PRIMARY KEY (`SEKSIONID`,`TERMINALID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`USERID`,`USERNAMED`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `BUSINESSID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `LOGID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `USERID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `faturedrel`
--
ALTER TABLE `faturedrel`
  ADD CONSTRAINT `FATUREDID_FK` FOREIGN KEY (`FATUREDID`) REFERENCES `fatured` (`FATUREDID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
