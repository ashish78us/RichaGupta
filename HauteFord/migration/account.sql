CREATE TABLE `account` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `userid` int(10) unsigned NOT NULL,
    `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
    `created` datetime NOT NULL,
    `updated` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `account`
    ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`id`);
