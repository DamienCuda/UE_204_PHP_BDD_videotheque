-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 17 déc. 2022 à 18:33
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `videotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `catalogue`
--

CREATE TABLE `catalogue` (
  `id` int(11) NOT NULL,
  `movie_picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no_img.jpg',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `acteurs` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `genre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `release_year` year(4) NOT NULL,
  `duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `synopsis` text COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `catalogue`
--

INSERT INTO `catalogue` (`id`, `movie_picture`, `title`, `director`, `acteurs`, `genre`, `release_year`, `duration`, `synopsis`, `price`) VALUES
(1, 'harry-potter.jpg', 'Harry Potter et la Coupe de feu', 'Mike Newell', 'Daniel Radcliffe, Emma Watson, Rupert Grint', 'Aventure, Fantastique', 2005, '2h 35min', 'La quatrième année à l’école de Poudlard est marquée par le « Tournoi des trois sorciers ». Les participants sont choisis par la fameuse « Coupe de feu » qui est à l’origine d’un scandale. Elle sélectionne Harry Potter alors qu’il n’a pas l’âge légal requis ! Accusé de tricherie et mis à mal par une série d’épreuves physiques de plus en plus difficiles, ce dernier sera enfin confronté à Celui dont on ne doit pas prononcer le nom, Lord V.', 3),
(3, 'beetlejuice.jpg', 'Beetlejuice', 'Tim Burton', 'Alec Baldwin, Geena Davis, Annie McEnroe, Maurice Page', 'Comedie, Fantastique', 1988, '1h32', 'Un couple de fantômes récemment décédés contracte les services d\'un bio-exorciste afin d\'éliminer les nouveaux propriétaires odieux de leur maison.', 3),
(4, 'evades.jpg', 'Les évadés', 'Frank Darabont', 'Tim Robbins, Morgan Freeman, Bob Gunton, William Sadler', 'Crime, Drame', 1994, '2h22', 'Deux hommes emprisonnés se lient pendant plusieurs années, trouvant du réconfort et une éventuelle rédemption grâce à des actes de décence commune.', 3),
(5, 'ratatouille.jpg', 'Ratatouille', 'Brad Bird, Jan Pinkava', 'Patton Oswalt, Ian Holm, Lou Romano, Brian Dennehy', 'Animation, Comédie, Famille', 2007, '1h51', 'Un rat qui sait cuisiner fait une alliance inhabituelle avec un jeune cuisinier d\'un célèbre restaurant.', 3),
(6, 'apocalypto.jpg', 'Apocalypto', 'Mel Gibson', 'Rudy Youngblood, Dalia Hernández, Jonathan Brewer, Morris Birdyellowhead', 'Action, Aventure, Drame', 2006, '2h19', 'Alors que le royaume maya fait face à son déclin, les dirigeants insistent sur le fait que la clé de la prospérité est de construire plus de temples et d\'offrir des sacrifices humains. Jaguar Paw, un jeune homme capturé pour être sacrifié, s\'enfuit pour éviter son destin.', 3),
(7, 'nocountry.jpg', 'No Country for Old Men', 'Ethan Coen, Joel Coen', 'Tommy Lee Jones, Javier Bardem, Josh Brolin, Woody Harrelson', 'Crime, Drame, Thriller', 2007, '2h02', 'La violence et le chaos s\'ensuivent après qu\'un chasseur tombe sur un trafic de drogue qui a mal tourné et plus de deux millions de dollars en espèces près du Rio Grande.', 3),
(8, 'thebeach.jpg', 'The Beach', 'Danny Boyle', 'Leonardo DiCaprio, Daniel York, Patcharawan Patarakijjanon, Virginie Ledoyen', 'Aventure, Romance, Drame', 2000, '1h59', 'Richard, la vingtaine, se rend en Thaïlande et se retrouve en possession d\'une étrange carte. Les rumeurs disent qu\'il mène à une plage paradisiaque solitaire, un bonheur tropical - excité et intrigué, il entreprend de le trouver.', 3),
(9, 'pulpfiction.jpg', 'Pulp Fiction', 'Quentin Tarantino', 'Tim Roth, Amanda Plummer, Laura Lovelace, John Travolta', 'Crime, Drame', 1994, '2h34', 'Les vies de deux tueurs à gages, un boxeur, la femme d\'un gangster et deux bandits de restaurant s\'entremêlent dans quatre histoires de violence et de rédemption.', 3),
(10, 'hangover.jpg', 'Very Bad Trip', 'Todd Phillips', 'Bradley Cooper, Ed Helms, Zach Galifianakis, Justin Bartha', 'Comédie', 2009, '1h40', 'Trois copains se réveillent d\'un enterrement de vie de garçon à Las Vegas, sans aucun souvenir de la nuit précédente et le célibataire a disparu. Ils parcourent la ville afin de retrouver leur ami avant son mariage.', 3),
(11, 'darkknight.jpg', 'The Dark Knight', 'Christopher Nolan', 'Christian Bale, Heath Ledger, Aaron Eckhart', 'Fantastique', 2008, '2h03', 'Lorsqu\'une menace mieux connue sous le nom du Joker émerge de son passé mystérieux et déclenche le chaos sur la ville de Gotham, Batman doit faire face au plus grand des défis psychologique et physique afin de combattre l\'injustice.', 3),
(12, 'whiplash.jpg', 'Whiplash', 'Damien Chazelle', 'Miles Teller, J.K. Simmons, Paul Reiser, Melissa Benoist', 'Drame, Musique', 2014, '1h47', 'Un jeune batteur prometteur s\'inscrit dans un conservatoire de musique impitoyable où ses rêves de grandeur sont encadrés par un instructeur qui ne reculera devant rien pour réaliser le potentiel d\'un élève.', 3),
(13, 'exmachina.jpg', 'Ex Machina', 'Alex Garland', 'Domhnall Gleeson,Corey Johnson,Oscar Isaac,Alicia Vikander', 'Drame,Mystère,Science-Fiction', 2015, '01h48', 'Un jeune programmeur est sélectionné pour participer à une expérience révolutionnaire d&#039;intelligence synthétique en évaluant les qualités humaines d&#039;une IA humanoïde à couper le souffle.', 3),
(14, '13_14_bXFGz4qOE8EgLiqS80DAQJvQxE5.jpg', 'Pinocchio par Guillermo del Toro', 'Mark Gustafson', 'Gregory Mann,Ewan McGregor,David Bradley,Christoph Waltz,Tilda Swinton,Ron Perlman', 'Animation,Fantastique,Drame', 2022, '02h01', 'Cette épopée musicale en stop-motion qui se déroule dans l&#039;Italie de l&#039;entre-deux-guerres marque les débuts de Guillermo del Toro en tant que parolier, avec la ballade &quot;Ciao Papa&quot;.', 3),
(15, '13_17_uQCxOziq79P3wDsRwQhhkhQyDsJ.jpg', 'One Way', 'Andrew Baird', 'Machine Gun Kelly,Storm Reid,Drea De Matteo,Travis Fimmel,Kevin Bacon', 'Crime,Thriller,Action', 2022, '01h35', 'Freddy, un délinquant minable, prend la fuite avec un sac rempli d&#039;argent et de cocaïne pure. Prêt à tout pour conserver son butin quoi qu&#039;il en coûte, il se laisse entraîner dans une spirale de violence qui finit par le rattraper. Car avec une balle logée dans le ventre, il n&#039;ignore pas que le temps lui est désormais compté.', 3);

-- --------------------------------------------------------

--
-- Structure de la table `movies_bookmark`
--

CREATE TABLE `movies_bookmark` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `movies_bookmark`
--

INSERT INTO `movies_bookmark` (`id`, `movie_id`, `user_id`, `date`) VALUES
(1, 12, 2, '2022-12-17 18:25:38'),
(2, 4, 2, '2022-12-17 18:25:43'),
(3, 6, 2, '2022-12-17 18:25:46'),
(4, 15, 3, '2022-12-17 18:26:14'),
(5, 13, 3, '2022-12-17 18:26:16'),
(6, 8, 3, '2022-12-17 18:26:18'),
(7, 9, 4, '2022-12-17 18:28:06'),
(8, 4, 4, '2022-12-17 18:28:08'),
(9, 11, 4, '2022-12-17 18:28:11'),
(10, 5, 4, '2022-12-17 18:28:13'),
(11, 7, 4, '2022-12-17 18:28:15'),
(12, 15, 4, '2022-12-17 18:28:23'),
(13, 14, 4, '2022-12-17 18:28:24'),
(14, 12, 4, '2022-12-17 18:28:25'),
(15, 13, 4, '2022-12-17 18:28:26'),
(16, 8, 4, '2022-12-17 18:28:31'),
(17, 10, 4, '2022-12-17 18:28:34'),
(18, 6, 4, '2022-12-17 18:28:37');

-- --------------------------------------------------------

--
-- Structure de la table `movies_location`
--

CREATE TABLE `movies_location` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_location` datetime NOT NULL,
  `location_duration` varchar(255) NOT NULL DEFAULT '24',
  `date_location_end` datetime NOT NULL,
  `is_loc` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `movies_location`
--

INSERT INTO `movies_location` (`id`, `movie_id`, `user_id`, `date_location`, `location_duration`, `date_location_end`, `is_loc`) VALUES
(1, 14, 2, '2022-12-17 18:25:10', '24', '2022-12-18 18:25:10', 1),
(2, 13, 2, '2022-12-17 18:25:13', '24', '2022-12-18 18:25:13', 1),
(3, 11, 2, '2022-12-17 18:25:23', '24', '2022-12-18 18:25:23', 1),
(4, 4, 2, '2022-12-17 18:25:27', '24', '2022-12-18 18:25:27', 1),
(5, 9, 2, '2022-12-17 18:25:31', '24', '2022-12-18 18:25:31', 1),
(6, 9, 3, '2022-12-17 18:26:21', '24', '2022-12-18 18:26:21', 1),
(7, 7, 3, '2022-12-17 18:26:25', '24', '2022-12-18 18:26:25', 1),
(8, 5, 3, '2022-12-17 18:26:29', '24', '2022-12-18 18:26:29', 1),
(9, 1, 3, '2022-12-17 18:26:35', '24', '2022-12-18 18:26:35', 1),
(10, 14, 4, '2022-12-17 18:27:56', '24', '2022-12-18 18:27:56', 1),
(11, 15, 4, '2022-12-17 18:27:58', '24', '2022-12-18 18:27:58', 1),
(12, 15, 5, '2022-12-17 18:29:34', '24', '2022-12-18 18:29:34', 1),
(13, 14, 5, '2022-12-17 18:29:36', '24', '2022-12-18 18:29:36', 1),
(14, 13, 5, '2022-12-17 18:29:38', '24', '2022-12-18 18:29:38', 1),
(15, 12, 5, '2022-12-17 18:29:40', '24', '2022-12-18 18:29:40', 1),
(16, 9, 5, '2022-12-17 18:29:42', '24', '2022-12-18 18:29:42', 1),
(17, 10, 5, '2022-12-17 18:29:44', '24', '2022-12-18 18:29:44', 1),
(18, 7, 5, '2022-12-17 18:29:47', '24', '2022-12-18 18:29:47', 1),
(19, 5, 5, '2022-12-17 18:29:50', '24', '2022-12-18 18:29:50', 1),
(20, 4, 5, '2022-12-17 18:29:52', '24', '2022-12-18 18:29:52', 1),
(21, 6, 5, '2022-12-17 18:29:55', '24', '2022-12-18 18:29:55', 1),
(22, 1, 5, '2022-12-17 18:30:00', '24', '2022-12-18 18:30:00', 1),
(23, 8, 5, '2022-12-17 18:30:15', '24', '2022-12-18 18:30:15', 1),
(24, 11, 5, '2022-12-17 18:30:18', '24', '2022-12-18 18:30:18', 1);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `movie_id`, `date`, `amount`) VALUES
(1, 2, 14, '2022-12-17 18:25:10', 3),
(2, 2, 13, '2022-12-17 18:25:13', 3),
(3, 2, 11, '2022-12-17 18:25:23', 3),
(4, 2, 4, '2022-12-17 18:25:27', 3),
(5, 2, 9, '2022-12-17 18:25:31', 3),
(6, 3, 9, '2022-12-17 18:26:21', 3),
(7, 3, 7, '2022-12-17 18:26:25', 3),
(8, 3, 5, '2022-12-17 18:26:29', 3),
(9, 3, 1, '2022-12-17 18:26:35', 3),
(10, 4, 14, '2022-12-17 18:27:56', 3),
(11, 4, 15, '2022-12-17 18:27:58', 3),
(12, 5, 15, '2022-12-17 18:29:34', 3),
(13, 5, 14, '2022-12-17 18:29:36', 3),
(14, 5, 13, '2022-12-17 18:29:38', 3),
(15, 5, 12, '2022-12-17 18:29:40', 3),
(16, 5, 9, '2022-12-17 18:29:42', 3),
(17, 5, 10, '2022-12-17 18:29:44', 3),
(18, 5, 7, '2022-12-17 18:29:47', 3),
(19, 5, 5, '2022-12-17 18:29:50', 3),
(20, 5, 4, '2022-12-17 18:29:52', 3),
(21, 5, 6, '2022-12-17 18:29:55', 3),
(22, 5, 1, '2022-12-17 18:30:00', 3),
(23, 5, 8, '2022-12-17 18:30:15', 3),
(24, 5, 11, '2022-12-17 18:30:18', 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_admin` int(11) NOT NULL DEFAULT 0,
  `rang` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Membre',
  `solde` int(11) NOT NULL DEFAULT 50
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `email`, `password`, `profile_picture`, `is_admin`, `rang`, `solde`) VALUES
(1, 'owner', 'owner@owner.fr', '$2y$04$0E0.NfX08Vye7qcv.ElmcuLzII80kz.FHpGmgxFroqGDtx3Vd9jPe', NULL, 1, 'Owner', 50),
(2, 'admin', 'admin@admin.fr', '$2y$04$0E0.NfX08Vye7qcv.ElmcuLzII80kz.FHpGmgxFroqGDtx3Vd9jPe', NULL, 1, 'Administrateur', 50),
(3, 'modo', 'moderateur@moderateur.fr', '$2y$04$Wxb2qNNgVPBZpPry3Bg1x.VrhzQgJtk.5z5MEn.LzhyiJpfXEP..m', NULL, 1, 'Modérateur', 44),
(4, 'user', 'user@user.fr', '$2y$04$nPkUlvlkdf7LyrRXdBlLxei27mmiGmYksw9N5l32cnIzruoozQtU6', NULL, 0, 'Membre', 11);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `catalogue`
--
ALTER TABLE `catalogue`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `movies_bookmark`
--
ALTER TABLE `movies_bookmark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `movies_location`
--
ALTER TABLE `movies_location`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifiant` (`login`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `catalogue`
--
ALTER TABLE `catalogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `movies_bookmark`
--
ALTER TABLE `movies_bookmark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `movies_location`
--
ALTER TABLE `movies_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
