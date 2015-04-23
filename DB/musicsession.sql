-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 23 Avril 2015 à 16:08
-- Version du serveur :  5.6.17
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=118 ;

--
-- Contenu de la table `dico`
--

INSERT INTO `dico` (`id_dico`, `key_dico`, `fr`, `en`) VALUES
(1, 'Home', 'Accueil', 'Home'),
(2, 'Notifications', 'Notifications', 'Notifications'),
(3, 'Messages', 'Messages', 'Messages'),
(4, 'NewSong', 'Ajouter musique', 'Add tune'),
(5, 'error', 'Erreur', 'Error'),
(6, 'Pseudo: ', 'Identifiant: ', 'Pseudo: '),
(7, 'Password: ', 'Mot de passe: ', 'Password: '),
(8, 'Firstname: ', 'Prénom: ', 'Firstname: '),
(9, 'Sign in', 'Se connecter', 'Sign in'),
(10, 'Name: ', 'Nom: ', 'Name: '),
(11, 'Sign up', 'S''inscrire', 'Sign up'),
(12, 'Forgotten password?', 'Mot de passe oublié?', 'Forgotten password?'),
(13, 'Share your music with your friends!', 'Partager votre musique avec vos amis!', 'Share your music with your friends!'),
(14, 'Authentification', 'Authntification', 'Authentification'),
(15, 'Inscription', 'Inscription', 'Inscription'),
(16, 'min', 'min', 'min'),
(17, 'h', 'h', 'h'),
(18, 'd', ' jours', ' days'),
(19, 'm', 'mois', 'months'),
(20, 'y', 'années', 'years'),
(21, 'Tunes list', 'Musiques', 'Tunebook'),
(22, 'Suggested friends', 'Suggestions', 'Who to follow'),
(23, 'Friends', 'Amis', 'Friends'),
(24, 'Tunes', 'Musiques', 'Tunes'),
(25, 'Send', 'Envoyer', 'Send'),
(26, 'Add a new song', 'Créer une musique', 'Add a new tune'),
(27, 'Import song', 'Créer musique', 'Import tune'),
(28, 'Account', 'Compte', 'Account'),
(29, 'Change your basic settings.', 'Modifier vos paramètres.', 'Change your basic settings.'),
(30, 'Profile photo', 'Photo de profile', 'Profile photo'),
(31, 'Change your profile photo.', 'Modifier votre photo de profile', 'Change your profile photo.'),
(32, 'Password', 'Mot de passe', 'Password'),
(33, 'Change your password settings.', 'Modifier votre mot de passe.', 'Change your password settings.'),
(34, 'Reporting', 'Signaler', 'Reporting'),
(35, 'Reporting abusive behavior.', 'Signaler un usage abusif.', 'Reporting abusive behavior.'),
(36, 'Remove your account', 'Supprimer votre compte', 'Remove your account'),
(37, 'Delete definitely your account and your informations.', 'Supprimer définitevement votre compte et toutes vos informations.', 'Delete definitely your account and your informations.'),
(38, 'Update', 'Mettre à jour', 'Update'),
(39, 'Upload your photo', 'Télécharger votre photo', 'Upload your photo'),
(40, 'Language', 'Langue', 'Language'),
(41, 'Profile photo: ', 'Photo de profile: ', 'Profile photo: '),
(42, 'Confirm your password: ', 'Confirmer votre mot de passe: ', 'Confirm your password: '),
(43, 'Delete account', 'Supprimer votre compte', 'Delete account'),
(44, 'log out', 'Déconnexion', 'Log out'),
(45, 'Title', 'Titre', 'Title'),
(46, 'Composer', 'Compositeur', 'Composer'),
(47, 'Category', 'Catégorie', 'Category'),
(48, 'Date', 'Date', 'Date'),
(49, 'Delete from your tunebook', 'Supprimer musique', 'Delete tune'),
(50, 'Add to your tunebook', 'Ajouter à vos musiques', 'Add to your tunebook'),
(51, 'Add a new version', 'Ajouter version', 'Add version'),
(52, 'Share this tune', 'Partager musique', 'Share tune'),
(53, 'Delete this score', 'Supprimer cette partition', 'Delete this score'),
(54, 'Follow', 'Suivre', 'Follow'),
(55, 'Block', 'Bloquer', 'Block'),
(56, 'Invalid news', 'News incorrect', 'Invalid news'),
(57, 'Your account is registered!', 'Votre compte est confirmé!', 'Your account is registered!'),
(58, 'Error during account confirmation!', 'Erreur pendant la confirmation de votre compte', 'Error during account confirmation!'),
(59, 'Special char not allowed in pseudo!', 'Les caractères spéciaux ne sont pas autorisés!', 'Special char not allowed in pseudo!'),
(60, 'Hello \\n Confirm your inscription here: ', 'Bonjour \\ Confirmer votre inscription ici: ', 'Hello \\n Confirm your inscription here: '),
(61, 'Confirm', 'Confirmer', 'Confirm'),
(62, 'Thanks to sign in on Music Score Writer, an e-mail had been sent to you: please confirm your e-mail adress!', 'Merci de votre inscription, un e-mail vous a été envoyé pour confirmer votre compte!', 'Thanks to sign in on Music Score Writer, an e-mail had been sent to you: please confirm your e-mail adress!'),
(63, 'Pseudo already exists!', 'Ce pseudo existe déjà!', 'Pseudo already exists!'),
(64, 'Error: invalid pseudo or password!', 'Erreur: identifiant ou mot de passe incorrect!', 'Error: invalid pseudo or password!'),
(65, 'Invalid pseudo!', 'Identifiant incorrect!', 'Invalid pseudo!'),
(66, 'Invalid password!', 'Mot de passe incorrect!', 'Invalid password!'),
(67, 'Error', 'Erreur!', 'Error!'),
(68, 'Invalid', 'Incorrect', 'Invalid'),
(69, 'Invalid e-mail', 'E-mail incorrect', 'Invalid e-mail'),
(70, 'Deleting succeded!', 'Suppression réussi!', 'Deleting succeded!'),
(71, 'Delete', 'Supprimer', 'Delete'),
(72, 'Reporting abusive behavior', 'Signalement des usages abusifs', 'Reporting abusive behavior'),
(73, 'Language: ', 'Langue: ', 'Language: '),
(74, 'No messages!', 'Pas de messages!', 'No messages!'),
(75, 'This title is already existing!', 'Ce titre existe déjà!', 'This title is already existing!'),
(76, 'Send with success!', 'Envoi réussi!', 'Send with success!'),
(77, 'Are you sure to remove your account?', 'Voulez-vous supprimer définitivement votre compte?', 'Are you sure to remove your account?'),
(78, 'Yes', 'Oui', 'Yes'),
(79, 'No', 'Non', 'No'),
(80, 'Error during deleting your accounts!', 'Erreur pendant la suppression de votre compte!', 'Error during deleting your accounts!'),
(81, 'No matching results!', 'Pas de résultats correspondants!', 'No matching results!'),
(82, ' is friend with you!', ' est ami avec vous!', ' is friend with you!'),
(83, ' send you an invitation!', ' vous a envoyé une invitation!', ' send you an invitation!'),
(84, 'Accept the invitation', 'Accepter l''invitation', 'Accept the invitation'),
(85, 'An invitation has already been sent!', 'Une invitation a déjà été envoyée!', 'An invitation has already been sent!'),
(86, 'The invitation has been sent!', 'L''invitation a été envoyée!', 'The invitation has been sent!'),
(87, 'Tunebook', 'Musiques', 'Tunebook'),
(88, 'logout', 'Déconnexion', 'Log out'),
(89, 'Parameters', 'Paramètres', 'Settings'),
(90, 'View tune', 'Afficher musique', 'View tune'),
(91, 'Add score', 'Ajouter partition', 'Add score'),
(92, 'Share tune', 'Partager musique', 'Share tune'),
(93, 'Delete tune', 'Supprimer musique', 'Delete tune'),
(94, 'Add tune', 'Ajouter musique', 'Add tune'),
(95, 'Download this score', 'Télécharger cette partition', 'Download this score'),
(96, 'Terms', 'Conditions', 'Terms'),
(97, 'Privacy', 'Confidentialité', 'Privacy'),
(98, 'Accessibility', 'Accessibilité', 'Accessibility'),
(99, 'Title: ', 'Titre: ', 'Title: '),
(100, 'Composer: ', 'Compositeur: ', 'Composer: '),
(101, 'Category: ', 'Catégorie: ', 'Category: '),
(102, 'Music score (pdf): ', 'Partition (pdf):', 'Music score (pdf): '),
(103, 'ds', ' jour', ' day'),
(104, 'ms', 'mois', 'month'),
(105, 'ys', 'année', 'year'),
(106, 'Remove user account and all user informations', 'Supprimer un compte utilisateur', 'Remove user''s account and all user''s informations'),
(107, 'No notifications', 'Pas de notifications', 'No notifications'),
(108, 'No news', 'Pas de news', 'No news'),
(109, 'Invitation message: ', 'Message d''invitation: ', 'Invitation message: '),
(110, 'E-mail friend: ', 'E-mail d''un ami: ', 'E-mail to a friend: '),
(111, 'Invite your friends!', 'Inviter vos amis!', 'Invite your friends!'),
(112, 'E-mail sent!', 'E-mail envoyé!', 'E-mail sent!'),
(113, 'Discover Music session network here: ', 'Viens découvrir le réseau Music Session ici: ', 'Discover Music session network here: '),
(114, 'Message of ', 'Message de ', 'Message of '),
(115, 'Hello', 'Salut', 'Hello'),
(116, 'You are not friend with this user!', 'Vous n''êtes pas amis avec cet utilisateur!', 'You are not friend with this user!'),
(117, 'No news', 'Pas de news', 'No news');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Contenu de la table `friendship`
--

INSERT INTO `friendship` (`id_fdshp`, `fk_user_a`, `fk_user_b`, `date_fdshp`) VALUES
(28, 21, 20, '2015-04-09 14:52:48'),
(48, 1, 20, '2015-04-20 17:16:20'),
(53, 1, 21, '2015-04-23 08:56:14');

-- --------------------------------------------------------

--
-- Structure de la table `invitation`
--

DROP TABLE IF EXISTS `invitation`;
CREATE TABLE IF NOT EXISTS `invitation` (
  `id_fdshp` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_a` int(11) NOT NULL,
  `fk_user_b` int(11) NOT NULL,
  `date_fdshp` timestamp NOT NULL,
  `readd` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_fdshp`),
  KEY `fk_userfriendship_a` (`fk_user_a`),
  KEY `fk_userfriendship_b` (`fk_user_b`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

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
(84, 81, 1);

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
  `readd` tinyint(4) NOT NULL,
  PRIMARY KEY (`id_msg`),
  KEY `fk_msg_sender` (`fk_sender`),
  KEY `fk_msg_receiver` (`fk_receiver`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id_msg`, `fk_sender`, `fk_receiver`, `date_msg`, `content_msg`, `readd`) VALUES
(1, 1, 20, '2015-04-09 16:48:50', 'Hi\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla turpis duis. ', 1),
(2, 20, 1, '2015-04-09 16:49:06', 'Hi\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla turpis duis. ', 1),
(3, 1, 20, '2015-04-09 16:55:35', 'To discover <a href=''Tune/index/56'' class=''hashtag''>#StairwayToHeaven</a> :)', 1),
(4, 1, 20, '2015-04-16 21:02:03', 'Test', 1),
(5, 20, 1, '2015-04-16 21:02:37', 'ok', 1),
(6, 1, 20, '2015-04-17 08:29:38', 'test', 1),
(7, 1, 20, '2015-04-17 08:30:40', 'test', 1),
(8, 1, 20, '2015-04-17 08:31:01', 'test', 1),
(9, 1, 20, '2015-04-17 08:32:02', 'test', 1),
(10, 1, 20, '2015-04-18 21:57:06', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishdelGatto</a>', 1),
(11, 1, 20, '2015-04-18 21:58:09', '<a href=''Tune/index/Nico/59'' class=''hashtag''>#ScottishdelGatto</a>', 1),
(12, 1, 21, '2015-04-20 19:12:12', 'gfdsgfgd', 1),
(13, 21, 1, '2015-04-20 19:13:59', 'hello world', 1),
(14, 20, 1, '2015-04-20 19:16:31', 'Hi', 1),
(15, 20, 1, '2015-04-20 19:16:46', 'test', 1),
(16, 21, 1, '2015-04-21 14:57:22', 'Hi', 1),
(17, 21, 1, '2015-04-21 14:57:25', 'test', 1),
(18, 20, 1, '2015-04-21 14:59:08', 'hi', 1),
(19, 21, 1, '2015-04-21 14:59:12', 'hi', 1),
(20, 20, 1, '2015-04-21 15:15:35', 'hi', 1),
(21, 21, 1, '2015-04-21 15:15:52', 'test', 1),
(22, 21, 1, '2015-04-21 15:21:39', 'test', 1),
(23, 21, 1, '2015-04-21 15:28:36', 'test', 1),
(24, 21, 1, '2015-04-21 15:30:26', 'test', 1),
(25, 21, 1, '2015-04-21 15:32:48', 'test', 1),
(26, 21, 1, '2015-04-21 15:33:03', 'test', 1),
(27, 20, 1, '2015-04-21 15:34:56', 'test', 1),
(28, 20, 1, '2015-04-21 15:41:57', 'fdsgsfdgfdg', 1),
(29, 21, 1, '2015-04-21 15:42:38', 'test', 1),
(30, 20, 1, '2015-04-21 15:45:31', 'fdsfsfsdf', 1),
(31, 21, 1, '2015-04-21 17:43:16', 'fdsdsfs', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

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
(62, 1, '2015-04-18 19:58:50', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(63, 1, '2015-04-18 19:58:54', '<a href=''Tune/index/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(64, 1, '2015-04-18 19:59:50', '<a href=''Tune/index/Nico/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(65, 1, '2015-04-18 20:00:54', '<a href=''Tune/index/Nico/59'' class=''hashtag''>#ScottishDelGatto</a>'),
(66, 1, '2015-04-18 20:01:02', '<a href=''Tune/index/Nico/60'' class=''hashtag''>#AuClairDelaLune</a>'),
(67, 1, '2015-04-23 08:59:50', '<a href=''Tune/index/Nico/81'' class=''hashtag''>#htfhghf</a>');

-- --------------------------------------------------------

--
-- Structure de la table `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE IF NOT EXISTS `report` (
  `id_report` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_report` int(11) NOT NULL,
  `date_report` datetime NOT NULL,
  `content_report` text NOT NULL,
  PRIMARY KEY (`id_report`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `report`
--

INSERT INTO `report` (`id_report`, `fk_user_report`, `date_report`, `content_report`) VALUES
(1, 1, '2015-04-15 22:15:11', 'Test');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `score`
--

INSERT INTO `score` (`id_score`, `fk_tune_score`, `fk_user_score`, `pdf_score`, `date_score`) VALUES
(3, 56, 1, 'led_zeppelin-stairway_to_heaven.pdf', '0000-00-00 00:00:00'),
(4, 57, 1, 'NissaLaBella.pdf', '0000-00-00 00:00:00'),
(5, 58, 1, '[Free-scores.com]_beethoven-ludwig-van-for-elise-549.pdf', '0000-00-00 00:00:00'),
(6, 59, 1, '27-Scottish_gatto.pdf', '0000-00-00 00:00:00'),
(7, 59, 1, '27-Scottish_gatto552e5ebd21e3c.pdf', '2015-04-15 14:51:09');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Contenu de la table `tune`
--

INSERT INTO `tune` (`id_tune`, `title_tune`, `composer`, `category_tune`, `date_tune`) VALUES
(56, 'StairwayToHeaven', 'led Zeppelin', 'rock', '2015-04-09 16:38:19'),
(57, 'NissaLaBella', 'Menica Rondelly', 'trad', '2015-04-09 16:40:00'),
(58, 'LettreaElise', 'Ludwig van Beethoven', 'classique', '2015-04-09 16:42:49'),
(59, 'ScottishDelGatto', 'Sergio Berardo', 'trad', '2015-04-09 16:44:30'),
(60, 'AuClairDelaLune', 'anonyme', 'chanson populaire', '2015-04-09 16:54:01'),
(81, 'htfhghf', 'gfhfghfgh', 'rock', '2015-04-23 10:59:22');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `pwdhashed`, `firstname`, `name`, `email`, `lang`, `key_user`, `confirmmail`, `access`) VALUES
(1, 'Nico', '$2y$10$GqY8ylgSapvUnIyktpV5Q.gdbAwE/1V88SBvYmz6TnpPDc9i9IpH6', 'Nicolas', 'Torre', 'nico@gmail.com', 'fr', '0', 1, 'user'),
(6, 'Admin', '$2y$10$GqY8ylgSapvUnIyktpV5Q.gdbAwE/1V88SBvYmz6TnpPDc9i9IpH6', 'Admin', 'Admin', 'admin@gmail.com', 'fr', '2587', 1, 'admin'),
(20, 'GreenDay', '$2y$10$GqY8ylgSapvUnIyktpV5Q.gdbAwE/1V88SBvYmz6TnpPDc9i9IpH6', 'Green', 'Day', 'greenday@gmailtest.com', 'en', '55268d00308ff', 0, 'user'),
(21, 'Jean', '$2y$10$GqY8ylgSapvUnIyktpV5Q.gdbAwE/1V88SBvYmz6TnpPDc9i9IpH6', 'jean', 'Dupont', 'jeandupont@gmailtest.com', 'fr', '5526922a7c662', 0, 'user');

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
-- Contraintes pour la table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `fk_userinvitation_a` FOREIGN KEY (`fk_user_a`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_userinvitation_b` FOREIGN KEY (`fk_user_b`) REFERENCES `user` (`id_user`);

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
