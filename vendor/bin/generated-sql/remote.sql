
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- global_cache_match_variables
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_cache_match_variables`;

CREATE TABLE `global_cache_match_variables`
(
    `rule_id` bigint(20) unsigned NOT NULL,
    `variable_name` VARCHAR(100) NOT NULL,
    `variable_value` VARCHAR(200) NOT NULL,
    PRIMARY KEY (`rule_id`,`variable_name`,`variable_value`),
    CONSTRAINT `global_cache_match_variables_fk_e37047`
        FOREIGN KEY (`rule_id`)
        REFERENCES `global_cache_rules` (`rule_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- global_cache_rules
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_cache_rules`;

CREATE TABLE `global_cache_rules`
(
    `rule_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `local_ttl` int(10) unsigned NOT NULL,
    `global_ttl` int(10) unsigned NOT NULL,
    PRIMARY KEY (`rule_id`),
    UNIQUE INDEX `rule_id` (`rule_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- global_cached_requests
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_cached_requests`;

CREATE TABLE `global_cached_requests`
(
    `query_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `query_url_root` VARCHAR(400) NOT NULL,
    `query_response` LONGTEXT NOT NULL,
    `query_time` int(10) unsigned NOT NULL,
    PRIMARY KEY (`query_id`),
    UNIQUE INDEX `query_id` (`query_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- global_get_variables
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_get_variables`;

CREATE TABLE `global_get_variables`
(
    `query_id` bigint(20) unsigned NOT NULL,
    `variable_name` VARCHAR(100) NOT NULL,
    `variable_value` TEXT NOT NULL,
    PRIMARY KEY (`query_id`,`variable_name`),
    CONSTRAINT `global_get_variables_fk_c5c7f3`
        FOREIGN KEY (`query_id`)
        REFERENCES `global_cached_requests` (`query_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- global_subscriber_ips
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_subscriber_ips`;

CREATE TABLE `global_subscriber_ips`
(
    `subscriber_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `subscriber_ip` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`subscriber_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- global_cache_hit_record
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `global_cache_hit_record`;

CREATE TABLE `global_cache_hit_record`
(
    `record_id` bigint(20) unsigned NOT NULL,
    `hit_count` bigint(20) unsigned NOT NULL,
    `miss_count` bigint(20) unsigned NOT NULL,
    PRIMARY KEY (`record_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
