-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 15 Avril 2015 à 18:45
-- Version du serveur :  5.6.17-log
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `musicsession`
--
CREATE DATABASE IF NOT EXISTS `musicsession` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `musicsession`;

-- --------------------------------------------------------

--
-- Structure de la table `dico`
--

DROP TABLE IF EXISTS `dico`;
CREATE TABLE IF NOT EXISTS `dico` (
  `id_dico` int(11) NOT NULL AUTO_INCREMENT,
  `key_dico` varchar(255) NOT NULL,
  `fr` varchar(255) NOT NULL,
  `en` varchar(255) NOT NULL,
  PRIMARY KEY (`id_dico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `dico`
--

INSERT INTO `dico` (`id_dico`, `key_dico`, `fr`, `en`) VALUES
(1, 'Home', 'Accueil', 'Home'),
(2, 'Notifications', 'Notifications', 'Notifications'),
(3, 'Messages', 'Messages', 'Messages'),
(4, 'NewSong', 'Nouvelle musique', 'New song'),
(5, 'error', 'Erreur', 'Error'),
(6, 'Pseudo: ', 'Identifiant: ', 'Pseudo: '),
(7, 'Password: ', 'Mot de passe: ', 'Password: '),
(8, 'Firstname: ', 'Prénom: ', 'Firstname: '),
(9, 'Sign in', 'Se connecter', 'Sign in'),
(10, 'Name: ', 'Nom: ', 'Name: '),
(11, 'Sign up', 'S''inscrire', 'Sign up'),
(12, 'Forgotten password?', 'Mot de passe oublié?', 'Forgotten password?');

-- --------------------------------------------------------

--
-- Structure de la table `friendship`
--

DROP TABLE IF EXISTS `friendship`;
CREATE TABLE IF NOT EXISTS `friendship` (
  `id_fdshp` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_a` int(11) NOT NULL,
  `fk_user_b` int(11) NOT NULL,
  `date_fdshp` timestamp NOT NULL,
  PRIMARY KEY (`id_fdshp`),
  KEY `fk_userfriendship_a` (`fk_user_a`),
  KEY `fk_userfriendship_b` (`fk_user_b`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Contenu de la table `friendship`
--

INSERT INTO `friendship` (`id_fdshp`, `fk_user_a`, `fk_user_b`, `date_fdshp`) VALUES
(28, 21, 20, '2015-04-09 14:52:48'),
(30, 20, 1, '2015-04-14 19:36:48'),
(31, 1, 21, '2015-04-15 13:31:40'),
(32, 1, 24, '2015-04-15 16:18:05');

-- --------------------------------------------------------

--
-- Structure de la table `likedtune`
--

DROP TABLE IF EXISTS `likedtune`;
CREATE TABLE IF NOT EXISTS `likedtune` (
  `id_likedtune` int(11) NOT NULL AUTO_INCREMENT,
  `fk_tune_lt` int(11) NOT NULL,
  `fk_user_lt` int(11) NOT NULL,
  PRIMARY KEY (`id_likedtune`),
  KEY `fk_likedtune_tune` (`fk_tune_lt`),
  KEY `fk_likedtune_user` (`fk_user_lt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77 ;

--
-- Contenu de la table `likedtune`
--

INSERT INTO `likedtune` (`id_likedtune`, `fk_tune_lt`, `fk_user_lt`) VALUES
(56, 56, 1),
(57, 57, 1),
(58, 58, 1),
(59, 59, 1),
(60, 60, 21),
(61, 58, 21),
(62, 56, 20),
(76, 67, 24);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id_msg` int(11) NOT NULL AUTO_INCREMENT,
  `fk_sender` int(11) NOT NULL,
  `fk_receiver` int(11) NOT NULL,
  `date_msg` datetime NOT NULL,
  `content_msg` text NOT NULL,
  PRIMARY KEY (`id_msg`),
  KEY `fk_msg_sender` (`fk_sender`),
  KEY `fk_msg_receiver` (`fk_receiver`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id_msg`, `fk_sender`, `fk_receiver`, `date_msg`, `content_msg`) VALUES
(1, 1, 20, '2015-04-09 16:48:50', 'Hi\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla turpis duis. '),
(2, 20, 1, '2015-04-09 16:49:06', 'Hi\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla turpis duis. '),
(3, 1, 20, '2015-04-09 16:55:35', 'To discover <a href=''Tune/index/56'' class=''hashtag''>#StairwayToHeaven</a> :)');

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id_news` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_news` int(11) NOT NULL,
  `date_news` timestamp NOT NULL,
  `content_news` varchar(255) NOT NULL,
  PRIMARY KEY (`id_news`),
  KEY `fk_news` (`fk_user_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id_news`, `fk_user_news`, `date_news`, `content_news`) VALUES
(1, 20, '2015-04-09 14:32:29', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lacinia libero in quam cursus, ut sodales leo molestie. Curabitur urna ligula, pretium eget eros a, maximus efficitur tortor. Aliquam sodales sodales tortor vel venenatis. Cras pharetra blan'),
(2, 1, '2015-04-09 14:32:58', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lacinia libero in quam cursus, ut sodales leo molestie. Curabitur urna ligula, pretium eget eros a, maximus efficitur tortor. Aliquam sodales sodales tortor vel venenatis. Cras pharetra blan'),
(3, 1, '2015-04-09 14:54:57', '<a href=''Tune/index/56'' class=''hashtag''>#StairwayToHeaven</a>'),
(54, 1, '2015-04-14 09:07:58', 'Test'),
(55, 1, '2015-04-14 14:30:48', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(56, 1, '2015-04-14 20:11:18', '#ScottishDelGatto'),
(57, 1, '2015-04-14 20:11:26', 'hgfdhg #ScottishDelGatto fghfdqgsfdg'),
(58, 1, '2015-04-14 21:03:48', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(59, 1, '2015-04-15 13:24:45', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(60, 1, '2015-04-15 13:31:20', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(61, 1, '2015-04-15 14:41:45', '<a href=''Tune/index/60'' class=''hashtag''>#AuClairDelaLune</a>'),
(62, 24, '2015-04-15 16:16:15', '<a href=''Tune/index/66'' class=''hashtag''>#test2</a>'),
(63, 24, '2015-04-15 16:19:46', '<a href=''Tune/index/66'' class=''hashtag''>#test2</a>');

-- --------------------------------------------------------

--
-- Structure de la table `score`
--

DROP TABLE IF EXISTS `score`;
CREATE TABLE IF NOT EXISTS `score` (
  `id_score` int(11) NOT NULL AUTO_INCREMENT,
  `fk_tune_score` int(11) NOT NULL,
  `fk_user_score` int(11) NOT NULL,
  `pdf_score` varchar(255) NOT NULL,
  `date_score` datetime NOT NULL,
  PRIMARY KEY (`id_score`),
  KEY `fk_tune_score_ref` (`fk_tune_score`),
  KEY `fk_user_score_ref` (`fk_user_score`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Contenu de la table `score`
--

INSERT INTO `score` (`id_score`, `fk_tune_score`, `fk_user_score`, `pdf_score`, `date_score`) VALUES
(3, 56, 1, 'led_zeppelin-stairway_to_heaven.pdf', '0000-00-00 00:00:00'),
(4, 57, 1, 'NissaLaBella.pdf', '0000-00-00 00:00:00'),
(5, 58, 1, '[Free-scores.com]_beethoven-ludwig-van-for-elise-549.pdf', '0000-00-00 00:00:00'),
(6, 59, 1, '27-Scottish_gatto.pdf', '0000-00-00 00:00:00'),
(7, 59, 1, '27-Scottish_gatto552e5ebd21e3c.pdf', '2015-04-15 14:51:09'),
(25, 67, 24, 'partition-au-clair-de-la-lune552e91ea0c882.pdf', '2015-04-15 18:29:30');

-- --------------------------------------------------------

--
-- Structure de la table `tune`
--

DROP TABLE IF EXISTS `tune`;
CREATE TABLE IF NOT EXISTS `tune` (
  `id_tune` int(11) NOT NULL AUTO_INCREMENT,
  `title_tune` varchar(255) NOT NULL,
  `composer` varchar(255) NOT NULL,
  `category_tune` varchar(255) NOT NULL,
  `date_tune` datetime NOT NULL,
  PRIMARY KEY (`id_tune`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- Contenu de la table `tune`
--

INSERT INTO `tune` (`id_tune`, `title_tune`, `composer`, `category_tune`, `date_tune`) VALUES
(56, 'StairwayToHeaven', 'led Zeppelin', 'rock', '2015-04-09 16:38:19'),
(57, 'NissaLaBella', 'Menica Rondelly', 'trad', '2015-04-09 16:40:00'),
(58, 'LettreaElise', 'Ludwig van Beethoven', 'classique', '2015-04-09 16:42:49'),
(59, 'ScottishDelGatto', 'Sergio Berardo', 'trad', '2015-04-09 16:44:30'),
(60, 'AuClairDelaLune', 'anonyme', 'chanson populaire', '2015-04-09 16:54:01'),
(67, 'test2', 'test2', 'rock', '2015-04-15 18:24:17');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `pwdhashed` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `lang` enum('fr','en') NOT NULL,
  `key_user` varchar(255) NOT NULL,
  `confirmmail` tinyint(1) NOT NULL,
  `access` enum('admin','user') NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `pwdhashed`, `firstname`, `name`, `email`, `lang`, `key_user`, `confirmmail`, `access`) VALUES
(1, 'Nico', '1234', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr', '0', 1, 'user'),
(6, 'Admin', '1234', 'Admin', 'Admin', 'admin@gmail.com', 'en', '2587', 1, 'admin'),
(20, 'GreenDay', '1234', 'Green', 'Day', 'greenday@gmailtest.com', 'en', '55268d00308ff', 0, 'user'),
(21, 'Jean', '1234', 'jean', 'Dupont', 'jeandupont@gmailtest.com', 'fr', '5526922a7c662', 0, 'user'),
(24, 'test', 'test', 'test', 'test', 'test@gfdsgf.com', 'en', '552e8e80cabb0', 0, 'user');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `friendship`
--
ALTER TABLE `friendship`
  ADD CONSTRAINT `fk_userfriendship_a` FOREIGN KEY (`fk_user_a`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_userfriendship_b` FOREIGN KEY (`fk_user_b`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `likedtune`
--
ALTER TABLE `likedtune`
  ADD CONSTRAINT `fk_likedtune_tune` FOREIGN KEY (`fk_tune_lt`) REFERENCES `tune` (`id_tune`),
  ADD CONSTRAINT `fk_likedtune_user` FOREIGN KEY (`fk_user_lt`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`fk_receiver`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`fk_sender`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news` FOREIGN KEY (`fk_user_news`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `score`
--
ALTER TABLE `score`
  ADD CONSTRAINT `fk_tune_score_ref` FOREIGN KEY (`fk_tune_score`) REFERENCES `tune` (`id_tune`),
  ADD CONSTRAINT `fk_user_score_ref` FOREIGN KEY (`fk_user_score`) REFERENCES `user` (`id_user`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
