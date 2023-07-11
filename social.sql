-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 11 juil. 2023 à 21:06
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `social`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'cool photo', '2023-07-01 12:02:48', '2023-07-01 12:02:48'),
(2, 1, 1, 'jjjjjjj', '2023-07-01 12:50:15', '2023-07-01 12:50:15'),
(3, 1, 1, 'bonjour comment sava ?', '2023-07-01 17:48:32', '2023-07-01 17:48:32'),
(4, 1, 2, 'salut les gens', '2023-07-01 17:48:44', '2023-07-01 17:48:44'),
(5, 1, 2, 'comment vous allez ?', '2023-07-01 17:49:02', '2023-07-01 17:49:02'),
(6, 2, 1, 'salut', '2023-07-01 18:48:30', '2023-07-01 18:48:30'),
(7, 2, 2, 'salur', '2023-07-01 18:49:53', '2023-07-01 18:49:53'),
(8, 2, 2, 'Lorem ipsum dolor sitelt amet, consectetur adipis icing elit, ', '2023-07-01 18:50:56', '2023-07-04 15:37:16'),
(9, 2, 2, 'tempor incididunt utitily labore et dolore magna aliqua metavta.', '2023-07-01 18:53:59', '2023-07-01 18:53:59'),
(10, 1, 1, 'salut les gens', '2023-07-02 12:39:13', '2023-07-02 12:39:13'),
(11, 1, 1, 'oskdjdjdjjdjdjdjdsmsm', '2023-07-02 12:40:37', '2023-07-02 12:40:37'),
(12, 1, 2, 'salut', '2023-07-02 12:42:32', '2023-07-02 12:42:32'),
(13, 1, 2, 'messi le goat', '2023-07-02 13:03:39', '2023-07-02 13:03:39'),
(14, 1, 2, 'cool', '2023-07-02 13:07:16', '2023-07-02 13:07:16'),
(21, 2, 4, 'jjj', '2023-07-06 12:09:25', '2023-07-06 12:09:25'),
(22, 2, 1, 'chh\r\n', '2023-07-06 12:29:03', '2023-07-06 12:29:03'),
(23, 2, 4, 'qjq\r\n', '2023-07-06 12:49:16', '2023-07-06 12:49:16'),
(24, 2, 4, 'wndjlnd', '2023-07-06 12:49:24', '2023-07-06 12:49:24'),
(25, 3, 24, 'salut', '2023-07-06 21:36:46', '2023-07-06 21:36:46'),
(26, 3, 24, 'comment sava les gens', '2023-07-06 21:36:57', '2023-07-06 21:36:57'),
(27, 3, 24, 'kkkkk\r\n', '2023-07-07 12:35:19', '2023-07-07 12:35:19'),
(28, 3, 4, 'momo ', '2023-07-08 13:17:03', '2023-07-08 13:17:03'),
(29, 3, 25, ' Lorem ipsum dolor sit amet consectetur adipisicing elit.', '2023-07-08 17:44:08', '2023-07-10 16:03:22'),
(30, 3, 25, ' Lorem ipsum dolor sit amet consectetur adipisicing elit.', '2023-07-10 15:00:12', '2023-07-10 16:04:50'),
(31, 3, 4, 'momo kkkkkk', '2023-07-10 15:39:43', '2023-07-10 15:39:43'),
(32, 3, 4, 'Des paroles vraiment sage ', '2023-07-10 15:40:13', '2023-07-11 01:02:34'),
(33, 3, 25, 'salut', '2023-07-10 17:22:50', '2023-07-10 17:22:50'),
(34, 3, 25, 'ssssssssss', '2023-07-10 17:33:19', '2023-07-10 17:33:19'),
(35, 3, 25, 'salut', '2023-07-10 17:46:29', '2023-07-10 17:46:29'),
(36, 3, 25, 'one peace ', '2023-07-10 17:46:46', '2023-07-10 17:52:24');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `isLike` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `isLike`, `created_at`) VALUES
(15, 1, 2, 0, '2023-07-01 18:43:00'),
(16, 2, 2, 0, '2023-07-01 19:17:21'),
(19, 2, 1, 0, '2023-07-01 19:18:39'),
(20, 2, 3, 0, '2023-07-02 19:21:16'),
(21, 1, 3, 0, '2023-07-05 00:32:44'),
(27, 3, 3, 0, '2023-07-05 10:34:48'),
(198, 3, 2, 0, '2023-07-05 11:47:19'),
(201, 2, 22, 0, '2023-07-06 12:43:55'),
(202, 2, 4, 0, '2023-07-06 12:49:45'),
(237, 3, 23, 0, '2023-07-06 23:09:00'),
(240, 3, 0, 0, '2023-07-07 16:55:10'),
(242, 2, 25, 0, '2023-07-07 22:53:47'),
(243, 3, 1, 0, '2023-07-08 17:17:15'),
(244, 3, 4, 0, '2023-07-10 13:48:39'),
(245, 3, 25, 0, '2023-07-10 17:52:41'),
(246, 3, 28, 0, '2023-07-11 16:08:52');

-- --------------------------------------------------------

--
-- Structure de la table `partager`
--

CREATE TABLE `partager` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `statut` varchar(250) NOT NULL,
  `date_partage` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `picture` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `picture`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'R.png', 'Lorem ipsum dolor sitelt amet, consectetur adipis icing elit, sed do eiusmod tempor incididunt utitily labore et dolore magna aliqua metavta.', '2023-07-01 12:02:24', '2023-07-01 12:02:24'),
(2, 1, 'messi(1).jpg', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque', '2023-07-01 17:44:59', '2023-07-01 17:44:59'),
(4, 3, '113948823.png', '\"La vraie sagesse consiste à ne pas s\'émerveiller devant ce qui est éphémère, mais à embrasser ce qui est durable.\" - Socrate', '2023-07-05 11:01:16', '2023-07-07 23:34:54'),
(23, 2, 'llll (1).png', 'Luffy est le protagoniste principal du manga et de l\'anime \"One Piece\", créé par Eiichiro Oda. Il est le capitaine de l\'équipage des Chapeaux de paille, également connu sous le nom de l\'équipage de Luffy. Luffy est un pirate avec comme objectif ultime de trouver le trésor légendaire connu sous le nom de \"One Piece\" et de devenir le roi des pirates.', '2023-07-06 21:11:10', '2023-07-06 21:11:10'),
(28, 3, 'albert-einstein-with-blue-hair-midjourney-4-1.jpg', 'Einstein ', '2023-07-11 00:50:29', '2023-07-11 00:50:29');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profil` text DEFAULT '\'profil.png\'',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `profil`, `password`, `created_at`, `updated_at`) VALUES
(1, 'King', 'Mohamed123@gmail.com', 'R.png', '$2y$10$Z2kI4Acc8mCvzuOGUKJuK.zPcB7RJJ7BTI6J1PSL011hEqrTVUOw2', '2023-07-01 12:00:14', '2023-07-02 11:43:47'),
(2, 'Haker', 'haker@gmail.com', '113948823.png', '$2y$10$NNeX8kGF/mrkqSFKIKTqVeUMPELM71sRPZwFfojC70/JY7iFvhW5m', '2023-07-01 12:00:39', '2023-07-06 12:09:50'),
(3, 'Luffy', 'onePeace@gmail.com', 'midjourney_all_this_useless_beauty.jpg', '$2y$10$y89gLL5rzrnqcV3q6M8.X.QUVN32xrBOXQ2Ny66M.jNXNh1mhv0JS', '2023-07-05 00:03:33', '2023-07-10 14:04:18'),
(4, 'Mohamed kone', 'kone35811@gmail.com', 'albert-einstein-with-blue-hair-midjourney-4-1.jpg', '$2y$10$V2ZkzOCAayLhUrFbcHjSMOGl5l3tUSju/5xiQUsJ/dPL9Ibef9TjS', '2023-07-10 18:31:40', '2023-07-10 18:34:41'),
(5, 'Camara', 'caramara@gmail.com', 'albert-einstein-with-blue-hair-midjourney-4-1.jpg', '$2y$10$HlMyJKEOQ2zm0Usc96NDBeU7YPH17z7fmvRUa9CA40A2qXodX1DLG', '2023-07-11 18:38:45', '2023-07-11 18:38:45');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partager`
--
ALTER TABLE `partager`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `partager_ibfk_1` (`user_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT pour la table `partager`
--
ALTER TABLE `partager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `partager`
--
ALTER TABLE `partager`
  ADD CONSTRAINT `partager_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
