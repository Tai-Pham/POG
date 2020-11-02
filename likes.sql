DROP TABLE IF EXISTS `likes`;

CREATE TABLE `likes` (
	`likeID` int NOT NULL AUTO_INCREMENT,
	`userID` int NOT NULL,
	`videoLocation` varchar(255) NOT NULL,
	`likeFlag` TINYINT(1) NOT NULL,
	PRIMARY KEY(`likeID`)
)
