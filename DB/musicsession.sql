-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 12 Mars 2015 à 23:43
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `dico`
--

INSERT INTO `dico` (`id_dico`, `key_dico`, `fr`, `en`) VALUES
(1, 'Home', 'Accueil', 'Home'),
(2, 'Notifications', 'Notifications', 'Notifications'),
(3, 'Messages', 'Messages', 'Messages'),
(4, 'NewSong', 'Nouvelle musique', 'New song'),
(5, 'error', 'Erreur', 'Error');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `friendship`
--

INSERT INTO `friendship` (`id_fdshp`, `fk_user_a`, `fk_user_b`, `date_fdshp`) VALUES
(13, 1, 2, '2015-03-05 06:12:51'),
(14, 1, 3, '2015-03-05 06:33:00'),
(15, 3, 2, '2015-03-05 06:41:51');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `likedtune`
--

INSERT INTO `likedtune` (`id_likedtune`, `fk_tune_lt`, `fk_user_lt`) VALUES
(2, 1, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id_msg`, `fk_sender`, `fk_receiver`, `date_msg`, `content_msg`) VALUES
(1, 1, 2, '2015-03-11 14:48:30', 'Bonjour\r\nCeci est un test!!!'),
(2, 1, 2, '2015-03-11 16:03:59', 'My first send of message!!!'),
(4, 1, 2, '2015-03-11 16:05:20', 'My first send of message!!!'),
(5, 1, 2, '2015-03-11 16:14:26', 'My first send of message!!!'),
(6, 3, 5, '2015-03-11 16:14:48', 'Test'),
(7, 1, 3, '2015-03-11 16:41:03', 'Bonjour Mickey'),
(8, 1, 3, '2015-03-11 16:42:33', 'Bonjour Astérix!!!'),
(9, 1, 3, '2015-03-11 16:42:43', 'Comment vas tu?'),
(11, 1, 3, '2015-03-11 17:00:20', 're'),
(12, 1, 2, '2015-03-11 17:03:43', 're'),
(13, 1, 3, '2015-03-11 17:03:57', 'OOOOoooo'),
(14, 1, 2, '2015-03-11 17:04:07', 'how re u?'),
(16, 2, 1, '2015-03-11 17:18:05', 're'),
(17, 1, 3, '2015-03-11 22:34:50', 'Bonjour');

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id_news` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_news` int(11) NOT NULL,
  `date_news` datetime NOT NULL,
  `content_news` varchar(255) NOT NULL,
  PRIMARY KEY (`id_news`),
  KEY `fk_news` (`fk_user_news`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id_news`, `fk_user_news`, `date_news`, `content_news`) VALUES
(1, 1, '2015-03-04 09:20:00', 'This is a test!!!'),
(2, 2, '2015-03-04 09:20:34', 'This a mickey news!!!'),
(3, 2, '2015-03-04 10:19:30', 'Bonjour'),
(4, 3, '2015-03-05 07:33:50', 'Helllo I&#039;m Astérix!!!'),
(5, 1, '2015-03-06 20:24:15', 'CouCOu'),
(6, 3, '2015-03-06 20:28:01', 'Je suis un gaulois!!!');

-- --------------------------------------------------------

--
-- Structure de la table `tune`
--

DROP TABLE IF EXISTS `tune`;
CREATE TABLE IF NOT EXISTS `tune` (
  `id_tune` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_tune` int(11) NOT NULL,
  `title_tune` varchar(255) NOT NULL,
  `composer` varchar(255) NOT NULL,
  `category_tune` varchar(255) NOT NULL,
  `date_tune` datetime NOT NULL,
  `pdf_tune` varchar(255) NOT NULL,
  PRIMARY KEY (`id_tune`),
  KEY `fk_tune_user` (`fk_user_tune`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `tune`
--

INSERT INTO `tune` (`id_tune`, `fk_user_tune`, `title_tune`, `composer`, `category_tune`, `date_tune`, `pdf_tune`) VALUES
(1, 1, 'Nissa la bella', 'Menica Rondelly', 'trad', '2015-03-12 17:59:06', 'NissaLaBella.pdf');

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
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `pwdhashed`, `firstname`, `name`, `email`, `lang`) VALUES
(1, 'Nicowez', '1234', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr'),
(2, 'Asterix', '1234', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr'),
(3, 'Obelix', '1234', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr'),
(5, 'Mickey', '1234', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr');

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
  ADD CONSTRAINT `fk_likedtune_user` FOREIGN KEY (`fk_user_lt`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_likedtune_tune` FOREIGN KEY (`fk_tune_lt`) REFERENCES `tune` (`id_tune`);

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
-- Contraintes pour la table `tune`
--
ALTER TABLE `tune`
  ADD CONSTRAINT `fk_tune_user` FOREIGN KEY (`fk_user_tune`) REFERENCES `user` (`id_user`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
