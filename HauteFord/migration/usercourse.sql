CREATE TABLE IF NOT EXISTS `user_course` (
     `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
     `userid` int(10) UNSIGNED NOT NULL,
     `courseid` bigint(20) UNSIGNED NOT NULL,
     `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     UNIQUE KEY `userid` (`userid`,`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
