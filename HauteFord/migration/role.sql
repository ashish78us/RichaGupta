CREATE TABLE `role` (
     `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
     `name` varchar(255) NOT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `role` (`id`, `name`) VALUES
       (1, 'admin'),
       (2, 'etudiant'),
       (3, 'banni'),
       (4, 'invite');
