CREATE TABLE `migration` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `lasttime` bigint(20) unsigned NOT NULL,
    `filename` varchar(255) NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;