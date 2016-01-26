SET FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT NOT NULL AUTO_INCREMENT
  COMMENT '',
  `email` VARCHAR(200) NOT NULL
  COMMENT '',
  `password` VARCHAR(500) NOT NULL
  COMMENT '',
  `phone_number` VARCHAR(60) NULL
  COMMENT '',
  `two_factor_enabled` INT NOT NULL DEFAULT 0
  COMMENT '',
  `enabled_date` TIMESTAMP NULL     DEFAULT NOW()
  COMMENT '',
  `enabled` INT NOT NULL DEFAULT 0
  COMMENT '',
  `access_failed_count` INT NOT NULL DEFAULT 0
  COMMENT '',
  `username` VARCHAR(45) NOT NULL
  COMMENT '',
  PRIMARY KEY (`id`)
    COMMENT '',
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
    COMMENT '',
  UNIQUE INDEX `username_UNIQUE` (`username` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `role`;

CREATE TABLE IF NOT EXISTS `role` (
  `id` INT NOT NULL AUTO_INCREMENT
  COMMENT '',
  `name` VARCHAR(45) NOT NULL
  COMMENT '',
  PRIMARY KEY (`id`)
    COMMENT '',
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `provider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `provider`;

CREATE TABLE IF NOT EXISTS `provider` (
  `id` INT NOT NULL AUTO_INCREMENT
  COMMENT '',
  `name` VARCHAR(200) NOT NULL
  COMMENT '',
  `app_key` VARCHAR(500) NOT NULL
  COMMENT '',
  `secret_key` VARCHAR(300) NOT NULL
  COMMENT '',
  PRIMARY KEY (`id`)
    COMMENT '',
  UNIQUE INDEX `provider_name_UNIQUE` (`name` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_role`;

CREATE TABLE IF NOT EXISTS `user_role` (
  `role_id` INT NOT NULL
  COMMENT '',
  `user_id` INT NOT NULL
  COMMENT '',
  PRIMARY KEY (`role_id`, `user_id`)
    COMMENT '',
  INDEX `fk_user_role_user1_idx` (`user_id` ASC)
    COMMENT '',
  CONSTRAINT `fk_user_role_role`
  FOREIGN KEY (`role_id`)
  REFERENCES `role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_role_user1`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `user_provider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_provider`;

CREATE TABLE IF NOT EXISTS `user_provider` (
  `user_id` INT NOT NULL
  COMMENT '',
  `provider_id` INT NOT NULL
  COMMENT '',
  `provider_user_id` VARCHAR(500) NOT NULL
  COMMENT '',
  PRIMARY KEY (`user_id`, `provider_id`)
    COMMENT '',
  INDEX `fk_user_provider_provider1_idx` (`provider_id` ASC)
    COMMENT '',
  CONSTRAINT `fk_user_provider_user1`
  FOREIGN KEY (`user_id`)
  REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_provider_provider1`
  FOREIGN KEY (`provider_id`)
  REFERENCES `provider` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB;

-- mt_role_save(?)
DROP PROCEDURE IF EXISTS `mt_role_save`;
DELIMITER //
CREATE PROCEDURE mt_role_save(IN `@name` VARCHAR(255))
  BEGIN
    REPLACE INTO role (`name`)
    VALUES (`@name`);

    SELECT LAST_INSERT_ID() AS id;
  END//
DELIMITER ;

-- mt_provider_save(?, ?, ?, ?);
DROP PROCEDURE IF EXISTS `mt_provider_save`;
DELIMITER //
CREATE PROCEDURE mt_provider_save(IN `@name` VARCHAR(255), IN `@app_key` VARCHAR(255),
                                  IN `@secret_key`        VARCHAR(255))
  BEGIN
    REPLACE INTO provider (`name`, `app_key`, `secret_key`)
    VALUES (`@name`, `@app_key`, `@secret_key`);

    SELECT LAST_INSERT_ID() AS id;
  END//
DELIMITER ;

-- mt_user_save(?, ?, ?, ?, ?, ?, ?, ?)
DROP PROCEDURE IF EXISTS `mt_user_save`;
DELIMITER //
CREATE PROCEDURE mt_user_save(
  IN `@email`              VARCHAR(500),
  IN `@password` VARCHAR(500),
  IN `@phone_number`       VARCHAR(60),
  IN `@two_factor_enabled` INT,
  IN `@enabled_date`       DATETIME,
  IN `@enabled` INT,
  IN `@access_failed_count` INT,
  IN `@username`           VARCHAR(45))
  BEGIN
    REPLACE INTO `user` (`password`, `phone_number`, `two_factor_enabled`, `enabled_date`, `enabled`, `access_failed_count`, `username`, `email`)
    VALUES (`@password`, `@phone_number`, `@two_factor_enabled`, `@enabled_date`, `@enabled`, `@access_failed_count`,
            `@username`, `@email`);

    SELECT LAST_INSERT_ID() AS id;
  END//
DELIMITER ;

-- mt_role_get(id)
DROP PROCEDURE IF EXISTS `mt_role_get`;
DELIMITER //
CREATE PROCEDURE mt_role_get(IN `@id` INT)
  BEGIN
    SELECT id, name
    FROM `role`
    WHERE `@id` = id OR `@id` IS NULL;
  END//
DELIMITER ;

-- mt_provider_get(id)
DROP PROCEDURE IF EXISTS `mt_provider_get`;
DELIMITER //
CREATE PROCEDURE mt_provider_get(IN `@id` INT)
  BEGIN
    SELECT id, name, app_key, secret_key
    FROM `provider`
    WHERE `@id` = id OR `@id` IS NULL;
  END//
DELIMITER ;

-- mt_provider_user_get(?, ?, ?)
DROP PROCEDURE IF EXISTS `mt_provider_user_get`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_get(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    SELECT `provider`.id, `provider`.name, provider_user_id, `user`.id AS user_id
    FROM `provider`
    	INNER JOIN `user_provider` ON `provider`.id = `user_provider`.provider_id
    	INNER JOIN `user` ON `user_provider`.user_id = `user`.id
    WHERE ( user_id=`@user_id` OR `@user_id` IS NULL )
    	AND ( provider_id=`@provider_id` OR `@provider_id` IS NULL )
    	AND ( provider_user_id = `@provider_user_id` OR `@provider_user_id` IS NULL );
  END//
DELIMITER ;

-- mt_user_get(id, username, email)
DROP PROCEDURE IF EXISTS `mt_user_get`;
DELIMITER //
CREATE PROCEDURE mt_user_get(IN `@id` INT, IN `@username` VARCHAR(500), IN `@email` VARCHAR(500))
  BEGIN
    SELECT id, email, password, phone_number, two_factor_enabled, enabled_date, enabled, access_failed_count, username
    FROM `user`
    WHERE (`@id` = id OR `@id` IS NULL)
          AND (`@username` = username OR `@username` IS NULL)
          AND (`@email` = email OR `@email` IS NULL);
  END//
DELIMITER ;

-- mt_user_save_role
DROP PROCEDURE IF EXISTS `mt_user_save_role`;
DELIMITER //
CREATE PROCEDURE mt_user_save_role(IN `@user_id` INT, IN `@role_id` INT)
  BEGIN
    INSERT INTO user_role(user_id, role_id)
    VALUES(`@user_id`, `@role_id`);
  END//
DELIMITER ;

-- mt_provider_user_save
DROP PROCEDURE IF EXISTS `mt_provider_user_save`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_save(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    INSERT INTO user_provider(user_id, provider_id, `provider_user_id`)
    VALUES(`@user_id`, `@provider_id`, `@provider_user_id`);
  END//
DELIMITER ;

-- mt_user_delete_role(?,?)
DROP PROCEDURE IF EXISTS `mt_user_delete_role`;
DELIMITER //
CREATE PROCEDURE mt_user_delete_role(IN `@user_id` INT, IN `@role_id` INT)
  BEGIN
    DELETE FROM user_role
    WHERE user_id=`@user_id` AND role_id=`@role_id`;
  END//
DELIMITER ;

-- mt_provider_user_delete(?,?)
DROP PROCEDURE IF EXISTS `mt_provider_user_delete`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_delete(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    DELETE FROM user_provider
    WHERE ( user_id=`@user_id` OR `@user_id` IS NULL )
    	AND ( provider_id=`@provider_id` OR `@provider_id` IS NULL )
    	AND ( provider_user_id = `@provider_user_id` OR `@provider_user_id` IS NULL );
  END//
DELIMITER ;

-- mt_user_delete(?)
DROP PROCEDURE IF EXISTS `mt_user_delete`;
DELIMITER //
CREATE PROCEDURE mt_user_delete(IN `@id` INT)
  BEGIN
    DELETE FROM user WHERE id=`@id`;
  END//
DELIMITER ;

-- mt_role_delete(?)
DROP PROCEDURE IF EXISTS `mt_role_delete`;
DELIMITER //
CREATE PROCEDURE mt_role_delete(IN `@id` INT)
  BEGIN
    DELETE FROM role WHERE id=`@id`;
  END//
DELIMITER ;

-- mt_provider_delete(?)
DROP PROCEDURE IF EXISTS `mt_provider_delete`;
DELIMITER //
CREATE PROCEDURE mt_provider_delete(IN `@id` INT)
  BEGIN
    DELETE FROM provider WHERE id=`@id`;
  END//
DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;
