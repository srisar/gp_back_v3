DROP DATABASE IF EXISTS giggle_pig_v3;
CREATE DATABASE IF NOT EXISTS giggle_pig_v3 /*!40100 DEFAULT CHARACTER SET utf8 */;
USE giggle_pig_v3;

-- Dumping structure for table giggle_pig users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`
(
	`id`            int(11)                         NOT NULL AUTO_INCREMENT,
	`password_hash` varchar(100)                    NOT NULL,
	`email`         varchar(255)                    NOT NULL,
	`full_name`     varchar(255)                    NOT NULL,
	`profile_pic`   varchar(300)                    NULL,
	`role`          enum ('ADMIN','MANAGER','USER') NOT NULL DEFAULT 'USER',
	`status`        enum ('ACTIVE','INACTIVE')      NOT NULL DEFAULT 'ACTIVE',
	`created_at`    TIMESTAMP,
	`updated_at`    TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


INSERT IGNORE INTO `users` (`id`, `email`, `password_hash`, `full_name`, `role`, `profile_pic`, created_at, updated_at)
VALUES (1, 'admin@example.com', '$2y$10$JN/JQbRZ8zj6ReU5StNgc.AXIWuw7c8OexEk1Hlnh7/TBkuDzdyp2', 'Administrator', 'ADMIN', '', NOW(), NOW());

