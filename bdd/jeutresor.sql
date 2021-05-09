-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 02 mai 2021 à 13:29
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeutresor`
--

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

DROP TABLE IF EXISTS `membres`;
CREATE TABLE IF NOT EXISTS `membres` (
  `memb_id` int(11) NOT NULL AUTO_INCREMENT,
  `memb_mail` varchar(50) NOT NULL,
  `memb_pseudo` varchar(20) NOT NULL,
  `memb_mdp` varchar(50) NOT NULL,
  `inscrit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`memb_id`),
  UNIQUE KEY `memb_id` (`memb_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membres`
--

INSERT INTO `membres` (`memb_id`, `memb_mail`, `memb_pseudo`, `memb_mdp`, `inscrit`) VALUES
(1, 'sefagucluu@gmail.com', 'sefacile', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '2021-05-01 20:00:53'),
(2, 'test@gmail.com', 'test', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2021-05-01 20:00:53'),
(3, 'test2@gmail.com', 'test2', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2021-04-07 20:01:16'),
(4, 'test3@gmail.com', 'test3', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2021-05-01 22:49:31'),
(5, 'test4@gmail.com', 'test4', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2021-05-01 22:52:47'),
(6, 'test5@gmail.com', 'test5', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', '2021-05-02 14:57:29');

-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

DROP TABLE IF EXISTS `parties`;
CREATE TABLE IF NOT EXISTS `parties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `compteur` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `parties`
--

INSERT INTO `parties` (`id`, `id_membre`, `score`, `compteur`, `date`) VALUES
(76, 2, 2990, 8, '2021-05-02 12:01:04'),
(75, 3, 1878, 10, '2021-05-02 12:01:04'),
(77, 4, 2504, 7, '2021-05-02 12:01:04'),
(78, 2, -1915, 20, '2021-05-02 12:32:17'),
(79, 5, 2384, 17, '2021-05-02 12:37:22'),
(80, 5, 3311, 8, '2021-05-02 12:42:56'),
(81, 5, 1563, 4, '2021-05-02 12:49:40'),
(82, 2, 2618, 12, '2021-05-02 13:09:35'),
(83, 6, 2463, 8, '2021-05-02 15:21:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
