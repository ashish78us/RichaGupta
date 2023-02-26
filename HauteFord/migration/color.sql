CREATE TABLE `color` (
        `idC` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `nameC` varchar(255) NOT NULL,
        PRIMARY KEY (`idC`),
        UNIQUE KEY `nameC` (`nameC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;