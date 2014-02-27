-- Tables for message threads
-- By Scott Vanderlind
-- Note, requires users table.

-- The threads meta table
CREATE TABLE IF NOT EXISTS threads (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `name` VARCHAR(100) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- The participants meta table
CREATE TABLE IF NOT EXISTS participants (
   `threadid` INT(11) NOT NULL,
   `userid` INT(11) NOT NULL,
   `status` ENUM('IN','OUT','LEFT') NOT NULL DEFAULT 'IN',
   `notifications` ENUM('ON','OFF') NOT NULL DEFAULT 'ON',
   `joined` INT(11) NOT NULL,
   `left` INT(11) DEFAULT NULL,
   KEY (`threadid`),
   KEY (`userid`),
   UNIQUE (`userid`,`threadid`,`joined`),
   CONSTRAINT `participant_user_fk` FOREIGN KEY (`userid`) REFERENCES users (`userid`) ON UPDATE CASCADE,
   CONSTRAINT `participant_thread_fk` FOREIGN KEY (`threadid`) REFERENCES threads (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- The messages table
CREATE TABLE IF NOT EXISTS messages (
   `id` INT(11) NOT NULL AUTO_INCREMENT,
   `threadid` INT(11) NOT NULL,
   `userid` INT(11) NOT NULL,
   `body` TEXT DEFAULT NULL,
   `medid` INT(11) DEFAULT NULL,
   `sent` INT(11) NOT NULL,
   PRIMARY KEY (`id`),
   KEY (`threadid`),
   KEY (`userid`),
   KEY (`sent`),
   CONSTRAINT `messages_user_fk` FOREIGN KEY (`userid`) REFERENCES users (`userid`) ON UPDATE CASCADE,
   CONSTRAINT `messages_thread_fk` FOREIGN KEY (`threadid`) REFERENCES threads (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT `messages_medid_fk` FOREIGN KEY (`medid`) REFERENCES media (`medid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- PUSH subscription keys
CREATE TABLE IF NOT EXISTS subscriptions (
   `userid` INT(11) NOT NULL,
   `type` ENUM('GCM','IOS') NOT NULL,
   `uuid` TEXT,
   `notifications` ENUM('ON','OFF') NOT NULL DEFAULT 'ON',
   PRIMARY KEY (`userid`),
   KEY (`type`),
   KEY (`notifications`),
   CONSTRAINT `subscriptions_user_fk` FOREIGN KEY (`userid`) REFERENCES users (`userid`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

