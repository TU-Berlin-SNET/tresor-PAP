CREATE DATABASE IF NOT EXISTS `tresor_papdb` COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user`
(
	`enterprise_id` INT(16) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`enterprise` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL
)ENGINE=InnoDB;

INSERT INTO `user` (`enterprise_id`, `enterprise`, `password`) VALUES ('1', 'DHZB', '$1$e.1.Ps5.$5qw/dVVNvQvS9Q9mDPbYP1');

CREATE TABLE IF NOT EXISTS `user_service_booked`
(
	`enterprise_id` INT(16) unsigned NOT NULL,
	`service_id` VARCHAR(255) NOT NULL,
	PRIMARY KEY(`enterprise_id`, `service_id`),
	FOREIGN KEY (`enterprise_id`) REFERENCES user(`enterprise_id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

INSERT INTO `user_service_booked` (`enterprise_id`, `service_id`) VALUES ('1', 'unknown');

CREATE TABLE IF NOT EXISTS `ldap_config`
(
	`enterprise_id` INT(16) unsigned NOT NULL PRIMARY KEY,
	`ldap_host` VARCHAR(255) NOT NULL,
	`ldap_port` SMALLINT NOT NULL,
	`ldap_rdn` VARCHAR(255) NOT NULL,
	`ldap_password` VARCHAR(255) NOT NULL,
	`ldap_search_string` VARCHAR(255) NOT NULL,
	FOREIGN KEY (`enterprise_id`) REFERENCES `user`(`enterprise_id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;