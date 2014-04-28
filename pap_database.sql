CREATE TABLE `user`
(
	`enterprise_id` INT(16) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`enterprise` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL
)ENGINE=InnoDB;

CREATE TABLE `user_service_booked`
(
	`enterprise_id` INT(16) unsigned NOT NULL,
	`service_id` INT(16) unsigned NOT NULL,
	PRIMARY KEY(`enterprise_id`, `service_id`),
	FOREIGN KEY (`enterprise_id`) REFERENCES user(`enterprise_id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

CREATE TABLE `ldap_config`
(
	`enterprise_id` INT(16) unsigned NOT NULL PRIMARY KEY,
	`ldap_host` VARCHAR(255) NOT NULL,
	`ldap_port` SMALLINT NOT NULL,
	`ldap_rdn` VARCHAR(255) NOT NULL,
	`ldap_password` VARCHAR(255) NOT NULL,
	`ldap_search_string` VARCHAR(255) NOT NULL,
	FOREIGN KEY (`enterprise_id`) REFERENCES `user`(`enterprise_id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;