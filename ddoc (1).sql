-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 05-Mar-2024 às 21:39
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
-- Estrutura da tabela `convo`
--

DROP TABLE IF EXISTS `convo`;
CREATE TABLE IF NOT EXISTS `convo` (
  `convo_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ouser_id` int NOT NULL,
  PRIMARY KEY (`convo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `definições`
--

DROP TABLE IF EXISTS `definições`;
CREATE TABLE IF NOT EXISTS `definições` (
  `definicoes_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `privateProfile` int DEFAULT NULL,
  PRIMARY KEY (`definicoes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `follower_id` int NOT NULL,
  `followee_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `follow`
--

INSERT INTO `follow` (`follower_id`, `followee_id`) VALUES
(8, 9),
(10, 9),
(10, 8),
(8, 10);

-- --------------------------------------------------------

--
-- Estrutura da tabela `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `messanger_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `Text` text NOT NULL,
  `convo_id` int NOT NULL,
  `DateTime` datetime NOT NULL,
  PRIMARY KEY (`message_id`)
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
  `theme_id` int NOT NULL,
  `Enabled` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `theme_id` int NOT NULL AUTO_INCREMENT,
  `theme` text NOT NULL,
  `finish_date` datetime NOT NULL,
  `is_finished` int DEFAULT NULL,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_profilePic`, `user_realName`, `user_biography`, `is_verified`) VALUES
(8, 'ADMIN', 'rafa.pinto.vieira@gmail.com', '$2y$10$YHqi2BhvQzXDhgYpwz/qLuzV18yzCylK4rY3.mRZ6wWkKTiGN.tWK', 'ProfilePic-makeitmeme_4GenC.jpeg-8.jpeg', 'FODA', 'Eu sou mega Feliz', NULL),
(9, 'SuggarDaddy', 'sugarisoverratedanyways@gmail.com', '$2y$10$OqK1mG6lmN.NU56ilbuOee8614ZVgVCk4RzzD7hgZuAiUTIDQku4q', 'ProfilePic-makeitmeme_vHF2x.jpeg-9.jpeg', 'Matos Diabetes', 'Tenho diabetes não perguntei és gay', NULL),
(10, 'Batman6969Ultra', 'FolhadoDesfolhado@gmail.com', '$2y$10$6XjlaKmE3L0OlM5.jzOhF.zI5iQE0FHtPYjdzenK7LkVUC3wuj5Dm', 'ProfilePic-makeitmeme_GSaLW.gif-10.gif', 'XPAMA', 'Eu sou o BarTman (Sou Autista Extresmo)', NULL),
(11, 'CatarinaVieira_', 'cv06@gmail.com', '$2y$10$LkDsFsrXCE1hw2xyCaiV4u9K2sGXUr2NgwGMSBN1jT0AiqdSJlmNW', NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
