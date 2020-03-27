-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  ven. 27 mars 2020 à 17:22
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP :  7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `game`
--

-- --------------------------------------------------------

--
-- Structure de la table `personnages`
--

CREATE TABLE `personnages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `experience` int(11) NOT NULL,
  `victory` int(11) NOT NULL DEFAULT 0,
  `defeat` int(11) NOT NULL DEFAULT 0,
  `addStrength` int(11) NOT NULL DEFAULT 0,
  `addArmor` int(11) NOT NULL DEFAULT 0,
  `addLife` int(11) NOT NULL DEFAULT 0,
  `Points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `personnages`
--

INSERT INTO `personnages` (`id`, `name`, `type`, `level`, `experience`, `victory`, `defeat`, `addStrength`, `addArmor`, `addLife`, `Points`) VALUES
(1, 'Pikachu', 'Warrior', 1, 0, 0, 0, 0, 0, 0, 0),
(3, 'Harry Potter', 'Mage', 1, 0, 0, 0, 0, 0, 0, 0),
(7, 'Mathieu', 'Healer', 1, 0, 0, 0, 0, 0, 0, 0),
(12, 'Florent LeMauvais', 'Bot', 1, 0, 0, 0, 0, 0, 0, 0),
(13, 'Florent LeDoux', 'Bot', 3, 0, 0, 0, 50, 40, 100, 0),
(14, 'Florent', 'Bot', 5, 0, 0, 0, 150, 70, 300, 0),
(16, 'Florent Lartiste', 'Bot', 10, 0, 0, 0, 450, 400, 900, 0),
(17, 'Florent LeSage', 'Bot', 15, 0, 0, 0, 1000, 900, 1800, 0),
(18, 'Mohamed', 'Healer', 100, 10000, 0, 0, 10000, 10000, 20000, 0),
(19, 'Fanny', 'Warrior', 1, 0, 0, 0, 0, 0, 0, 0),
(20, 'Floran LeDure', 'Bot', 25, 0, 0, 0, 3800, 2500, 3000, 0),
(21, 'Floran LaBrute', 'Bot', 35, 0, 0, 0, 4500, 4000, 6000, 0),
(22, 'Florent LeBoss', 'Bot', 50, 0, 4, 0, 7000, 8500, 10000, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `personnages`
--
ALTER TABLE `personnages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `personnages`
--
ALTER TABLE `personnages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
