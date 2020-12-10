CREATE DATABASE IF NOT EXISTS pogdb;
USE pogdb;

DROP TABLE IF EXISTS `login`;

CREATE TABLE `login` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt1` varchar(255) NOT NULL,
  `salt2` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `account`;

CREATE TABLE `account` (
  `userID` int NOT NULL,
  `following` int NOT NULL,
  `followers` int NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `videos`;

CREATE TABLE `videos` (
  `userID` int NOT NULL,
  `videoID` int NOT NULL AUTO_INCREMENT,
  `videoLocation` varchar(255) NOT NULL,
  `creator` varchar(45) NOT NULL,
  `title` varchar(45) NOT NULL,
  `likes` int NOT NULL,
  `dislikes` int NOT NULL,
  PRIMARY KEY (`videoID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `commentId` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `videoLocation` varchar(255) NOT NULL,
  `comment` varchar(140) NOT NULL,
  `username` varchar(45) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`commentId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
	`likeID` int NOT NULL AUTO_INCREMENT,
	`userID` int NOT NULL,
	`videoLocation` varchar(255) NOT NULL,
	`likeFlag` TINYINT(1) NOT NULL,
	PRIMARY KEY(`likeID`)
)
