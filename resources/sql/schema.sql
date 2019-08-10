SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `ageDivisions`;
CREATE TABLE `ageDivisions` (
  `id` int(11) NOT NULL,
  `label` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `eventTypeId` int(11) NOT NULL,
  `city` varchar(200) NOT NULL DEFAULT '',
  `country` varchar(3) NOT NULL,
  `date` date NOT NULL,
  `ageDivisionId` int(11) NOT NULL DEFAULT 1,
  `prizeMoneyId` int(11) NOT NULL DEFAULT 0,
  `playerCount` int(11) NOT NULL,
  `eventName` varchar(200) NOT NULL,
  `api` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `eventTypes`;
CREATE TABLE `eventTypes` (
  `id` int(11) NOT NULL,
  `label` varchar(200) NOT NULL,
  `seasonId` int(11) NOT NULL,
  `points` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `playerName` varchar(200) NOT NULL,
  `country` varchar(3) NOT NULL,
  `facebook` varchar(128) NOT NULL DEFAULT '',
  `twitter` varchar(128) NOT NULL,
  `youtube` varchar(128) NOT NULL DEFAULT '',
  `twitch` varchar(128) NOT NULL DEFAULT '',
  `api` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `eventId` int(11) NOT NULL,
  `playerId` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `team` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `qrlink` varchar(200) NOT NULL DEFAULT '',
  `api` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `seasons`;
CREATE TABLE `seasons` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `ageDivisions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `eventTypes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `ageDivisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `eventTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `seasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

