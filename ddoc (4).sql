-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 20-Maio-2024 às 18:30
-- Versão do servidor: 8.0.31
-- versão do PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET GLOBAL event_scheduler = ON;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ddoc`
--

DELIMITER $$
--
-- Procedimentos
--
DROP PROCEDURE IF EXISTS `CheckThemeIsFinished`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckThemeIsFinished` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE _theme_id INT;
    DECLARE _finish_date DATETIME;
    DECLARE cur CURSOR FOR SELECT theme_id, finish_date FROM theme WHERE is_finished = 0;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO _theme_id, _finish_date;
        IF done THEN
            LEAVE read_loop;
        END IF;

        IF _finish_date < NOW() THEN
            UPDATE theme SET is_finished = 1 WHERE theme_id = _theme_id;
            UPDATE posts SET Enabled = 1 WHERE theme_id = _theme_id;
        END IF;
    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `accountrankings`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `accountrankings`;
CREATE TABLE IF NOT EXISTS `accountrankings` (
`UserRank` bigint unsigned
,`UserName` text
,`TotalLikes` bigint
,`UserImage` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `accountrankingstype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `accountrankingstype`;
CREATE TABLE IF NOT EXISTS `accountrankingstype` (
`UserRank` bigint unsigned
,`UserName` text
,`TotalLikes` bigint
,`UserImage` varchar(50)
,`PostType` varchar(255)
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `definitions`
--

DROP TABLE IF EXISTS `definitions`;
CREATE TABLE IF NOT EXISTS `definitions` (
  `definicoes_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `privateProfile` int DEFAULT NULL,
  PRIMARY KEY (`definicoes_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `follower_id` int NOT NULL,
  `followee_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `follow`
--

INSERT INTO `follow` (`follower_id`, `followee_id`) VALUES
(8, 9),
(29, 9),
(15, 8),
(8, 11),
(8, 12),
(12, 8),
(8, 15);

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
) ENGINE=MyISAM AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `likes`
--

INSERT INTO `likes` (`like_id`, `user_id`, `post_id`) VALUES
(12, 12, 44),
(78, 8, 44),
(11, 12, 43),
(10, 8, 42),
(15, 1, 1),
(17, 1, 42),
(18, 1, 43),
(28, 8, 43),
(83, 8, 46),
(55, 8, 47),
(76, 14, 46),
(62, 14, 0),
(67, 8, 50),
(79, 8, 71),
(206, 8, 74),
(212, 29, 75);

-- --------------------------------------------------------

--
-- Estrutura da tabela `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int NOT NULL AUTO_INCREMENT,
  `messanger_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `Text` text COLLATE utf8mb4_general_ci NOT NULL,
  `convo_id` int NOT NULL,
  `DateTime` datetime NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver_id` int NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `date_sent`, `receiver_id`, `is_read`) VALUES
(207, 'User RealNice started following you', '2024-05-20 07:45:24', 9, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `post_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `caption` text COLLATE utf8mb4_general_ci,
  `theme_id` int NOT NULL,
  `Enabled` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `post_type`, `post_url`, `caption`, `theme_id`, `Enabled`, `created_at`, `updated_at`) VALUES
(43, 12, 'image', 'image-wired-outline-955-demand.gif-12.gif', 'Fixe Post', 5, 1, '2024-03-22 14:23:16', '2024-04-01 20:00:06'),
(44, 12, 'image', 'image-makeitmeme_ycJ2r.jpeg-12.jpeg', 'asdas', 5, 1, '2024-03-22 14:25:34', '2024-03-22 14:26:04'),
(46, 8, 'image', 'image-CM8.png-8.png', 'Fixolas', 6, 1, '2024-04-03 18:32:24', '2024-04-04 14:38:02'),
(74, 8, 'audio', 'audio-itense build up.mp3-8.mp3', 'Mauricio', 9, 0, '2024-05-15 23:06:07', '2024-05-19 18:26:42'),
(50, 8, 'audio', 'audio-itense build up.mp3-8.mp3', 'Fixolitas Magnificos', 7, 1, '2024-04-11 17:31:30', '2024-05-02 20:43:14'),
(59, 8, 'video', 'video-Mt 125 wheelie.mp4-8.mp4', 'Fodaasdasasdas', 7, 1, '2024-04-11 18:33:02', '2024-04-11 18:51:01'),
(71, 15, 'image', 'image-Nando.png-15.png', 'A volta da nação', 9, 0, '2024-05-14 20:59:01', '2024-05-19 18:23:25');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingposts`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingposts`;
CREATE TABLE IF NOT EXISTS `rankingposts` (
`PostId` int
,`PostRank` bigint unsigned
,`PostImage` varchar(255)
,`NameOfThePost` text
,`TYPE` varchar(255)
,`Likes` bigint
,`PersonWhoPostedIt` text
,`theme_id` int
,`IsFinished` int
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpostsall`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpostsall`;
CREATE TABLE IF NOT EXISTS `rankingpostsall` (
`PostRank` bigint unsigned
,`PostImage` varchar(255)
,`NameOfThePost` text
,`TYPE` varchar(255)
,`Likes` bigint
,`PersonWhoPostedIt` text
,`theme_id` int
,`IsFinished` int
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpostsotype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpostsotype`;
CREATE TABLE IF NOT EXISTS `rankingpostsotype` (
`PostRank` bigint unsigned
,`PostImage` varchar(255)
,`NameOfThePost` text
,`TYPE` varchar(255)
,`Likes` bigint
,`PersonWhoPostedIt` text
,`theme_id` int
,`IsFinished` int
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `rankingpoststype`
-- (Veja abaixo para a view atual)
--
DROP VIEW IF EXISTS `rankingpoststype`;
CREATE TABLE IF NOT EXISTS `rankingpoststype` (
`PostRank` bigint unsigned
,`PostImage` varchar(255)
,`NameOfThePost` text
,`TYPE` varchar(255)
,`Likes` bigint
,`PersonWhoPostedIt` text
,`theme_id` int
,`IsFinished` int
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE IF NOT EXISTS `report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` int NOT NULL,
  `post_id` int NOT NULL,
  `why` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `theme_id` int NOT NULL AUTO_INCREMENT,
  `theme` text COLLATE utf8mb4_general_ci NOT NULL,
  `finish_date` datetime NOT NULL,
  `is_finished` int DEFAULT '0',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `theme`
--

INSERT INTO `theme` (`theme_id`, `theme`, `finish_date`, `is_finished`) VALUES
(5, 'Expocolgaia', '2024-03-23 13:00:00', 1),
(4, 'Animais Fofinhos', '2024-03-19 22:51:00', 1),
(6, 'Teste 3', '2024-04-03 20:25:07', 1),
(7, 'Teste4', '2024-04-12 18:51:00', 1),
(8, 'Teste 8', '2024-05-06 15:48:00', 1),
(9, 'Anotha One', '2024-05-21 21:30:54', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_profilePic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_realName` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_biography` longtext COLLATE utf8mb4_general_ci,
  `is_verified` int DEFAULT NULL,
  `can_post` int DEFAULT '0',
  `user_lang` varchar(4) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pt',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_profilePic`, `user_realName`, `user_biography`, `is_verified`, `can_post`, `user_lang`) VALUES
(8, 'ADMIN', 'rafa.pinto.vieira@gmail.com', '$2y$10$YHqi2BhvQzXDhgYpwz/qLuzV18yzCylK4rY3.mRZ6wWkKTiGN.tWK', 'ProfilePic-makeitmeme_ZzjUu.jpeg-8.jpeg', 'asdasdasdas', 'HELOOOOO THEREEE', NULL, 0, 'pt'),
(9, 'SuggarDaddy', 'sugarisoverratedanyways@gmail.com', '$2y$10$OqK1mG6lmN.NU56ilbuOee8614ZVgVCk4RzzD7hgZuAiUTIDQku4q', 'ProfilePic-makeitmeme_vHF2x.jpeg-9.jpeg', 'Matos Diabetes', 'Tenho diabetes não perguntei és gay', NULL, 0, 'pt'),
(15, 'BatmanAgainstNi', 'FolhadoDesfolhado@gmail.com', '$2y$10$0mBYkOA2qT6wRhHK1NH9OuYkwojQROWikWAo61l2H33jTLy/6.nqC', 'ProfilePic-Melder.png-15.png', 'Odeio Pretos', 'Eu amo morcegos, mas odeio os preto da guiné (aka todos)', NULL, 0, 'pt'),
(11, 'CatarinaVieira_', 'cv06@gmail.com', '$2y$10$LkDsFsrXCE1hw2xyCaiV4u9K2sGXUr2NgwGMSBN1jT0AiqdSJlmNW', NULL, NULL, NULL, NULL, 0, 'pt'),
(12, 'BulkMasters', 'Expo@gmail.com', '$2y$10$MPmeWav54LgXeqXmszq/Y.g/uPGZsN1/DASGgDnYNi.6/CnuhlpW2', 'ProfilePic-Passaro.jpg-12.jpg', 'Bulkiest Mastery', 'Nós somos os BulkMasters, votem em nós', NULL, 0, 'pt'),
(14, 'Ferras', 'ferras@gmail.com', '$2y$10$wJT0kbveeId7zC7gEnLE8uhBLpebR0clZXBJjubXqVKGf62TDJcZa', NULL, NULL, NULL, NULL, 0, 'pt'),
(28, 'Tester3', 'tester3@gmail.com', '$2y$10$VGlaFovEmFx3DU7o.q/woO.iscJZSQuIvo6oHoTxsW9yYnsC4OH6C', NULL, NULL, NULL, NULL, 0, 'pt'),
(29, 'RealNice', 'testenovo@gmail.com', '$2y$10$s8JUaIXFUO7LtROV7EQDOuVkYkSXn7PCLr4xmdQgyZW4B98mLmge.', 'ProfilePic-makeitmeme_TchXy.jpeg-29.jpeg', 'Rafael Johny', 'Eu sou muito engrançado', NULL, 0, 'pt');

-- --------------------------------------------------------

--
-- Estrutura para vista `accountrankings`
--
DROP TABLE IF EXISTS `accountrankings`;

DROP VIEW IF EXISTS `accountrankings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankings`  AS SELECT row_number() OVER (ORDER BY ifnull(count(`l`.`post_id`),0) desc ) AS `UserRank`, `u`.`user_name` AS `UserName`, ifnull(count(`l`.`post_id`),0) AS `TotalLikes`, `u`.`user_profilePic` AS `UserImage` FROM ((`users` `u` left join `posts` `p` on((`u`.`user_id` = `p`.`user_id`))) left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) GROUP BY `u`.`user_id`, `u`.`user_name`, `u`.`user_profilePic`  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `accountrankingstype`
--
DROP TABLE IF EXISTS `accountrankingstype`;

DROP VIEW IF EXISTS `accountrankingstype`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankingstype`  AS SELECT row_number() OVER (PARTITION BY `p`.`post_type` ORDER BY ifnull(count(`l`.`post_id`),0) desc ) AS `UserRank`, `u`.`user_name` AS `UserName`, ifnull(count(`l`.`post_id`),0) AS `TotalLikes`, `u`.`user_profilePic` AS `UserImage`, `p`.`post_type` AS `PostType` FROM ((`users` `u` left join `posts` `p` on((`u`.`user_id` = `p`.`user_id`))) left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) WHERE (`p`.`post_type` is not null) GROUP BY `p`.`post_type`, `u`.`user_id`, `u`.`user_name`, `u`.`user_profilePic`  ;

-- --------------------------------------------------------

--
-- Estrutura para vista `rankingposts`
--
DROP TABLE IF EXISTS `rankingposts`;

DROP VIEW IF EXISTS `rankingposts`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingposts`  AS SELECT `p`.`post_id` AS `PostId`, row_number() OVER (PARTITION BY `t`.`theme_id` ORDER BY count(`l`.`post_id`) desc ) AS `PostRank`, `p`.`post_url` AS `PostImage`, `p`.`caption` AS `NameOfThePost`, `p`.`post_type` AS `TYPE`, count(`l`.`post_id`) AS `Likes`, `u`.`user_name` AS `PersonWhoPostedIt`, `t`.`theme_id` AS `theme_id`, `t`.`is_finished` AS `IsFinished` FROM (((`posts` `p` left join `likes` `l` on((`p`.`post_id` = `l`.`post_id`))) left join `users` `u` on((`p`.`user_id` = `u`.`user_id`))) left join `theme` `t` on((`p`.`theme_id` = `t`.`theme_id`))) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`theme_id`, `t`.`is_finished` ORDER BY `t`.`theme_id` ASC, `PostRank` ASC  ;

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

DELIMITER $$
--
-- Eventos
--
DROP EVENT IF EXISTS `CheckThemeIsFinished`$$
CREATE DEFINER=`root`@`localhost` EVENT `CheckThemeIsFinished` ON SCHEDULE EVERY 1 MINUTE STARTS '2024-05-02 15:44:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL CheckThemeIsFinished()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
