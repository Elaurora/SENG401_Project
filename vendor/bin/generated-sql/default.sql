
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- cache_match_variables
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cache_match_variables`;

CREATE TABLE `cache_match_variables`
(
    `rule_id` bigint(20) unsigned NOT NULL,
    `variable_name` VARCHAR(100) NOT NULL,
    `variable_value` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`rule_id`,`variable_name`,`variable_value`),
    CONSTRAINT `cache_match_variables_fk_857274`
        FOREIGN KEY (`rule_id`)
        REFERENCES `cache_rules` (`rule_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- cache_rules
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cache_rules`;

CREATE TABLE `cache_rules`
(
    `rule_id` bigint(20) unsigned NOT NULL,
    `local_ttl` int(10) unsigned NOT NULL,
    `global_ttl` int(10) unsigned NOT NULL,
    PRIMARY KEY (`rule_id`),
    UNIQUE INDEX `rule_id` (`rule_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- cached_requests
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cached_requests`;

CREATE TABLE `cached_requests`
(
    `query_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `query_url_root` VARCHAR(400) NOT NULL,
    `query_response` LONGTEXT NOT NULL,
    `query_time` int(10) unsigned NOT NULL,
    PRIMARY KEY (`query_id`),
    UNIQUE INDEX `query_id` (`query_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- get_variables
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `get_variables`;

CREATE TABLE `get_variables`
(
    `query_id` bigint(20) unsigned NOT NULL,
    `variable_name` VARCHAR(100) NOT NULL,
    `variable_value` TEXT NOT NULL,
    PRIMARY KEY (`query_id`,`variable_name`),
    CONSTRAINT `get_variables_fk_53fde7`
        FOREIGN KEY (`query_id`)
        REFERENCES `cached_requests` (`query_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- cache_hit_record
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `cache_hit_record`;

CREATE TABLE `cache_hit_record`
(
    `record_id` bigint(20) unsigned NOT NULL,
    `hit_count` bigint(20) unsigned NOT NULL,
    `miss_count` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`record_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
