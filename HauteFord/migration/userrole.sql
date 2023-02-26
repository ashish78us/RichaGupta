CREATE TABLE IF NOT EXISTS `user_role` (
     `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
     `userid` int(10) UNSIGNED NOT NULL,
     `roleid` int(10) UNSIGNED NOT NULL,
     `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     UNIQUE KEY `userid` (`userid`,`roleid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
