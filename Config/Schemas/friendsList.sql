-- A friends roster.

CREATE TABLE IF NOT EXISTS `friendships` (
   `usera` INT(11) NOT NULL,
   `userb` INT(11) NOT NULL,
   `status` ENUM('PENDING','ACCEPTED','REJECTED','REMOVED') 
    NOT NULL DEFAULT 'PENDING',
   KEY (`usera`),
   KEY (`userb`),
   UNIQUE KEY `ab` (`usera`, `userb`),
   UNIQUE KEY `ba` (`userb`, `usera`),
   CONSTRAINT `friendship_usera_fk` FOREIGN KEY (`usera`)
    REFERENCES users (`userid`) 
    ON UPDATE CASCADE ON DELETE CASCADE,
   CONSTRAINT `friendship_userb_fk` FOREIGN KEY (`userb`)
    REFERENCES users (`userid`) 
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
   
