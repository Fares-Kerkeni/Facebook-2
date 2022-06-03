-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 22, 2022 at 09:21 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_network`
--

-- --------------------------------------------------------

--
-- Table structure for table `aime_commentaire`
--

CREATE TABLE `aime_commentaire` (
  `id_aime_commentaire` int(11) UNSIGNED NOT NULL,
  `id_commentaire` int(10) UNSIGNED DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aime_commentaire`
--

INSERT INTO `aime_commentaire` (`id_aime_commentaire`, `id_commentaire`, `id_user`) VALUES
(7, 40, 2);

-- --------------------------------------------------------

--
-- Table structure for table `aime_poste`
--

CREATE TABLE `aime_poste` (
  `id_aime_poste` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `id_poste` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aime_poste`
--

INSERT INTO `aime_poste` (`id_aime_poste`, `id_user`, `id_poste`) VALUES
(29, 5, 51),
(34, 1, 50),
(39, 2, 50),
(40, 2, 51),
(47, 9, 51),
(51, 9, 56),
(52, 9, 55),
(53, 2, 56);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(10) UNSIGNED NOT NULL,
  `date_creation_chat` datetime NOT NULL,
  `nom_chat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `date_creation_chat`, `nom_chat`) VALUES
(7, '2022-05-22 11:08:35', 'neoznzoevinitydead');

-- --------------------------------------------------------

--
-- Table structure for table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `id_poste` int(10) UNSIGNED DEFAULT NULL,
  `texte_commentaire` text NOT NULL,
  `image_commentaire` varchar(255) NOT NULL,
  `date_commentaire` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_user`, `id_poste`, `texte_commentaire`, `image_commentaire`, `date_commentaire`) VALUES
(29, 5, 51, 'coucou', 'image', '2022-05-21 01:11:16'),
(30, 1, 51, 'xd', 'image', '2022-05-21 01:23:30'),
(32, 8, 53, 'ftg', 'image', '2022-05-21 04:55:32'),
(33, 9, 51, 'cc', 'image', '2022-05-21 05:22:02'),
(34, 9, 51, 'tg', 'image', '2022-05-21 06:00:26'),
(35, 9, 56, 'yh', 'image', '2022-05-21 06:05:13'),
(36, 9, 51, 'cc', 'image', '2022-05-21 06:07:14'),
(37, 9, 51, 'xd', 'image', '2022-05-21 08:00:58'),
(38, 9, 56, 'gg', 'image', '2022-05-21 08:13:30'),
(39, 9, 51, 'xd', 'image', '2022-05-21 08:17:51'),
(40, 2, 56, 'ftg theo tu parles trop', 'image', '2022-05-21 11:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_contact` int(10) UNSIGNED DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `id_contact`, `id_user`) VALUES
(2, 3, 1),
(3, 3, 2),
(4, 1, 5),
(5, 5, 1),
(6, 7, 5),
(10, 2, 1),
(11, 1, 2),
(16, 8, 8),
(17, 1, 1),
(18, 2, 2),
(19, 3, 3),
(20, 4, 4),
(21, 5, 5),
(27, 2, 8),
(28, 8, 2),
(29, 9, 9),
(30, 5, 9),
(31, 9, 5),
(32, 3, 9),
(33, 9, 3),
(34, 2, 9),
(35, 9, 2),
(48, 10, 9),
(50, 10, 5),
(54, 7, 4),
(56, 7, 1),
(58, 10, 3),
(59, 4, 1),
(60, 1, 4),
(62, 10, 2),
(64, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `description`
--

CREATE TABLE `description` (
  `id_description` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `banniere` varchar(255) NOT NULL,
  `ecole` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ville` varchar(255) NOT NULL,
  `code_postal` int(11) NOT NULL,
  `pays` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `description`
--

INSERT INTO `description` (`id_description`, `id_user`, `image`, `banniere`, `ecole`, `description`, `ville`, `code_postal`, `pays`) VALUES
(1, 4, '6288c83cca4c27.32878589.jpg', '6288c839919037.57527407.jpg', '', '', '', 0, ''),
(2, 5, '6288c8705e9475.16459839.jpg', '6288c873d3c459.22869141.jpg', '', '', '', 0, ''),
(5, 1, '6288c468c08437.13622153.jpg', '6288c46459f066.78938159.jpg', '', '', '', 0, ''),
(6, 2, '6288c4880fac98.08596631.jpg', '6288c490914912.97608744.png', '', '', '', 0, ''),
(7, 3, '6288c490914912.97608744.png', '6288c4880fac98.08596631.jpg', '', '', '', 0, ''),
(8, 8, '6288fd1acafec3.26378430.jpg', '6288fd1fca0ca1.22669189.jpg', '', '', '', 0, ''),
(9, 9, 'default_profil.jpg', 'default_banner.jpg', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `group_page`
--

CREATE TABLE `group_page` (
  `id_group_page` int(11) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banniere` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_page_postes`
--

CREATE TABLE `group_page_postes` (
  `id_group_page_poste` int(10) UNSIGNED NOT NULL,
  `id_group_page` int(10) UNSIGNED DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `texte_poste` text,
  `image_poste` varchar(255) DEFAULT NULL,
  `date_poste` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_page_user`
--

CREATE TABLE `group_page_user` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_group_page` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id_message` int(10) UNSIGNED NOT NULL,
  `id_chat` int(10) UNSIGNED DEFAULT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `texte_message` text,
  `image_message` int(11) DEFAULT NULL,
  `date_message` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postes`
--

CREATE TABLE `postes` (
  `id_poste` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED DEFAULT NULL,
  `texte_poste` text NOT NULL,
  `image_poste` varchar(255) NOT NULL,
  `date_poste` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `postes`
--

INSERT INTO `postes` (`id_poste`, `id_user`, `texte_poste`, `image_poste`, `date_poste`) VALUES
(50, 2, 'salut', '6288d049864488.11933066.jpg', '2022-05-21 01:01:14'),
(51, 5, 'salut', '6288c8ca137467.36725225.jpg', '2022-05-21 01:11:06'),
(53, 8, 'nn jrigole dsl', '6288fd45613700.30420537.jpg', '2022-05-21 04:54:44'),
(55, 9, 'ptdr', '62890498b16061.44821386.jpg', '2022-05-21 05:25:31'),
(56, 9, 'xddd', '628904f11cc1b9.48858343.jpg', '2022-05-21 05:27:45'),
(57, 2, 'xddd', '', '2022-05-22 12:21:18');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `adresse_mail` varchar(255) NOT NULL,
  `numero_tel` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `prenom`, `nom`, `pseudo`, `adresse_mail`, `numero_tel`, `genre`, `date_naissance`, `mot_de_passe`) VALUES
(1, 'nolan', 'ramos', 'arislea', 'nolan.ramos.01@gmail.com', 613894062, 'homme', '2022-05-13', 'nolan'),
(2, 'fares', 'kerkeni', 'vinitydead', 'fares.kerkeni@gmail.com', 611223344, 'femme', '2022-05-13', 'fares'),
(3, 'Sandie', 'Ouallet', 'sandiiiiiiiiiiiiiiiie', 'sandieouallet@gmail.com', 655667788, 'homme', '2022-05-12', 'sandie'),
(4, 'Phara', 'Mlrx', 'Kenza Phara', 'phara.mlrx@yopmail.com', 989878778, 'non binaire', '2022-05-19', '1234'),
(5, 'huang', 'victor', 'vicohhhhh', 'vicoh@gmail.com', 888998899, 'je sais pas', '2022-05-19', 'vicoh'),
(8, 'theo', 'payen', 'kyt', 'theo@gmail.com', 508080909, 'homme', '2022-05-21', 'theo'),
(9, 'clement', 'yvars', 'clem', 'clement@gmail.com', 508090506, 'femme', '2022-05-21', 'clem');

-- --------------------------------------------------------

--
-- Table structure for table `user_chat`
--

CREATE TABLE `user_chat` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `id_chat` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_chat`
--

INSERT INTO `user_chat` (`id_user`, `id_chat`) VALUES
(2, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aime_commentaire`
--
ALTER TABLE `aime_commentaire`
  ADD PRIMARY KEY (`id_aime_commentaire`),
  ADD KEY `aime_commentaire_id_user_foreign` (`id_user`) USING BTREE,
  ADD KEY `aime_commentaire_id_commentaire_foreign` (`id_commentaire`) USING BTREE;

--
-- Indexes for table `aime_poste`
--
ALTER TABLE `aime_poste`
  ADD PRIMARY KEY (`id_aime_poste`),
  ADD KEY `aime_poste_id_user_foreign` (`id_user`),
  ADD KEY `aime_poste_id_poste_foreign` (`id_poste`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`);

--
-- Indexes for table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `commentaire_id_user_foreign` (`id_user`),
  ADD KEY `commentaire_id_poste_foreign` (`id_poste`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contacts_id_user_foreign` (`id_user`);

--
-- Indexes for table `description`
--
ALTER TABLE `description`
  ADD PRIMARY KEY (`id_description`),
  ADD KEY `description_id_user_foreign` (`id_user`);

--
-- Indexes for table `group_page`
--
ALTER TABLE `group_page`
  ADD PRIMARY KEY (`id_group_page`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `group_page_postes`
--
ALTER TABLE `group_page_postes`
  ADD PRIMARY KEY (`id_group_page_poste`),
  ADD KEY `id_group_page` (`id_group_page`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `group_page_user`
--
ALTER TABLE `group_page_user`
  ADD PRIMARY KEY (`id_user`,`id_group_page`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `messages_id_chat_foreign` (`id_chat`),
  ADD KEY `messages_id_user_foreign` (`id_user`);

--
-- Indexes for table `postes`
--
ALTER TABLE `postes`
  ADD PRIMARY KEY (`id_poste`),
  ADD KEY `postes_id_user_foreign` (`id_user`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `user_chat`
--
ALTER TABLE `user_chat`
  ADD PRIMARY KEY (`id_user`,`id_chat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aime_commentaire`
--
ALTER TABLE `aime_commentaire`
  MODIFY `id_aime_commentaire` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `aime_poste`
--
ALTER TABLE `aime_poste`
  MODIFY `id_aime_poste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `description`
--
ALTER TABLE `description`
  MODIFY `id_description` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `group_page`
--
ALTER TABLE `group_page`
  MODIFY `id_group_page` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `group_page_postes`
--
ALTER TABLE `group_page_postes`
  MODIFY `id_group_page_poste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `postes`
--
ALTER TABLE `postes`
  MODIFY `id_poste` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aime_commentaire`
--
ALTER TABLE `aime_commentaire`
  ADD CONSTRAINT `aime_commentaire_id_commentaire_foreign` FOREIGN KEY (`id_commentaire`) REFERENCES `commentaire` (`id_commentaire`),
  ADD CONSTRAINT `aime_commentaire_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `group_page`
--
ALTER TABLE `group_page`
  ADD CONSTRAINT `group_page_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `group_page_postes`
--
ALTER TABLE `group_page_postes`
  ADD CONSTRAINT `group_page_postes_ibfk_1` FOREIGN KEY (`id_group_page`) REFERENCES `group_page` (`id_group_page`),
  ADD CONSTRAINT `group_page_postes_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
