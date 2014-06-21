-- Tables for PhotoDirector game
-- By Scott Vanderlind

-- The games meta table
CREATE TABLE IF NOT EXISTS games (
   `gameid` INT(11) NOT NULL AUTO_INCREMENT,
   `userid` INT(11) NOT NULL,
   `title` VARCHAR(100) NOT NULL,
   `challenge` VARCHAR(140) NOT NULL,
   `status` ENUM('OPEN', 'TIMEOUT', 'COMPLETE'),
   `starts` INT(11) NOT NULL,
   `ends` INT(11) NOT NULL,
   PRIMARY KEY (`gameid`),
   KEY (`userid`),
   KEY (`status`),
   CONSTRAINT `games_user_fk` FOREIGN KEY (`userid`) 
    REFERENCES users (`userid`) 
    ON UPDATE CASCADE 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS game_participants (
   `gameid` INT(11) NOT NULL,
   `userid` INT(11) NOT NULL,
   KEY (`gameid`),
   CONSTRAINT `game_participant_user_fk` FOREIGN KEY (`userid`)
    REFERENCES users (`userid`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
   CONSTRAINT `game_participants_game_fk` FOREIGN KEY (`gameid`)
    REFERENCES games (`gameid`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS game_submissions (
   `submissionid` INT(11) NOT NULL AUTO_INCREMENT,
   `gameid` INT(11) NOT NULL,
   `userid` INT(11) NOT NULL,
   `medid` INT(11) NOT NULL,
   `time` INT(11) NOT NULL,
   `winning` INT(1) NOT NULL DEFAULT 0,
   PRIMARY KEY(`submissionid`),
   KEY (`gameid`),
   CONSTRAINT `game_submission_user_fk` FOREIGN KEY (`userid`)
    REFERENCES users (`userid`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
   CONSTRAINT `game_submission_game_fk` FOREIGN KEY (`gameid`)
    REFERENCES games (`gameid`)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
   CONSTRAINT `game_submission_medid_fk` FOREIGN KEY (`medid`)
    REFERENCES media (`medid`)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

