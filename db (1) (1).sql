-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 01. Jan 2022 um 18:01
-- Server-Version: 10.3.31-MariaDB-0+deb10u1
-- PHP-Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `s4585_virty`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cheat`
--

CREATE TABLE `cheat` (
  `status` int(1) NOT NULL DEFAULT 0,
  `version` float NOT NULL DEFAULT 0,
  `maintenance` int(1) NOT NULL DEFAULT 0,
  `motd` varchar(255) NOT NULL,
  `apikey` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cheat`
--

INSERT INTO `cheat` (`status`, `version`, `maintenance`, `motd`, `apikey`) VALUES
(0, 2, 0, 'CS:GO cheat comming soon', 'ckWG');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `license`
--

CREATE TABLE `license` (
  `code` varchar(255) NOT NULL,
  `createdBy` varchar(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `license`
--

INSERT INTO `license` (`code`, `createdBy`, `createdAt`) VALUES
('admin', 'Clynt', '2021-12-24 22:50:21');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `log_user` varchar(255) NOT NULL,
  `log_action` varchar(255) NOT NULL,
  `log_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `logs`
--

INSERT INTO `logs` (`id`, `log_user`, `log_action`, `log_time`) VALUES
(376, 'Clynt', 'Deleted all logs', '2021-12-28 13:15:52');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `subscription`
--

CREATE TABLE `subscription` (
  `code` varchar(255) NOT NULL,
  `createdBy` varchar(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hwid` varchar(255) DEFAULT NULL,
  `admin` int(1) NOT NULL DEFAULT 0,
  `reseller` int(1) NOT NULL DEFAULT 0,
  `sub` date DEFAULT NULL,
  `banned` int(1) NOT NULL DEFAULT 0,
  `invitedBy` varchar(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT current_timestamp(),
  `dcid` varchar(255) NOT NULL,
  `last_reset` int(11) NOT NULL,
  `balance` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `users` (`uid`, `username`, `password`, `hwid`, `admin`, `reseller`, `sub`, `banned`, `invitedBy`, `createdAt`, `dcid`, `last_reset`, `balance`) VALUES
(1, 'admin', '$2y$10$7wOzYc.AXpXc1nE/b0IqLOsP2w1cK9LZXDUi6hoSyuWBDj3DoBjOK', NULL, 1, 0, NULL, 0, 'Clynt', '2022-01-01 15:24:13', '', 0, 0);
COMMIT;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `license`
--
ALTER TABLE `license`
  ADD UNIQUE KEY `code` (`code`);

--
-- Indizes für die Tabelle `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);


--
-- Indizes für die Tabelle `subscription`
--
ALTER TABLE `subscription`
  ADD UNIQUE KEY `code` (`code`);


--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD UNIQUE KEY `hwid` (`hwid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
