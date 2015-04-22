-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 21 Avril 2015 à 15:50
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

-- --------------------------------------------------------

--
-- Structure de la table `dico`
--

CREATE TABLE IF NOT EXISTS `dico` (
  `id_dico` int(11) NOT NULL AUTO_INCREMENT,
  `key_dico` varchar(255) NOT NULL,
  `fr` varchar(255) NOT NULL,
  `en` varchar(255) NOT NULL,
  PRIMARY KEY (`id_dico`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

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
(116, 'You are not friend with this user!', 'Vous n''êtes pas amis avec cet utilisateur!', 'You are not friend with this user!');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
