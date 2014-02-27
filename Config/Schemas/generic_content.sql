CREATE TABLE IF NOT EXISTS `content` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(7) NOT NULL,
   `date` INT(11) NOT NULL,
   `type` ENUM('POST', 'PAGE') NOT NULL,
   `private` INT(1) NOT NULL DEFAULT 0,
   `comments` INT(1) NOT NULL DEFAULT 0,
   `title` TEXT NOT NULL,
   `body` TEXT NOT NULL,
   `leader` INT(11) DEFAULT NULL,
   PRIMARY KEY (`id`),
   KEY `userid` (`userid`),
   CONSTRAINT `content_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `comments` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(7) NOT NULL,
   `contentid` INT(11) NOT NULL,
   `date` INT(11) NOT NULL,
   `body` TEXT,
   PRIMARY KEY (`id`),
   KEY (`contentid`),
   CONSTRAINT `comment_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
   CONSTRAINT `comment_contentid_fk` FOREIGN KEY (`contentid`) REFERENCES `content` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
