-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 16 sep. 2024 à 11:39
-- Version du serveur : 10.3.29-MariaDB-0+deb10u1
-- Version de PHP : 8.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `employer`
--

-- --------------------------------------------------------

--
-- Structure de la table `dept`
--

CREATE TABLE `dept` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `lieu` varchar(25) NOT NULL,
  `n_dept` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `dept`
--

INSERT INTO `dept` (`id`, `nom`, `lieu`, `n_dept`) VALUES
(1, 'recherche', 'Rennes', 10),
(3, 'vente', 'Metz', 20),
(5, 'direction', 'Gif', 30),
(7, 'fabrication', 'Toulon', 40);

-- --------------------------------------------------------

--
-- Structure de la table `emp`
--

CREATE TABLE `emp` (
  `id` int(11) NOT NULL,
  `nom` varchar(25) NOT NULL,
  `num` int(25) NOT NULL,
  `fonction` varchar(25) NOT NULL,
  `n_sup` int(11) DEFAULT NULL,
  `embauche` date NOT NULL,
  `salaire` int(25) NOT NULL,
  `comm` int(25) DEFAULT NULL,
  `n_dept` int(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `emp`
--

INSERT INTO `emp` (`id`, `nom`, `num`, `fonction`, `n_sup`, `embauche`, `salaire`, `comm`, `n_dept`) VALUES
(2, 'JOUBERT', 25717, 'président', NULL, '2010-10-02', 50000, 8000, 5),
(3, 'MARTIN', 16712, 'directeur', 2, '2023-05-10', 40000, 45000, 5),
(4, 'DUPONT', 17574, 'administratif', 3, '2003-05-12', 9000, 500000, 5);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `pers`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `pers` (
`id` int(11)
,`nom` varchar(25)
,`num` int(25)
,`fonction` varchar(25)
,`n_sup` int(11)
,`embauche` date
,`salaire` int(25)
,`comm` int(25)
,`n_dept` int(25)
);

-- --------------------------------------------------------

--
-- Structure de la vue `pers`
--
DROP TABLE IF EXISTS `pers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`login4671`@`localhost` SQL SECURITY DEFINER VIEW `pers`  AS SELECT `emp`.`id` AS `id`, `emp`.`nom` AS `nom`, `emp`.`num` AS `num`, `emp`.`fonction` AS `fonction`, `emp`.`n_sup` AS `n_sup`, `emp`.`embauche` AS `embauche`, `emp`.`salaire` AS `salaire`, `emp`.`comm` AS `comm`, `emp`.`n_dept` AS `n_dept` FROM `emp` ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `emp`
--
ALTER TABLE `emp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test` (`n_dept`),
  ADD KEY `fg` (`n_sup`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `dept`
--
ALTER TABLE `dept`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `emp`
--
ALTER TABLE `emp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emp`
--
ALTER TABLE `emp`
  ADD CONSTRAINT `fg` FOREIGN KEY (`n_sup`) REFERENCES `emp` (`id`),
  ADD CONSTRAINT `test` FOREIGN KEY (`n_dept`) REFERENCES `dept` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
