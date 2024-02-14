-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 14-Fev-2024 às 07:34
-- Versão do servidor: 8.0.31
-- versão do PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ddoc`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `followers`
--

DROP TABLE IF EXISTS `followers`;
CREATE TABLE IF NOT EXISTS `followers` (
  `user_id` int NOT NULL,
  `follower_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`follower_id`),
  KEY `follower_id` (`follower_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `following`
--

DROP TABLE IF EXISTS `following`;
CREATE TABLE IF NOT EXISTS `following` (
  `user_id` int NOT NULL,
  `followed_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`followed_id`),
  KEY `followed_id` (`followed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `like_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  PRIMARY KEY (`like_id`),
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `post_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `caption` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_type`, `post_url`, `caption`, `created_at`, `updated_at`) VALUES
(33, 8, 'image', 'image-makeitmeme_oW5xf.jpeg-8.jpeg', 'Tren', '2024-02-07 17:44:08', '2024-02-07 18:18:34'),
(34, 8, 'audio', 'audio-rodrigo-goes-recording-11-17-2023-14-56-41.mp3-8.mp3', 'Audio Rodrigo', '2024-02-07 18:27:26', '2024-02-07 18:28:45'),
(35, 9, 'image', 'image-Fodasse.png-9.png', 'Insubetes', '2024-02-08 15:48:42', '2024-02-08 15:48:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `theme_id` int NOT NULL AUTO_INCREMENT,
  `theme` text NOT NULL,
  `is_finished` int DEFAULT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_profilePic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user_realName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_biography` longtext,
  `is_verified` int DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_profilePic`, `user_realName`, `user_biography`, `is_verified`) VALUES
(8, 'ADMIN', 'rafa.pinto.vieira@gmail.com', '$2y$10$YHqi2BhvQzXDhgYpwz/qLuzV18yzCylK4rY3.mRZ6wWkKTiGN.tWK', 'ProfilePic-makeitmeme_4GenC.jpeg-8.jpeg', 'FODA', 'Eu sou mega Feliz', NULL),
(9, 'SuggarDaddy', 'sugarisoverratedanyways@gmail.com', '$2y$10$OqK1mG6lmN.NU56ilbuOee8614ZVgVCk4RzzD7hgZuAiUTIDQku4q', 'ProfilePic-makeitmeme_vHF2x.jpeg-9.jpeg', 'Matos Diabetes', 'Tenho diabetes não perguntei és gay', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
