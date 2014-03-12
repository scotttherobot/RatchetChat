CREATE TABLE IF NOT EXISTS `user_locations` (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(11) NOT NULL,
   `latitude` FLOAT(10,6) NOT NULL,
   `longitude` FLOAT(10,6) NOT NULL,
   `date` INT(11) NOT NULL,
   PRIMARY KEY (`id`),
   KEY (`userid`),
   KEY `date` (`date`),
   CONSTRAINT `locations_userid_fk` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
