-- MySQL dump 10.13  Distrib 8.0.32, for macos13.0 (x86_64)
--
-- Host: svcsa.org    Database: match
-- ------------------------------------------------------
-- Server version	5.7.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `article` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` varchar(1023) DEFAULT NULL,
  `Content` text,
  `Author` varchar(1023) DEFAULT NULL,
  `Posttime` datetime DEFAULT NULL,
  `Hits` int(11) DEFAULT NULL,
  `Category` int(11) NOT NULL,
  `Top` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2730403019 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `authorization`
--

DROP TABLE IF EXISTS `authorization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authorization` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Username` varchar(1023) DEFAULT NULL,
  `Password` varchar(1023) DEFAULT NULL,
  `Level` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_competition`
--

DROP TABLE IF EXISTS `bb_competition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_competition` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `Picture` varchar(1023) DEFAULT NULL,
  `Description` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveSeasonOnCompetition` AFTER DELETE ON `bb_competition` FOR EACH ROW begin
if old.id>0 then
 
delete from bb_season where CompetitionID = old.id;


end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `bb_competitionseason_view`
--

DROP TABLE IF EXISTS `bb_competitionseason_view`;
/*!50001 DROP VIEW IF EXISTS `bb_competitionseason_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_competitionseason_view` AS SELECT 
 1 AS `SeasonID`,
 1 AS `SeasonName`,
 1 AS `StartTime`,
 1 AS `TeamNumber`,
 1 AS `GroupNumber`,
 1 AS `PlayoffGroupNumber`,
 1 AS `Rules`,
 1 AS `CompetitionID`,
 1 AS `CompetitionName`,
 1 AS `Picture`,
 1 AS `Description`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bb_log`
--

DROP TABLE IF EXISTS `bb_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_log` (
  `LogID` int(11) NOT NULL AUTO_INCREMENT,
  `MatchID` int(11) NOT NULL,
  `PlayerID` int(11) DEFAULT '0',
  `Event` mediumtext,
  `TeamID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB AUTO_INCREMENT=57930 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_match`
--

DROP TABLE IF EXISTS `bb_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_match` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TeamAID` int(11) NOT NULL,
  `TeamBID` int(11) NOT NULL,
  `ScoreTeamA` int(11) DEFAULT NULL,
  `ScoreTeamB` int(11) DEFAULT NULL,
  `State` int(11) DEFAULT NULL,
  `StartTime` datetime DEFAULT NULL,
  `VideoSrc` varchar(1023) DEFAULT NULL,
  `SeasonID` int(11) NOT NULL,
  `Court` varchar(100) NOT NULL,
  `Round` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=417 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveStatsAndPicOnDel` AFTER DELETE ON `bb_match` FOR EACH ROW begin
if old.id>0 then
 
delete from bb_statistics where MatchID = old.id;
delete from bb_picture where MatchID = old.id;

end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `bb_matchteam_view`
--

DROP TABLE IF EXISTS `bb_matchteam_view`;
/*!50001 DROP VIEW IF EXISTS `bb_matchteam_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_matchteam_view` AS SELECT 
 1 AS `MatchID`,
 1 AS `TeamAID`,
 1 AS `TeamBID`,
 1 AS `ScoreTeamA`,
 1 AS `ScoreTeamB`,
 1 AS `State`,
 1 AS `StartTime`,
 1 AS `VideoSrc`,
 1 AS `SeasonID`,
 1 AS `Court`,
 1 AS `Round`,
 1 AS `TeamAName`,
 1 AS `TeamAShortName`,
 1 AS `TeamACaptain`,
 1 AS `TeamAEmail`,
 1 AS `TeamATel`,
 1 AS `TeamAWechat`,
 1 AS `TeamALogoSrc`,
 1 AS `TeamAPhotoSrc`,
 1 AS `TeamBName`,
 1 AS `TeamBShortName`,
 1 AS `TeamBCaptain`,
 1 AS `TeamBEmail`,
 1 AS `TeamBTel`,
 1 AS `TeamBWechat`,
 1 AS `TeamBLogoSrc`,
 1 AS `TeamBPhotoSrc`,
 1 AS `CompetitionID`,
 1 AS `MatchName`,
 1 AS `SeasonName`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bb_picture`
--

DROP TABLE IF EXISTS `bb_picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_picture` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MatchID` int(11) NOT NULL,
  `Src` varchar(1023) DEFAULT NULL,
  `Description` varchar(1023) DEFAULT NULL,
  `Flag` int(10) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_player`
--

DROP TABLE IF EXISTS `bb_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_player` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `Birth` datetime DEFAULT NULL,
  `Height` varchar(1023) DEFAULT NULL,
  `Weight` varchar(1023) DEFAULT NULL,
  `PhotoSrc` varchar(1023) DEFAULT NULL,
  `Email` varchar(1023) DEFAULT NULL,
  `Sex` varchar(100) NOT NULL DEFAULT '男',
  `Approval` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1573 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_playoff`
--

DROP TABLE IF EXISTS `bb_playoff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_playoff` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Title` text NOT NULL,
  `Rule` int(11) NOT NULL,
  `SeasonID` int(11) NOT NULL,
  `CompetitionID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_season`
--

DROP TABLE IF EXISTS `bb_season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_season` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `StartTime` datetime DEFAULT NULL,
  `TeamNumber` int(11) NOT NULL,
  `GroupNumber` int(11) NOT NULL,
  `PlayoffGroupNumber` int(11) NOT NULL,
  `Rules` int(11) DEFAULT NULL,
  `CompetitionID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveMatchTeamPlayerOnDel` AFTER DELETE ON `bb_season` FOR EACH ROW begin
if old.id>0 then
 
delete from bb_player where SeasonID = old.id;
delete from bb_match where SeasonID = old.id;
delete from bb_team where SeasonID = old.id;

end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Temporary view structure for view `bb_seasonplayer_view`
--

DROP TABLE IF EXISTS `bb_seasonplayer_view`;
/*!50001 DROP VIEW IF EXISTS `bb_seasonplayer_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_seasonplayer_view` AS SELECT 
 1 AS `SeasonID`,
 1 AS `SeasonName`,
 1 AS `StartTime`,
 1 AS `TeamNumber`,
 1 AS `Rules`,
 1 AS `CompetitionID`,
 1 AS `PlayerID`,
 1 AS `PlayerName`,
 1 AS `PlayerNumber`,
 1 AS `Sex`,
 1 AS `Birth`,
 1 AS `Height`,
 1 AS `Weight`,
 1 AS `PhotoSrc`,
 1 AS `Email`,
 1 AS `TeamID`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `bb_seasonplayerstatistics_view`
--

DROP TABLE IF EXISTS `bb_seasonplayerstatistics_view`;
/*!50001 DROP VIEW IF EXISTS `bb_seasonplayerstatistics_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_seasonplayerstatistics_view` AS SELECT 
 1 AS `Round`,
 1 AS `PlayerID`,
 1 AS `Foul`,
 1 AS `Points`,
 1 AS `Assist`,
 1 AS `Steal`,
 1 AS `Block`,
 1 AS `OffensiveRebound`,
 1 AS `Rebound`,
 1 AS `FGP`,
 1 AS `3GP`,
 1 AS `FTP`,
 1 AS `PlayerName`,
 1 AS `PlayerNumber`,
 1 AS `Birth`,
 1 AS `Height`,
 1 AS `Weight`,
 1 AS `PlayerPhotoSrc`,
 1 AS `PlayerEmail`,
 1 AS `TeamID`,
 1 AS `TeamName`,
 1 AS `TeamShortName`,
 1 AS `Captain`,
 1 AS `TeamEmail`,
 1 AS `Tel`,
 1 AS `Wechat`,
 1 AS `LogoSrc`,
 1 AS `TeamPhotoSrc`,
 1 AS `SeasonID`,
 1 AS `CompetitionID`,
 1 AS `Turnover`,
 1 AS `SeasonName`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bb_seasonteam`
--

DROP TABLE IF EXISTS `bb_seasonteam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_seasonteam` (
  `SeasonID` int(11) NOT NULL DEFAULT '0',
  `TeamID` int(11) NOT NULL DEFAULT '0',
  `TimePreference` int(11) NOT NULL DEFAULT '0',
  `Approval` int(11) NOT NULL DEFAULT '0',
  `PlayoffGroupID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  PRIMARY KEY (`SeasonID`,`TeamID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `bb_seasonteam_view`
--

DROP TABLE IF EXISTS `bb_seasonteam_view`;
/*!50001 DROP VIEW IF EXISTS `bb_seasonteam_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_seasonteam_view` AS SELECT 
 1 AS `TimePreference`,
 1 AS `SeasonID`,
 1 AS `SeasonName`,
 1 AS `StartTime`,
 1 AS `TeamNumber`,
 1 AS `GroupNumber`,
 1 AS `PlayoffGroupNumber`,
 1 AS `GroupID`,
 1 AS `PlayoffGroupID`,
 1 AS `Approval`,
 1 AS `Rules`,
 1 AS `CompetitionID`,
 1 AS `TeamID`,
 1 AS `TeamName`,
 1 AS `ShortName`,
 1 AS `Captain`,
 1 AS `Email`,
 1 AS `Tel`,
 1 AS `Wechat`,
 1 AS `LogoSrc`,
 1 AS `PhotoSrc`,
 1 AS `Description`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `bb_seasonteamplayer`
--

DROP TABLE IF EXISTS `bb_seasonteamplayer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_seasonteamplayer` (
  `SeasonID` int(11) NOT NULL DEFAULT '0',
  `TeamID` int(11) NOT NULL DEFAULT '0',
  `PlayerID` int(11) NOT NULL DEFAULT '0',
  `PlayerNumber` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`SeasonID`,`TeamID`,`PlayerID`,`PlayerNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bb_statistics`
--

DROP TABLE IF EXISTS `bb_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_statistics` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `MatchID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `Starter` int(11) DEFAULT '0',
  `Foul` float(12,4) DEFAULT NULL,
  `Points` float(12,4) DEFAULT NULL,
  `Assist` float(12,4) DEFAULT NULL,
  `Steal` float(12,4) DEFAULT NULL,
  `Block` float(12,4) DEFAULT NULL,
  `Turnover` float(12,4) DEFAULT NULL,
  `OffensiveRebound` float(12,4) DEFAULT NULL,
  `Rebound` float(12,4) DEFAULT NULL,
  `3PointsHit` float(12,4) DEFAULT NULL,
  `2PointsHit` float(12,4) DEFAULT NULL,
  `1PointsHit` float(12,4) DEFAULT NULL,
  `Hit` float(12,4) DEFAULT NULL,
  `3PointsShot` float(12,4) DEFAULT NULL,
  `2PointsShot` float(12,4) DEFAULT NULL,
  `1PointsShot` float(12,4) DEFAULT NULL,
  `Shot` float(12,4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5257 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `AddHitShot` BEFORE INSERT ON `bb_statistics` FOR EACH ROW begin
if NEW.id>0 then
 
set NEW.Hit=NEW.2PointsHit + NEW.3PointsHit;
set NEW.Shot=NEW.2PointsShot + NEW.3PointsShot;

end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `bb_team`
--

DROP TABLE IF EXISTS `bb_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bb_team` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `ShortName` varchar(1023) DEFAULT NULL,
  `Captain` varchar(1023) DEFAULT NULL,
  `Email` varchar(1023) DEFAULT NULL,
  `Tel` varchar(1023) DEFAULT NULL,
  `Wechat` varchar(1023) DEFAULT NULL,
  `LogoSrc` varchar(1023) DEFAULT NULL,
  `PhotoSrc` varchar(1023) DEFAULT NULL,
  `Description` mediumtext,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `bb_teamplayer_view`
--

DROP TABLE IF EXISTS `bb_teamplayer_view`;
/*!50001 DROP VIEW IF EXISTS `bb_teamplayer_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_teamplayer_view` AS SELECT 
 1 AS `SeasonID`,
 1 AS `TeamID`,
 1 AS `TeamName`,
 1 AS `ShortName`,
 1 AS `Captain`,
 1 AS `TeamEmail`,
 1 AS `Tel`,
 1 AS `Wechat`,
 1 AS `LogoSrc`,
 1 AS `TeamPhotoSrc`,
 1 AS `Description`,
 1 AS `PlayerID`,
 1 AS `PlayerName`,
 1 AS `Sex`,
 1 AS `Birth`,
 1 AS `Height`,
 1 AS `Weight`,
 1 AS `PlayerPhotoSrc`,
 1 AS `PlayerEmail`,
 1 AS `PlayerNumber`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `bb_teamplayerstatistics_view`
--

DROP TABLE IF EXISTS `bb_teamplayerstatistics_view`;
/*!50001 DROP VIEW IF EXISTS `bb_teamplayerstatistics_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `bb_teamplayerstatistics_view` AS SELECT 
 1 AS `StatisticsID`,
 1 AS `MatchID`,
 1 AS `PlayerID`,
 1 AS `Foul`,
 1 AS `Points`,
 1 AS `Assist`,
 1 AS `Steal`,
 1 AS `Block`,
 1 AS `OffensiveRebound`,
 1 AS `Rebound`,
 1 AS `3PointsHit`,
 1 AS `2PointsHit`,
 1 AS `1PointsHit`,
 1 AS `Hit`,
 1 AS `3PointsShot`,
 1 AS `2PointsShot`,
 1 AS `1PointsShot`,
 1 AS `Shot`,
 1 AS `PlayerName`,
 1 AS `PlayerNumber`,
 1 AS `Birth`,
 1 AS `Height`,
 1 AS `Weight`,
 1 AS `PlayerPhotoSrc`,
 1 AS `PlayerEmail`,
 1 AS `TeamID`,
 1 AS `TeamName`,
 1 AS `TeamShortName`,
 1 AS `Captain`,
 1 AS `TeamEmail`,
 1 AS `Tel`,
 1 AS `Wechat`,
 1 AS `LogoSrc`,
 1 AS `TeamPhotoSrc`,
 1 AS `SeasonID`,
 1 AS `CompetitionID`,
 1 AS `Turnover`,
 1 AS `Starter`,
 1 AS `MatchName`,
 1 AS `Round`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `ctfc_agegroup`
--

DROP TABLE IF EXISTS `ctfc_agegroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_agegroup` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `MinAge` int(11) NOT NULL,
  `MaxAge` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveStatsPicPlayerOnDel` AFTER DELETE ON `ctfc_agegroup` FOR EACH ROW begin
if old.id>0 then
 
delete from ctfc_player where MatchID = old.id;
delete from ctfc_picture where MatchID = old.id;
delete from ctfc_statistics where MatchID = old.id;

end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ctfc_heat`
--

DROP TABLE IF EXISTS `ctfc_heat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_heat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ItemPlayerID` int(11) NOT NULL,
  `LaneNumber` int(11) NOT NULL,
  `Result` int(11) NOT NULL,
  `Note` varchar(1023) NOT NULL,
  `EventID` int(11) NOT NULL,
  `HeatID` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_item`
--

DROP TABLE IF EXISTS `ctfc_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_item` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `IsSingle` int(11) NOT NULL,
  `IsTrack` int(11) NOT NULL,
  `HeatSize` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveMatchOnDel` AFTER DELETE ON `ctfc_item` FOR EACH ROW begin
if old.id>0 then
 
delete from ctfc_match where EventID = old.id;


end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ctfc_itemplayer`
--

DROP TABLE IF EXISTS `ctfc_itemplayer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_itemplayer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `SeasonID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `Sex` varchar(11) NOT NULL,
  `AgeGroupID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `PlayerID1` int(11) NOT NULL,
  `PlayerID2` int(11) NOT NULL,
  `PlayerID3` int(11) NOT NULL,
  `PlayerID4` int(11) NOT NULL,
  `PlayerID5` int(11) NOT NULL,
  `PlayerID6` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_player`
--

DROP TABLE IF EXISTS `ctfc_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_player` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `Sex` varchar(1023) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `PhotoSrc` varchar(1023) DEFAULT NULL,
  `Email` varchar(1023) DEFAULT NULL,
  `Approval` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_playernumber`
--

DROP TABLE IF EXISTS `ctfc_playernumber`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_playernumber` (
  `SeasonID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `PlayerID` int(11) NOT NULL,
  `PlayerNumber` int(11) NOT NULL,
  PRIMARY KEY (`SeasonID`,`TeamID`,`PlayerID`,`PlayerNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_season`
--

DROP TABLE IF EXISTS `ctfc_season`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_season` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(1023) NOT NULL,
  `Date` datetime DEFAULT NULL,
  `Venue` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `RemoveMatchOnDel2` AFTER DELETE ON `ctfc_season` FOR EACH ROW begin
if old.id>0 then
 
delete from ctfc_match where SeasonID = old.id;


end if ;
 
end */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ctfc_seasonitem`
--

DROP TABLE IF EXISTS `ctfc_seasonitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_seasonitem` (
  `SeasonID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Sex` varchar(11) NOT NULL,
  `MinAgeGroupID` int(11) NOT NULL,
  `MaxAgeGroupID` int(11) NOT NULL,
  PRIMARY KEY (`ItemID`,`SeasonID`,`Sex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_seasonteam`
--

DROP TABLE IF EXISTS `ctfc_seasonteam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_seasonteam` (
  `SeasonID` int(11) NOT NULL,
  `TeamID` int(11) NOT NULL,
  `Approve` int(11) NOT NULL,
  PRIMARY KEY (`SeasonID`,`TeamID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ctfc_team`
--

DROP TABLE IF EXISTS `ctfc_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ctfc_team` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  `ShortName` varchar(255) NOT NULL,
  `LogoSrc` varchar(1023) DEFAULT NULL,
  `PhotoSrc` varchar(1023) NOT NULL,
  `Description` varchar(1023) DEFAULT NULL,
  `CaptainName` varchar(255) DEFAULT '0',
  `CaptainEmail` varchar(255) NOT NULL,
  `CaptainPhone` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sponsor`
--

DROP TABLE IF EXISTS `sponsor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sponsor` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Sponsorname` varchar(1023) DEFAULT NULL,
  `Logo` varchar(1023) NOT NULL,
  `Profile` varchar(1023) DEFAULT NULL,
  `Link` varchar(1023) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system`
--

DROP TABLE IF EXISTS `system`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system` (
  `Contact` text,
  `SMTP` varchar(1023) DEFAULT NULL,
  `Email` varchar(1023) DEFAULT NULL,
  `EmailPassword` varchar(1023) DEFAULT NULL,
  `TwilioSid` varchar(1023) DEFAULT NULL,
  `TwilioAuth` varchar(1023) DEFAULT NULL,
  `TwilioNumber` varchar(1023) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'match'
--

--
-- Final view structure for view `bb_competitionseason_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_competitionseason_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_competitionseason_view` AS select `bb_season`.`ID` AS `SeasonID`,`bb_season`.`Name` AS `SeasonName`,`bb_season`.`StartTime` AS `StartTime`,`bb_season`.`TeamNumber` AS `TeamNumber`,`bb_season`.`GroupNumber` AS `GroupNumber`,`bb_season`.`PlayoffGroupNumber` AS `PlayoffGroupNumber`,`bb_season`.`Rules` AS `Rules`,`bb_season`.`CompetitionID` AS `CompetitionID`,`bb_competition`.`Name` AS `CompetitionName`,`bb_competition`.`Picture` AS `Picture`,`bb_competition`.`Description` AS `Description` from (`bb_season` join `bb_competition` on((`bb_competition`.`ID` = `bb_season`.`CompetitionID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_matchteam_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_matchteam_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_matchteam_view` AS select `bb_match`.`ID` AS `MatchID`,`bb_match`.`TeamAID` AS `TeamAID`,`bb_match`.`TeamBID` AS `TeamBID`,`bb_match`.`ScoreTeamA` AS `ScoreTeamA`,`bb_match`.`ScoreTeamB` AS `ScoreTeamB`,`bb_match`.`State` AS `State`,`bb_match`.`StartTime` AS `StartTime`,`bb_match`.`VideoSrc` AS `VideoSrc`,`bb_match`.`SeasonID` AS `SeasonID`,`bb_match`.`Court` AS `Court`,`bb_match`.`Round` AS `Round`,`teama`.`Name` AS `TeamAName`,`teama`.`ShortName` AS `TeamAShortName`,`teama`.`Captain` AS `TeamACaptain`,`teama`.`Email` AS `TeamAEmail`,`teama`.`Tel` AS `TeamATel`,`teama`.`Wechat` AS `TeamAWechat`,`teama`.`LogoSrc` AS `TeamALogoSrc`,`teama`.`PhotoSrc` AS `TeamAPhotoSrc`,`teamb`.`Name` AS `TeamBName`,`teamb`.`ShortName` AS `TeamBShortName`,`teamb`.`Captain` AS `TeamBCaptain`,`teamb`.`Email` AS `TeamBEmail`,`teamb`.`Tel` AS `TeamBTel`,`teamb`.`Wechat` AS `TeamBWechat`,`teamb`.`LogoSrc` AS `TeamBLogoSrc`,`teamb`.`PhotoSrc` AS `TeamBPhotoSrc`,`bb_season`.`CompetitionID` AS `CompetitionID`,concat(`teama`.`ShortName`,' VS ',`teamb`.`ShortName`) AS `MatchName`,`bb_season`.`Name` AS `SeasonName` from (((`bb_match` join `bb_team` `teama` on((`bb_match`.`TeamAID` = `teama`.`ID`))) join `bb_team` `teamb` on((`bb_match`.`TeamBID` = `teamb`.`ID`))) join `bb_season` on((`bb_match`.`SeasonID` = `bb_season`.`ID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_seasonplayer_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_seasonplayer_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_seasonplayer_view` AS select `bb_season`.`ID` AS `SeasonID`,`bb_season`.`Name` AS `SeasonName`,`bb_season`.`StartTime` AS `StartTime`,`bb_season`.`TeamNumber` AS `TeamNumber`,`bb_season`.`Rules` AS `Rules`,`bb_season`.`CompetitionID` AS `CompetitionID`,`bb_player`.`ID` AS `PlayerID`,`bb_player`.`Name` AS `PlayerName`,`bb_seasonteamplayer`.`PlayerNumber` AS `PlayerNumber`,`bb_player`.`Sex` AS `Sex`,`bb_player`.`Birth` AS `Birth`,`bb_player`.`Height` AS `Height`,`bb_player`.`Weight` AS `Weight`,`bb_player`.`PhotoSrc` AS `PhotoSrc`,`bb_player`.`Email` AS `Email`,`bb_seasonteamplayer`.`TeamID` AS `TeamID` from ((`bb_seasonteamplayer` join `bb_season` on((`bb_seasonteamplayer`.`SeasonID` = `bb_season`.`ID`))) join `bb_player` on((`bb_seasonteamplayer`.`PlayerID` = `bb_player`.`ID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_seasonplayerstatistics_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_seasonplayerstatistics_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_seasonplayerstatistics_view` AS select `bb_teamplayerstatistics_view`.`Round` AS `Round`,`bb_teamplayerstatistics_view`.`PlayerID` AS `PlayerID`,avg(`bb_teamplayerstatistics_view`.`Foul`) AS `Foul`,avg(`bb_teamplayerstatistics_view`.`Points`) AS `Points`,avg(`bb_teamplayerstatistics_view`.`Assist`) AS `Assist`,avg(`bb_teamplayerstatistics_view`.`Steal`) AS `Steal`,avg(`bb_teamplayerstatistics_view`.`Block`) AS `Block`,avg(`bb_teamplayerstatistics_view`.`OffensiveRebound`) AS `OffensiveRebound`,avg(`bb_teamplayerstatistics_view`.`Rebound`) AS `Rebound`,((avg(`bb_teamplayerstatistics_view`.`3PointsHit`) + avg(`bb_teamplayerstatistics_view`.`2PointsHit`)) / (((avg(`bb_teamplayerstatistics_view`.`3PointsHit`) + avg(`bb_teamplayerstatistics_view`.`2PointsHit`)) + avg(`bb_teamplayerstatistics_view`.`3PointsShot`)) + avg(`bb_teamplayerstatistics_view`.`2PointsShot`))) AS `FGP`,(avg(`bb_teamplayerstatistics_view`.`3PointsHit`) / (avg(`bb_teamplayerstatistics_view`.`3PointsShot`) + avg(`bb_teamplayerstatistics_view`.`3PointsHit`))) AS `3GP`,(avg(`bb_teamplayerstatistics_view`.`1PointsHit`) / (avg(`bb_teamplayerstatistics_view`.`1PointsShot`) + avg(`bb_teamplayerstatistics_view`.`1PointsHit`))) AS `FTP`,`bb_teamplayerstatistics_view`.`PlayerName` AS `PlayerName`,`bb_teamplayerstatistics_view`.`PlayerNumber` AS `PlayerNumber`,`bb_teamplayerstatistics_view`.`Birth` AS `Birth`,`bb_teamplayerstatistics_view`.`Height` AS `Height`,`bb_teamplayerstatistics_view`.`Weight` AS `Weight`,`bb_teamplayerstatistics_view`.`PlayerPhotoSrc` AS `PlayerPhotoSrc`,`bb_teamplayerstatistics_view`.`PlayerEmail` AS `PlayerEmail`,`bb_teamplayerstatistics_view`.`TeamID` AS `TeamID`,`bb_teamplayerstatistics_view`.`TeamName` AS `TeamName`,`bb_teamplayerstatistics_view`.`TeamShortName` AS `TeamShortName`,`bb_teamplayerstatistics_view`.`Captain` AS `Captain`,`bb_teamplayerstatistics_view`.`TeamEmail` AS `TeamEmail`,`bb_teamplayerstatistics_view`.`Tel` AS `Tel`,`bb_teamplayerstatistics_view`.`Wechat` AS `Wechat`,`bb_teamplayerstatistics_view`.`LogoSrc` AS `LogoSrc`,`bb_teamplayerstatistics_view`.`TeamPhotoSrc` AS `TeamPhotoSrc`,`bb_teamplayerstatistics_view`.`SeasonID` AS `SeasonID`,`bb_teamplayerstatistics_view`.`CompetitionID` AS `CompetitionID`,avg(`bb_teamplayerstatistics_view`.`Turnover`) AS `Turnover`,`bb_season`.`Name` AS `SeasonName` from (`bb_teamplayerstatistics_view` join `bb_season` on((`bb_teamplayerstatistics_view`.`SeasonID` = `bb_season`.`ID`))) group by `bb_teamplayerstatistics_view`.`PlayerID`,`bb_teamplayerstatistics_view`.`SeasonID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_seasonteam_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_seasonteam_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_seasonteam_view` AS select `bb_seasonteam`.`TimePreference` AS `TimePreference`,`bb_season`.`ID` AS `SeasonID`,`bb_season`.`Name` AS `SeasonName`,`bb_season`.`StartTime` AS `StartTime`,`bb_season`.`TeamNumber` AS `TeamNumber`,`bb_season`.`GroupNumber` AS `GroupNumber`,`bb_season`.`PlayoffGroupNumber` AS `PlayoffGroupNumber`,`bb_seasonteam`.`GroupID` AS `GroupID`,`bb_seasonteam`.`PlayoffGroupID` AS `PlayoffGroupID`,`bb_seasonteam`.`Approval` AS `Approval`,`bb_season`.`Rules` AS `Rules`,`bb_season`.`CompetitionID` AS `CompetitionID`,`bb_team`.`ID` AS `TeamID`,`bb_team`.`Name` AS `TeamName`,`bb_team`.`ShortName` AS `ShortName`,`bb_team`.`Captain` AS `Captain`,`bb_team`.`Email` AS `Email`,`bb_team`.`Tel` AS `Tel`,`bb_team`.`Wechat` AS `Wechat`,`bb_team`.`LogoSrc` AS `LogoSrc`,`bb_team`.`PhotoSrc` AS `PhotoSrc`,`bb_team`.`Description` AS `Description` from ((`bb_seasonteam` join `bb_season` on((`bb_seasonteam`.`SeasonID` = `bb_season`.`ID`))) join `bb_team` on((`bb_seasonteam`.`TeamID` = `bb_team`.`ID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_teamplayer_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_teamplayer_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_teamplayer_view` AS select `bb_seasonteamplayer`.`SeasonID` AS `SeasonID`,`bb_team`.`ID` AS `TeamID`,`bb_team`.`Name` AS `TeamName`,`bb_team`.`ShortName` AS `ShortName`,`bb_team`.`Captain` AS `Captain`,`bb_team`.`Email` AS `TeamEmail`,`bb_team`.`Tel` AS `Tel`,`bb_team`.`Wechat` AS `Wechat`,`bb_team`.`LogoSrc` AS `LogoSrc`,`bb_team`.`PhotoSrc` AS `TeamPhotoSrc`,`bb_team`.`Description` AS `Description`,`bb_player`.`ID` AS `PlayerID`,`bb_player`.`Name` AS `PlayerName`,`bb_player`.`Sex` AS `Sex`,`bb_player`.`Birth` AS `Birth`,`bb_player`.`Height` AS `Height`,`bb_player`.`Weight` AS `Weight`,`bb_player`.`PhotoSrc` AS `PlayerPhotoSrc`,`bb_player`.`Email` AS `PlayerEmail`,`bb_seasonteamplayer`.`PlayerNumber` AS `PlayerNumber` from ((`bb_seasonteamplayer` join `bb_team` on((`bb_seasonteamplayer`.`TeamID` = `bb_team`.`ID`))) join `bb_player` on((`bb_seasonteamplayer`.`PlayerID` = `bb_player`.`ID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `bb_teamplayerstatistics_view`
--

/*!50001 DROP VIEW IF EXISTS `bb_teamplayerstatistics_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `bb_teamplayerstatistics_view` AS select `bb_statistics`.`ID` AS `StatisticsID`,`bb_statistics`.`MatchID` AS `MatchID`,`bb_statistics`.`PlayerID` AS `PlayerID`,`bb_statistics`.`Foul` AS `Foul`,`bb_statistics`.`Points` AS `Points`,`bb_statistics`.`Assist` AS `Assist`,`bb_statistics`.`Steal` AS `Steal`,`bb_statistics`.`Block` AS `Block`,`bb_statistics`.`OffensiveRebound` AS `OffensiveRebound`,`bb_statistics`.`Rebound` AS `Rebound`,`bb_statistics`.`3PointsHit` AS `3PointsHit`,`bb_statistics`.`2PointsHit` AS `2PointsHit`,`bb_statistics`.`1PointsHit` AS `1PointsHit`,`bb_statistics`.`Hit` AS `Hit`,`bb_statistics`.`3PointsShot` AS `3PointsShot`,`bb_statistics`.`2PointsShot` AS `2PointsShot`,`bb_statistics`.`1PointsShot` AS `1PointsShot`,`bb_statistics`.`Shot` AS `Shot`,`bb_teamplayer_view`.`PlayerName` AS `PlayerName`,`bb_teamplayer_view`.`PlayerNumber` AS `PlayerNumber`,`bb_teamplayer_view`.`Birth` AS `Birth`,`bb_teamplayer_view`.`Height` AS `Height`,`bb_teamplayer_view`.`Weight` AS `Weight`,`bb_teamplayer_view`.`PlayerPhotoSrc` AS `PlayerPhotoSrc`,`bb_teamplayer_view`.`PlayerEmail` AS `PlayerEmail`,`bb_teamplayer_view`.`TeamID` AS `TeamID`,`bb_teamplayer_view`.`TeamName` AS `TeamName`,`bb_teamplayer_view`.`ShortName` AS `TeamShortName`,`bb_teamplayer_view`.`Captain` AS `Captain`,`bb_teamplayer_view`.`TeamEmail` AS `TeamEmail`,`bb_teamplayer_view`.`Tel` AS `Tel`,`bb_teamplayer_view`.`Wechat` AS `Wechat`,`bb_teamplayer_view`.`LogoSrc` AS `LogoSrc`,`bb_teamplayer_view`.`TeamPhotoSrc` AS `TeamPhotoSrc`,`bb_teamplayer_view`.`SeasonID` AS `SeasonID`,`bb_season`.`CompetitionID` AS `CompetitionID`,`bb_statistics`.`Turnover` AS `Turnover`,`bb_statistics`.`Starter` AS `Starter`,`bb_matchteam_view`.`MatchName` AS `MatchName`,`bb_matchteam_view`.`Round` AS `Round` from (((`bb_statistics` join `bb_teamplayer_view` on((`bb_statistics`.`PlayerID` = `bb_teamplayer_view`.`PlayerID`))) join `bb_season` on((`bb_teamplayer_view`.`SeasonID` = `bb_season`.`ID`))) join `bb_matchteam_view` on(((`bb_statistics`.`MatchID` = `bb_matchteam_view`.`MatchID`) and (`bb_teamplayer_view`.`SeasonID` = `bb_matchteam_view`.`SeasonID`)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-04 11:56:59