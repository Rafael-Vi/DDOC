-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 17-Abr-2024 às 06:40
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
CREATE DATABASE IF NOT EXISTS `ddoc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `ddoc`;

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `accountrankings`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `accountrankings`;
CREATE TABLE IF NOT EXISTS `accountrankings` (
`TotalLikes` bigint
,`UserImage` varchar(50)
,`UserName` text
,`UserRank` bigint unsigned
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `accountrankingstype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `accountrankingstype`;
CREATE TABLE IF NOT EXISTS `accountrankingstype` (
`PostType` varchar(255)
,`TotalLikes` bigint
,`UserImage` varchar(50)
,`UserName` text
,`UserRank` bigint unsigned
);

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
(8, 10),
(8, 11),
(8, 12);

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
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_id`) VALUES
(12, 12, 44),
(29, 8, 44),
(11, 12, 43),
(10, 8, 42),
(15, 1, 1),
(17, 1, 42),
(18, 1, 43),
(28, 8, 43),
(53, 8, 46),
(51, 8, 47);

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
-- Estrutura da tabela `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `date_sent`, `receiver_id`) VALUES
(27, 'User ADMIN liked your post', '2024-04-11 18:43:20', 8);

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
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_type`, `post_url`, `caption`, `theme_id`, `Enabled`, `created_at`, `updated_at`) VALUES
(43, 12, 'image', 'image-wired-outline-955-demand.gif-12.gif', 'Fixe Post', 5, 1, '2024-03-22 14:23:16', '2024-04-01 20:00:06'),
(44, 12, 'image', 'image-makeitmeme_ycJ2r.jpeg-12.jpeg', 'asdas', 5, 1, '2024-03-22 14:25:34', '2024-03-22 14:26:04'),
(46, 8, 'image', 'image-CM8.png-8.png', 'Fixolas', 6, 1, '2024-04-03 18:32:24', '2024-04-04 14:38:02'),
(47, 8, 'image', 'image-makeitmeme_rdBMA.jpeg-8.jpeg', 'Corrida', 7, 1, '2024-04-04 16:28:11', '2024-04-11 18:51:01'),
(48, 8, 'image', 'image-makeitmeme_nW2Yh.jpeg-8.jpeg', 'asdasdasdasdas', 7, 1, '2024-04-08 21:22:17', '2024-04-11 18:51:01'),
(49, 8, 'image', 'image-Jorche.png-8.png', 'adsdasdasdasdas', 7, 1, '2024-04-09 20:44:35', '2024-04-11 18:51:01'),
(50, 8, 'audio', 'audio-itense build up.mp3-8.mp3', 'Fixe Post Magnífico', 7, 1, '2024-04-11 17:31:30', '2024-04-11 18:51:01'),
(59, 8, 'video', 'video-Mt 125 wheelie.mp4-8.mp4', 'Fodaasdasasdas', 7, 1, '2024-04-11 18:33:02', '2024-04-11 18:51:01');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingposts`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingposts`;
CREATE TABLE IF NOT EXISTS `rankingposts` (
`IsFinished` int
,`Likes` bigint
,`NameOfThePost` text
,`PersonWhoPostedIt` text
,`PostImage` varchar(255)
,`PostRank` bigint unsigned
,`theme_id` int
,`TYPE` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpostsall`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpostsall`;
CREATE TABLE IF NOT EXISTS `rankingpostsall` (
`IsFinished` int
,`Likes` bigint
,`NameOfThePost` text
,`PersonWhoPostedIt` text
,`PostImage` varchar(255)
,`PostRank` bigint unsigned
,`theme_id` int
,`TYPE` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpostsotype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpostsotype`;
CREATE TABLE IF NOT EXISTS `rankingpostsotype` (
`IsFinished` int
,`Likes` bigint
,`NameOfThePost` text
,`PersonWhoPostedIt` text
,`PostImage` varchar(255)
,`PostRank` bigint unsigned
,`theme_id` int
,`TYPE` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpoststype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpoststype`;
CREATE TABLE IF NOT EXISTS `rankingpoststype` (
`IsFinished` int
,`Likes` bigint
,`NameOfThePost` text
,`PersonWhoPostedIt` text
,`PostImage` varchar(255)
,`PostRank` bigint unsigned
,`theme_id` int
,`TYPE` varchar(255)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `theme_id` int NOT NULL AUTO_INCREMENT,
  `theme` text NOT NULL,
  `finish_date` datetime NOT NULL,
  `is_finished` int DEFAULT '0',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `theme`
--

INSERT INTO `theme` (`theme_id`, `theme`, `finish_date`, `is_finished`) VALUES
(5, 'Expocolgaia', '2024-03-23 13:00:00', 1),
(4, 'Animais Fofinhos', '2024-03-19 22:51:00', 1),
(6, 'Teste 3', '2024-04-03 20:25:07', 1),
(7, 'Teste4', '2024-04-12 18:51:00', 1),
(8, 'Teste 8', '2024-04-18 19:05:02', 0);

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
  `can_post` int DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_profilePic`, `user_realName`, `user_biography`, `is_verified`, `can_post`) VALUES
(8, 'ADMIN', 'rafa.pinto.vieira@gmail.com', '$2y$10$YHqi2BhvQzXDhgYpwz/qLuzV18yzCylK4rY3.mRZ6wWkKTiGN.tWK', 'ProfilePic-makeitmeme_4GenC.jpeg-8.jpeg', 'FODA', 'Eu sou mega Feliz', NULL, 0),
(9, 'SuggarDaddy', 'sugarisoverratedanyways@gmail.com', '$2y$10$OqK1mG6lmN.NU56ilbuOee8614ZVgVCk4RzzD7hgZuAiUTIDQku4q', 'ProfilePic-makeitmeme_vHF2x.jpeg-9.jpeg', 'Matos Diabetes', 'Tenho diabetes não perguntei és gay', NULL, 0),
(10, 'Batman6969Ultra', 'FolhadoDesfolhado@gmail.com', '$2y$10$6XjlaKmE3L0OlM5.jzOhF.zI5iQE0FHtPYjdzenK7LkVUC3wuj5Dm', 'ProfilePic-makeitmeme_GSaLW.gif-10.gif', 'XPAMA', 'Eu sou o BarTman (Sou Autista Extresmo)', NULL, 0),
(11, 'CatarinaVieira_', 'cv06@gmail.com', '$2y$10$LkDsFsrXCE1hw2xyCaiV4u9K2sGXUr2NgwGMSBN1jT0AiqdSJlmNW', NULL, NULL, NULL, NULL, 0),
(12, 'BulkMasters', 'Expo@gmail.com', '$2y$10$MPmeWav54LgXeqXmszq/Y.g/uPGZsN1/DASGgDnYNi.6/CnuhlpW2', 'ProfilePic-Passaro.jpg-12.jpg', 'Bulkiest Mastery', 'Nós somos os BulkMasters, votem em nós', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para vista `accountrankings`
--
DROP TABLE IF EXISTS `accountrankings`;

DROP VIEW IF EXISTS `accountrankings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankings`  AS SELECT row_number() OVER (ORDER BY ifnull(count(`l`.`post_id`),0) desc ) AS `UserRank`, `u`.`user_name` AS `UserName`, ifnull(count(`l`.`post_id`),0) AS `TotalLikes`, `u`.`user_profilePic` AS `UserImage` FROM ((`users` `u` left join `posts` `p` on((`u`.`user_id` = `p`.`user_id`))) left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) GROUP BY `u`.`user_id`, `u`.`user_name`, `u`.`user_profilePic``user_profilePic`  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `accountrankingstype`
--
DROP TABLE IF EXISTS `accountrankingstype`;

DROP VIEW IF EXISTS `accountrankingstype`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankingstype`  AS SELECT row_number() OVER (PARTITION BY `p`.`post_type` ORDER BY ifnull(count(`l`.`post_id`),0) desc ) AS `UserRank`, `u`.`user_name` AS `UserName`, ifnull(count(`l`.`post_id`),0) AS `TotalLikes`, `u`.`user_profilePic` AS `UserImage`, `p`.`post_type` AS `PostType` FROM ((`users` `u` left join `posts` `p` on((`u`.`user_id` = `p`.`user_id`))) left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) WHERE (`p`.`post_type` is not null) GROUP BY `p`.`post_type`, `u`.`user_id`, `u`.`user_name`, `u`.`user_profilePic``user_profilePic`  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `rankingposts`
--
DROP TABLE IF EXISTS `rankingposts`;

DROP VIEW IF EXISTS `rankingposts`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingposts`  AS SELECT row_number() OVER (PARTITION BY `t`.`theme_id` ORDER BY count(`l`.`post_id`) desc ) AS `PostRank`, `p`.`post_url` AS `PostImage`, `p`.`caption` AS `NameOfThePost`, `p`.`post_type` AS `TYPE`, count(`l`.`post_id`) AS `Likes`, `u`.`user_name` AS `PersonWhoPostedIt`, `t`.`theme_id` AS `theme_id`, `t`.`is_finished` AS `IsFinished` FROM (((`posts` `p` left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) left join `users` `u` on((`p`.`user_id` = `u`.`user_id`))) left join `theme` `t` on((`p`.`theme_id` = `t`.`theme_id`))) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`theme_id`, `t`.`is_finished` ORDER BY `t`.`theme_id` ASC, `PostRank` ASC  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `rankingpostsall`
--
DROP TABLE IF EXISTS `rankingpostsall`;

DROP VIEW IF EXISTS `rankingpostsall`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpostsall`  AS SELECT row_number() OVER (ORDER BY count(`l`.`post_id`) desc ) AS `PostRank`, `p`.`post_url` AS `PostImage`, `p`.`caption` AS `NameOfThePost`, `p`.`post_type` AS `TYPE`, count(`l`.`post_id`) AS `Likes`, `u`.`user_name` AS `PersonWhoPostedIt`, `t`.`theme_id` AS `theme_id`, `t`.`is_finished` AS `IsFinished` FROM (((`posts` `p` left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) left join `users` `u` on((`p`.`user_id` = `u`.`user_id`))) left join `theme` `t` on((`p`.`theme_id` = `t`.`theme_id`))) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`theme_id`, `t`.`is_finished` ORDER BY `PostRank` ASC  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `rankingpostsotype`
--
DROP TABLE IF EXISTS `rankingpostsotype`;

DROP VIEW IF EXISTS `rankingpostsotype`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpostsotype`  AS SELECT row_number() OVER (PARTITION BY `p`.`post_type` ORDER BY count(`l`.`post_id`) desc ) AS `PostRank`, `p`.`post_url` AS `PostImage`, `p`.`caption` AS `NameOfThePost`, `p`.`post_type` AS `TYPE`, count(`l`.`post_id`) AS `Likes`, `u`.`user_name` AS `PersonWhoPostedIt`, `t`.`theme_id` AS `theme_id`, `t`.`is_finished` AS `IsFinished` FROM (((`posts` `p` left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) left join `users` `u` on((`p`.`user_id` = `u`.`user_id`))) left join `theme` `t` on((`p`.`theme_id` = `t`.`theme_id`))) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`theme_id`, `t`.`is_finished` ORDER BY `t`.`theme_id` ASC, `PostRank` ASC  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `rankingpoststype`
--
DROP TABLE IF EXISTS `rankingpoststype`;

DROP VIEW IF EXISTS `rankingpoststype`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpoststype`  AS SELECT row_number() OVER (PARTITION BY `t`.`theme_id`,`p`.`post_type` ORDER BY count(`l`.`post_id`) desc ) AS `PostRank`, `p`.`post_url` AS `PostImage`, `p`.`caption` AS `NameOfThePost`, `p`.`post_type` AS `TYPE`, count(`l`.`post_id`) AS `Likes`, `u`.`user_name` AS `PersonWhoPostedIt`, `t`.`theme_id` AS `theme_id`, `t`.`is_finished` AS `IsFinished` FROM (((`posts` `p` left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) left join `users` `u` on((`p`.`user_id` = `u`.`user_id`))) left join `theme` `t` on((`p`.`theme_id` = `t`.`theme_id`))) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`theme_id`, `t`.`is_finished` ORDER BY `t`.`theme_id` ASC, `PostRank` ASC  ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
