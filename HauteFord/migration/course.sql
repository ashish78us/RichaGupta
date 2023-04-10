CREATE TABLE IF NOT EXISTS `course` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
   `code` varchar(4) NOT NULL,
   PRIMARY KEY (`id`),
    UNIQUE KEY `code` (`code`)
    ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;

INSERT INTO `course` (`id`, `name`, `code`) VALUES
    (1, 'Base des réseaux', '1351'),
    (2, 'Environnement et technologies du web', '1352'),
    (3, 'SGBD (Système de gestion de bases de données)', '1353'),
    (4, 'Création de sites web statiques', '1354'),
    (5, 'Approche Design', '1355'),
    (6, 'CMS - niveau 1', '1356'),
    (7, 'Initiation à la programmation', '1357'),
    (8, 'Activités professionnelles de formation', '1358'),
    (9, 'Scripts clients', '1359'),
    (10, 'Scripts serveurs', '1360'),
    (11, 'Framework et POO côté Serveur', '1361'),
    (12, 'Projet Web dynamique', '1362'),
    (13, 'Veille technologique', '1363'),
    (14, 'Epreuve intégrée', '1364'),
    (15, 'Anglais UE1', '1783'),
    (16, 'Anglais UE2', '1784'),
    (17, 'Initiation aux bases de données', '1440'),
    (18, 'Principes algorithmiques et programmation', '1442'),
    (19, 'Programmation orientée objet', '1443'),
    (20, 'Web : principes de base', '1444'),
    (21, 'Techniques de gestion de projet', '1448'),
    (22, 'Principes d’analyse informatique', '1449'),
    (23, 'Eléments de statistique', '1755'),
    (24, 'Structure des ordinateurs', '1808'),
    (25, 'Gestion et exploitation de bases de données', '1811'),
    (26, 'Mathématiques appliquées à l’informatique', '1807'),
    (27, 'Bases des réseaux', '1323'),
    (28, 'Projet d’analyse et de conception', '1450'),
    (29, 'Information et communication professionnelle', '1754'),
    (30, 'Produits logiciels de gestion intégrés', '1438'),
    (31, 'Administration, gestion et sécurisation des réseaux', '1439'),
    (32, 'Projet de développement SGBD', '1446'),
    (33, 'Stage d’intégration professionnelle', '1451'),
    (34, 'Projet d’intégration de développement', '1447'),
    (35, 'Activités professionnelles de formation', '1452'),
    (36, 'Epreuve intégrée de la section', '1453'),
    (37, 'Organisation des entreprises et éléments de management', '1753'),
    (38, 'Système d’exploitation', '1809'),
    (39, 'Projet de développement web', '1812'),
    (40, 'Notions de E-business', '1437');


CREATE TABLE IF NOT EXISTS `formation` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL UNIQUE,
    `niveau_etude` VARCHAR(255) NOT NULL,
   `status` varchar(255) NOT NULL,
   `date_debut` DATE NOT NULL,
    `date_fin` DATE NOT NULL,
     PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO `formation` (`id`, `name`, `niveau_etude` ,`status`,`date_debut`,`date_fin`) VALUES
(1, 'BES Webdeveloper','BES','Active', '2022-01-01', '2025-03-31' ),
(2, 'Bachelier en informatique de gestion','BAC','Active', '2022-01-01', '2027-03-31');

CREATE TABLE IF NOT EXISTS `formation_course` (
    CREATE TABLE IF NOT EXISTS `formation_course` (
    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `formationid` bigint(20) UNSIGNED NOT NULL,
    `courseid` bigint(20) UNSIGNED NOT NULL,
    `period` tinyint(3) UNSIGNED NOT NULL,
    `determinant` tinyint(1) NOT NULL DEFAULT '0',
    `prepreq` bigint(20) UNSIGNED DEFAULT NULL,
    `teacher` bigint(20) UNSIGNED DEFAULT NULL,
   PRIMARY KEY (`id`),
    UNIQUE KEY `formationid_2` (`formationid`,`courseid`),
    KEY `formationid` (`formationid`),
    KEY `courseid` (`courseid`),
    KEY `teacher` (`teacher`)
    ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;
   PRIMARY KEY (`id`),
    UNIQUE KEY `formationid_2` (`formationid`,`courseid`),
    KEY `formationid` (`formationid`),
    KEY `courseid` (`courseid`),
    KEY `teacher` (`teacher`)
    ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;
--
-- Déchargement des données de la table `formation_course`
--
INSERT INTO `formation_course` (`id`, `formationid`, `courseid`, `period`, `determinant`, `prepreq`, `teacher`) VALUES
    (1, 1, 1, 40, 0, NULL, NULL),
    (2, 1, 2, 40, 0, NULL, NULL),
    (3, 1, 3, 80, 0, NULL, NULL),
    (4, 1, 4, 160, 1, 2, NULL),
    (5, 1, 5, 80, 0, 2, NULL),
    (6, 1, 6, 40, 0, 2, NULL),
    (7, 1, 7, 40, 0, NULL, NULL),
    (8, 1, 9, 120, 1, 7, NULL),
    (9, 1, 10, 120, 1, 7, NULL),
    (10, 1, 11, 80, 1, 7, NULL),
    (11, 1, 12, 120, 1, 9, NULL),
    (12, 1, 8, 240, 0, 4, NULL),
    (13, 1, 13, 40, 1, NULL, NULL),
    (14, 1, 16, 80, 0, 15, NULL),
    (15, 1, 15, 80, 0, NULL, NULL),
    (16, 1, 14, 120, 1, 11, NULL),
    (17, 2, 17, 60, 0, NULL, NULL),
    (18, 2, 25, 60, 0, 17, NULL),
    (19, 2, 18, 120, 0, NULL, NULL),
    (20, 2, 20, 40, 0, NULL, NULL),
    (21, 2, 21, 40, 0, NULL, NULL),
    (22, 2, 22, 60, 0, NULL, NULL),
    (23, 2, 23, 40, 0, NULL, NULL),
    (24, 2, 24, 60, 0, NULL, NULL),
    (25, 2, 26, 60, 0, NULL, NULL),
    (26, 2, 27, 80, 0, NULL, NULL),
    (27, 2, 19, 120, 0, 19, NULL),
    (28, 2, 28, 100, 0, NULL, NULL),
    (29, 2, 16, 80, 0, NULL, NULL),
    (30, 2, 29, 40, 0, NULL, NULL),
    (31, 2, 30, 120, 0, NULL, NULL),
    (32, 2, 31, 100, 1, NULL, NULL),
    (33, 2, 32, 80, 1, 18, NULL),
    (34, 2, 33, 120, 0, NULL, NULL),
    (35, 2, 34, 60, 1, NULL, NULL),
    (36, 2, 35, 240, 1, 35, NULL),
    (37, 2, 36, 160, 1, 36, NULL),
    (38, 2, 38, 100, 0, 24, NULL),
    (39, 2, 39, 100, 1, 20, NULL),
    (40, 2, 40, 100, 1, 20, NULL),
    (41, 2, 13, 40, 1, NULL, NULL);
