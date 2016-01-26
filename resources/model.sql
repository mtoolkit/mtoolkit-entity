SET FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mt_user`;

CREATE TABLE IF NOT EXISTS `mt_user` (
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
  `enabled_date` DATETIME NULL     DEFAULT '1970-01-01'
  COMMENT '',
  `last_login_date` DATETIME NULL     DEFAULT '1970-01-01'
  COMMENT '',
  `insert_date` TIMESTAMP NULL     DEFAULT NOW()
  COMMENT '',
  `enabled` INT NOT NULL DEFAULT 0
  COMMENT '',
  `access_failed_count` INT NOT NULL DEFAULT 0
  COMMENT '',
  `username` VARCHAR(45) NOT NULL
  COMMENT '',
  PRIMARY KEY (`id`)
    COMMENT '',
  UNIQUE INDEX `mt_email_UNIQUE` (`email` ASC)
    COMMENT '',
  UNIQUE INDEX `mt_username_UNIQUE` (`username` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mt_role`;

CREATE TABLE IF NOT EXISTS `mt_role` (
  `id` INT NOT NULL AUTO_INCREMENT
  COMMENT '',
  `name` VARCHAR(45) NOT NULL
  COMMENT '',
  PRIMARY KEY (`id`)
    COMMENT '',
  UNIQUE INDEX `mt_name_UNIQUE` (`name` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `provider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mt_provider`;

CREATE TABLE IF NOT EXISTS `mt_provider` (
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
  UNIQUE INDEX `mt_provider_name_UNIQUE` (`name` ASC)
    COMMENT ''
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mt_user_role`;

CREATE TABLE IF NOT EXISTS `mt_user_role` (
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
  REFERENCES `mt_role` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_role_user1`
  FOREIGN KEY (`user_id`)
  REFERENCES `mt_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `user_provider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mt_user_provider`;

CREATE TABLE IF NOT EXISTS `mt_user_provider` (
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
  REFERENCES `mt_user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_provider_provider1`
  FOREIGN KEY (`provider_id`)
  REFERENCES `mt_provider` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)
  ENGINE = InnoDB;

-- mt_role_save(?)
DROP PROCEDURE IF EXISTS `mt_role_save`;
DELIMITER //
CREATE PROCEDURE mt_role_save(IN `@name` VARCHAR(255))
  BEGIN
    REPLACE INTO mt_role (`name`)
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
    REPLACE INTO mt_provider (`name`, `app_key`, `secret_key`)
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
    REPLACE INTO `mt_user` (`password`, `phone_number`, `two_factor_enabled`, `enabled_date`, `enabled`, `access_failed_count`, `username`, `email`)
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
    FROM `mt_role`
    WHERE `@id` = id OR `@id` IS NULL;
  END//
DELIMITER ;

-- mt_provider_get(id)
DROP PROCEDURE IF EXISTS `mt_provider_get`;
DELIMITER //
CREATE PROCEDURE mt_provider_get(IN `@id` INT)
  BEGIN
    SELECT id, name, app_key, secret_key
    FROM `mt_provider`
    WHERE `@id` = id OR `@id` IS NULL;
  END//
DELIMITER ;

-- mt_provider_user_get(?, ?, ?)
DROP PROCEDURE IF EXISTS `mt_provider_user_get`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_get(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    SELECT `mt_provider`.id, `mt_provider`.name, provider_user_id, `mt_user`.id AS user_id
    FROM `mt_provider`
    	INNER JOIN `mt_user_provider` ON `mt_provider`.id = `mt_user_provider`.provider_id
    	INNER JOIN `mt_user` ON `mt_user_provider`.user_id = `mt_user`.id
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
    SELECT id, email, password, phone_number, two_factor_enabled, enabled_date, enabled, access_failed_count, username, last_login_date, insert_date
    FROM `mt_user`
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
    INSERT INTO mt_user_role(user_id, role_id)
    VALUES(`@user_id`, `@role_id`);
  END//
DELIMITER ;

-- mt_provider_user_save
DROP PROCEDURE IF EXISTS `mt_provider_user_save`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_save(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    INSERT INTO mt_user_provider(user_id, provider_id, `provider_user_id`)
    VALUES(`@user_id`, `@provider_id`, `@provider_user_id`);
  END//
DELIMITER ;

-- mt_user_delete_role(?,?)
DROP PROCEDURE IF EXISTS `mt_user_delete_role`;
DELIMITER //
CREATE PROCEDURE mt_user_delete_role(IN `@user_id` INT, IN `@role_id` INT)
  BEGIN
    DELETE FROM mt_user_role
    WHERE user_id=`@user_id` AND role_id=`@role_id`;
  END//
DELIMITER ;

-- mt_provider_user_delete(?,?)
DROP PROCEDURE IF EXISTS `mt_provider_user_delete`;
DELIMITER //
CREATE PROCEDURE mt_provider_user_delete(IN `@user_id` INT, IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN
    DELETE FROM mt_user_provider
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
    DELETE FROM mt_user WHERE id=`@id`;
  END//
DELIMITER ;

-- mt_role_delete(?)
DROP PROCEDURE IF EXISTS `mt_role_delete`;
DELIMITER //
CREATE PROCEDURE mt_role_delete(IN `@id` INT)
  BEGIN
    DELETE FROM mt_role WHERE id=`@id`;
  END//
DELIMITER ;

-- mt_provider_delete(?)
DROP PROCEDURE IF EXISTS `mt_provider_delete`;
DELIMITER //
CREATE PROCEDURE mt_provider_delete(IN `@id` INT)
  BEGIN
    DELETE FROM mt_provider WHERE id=`@id`;
  END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `mt_login`;
DELIMITER //
CREATE PROCEDURE mt_login(IN `@username` VARCHAR(45), IN `@password` VARCHAR(500))
  BEGIN
    DECLARE `@user_count` DOUBLE;

    SELECT COUNT(id) INTO `@user_count`
    FROM mt_user
    WHERE username=`@username` AND `password`=`@password`;

    IF `@user_count` = 1 THEN
      UPDATE mt_user SET last_login_date=NOW() WHERE username=`@username` AND `password`=`@password`;

      SELECT id, email, password, phone_number, two_factor_enabled, enabled_date, enabled, access_failed_count, username, last_login_date, insert_date
      FROM mt_user
      WHERE username=`@username` AND `password`=`@password`;
    ELSEIF `@user_count` = 0 THEN
      UPDATE mt_user set access_failed_count = access_failed_count + 1 WHERE username = `@username`;
    END IF;
  END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `mt_provider_login`;
DELIMITER //
CREATE PROCEDURE mt_provider_login(IN `@provider_id` INT, IN `@provider_user_id` VARCHAR(500))
  BEGIN

    DECLARE `@user_count` DOUBLE;

    SELECT COUNT(mt_provider.provider_user_id) INTO `@user_count`
    FROM mt_provider
    WHERE provider_id=`@provider_id` AND `provider_user_id`=`@provider_user_id`;

    IF `@user_count` = 1 THEN
      UPDATE mt_user
        INNER JOIN mt_user_provider
        SET last_login_date=NOW()
        WHERE provider_id=`@provider_id` AND `provider_user_id`=`@provider_user_id`;

      SELECT id, email, password, phone_number, two_factor_enabled, enabled_date, enabled, access_failed_count, username, last_login_date, insert_date
      FROM mt_user
        INNER JOIN mt_user_provider
      WHERE provider_id=`@provider_id` AND `provider_user_id`=`@provider_user_id`;
    ELSEIF `@user_count` = 0 THEN
      UPDATE mt_user
        INNER JOIN mt_user_provider
      SET access_failed_count = access_failed_count + 1
      WHERE provider_id=`@provider_id` AND `provider_user_id`=`@provider_user_id`;
    END IF;

  END//
DELIMITER ;

SET FOREIGN_KEY_CHECKS=1;
