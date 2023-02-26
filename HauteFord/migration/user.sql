CREATE TABLE `user` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `nom` varchar(255) NOT NULL,
    `prenom` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `pays` varchar(255) NOT NULL,
    `birthdate` datetime NOT NULL,
    `phone` varchar(255) NOT NULL,
    `address` varchar(255) NOT NULL,
    `created` datetime NOT NULL,
    `lastlogin` datetime NOT NULL,
    `admin` tinyint(1) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;