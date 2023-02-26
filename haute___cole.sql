-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 26 fév. 2023 à 20:25
-- Version du serveur : 5.7.31
-- Version de PHP : 8.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `haute_école`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE `account` (
  `id` int(10) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `color`
--

CREATE TABLE `color` (
  `idC` int(10) UNSIGNED NOT NULL,
  `nameC` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

CREATE TABLE `course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(4) NOT NULL,
  `status` varchar(255) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id`, `name`, `code`, `status`) VALUES
(1, 'Base des réseaux', '1351', 'active'),
(2, 'Environnement et technologies du web', '1352', 'active'),
(3, 'SGBD (Système de gestion de bases de données)', '1353', 'active'),
(4, 'Création de sites web statiques', '1354', 'active'),
(5, 'Approche Design', '1355', 'active'),
(6, 'CMS - niveau 1', '1356', 'active'),
(7, 'Initiation à la programmation', '1357', 'active'),
(8, 'Activités professionnelles de formation', '1358', 'active'),
(9, 'Scripts clients', '1359', 'active'),
(10, 'Scripts serveurs', '1360', 'active'),
(11, 'Framework et POO côté Serveur', '1361', 'active'),
(12, 'Projet Web dynamique', '1362', 'active'),
(13, 'Veille technologique', '1363', 'active'),
(14, 'Epreuve intégrée', '1364', 'active'),
(15, 'Anglais UE1', '1783', 'active'),
(16, 'Anglais UE2', '1784', 'active'),
(17, 'Initiation aux bases de données', '1440', 'active'),
(18, 'Principes algorithmiques et programmation', '1442', 'active'),
(19, 'Programmation orientée objet', '1443', 'active'),
(20, 'Web : principes de base', '1444', 'active'),
(21, 'Techniques de gestion de projet', '1448', 'active'),
(22, 'Principes d’analyse informatique', '1449', 'active'),
(23, 'Eléments de statistique', '1755', 'active'),
(24, 'Structure des ordinateurs', '1808', 'active'),
(25, 'Gestion et exploitation de bases de données', '1811', 'active'),
(26, 'Mathématiques appliquées à l’informatique', '1807', 'active'),
(27, 'Bases des réseaux', '1323', 'active'),
(28, 'Projet d’analyse et de conception', '1450', 'active'),
(29, 'Information et communication professionnelle', '1754', 'active'),
(30, 'Produits logiciels de gestion intégrés', '1438', 'active'),
(31, 'Administration, gestion et sécurisation des réseaux', '1439', 'active'),
(32, 'Projet de développement SGBD', '1446', 'active'),
(33, 'Stage d’intégration professionnelle', '1451', 'active'),
(34, 'Projet d’intégration de développement', '1447', 'active'),
(35, 'Activités professionnelles de formation', '1452', 'active'),
(36, 'Epreuve intégrée de la section', '1453', 'active'),
(37, 'Organisation des entreprises et éléments de management', '1753', 'active'),
(38, 'Système d’exploitation', '1809', 'active'),
(39, 'Projet de développement web', '1812', 'active'),
(40, 'Notions de E-business', '1437', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `niveau_etude` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `name`, `niveau_etude`, `status`, `date_debut`, `date_fin`) VALUES
(1, 'BES Webdeveloper', 'BES', 'Active', '2022-01-01', '2025-03-31'),
(2, 'Bachelier en informatique de gestion', 'BAC', 'Active', '2022-01-01', '2027-03-31'),
(3, 'Bachelier en E-Business', 'BEB', 'Active', '2023-02-04', '2024-06-21'),
(4, 'Master en Informatique', 'MES', 'active', '2023-02-05', '2024-11-23');

-- --------------------------------------------------------

--
-- Structure de la table `formation_course`
--

CREATE TABLE `formation_course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `formationid` bigint(20) UNSIGNED NOT NULL,
  `courseid` bigint(20) UNSIGNED NOT NULL,
  `period` tinyint(3) UNSIGNED NOT NULL,
  `determinant` tinyint(1) NOT NULL DEFAULT '0',
  `prepreq` bigint(20) UNSIGNED DEFAULT NULL,
  `teacher` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `formation_course`
--

INSERT INTO `formation_course` (`id`, `formationid`, `courseid`, `period`, `determinant`, `prepreq`, `teacher`, `status`) VALUES
(1, 1, 1, 40, 0, NULL, NULL, 'active'),
(2, 1, 2, 40, 0, NULL, NULL, 'active'),
(3, 1, 3, 80, 0, NULL, NULL, 'active'),
(4, 1, 4, 160, 1, 2, NULL, 'active'),
(5, 1, 5, 80, 0, 2, NULL, 'active'),
(6, 1, 6, 40, 0, 2, NULL, 'active'),
(7, 1, 7, 40, 0, NULL, NULL, 'active'),
(8, 1, 9, 120, 1, 7, NULL, 'active'),
(9, 1, 10, 120, 1, 7, NULL, 'active'),
(10, 1, 11, 80, 1, 7, NULL, 'active'),
(11, 1, 12, 120, 1, 9, NULL, 'active'),
(12, 1, 8, 240, 0, 4, NULL, 'active'),
(13, 1, 13, 40, 1, NULL, NULL, 'active'),
(14, 1, 16, 80, 0, 15, NULL, 'active'),
(15, 1, 15, 80, 0, NULL, NULL, 'active'),
(16, 1, 14, 120, 1, 11, NULL, 'active'),
(17, 2, 17, 60, 0, NULL, NULL, 'active'),
(18, 2, 25, 60, 0, 17, NULL, 'active'),
(19, 2, 18, 120, 0, NULL, NULL, 'active'),
(20, 2, 20, 40, 0, NULL, NULL, 'active'),
(21, 2, 21, 40, 0, NULL, NULL, 'active'),
(22, 2, 22, 60, 0, NULL, NULL, 'active'),
(23, 2, 23, 40, 0, NULL, NULL, 'active'),
(24, 2, 24, 60, 0, NULL, NULL, 'active'),
(25, 2, 26, 60, 0, NULL, NULL, 'active'),
(26, 2, 27, 80, 0, NULL, NULL, 'active'),
(27, 2, 19, 120, 0, 19, NULL, 'active'),
(28, 2, 28, 100, 0, NULL, NULL, 'active'),
(29, 2, 16, 80, 0, NULL, NULL, 'active'),
(30, 2, 29, 40, 0, NULL, NULL, 'active'),
(31, 2, 30, 120, 0, NULL, NULL, 'active'),
(32, 2, 31, 100, 1, NULL, NULL, 'active'),
(33, 2, 32, 80, 1, 18, NULL, 'active'),
(34, 2, 33, 120, 0, NULL, NULL, 'active'),
(35, 2, 34, 60, 1, NULL, NULL, 'active'),
(36, 2, 35, 240, 1, 35, NULL, 'active'),
(37, 2, 36, 160, 1, 36, NULL, 'active'),
(38, 2, 38, 100, 0, 24, NULL, 'active'),
(39, 2, 39, 100, 1, 20, NULL, 'active'),
(40, 2, 40, 100, 1, 20, NULL, 'active'),
(41, 2, 13, 40, 1, NULL, NULL, 'active');

-- --------------------------------------------------------

--
-- Structure de la table `migration`
--

CREATE TABLE `migration` (
  `id` int(10) UNSIGNED NOT NULL,
  `lasttime` bigint(20) UNSIGNED NOT NULL,
  `filename` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `migration`
--

INSERT INTO `migration` (`id`, `lasttime`, `filename`) VALUES
(1, 1675272700, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(3, 'banni'),
(2, 'etudiant'),
(4, 'invite');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `birthdate` datetime NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `lastlogin` datetime NOT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nom`, `prenom`, `email`, `pays`, `updated`, `birthdate`, `phone`, `address`, `created`, `lastlogin`, `admin`, `image`) VALUES
(1, 'Shilpa', '$2y$10$Hyluo4m69fr1lBkcAqC9R.YkbaH/roqxYGDRzz0H9jnKit6OWkyXa', 'Shetty13', 'Shilpa', 'shilpa@gmail.com', 'Indian', '2023-01-28 00:55:48', '2022-06-30 00:00:00', '23568657', '118/241 , reu de beez,5000 namur', '2023-01-11 20:20:36', '2023-02-06 19:38:58', NULL, 'image\\user\\photo\\1.jpg'),
(2, 'Karishma', '$2y$10$QpgXtL9Fgo5bXzVlsxqige2e8B/A7xujBaFnEdVthxtSc7/np1IyC', 'Kathar', 'Karishma', 'karishma@gmail.com', 'Russian', NULL, '2022-05-05 00:00:00', '23568657', '118/241 , reu de bruxelles,8000 bruges', '2023-01-11 20:23:44', '2023-01-11 20:27:57', NULL, 'image\\user\\photo\\2.jpg'),
(3, 'Amrita', '$2y$10$Aj04dVb8awZU6rsrW1sceeYkO2yFAOsTr5Ml8O7qrGdlCtrSMOd02', 'Singh', 'Amrita', 'amrita@hotmail.com', 'Italian', NULL, '2021-09-08 00:00:00', '456865769', '118/241 , reu de bruxelles,8000 bruges', '2023-01-14 09:48:51', '2023-01-14 09:49:05', NULL, 'image\\user\\photo\\3.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `user_course`
--

CREATE TABLE `user_course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `courseid` bigint(20) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE `user_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `userid` int(10) UNSIGNED NOT NULL,
  `roleid` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user_role`
--

INSERT INTO `user_role` (`id`, `userid`, `roleid`, `created`) VALUES
(1, 1, 4, '2023-01-14 09:43:23'),
(2, 3, 4, '2023-01-14 09:49:05');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Index pour la table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`idC`),
  ADD UNIQUE KEY `nameC` (`nameC`);

--
-- Index pour la table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formation_course`
--
ALTER TABLE `formation_course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `formationid_2` (`formationid`,`courseid`),
  ADD KEY `formationid` (`formationid`),
  ADD KEY `courseid` (`courseid`),
  ADD KEY `teacher` (`teacher`);

--
-- Index pour la table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `user_course`
--
ALTER TABLE `user_course`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`,`courseid`);

--
-- Index pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userid` (`userid`,`roleid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `account`
--
ALTER TABLE `account`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `color`
--
ALTER TABLE `color`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `course`
--
ALTER TABLE `course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `formation_course`
--
ALTER TABLE `formation_course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `migration`
--
ALTER TABLE `migration`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_course`
--
ALTER TABLE `user_course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
