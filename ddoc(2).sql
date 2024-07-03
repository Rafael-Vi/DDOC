-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2024 at 01:48 PM
-- Server version: 10.11.6-MariaDB-0+deb12u1
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ddoc`
--
CREATE DATABASE IF NOT EXISTS `ddoc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ddoc`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckThemeIsFinished` ()   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE _id_theme INT;
    DECLARE _finish_date DATETIME;
    DECLARE cur CURSOR FOR SELECT id_theme, finish_date FROM theme WHERE is_finished = 0;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO _id_theme, _finish_date;
        IF done THEN
            LEAVE read_loop;
        END IF;

        IF _finish_date < DATE_ADD(NOW(), INTERVAL 1 HOUR) THEN
            UPDATE theme SET is_finished = 1 WHERE id_theme = _id_theme;
            -- Update the posts table, setting Enabled to 1 for the matching id_theme
            UPDATE posts SET Enabled = 1 WHERE id_theme = _id_theme;
            -- Disable posting for all users when any theme finishes
            UPDATE users SET can_post = 0;
        END IF;
    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `accountrankings`
-- (See below for the actual view)
--
CREATE TABLE `accountrankings` (
`UserRank` bigint(21)
,`id_users` int(11)
,`UserName` text
,`TotalLikes` bigint(21)
,`UserImage` varchar(50)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `accountrankingstype`
-- (See below for the actual view)
--
CREATE TABLE `accountrankingstype` (
`UserRank` bigint(21)
,`id_users` int(11)
,`UserName` text
,`TotalLikes` bigint(21)
,`UserImage` varchar(50)
,`PostType` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `database_status`
--

CREATE TABLE `database_status` (
  `id_database_status` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `database_status`
--

INSERT INTO `database_status` (`id_database_status`, `status`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `follow`
--

CREATE TABLE `follow` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follow`
--

INSERT INTO `follow` (`id`, `follower_id`, `followee_id`) VALUES
(15, 34, 8),
(84, 48, 0),
(121, 8, 0),
(143, 8, 34),
(149, 34, 48),
(150, 68, 8),
(151, 8, 68),
(152, 67, 8),
(155, 67, 68),
(156, 67, 34),
(157, 8, 69),
(158, 69, 8),
(159, 67, 69),
(161, 69, 67),
(163, 34, 68),
(164, 8, 67);

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `id_users`, `post_id`) VALUES
(1567, 8, 152),
(1568, 34, 153),
(1569, 8, 153),
(1574, 8, 158),
(1576, 67, 158),
(1619, 67, 157),
(1667, 34, 158),
(1668, 34, 160),
(1670, 67, 160),
(1700, 69, 158),
(1703, 34, 162),
(1704, 69, 162);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `messenger_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `DateTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `messenger_id`, `receiver_id`, `message`, `DateTime`) VALUES
(153, 8, 34, 'clJOSHJVVzEzRkZNclJNdkt5d2tLZz09OjpaUFVUVDJNdWROVVRGaUh1YWtubUNnPT0=', '2024-06-25 19:59:50'),
(154, 34, 8, 'L0k1Z2J6bGNPQys2MTBsQnZyMWU2QT09OjpOc0hIM1ZsWTAzL0ZLN2tDQnJRWk5BPT0=', '2024-06-25 20:01:16'),
(155, 34, 8, 'MHE5b21BY0xEb0FqYmNlZVphY0d1UT09OjpCN21BVzZZMHVIcGp4dFYzaUVkTTFBPT0=', '2024-06-25 21:37:47'),
(159, 8, 34, 'UFNkLzN6ak1LQzYrRUQxQ0IxcjFkdz09OjpDYWtxUEVGbGwxL0l4VWptZURNLzRnPT0=', '2024-06-27 19:10:34'),
(160, 34, 8, 'NkwxdG5WS0JmVzUrVkxIcXN1VWUvUT09OjpLUndRQlhtblFKZTBVOXdIWXNnZWtRPT0=', '2024-06-27 19:46:22'),
(161, 8, 34, 'Q1lrUEFNaERwTnFPeHFGMVNVYXRNQT09OjphZUtOd2ZDbTJpTnY1NUdSKzZRa05nPT0=', '2024-06-27 19:58:14'),
(163, 8, 34, 'OWR2VVZLOW5zN3I3dW9jL3JJRkYxV0N1Ym9NazBjN3RiV1JqcDZVR0pwdz06OjBXQ3VBQW5HRUltMXFEbFFDV2FrZHc9PQ==', '2024-06-27 19:58:23'),
(164, 34, 8, 'U09xTk1uWXlteHdtZ3dFdHpadWVJUT09OjpFRVdZZXcraUh5LzZUWVFqK1BKdUF3PT0=', '2024-06-27 20:03:20'),
(165, 34, 8, 'S2h3d2I3c0dLSHdxd25KaDlhdnM0UT09OjoyQ3JjSDRRVTRRR2Zuci81djE3L01BPT0=', '2024-06-27 20:03:23'),
(166, 8, 34, 'Z3FiLzhzbmdJZ2xmaFBtZ1NOd1dpSTEvOGlqZTA4dmZSK2JHVUxhY1hFND06OjRGOW9OSDNHc3BmL3B0VFZBbm5wOHc9PQ==', '2024-06-27 20:04:41'),
(167, 8, 34, 'TllvYlFWZVVpV3pvRTUyT1J3QngyaS9FY2E0VXFTOXFJUmkzMVVTQ1FTQndlc1NETlR0ZDNvWmE2ZWVLSk9hMjo6eHNxdjRkeDBxRU41VXdXNE9COHRHUT09', '2024-06-27 20:04:49'),
(168, 8, 68, 'OVhvMW9RZGVZanVlUE9SK2R0bWtPZz09OjptQUIzSjJNcVRlbGFqU0g0QWNtNjlBPT0=', '2024-06-27 20:11:24'),
(169, 8, 68, 'U2lGV1p6bnpubVg3Y3dMVnhaWmRaSDcxOW4wL3JVb2RRR3FOM3FQZ3g5ND06OlR2TmpGVUVKNkFYQWhZMlhpV3RpZGc9PQ==', '2024-06-27 20:11:28'),
(170, 68, 8, 'am5ReXBkZVE1K1ZjcHYzOHJNYlZvZz09OjpnUUdJU0VNQTQzR01YZEtVV09Wb2FnPT0=', '2024-06-27 20:11:32'),
(171, 68, 8, 'OGQ5OHI2VkRSdlVnb3dDcWNoWDRLZz09Ojplem90bk0vSElrQkFId1RKWTZSL0VnPT0=', '2024-06-27 20:11:36'),
(172, 68, 8, 'WXcwKzZqdHJYcG1ocFc2Z0ZqWjZuQT09Ojp1NkU1R2ZDOFNoVnlMQnRDcnh4RnJRPT0=', '2024-06-27 20:11:40'),
(173, 8, 68, 'Zzh3b2pTeDlPMjRYUGk4c0VjYmN0ZVZ3cWJlT1B1blZRak9ZZHNCMzVtUzJkR0cvUDVzYmk2czFkOHBwaGZWSzFTZ2NRTE1CYWZydmFqbk0yQWdyT3YwTzl0K3NFdVFtam5CckU4TUtsNHFrS1FXZWJlYi8wL3lKLzVFOFVQODQ6OmREekJmbjI2OExVU2xtUGowc0dWdFE9PQ==', '2024-06-27 20:11:42'),
(174, 68, 8, 'WjRwazd1U2pxb3A1TUlNMFVlMmlHdz09OjprZTg1QXMyKzF6OWFGQ3M2YUlmLzdBPT0=', '2024-06-27 20:11:44'),
(175, 8, 67, 'VC9aTGY4VDVobCtSTURMU1g4N3p1STdWTEwvZTZiTHM0bUJIUDlDVGQxMD06OlNlMjg2cFE2UElnR0xyMy9mTXBSNXc9PQ==', '2024-06-27 20:16:59'),
(176, 8, 67, 'L2NQMHorbGFHVGk1T3ZoUlJRUjU5UT09OjpZQ1U2OGZSamxHZnhzNiszUjBVSERBPT0=', '2024-06-27 20:17:04'),
(177, 67, 8, 'TE9CL1pkYlhuaGpPaDVBeVRJMWdiYU5lWXlwQVNjdXVsODM2VWIyTm16bk9YT3ZVeEFwU1lWNmZqNmdNenZhajo6a3RmZEtXUXg3R3NadWhFR0hnMTlZZz09', '2024-06-27 20:18:51'),
(178, 67, 8, 'dVcyQU91U1dxOEI1SGxrRVppQTBtQ2M1Z2FrTEF4NkRBMzRMYnQ0R2ZBaz06OkZjNzFXK3JvRXh6S21DV09EQ01kd0E9PQ==', '2024-06-27 20:18:57'),
(179, 67, 8, 'bFdzcnlPNW1oZEJtemFWREw1OWt6UT09Ojo5V0IwWWw0SDhwYnNtOU5jY3pRWGh3PT0=', '2024-06-27 20:19:00'),
(180, 67, 8, 'SzdWOG53ODc3QzRqU1NJWEpFWFYydz09Ojp0SFUreTdlaE9lWWdoZFZiQWRSZm13PT0=', '2024-06-27 20:19:03'),
(181, 69, 8, 'SGt5bTlWQUlEaTFPdHAwM0xzS3BKZz09OjpRTGtuRXgrVzRpeTdCTkNDVVFzVm5BPT0=', '2024-06-27 20:27:27'),
(182, 8, 69, 'N2NqMGVyMjJIcVZlWjl5ZHhndVZKMitlcFpGdVN4VmFOdUV1dG94WSsyUT06OlVGN2d1N0tCbllUVzkzSTcvRVh0aUE9PQ==', '2024-06-27 20:27:28'),
(184, 69, 8, 'YUtDOUhuczllaXZuNmNWV29oVy9OZz09Ojo4SWE5eHRnYmNIRzlZMS9WQTRFcGRnPT0=', '2024-06-27 20:30:15'),
(185, 69, 67, 'd0NjT2YxU3FaSVpYOFpPVm0rTHdERGQ4UktJMUNacnRqVXN0bjZEOURLbFdST2ppa1kyU2NlbXZoVnpjWUZiODR6RUcxL3BLcDBuMDU2N1VKV0tYTVE9PTo6ZDcvaGxaUzRTZXFBNUV4QjNMR1dNdz09', '2024-06-27 20:32:20'),
(186, 34, 8, 'Y3NlWkRvMktYNDhCRThHRlQ4TUFsc1c3UER0SXpCZEN6bWRBSnUxVkJIWT06OmJSYXhob2M5U1hPWEwwcDFqYTkwQXc9PQ==', '2024-06-27 20:40:25'),
(187, 67, 69, 'R0tpRFJ6N2tZWS9uNDFRT1IxUXVIK2dyVUh1RFY1SjhEcDRiMjMxc2RZbz06OkZUUW5OUm8ra0Jaa09KWkh0amJKamc9PQ==', '2024-06-27 20:42:54'),
(188, 69, 67, 'RzNhSjZyajhTSmwyUjJUcExEbk9HZz09Ojp0YkFGTjBwbnl0cEhOTEtmMUVUU0l3PT0=', '2024-06-27 20:44:33'),
(189, 69, 67, 'QTR2aE4xY3dXamlNZHhLWlFFMHkxdz09OjpZeXB0eWJRbktkN3hUZm5ocjRmWCtBPT0=', '2024-06-27 22:22:30'),
(190, 69, 67, 'UzdLR2NmZUNZdlVEUFp0MWdoek1adDFDT2t6WGkvWjdOMzh0UzN2OG5BcDNPRlRVeTFFUFlmbnFlNjJEczRCRzo6Q1Y1eVFrVERjcVhjUlRwT0RlcFNrUT09', '2024-06-27 22:22:36'),
(191, 69, 67, 'YmtpdHN2clpiU0JHVFVobkdia1hCVFZGYTFiMnY1SFdTNlJEZUR6MTRURktMZm9OdnY3WENSNjQzWFJBTFdIRmZUK0ZmblBmZUlRVjRyQjVNaEE2dTNIY1ZFclB6dURLRGpmcXJ1cGRqenJ5UjVuOVJmc2J6QnhiT011U0huMzM6OnViWEtuaE9pWWw2QTM0NTkrZE95U2c9PQ==', '2024-06-27 22:22:51'),
(192, 69, 67, 'OThOSHdRMlcyU0tTY3QxejFjRkw0ZHlyTGUvOVNNbVd3ZTBaWEJabVgyTT06OmVXc3JUY2hmSml1TXlDYXJvL2JYQVE9PQ==', '2024-06-27 22:22:58'),
(193, 69, 67, 'N0lmUkQ1NW0ySlc0Q01GdHpTK2N3Ylp4ZGJFaVlTOTZkT1N0VnRLakhVbG5IaUxjSjZQRnV5UUs5YlU2amlHbE5XRmVFM0VrdzA5SXo3NXNRS2ozbXc9PTo6YnhqQ1JiejIxcGVVQkkrZXF5R3FuUT09', '2024-06-27 22:23:10'),
(194, 69, 67, 'WjZETWtFQkRUZkZpeUJRQ0dnY2J0RlM4U0NzQkhITDBVbzR1ZlUxMVpVMUFRYmt4d3VkejlxWDBMdUhEd09uakg4TEtRMG4wTmNFYitXSjFWcFJNRFVyN2hnKy9MaUxaSWJRb2xFdzBEYjg9OjpscmZ2RmxaVFJsajNFMzZ6Slgza2dBPT0=', '2024-06-27 22:23:33'),
(195, 69, 67, 'b0hiQ3pZZ2JQaldwZnVQcGt2N0dhQT09OjpITFRaY29aNkxaS1FpL3kzd2RoS3h3PT0=', '2024-06-27 22:23:53'),
(197, 69, 8, 'TDFnSWdGTDJyY3Y3MXkvQlJRZHJyWUdXbTU5eGd1QTJiR0JoZlpJR3BnNDQ3eVJrbmhOdnpWbDdYR3Q4bVJBQjo6bEJNTFdVbmlLdGFRVm5nRzVDcUtrUT09', '2024-06-28 23:31:01');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime NOT NULL DEFAULT current_timestamp(),
  `receiver_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `date_sent`, `receiver_id`, `is_read`) VALUES
(637, 'You have received a new message from ADMIN', '2024-06-25 19:59:50', 34, 1),
(640, 'A new post has been created by ADMIN', '2024-06-26 22:15:50', 34, 1),
(642, 'User ADMIN liked your post', '2024-06-27 16:14:11', 34, 1),
(643, 'User ADMIN liked your post', '2024-06-27 16:14:15', 34, 1),
(644, 'User ADMIN liked your post', '2024-06-27 16:14:17', 34, 1),
(645, 'User ADMIN liked your post', '2024-06-27 16:14:18', 34, 1),
(646, 'User ADMIN liked your post', '2024-06-27 16:14:18', 34, 1),
(647, 'User ADMIN liked your post', '2024-06-27 16:14:19', 34, 1),
(648, 'User ADMIN liked your post', '2024-06-27 16:14:20', 34, 1),
(649, 'You have received a new message from ADMIN', '2024-06-27 17:00:13', 34, 1),
(650, 'You have received a new message from ADMIN', '2024-06-27 17:00:20', 34, 1),
(651, 'You have received a new message from ADMIN', '2024-06-27 17:21:00', 34, 1),
(652, 'You have received a new message from ADMIN', '2024-06-27 19:10:34', 34, 1),
(653, 'A new post has been created by ADMIN', '2024-06-27 19:34:26', 34, 1),
(655, 'A new post has been created by ADMIN', '2024-06-27 19:35:11', 34, 1),
(657, 'A new post has been created by ADMIN', '2024-06-27 19:35:48', 34, 1),
(662, 'User batman69gamer liked your post', '2024-06-27 19:47:59', 34, 1),
(663, 'User ADMIN liked your post', '2024-06-27 19:48:53', 34, 1),
(664, 'A new post has been created by ADMIN', '2024-06-27 19:54:45', 34, 1),
(666, 'You have received a new message from ADMIN', '2024-06-27 19:58:14', 34, 1),
(667, 'You have received a new message from ADMIN', '2024-06-27 19:58:19', 34, 1),
(668, 'You have received a new message from ADMIN', '2024-06-27 19:58:23', 34, 1),
(673, 'User batman69gamer liked your post', '2024-06-27 20:04:05', 34, 1),
(674, 'You have received a new message from ADMIN', '2024-06-27 20:04:41', 34, 1),
(675, 'You have received a new message from ADMIN', '2024-06-27 20:04:49', 34, 1),
(694, 'O utilizador BelgasMelgas começou a seguir você', '2024-06-27 20:17:50', 68, 0),
(695, 'O utilizador BelgasMelgas começou a seguir você', '2024-06-27 20:17:54', 34, 1),
(701, 'O utilizador ADMIN começou a seguir você', '2024-06-27 20:24:12', 69, 1),
(733, 'O utilizador BelgasMelgas começou a seguir você', '2024-06-27 20:26:04', 69, 1),
(734, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:21', 68, 0),
(735, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:21', 68, 0),
(736, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:21', 68, 0),
(737, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:22', 68, 0),
(738, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:22', 68, 0),
(739, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:23', 68, 0),
(740, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:23', 68, 0),
(741, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:23', 68, 0),
(742, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:24', 68, 0),
(743, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:24', 68, 0),
(744, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:24', 68, 0),
(745, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:26:25', 68, 0),
(786, 'Você recebeu uma nova mensagem de ADMIN', '2024-06-27 20:27:28', 69, 1),
(803, 'O utilizador batman69gamer gostou do seu post', '2024-06-27 20:40:10', 34, 1),
(806, 'O utilizador batman69gamer gostou do seu post', '2024-06-27 20:40:50', 34, 1),
(807, 'O utilizador batman69gamer gostou do seu post', '2024-06-27 20:40:52', 68, 0),
(808, 'O utilizador batman69gamer começou a seguir você', '2024-06-27 20:40:59', 68, 0),
(809, 'O utilizador batman69gamer começou a seguir você', '2024-06-27 20:41:01', 68, 0),
(810, 'O utilizador BelgasMelgas gostou do seu post', '2024-06-27 20:42:22', 34, 1),
(811, 'Você recebeu uma nova mensagem de BelgasMelgas', '2024-06-27 20:42:54', 69, 1),
(823, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:13', 67, 0),
(824, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:13', 67, 0),
(825, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:14', 67, 0),
(826, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:14', 67, 0),
(827, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:14', 67, 0),
(828, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:15', 67, 0),
(829, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:15', 67, 0),
(830, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:15', 67, 0),
(831, 'O utilizador enzo gostou do seu post', '2024-06-27 22:22:16', 67, 0),
(832, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:22:30', 67, 0),
(833, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:22:36', 67, 0),
(834, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:22:51', 67, 0),
(835, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:22:58', 67, 0),
(836, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:23:10', 67, 0),
(837, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:23:33', 67, 0),
(838, 'Você recebeu uma nova mensagem de enzo', '2024-06-27 22:23:54', 67, 0),
(839, 'O utilizador ADMIN gostou do seu post', '2024-06-27 22:29:26', 34, 1),
(840, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:24', 67, 0),
(841, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:24', 67, 0),
(842, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:25', 67, 0),
(843, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:25', 67, 0),
(844, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:25', 67, 0),
(845, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:26', 67, 0),
(846, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:26', 67, 0),
(847, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:26', 67, 0),
(848, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:27', 67, 0),
(849, 'O utilizador enzo gostou do seu post', '2024-06-28 08:12:27', 67, 0),
(850, 'O utilizador ADMIN gostou do seu post', '2024-06-28 09:08:33', 34, 1),
(851, 'O utilizador ADMIN começou a seguir você', '2024-06-28 09:09:33', 67, 0),
(852, 'Você recebeu uma nova mensagem de ADMIN', '2024-06-28 09:09:43', 67, 0),
(854, 'Um post foi criado por ADMIN', '2024-06-28 09:16:09', 34, 1),
(855, 'Um post foi criado por ADMIN', '2024-06-28 09:16:09', 68, 0),
(856, 'Um post foi criado por ADMIN', '2024-06-28 09:16:09', 67, 0),
(857, 'Um post foi criado por ADMIN', '2024-06-28 09:16:09', 69, 1),
(858, 'Um post foi criado por batman69gamer', '2024-06-28 21:22:21', 8, 0),
(859, 'Um post foi criado por batman69gamer', '2024-06-28 21:22:21', 67, 0),
(860, 'O utilizador batman69gamer gostou do seu post', '2024-06-28 21:22:25', 34, 1),
(861, 'O utilizador enzo gostou do seu post', '2024-06-28 23:30:35', 34, 1),
(862, 'Você recebeu uma nova mensagem de enzo', '2024-06-28 23:31:01', 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `post_type` varchar(255) NOT NULL,
  `post_url` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `id_theme` int(11) NOT NULL,
  `Enabled` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `id_users`, `post_type`, `post_url`, `caption`, `id_theme`, `Enabled`, `created_at`, `updated_at`) VALUES
(152, 8, 'video', 'video-1967 Ford Mustang .mp4-8.mp4', 'POV: Eu quando acabar as aulas', 30, 1, '2024-06-27 19:35:48', '2024-06-27 19:49:01'),
(153, 34, 'image', 'image-Captura de ecrã 2024-06-06 212323.png-34.png', 'eu a abrir esta merda de rede social depois de meses de assiduidade (nunca mais uso esta merda rota)\r\n', 30, 1, '2024-06-27 19:47:50', '2024-06-27 19:49:01'),
(157, 68, 'image', 'image-istockphoto-1387705862-612x612.jpg-68.jpg', 'resumo do secundário: entrei normal, saí diabético', 32, 1, '2024-06-27 20:09:37', '2024-06-28 11:31:01'),
(158, 67, 'image', 'image-Untitled.jpg-67.jpg', 'POV: Eu a apresentar a PAP sabendo que se não tiver 20 a minha média não vai ser suficiente para o curso que quero (eu vou-me matar)', 32, 1, '2024-06-27 20:13:27', '2024-06-28 11:31:01'),
(160, 34, 'image', 'image-valter.jpg-34.jpg', 'como o rafa se sente a apagar os meus posts:', 32, 1, '2024-06-27 20:40:07', '2024-06-28 11:31:01'),
(161, 8, 'image', 'image-P.png-8.png', 'Foto bonita', 32, 1, '2024-06-28 09:16:09', '2024-06-28 11:31:01'),
(162, 34, 'image', 'image-Captura de ecrã 2024-06-06 213149.png-34.png', 'eu no exame a ver que ja vou falhar a primeira pergunta ( nome: )', 33, 1, '2024-06-28 21:22:21', '2024-06-29 11:31:01');

-- --------------------------------------------------------

--
-- Stand-in structure for view `rankingposts`
-- (See below for the actual view)
--
CREATE TABLE `rankingposts` (
`PostId` int(11)
,`PostRank` bigint(21)
,`PostImage` varchar(255)
,`NameOfThePost` text
,`PostType` varchar(255)
,`Likes` bigint(21)
,`PersonWhoPostedIt` text
,`id_theme` int(11)
,`IsFinished` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rankingpostsall`
-- (See below for the actual view)
--
CREATE TABLE `rankingpostsall` (
`PostRank` bigint(21)
,`PostId` int(11)
,`PostImage` varchar(255)
,`NameOfThePost` text
,`PostType` varchar(255)
,`Likes` bigint(21)
,`PersonWhoPostedIt` text
,`id_theme` int(11)
,`IsFinished` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rankingpoststype`
-- (See below for the actual view)
--
CREATE TABLE `rankingpoststype` (
`PostRank` bigint(21)
,`PostId` int(11)
,`PostImage` varchar(255)
,`NameOfThePost` text
,`PostType` varchar(255)
,`Likes` bigint(21)
,`PersonWhoPostedIt` text
,`id_theme` int(11)
,`IsFinished` tinyint(1)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `rankingpoststypeall`
-- (See below for the actual view)
--
CREATE TABLE `rankingpoststypeall` (
`PostRank` bigint(21)
,`PostImage` varchar(255)
,`NameOfThePost` text
,`PostType` varchar(255)
,`Likes` bigint(21)
,`PersonWhoPostedIt` text
,`id_theme` int(11)
,`IsFinished` tinyint(1)
,`PostId` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id_report` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `why` text NOT NULL,
  `R_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id_report`, `sender`, `post_id`, `why`, `R_type`) VALUES
(159, 69, 158, 'o utilizador colocou uma imagem do professor ramos, fiquei ofendido', 'type1'),
(160, 8, 160, 'Está a falar mal de mim', 'type1');

-- --------------------------------------------------------

--
-- Table structure for table `theme`
--

CREATE TABLE `theme` (
  `id_theme` int(11) NOT NULL,
  `theme` text NOT NULL DEFAULT '',
  `finish_date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_finished` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theme`
--

INSERT INTO `theme` (`id_theme`, `theme`, `finish_date`, `is_finished`) VALUES
(30, 'Memes', '2024-06-27 20:49:00', 1),
(32, 'Final das Aulas', '2024-06-28 12:31:00', 1),
(33, 'Exames', '2024-06-29 12:31:00', 1),
(34, 'Pôr do Sol', '2024-06-30 12:31:00', 1),
(35, 'Super Carros', '2024-07-01 12:31:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `user_name` text DEFAULT NULL,
  `user_email` text DEFAULT NULL,
  `user_password` text DEFAULT NULL,
  `user_profilePic` varchar(50) DEFAULT NULL,
  `user_realName` text DEFAULT NULL,
  `user_biography` longtext DEFAULT NULL,
  `email_verify` tinyint(1) DEFAULT 0,
  `can_post` tinyint(1) DEFAULT 0,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `user_name`, `user_email`, `user_password`, `user_profilePic`, `user_realName`, `user_biography`, `email_verify`, `can_post`, `is_admin`) VALUES
(8, 'ADMIN', 'rafa.pinto.vieira@gmail.com', '$2y$10$YHqi2BhvQzXDhgYpwz/qLuzV18yzCylK4rY3.mRZ6wWkKTiGN.tWK', 'ProfilePic-667e7e0223600.png', 'Rafael Vieira', 'HELOOOOO THEREEE', 1, 0, 1),
(11, 'CatarinaVieira_', 'cv06@gmail.com', '$2y$10$LkDsFsrXCE1hw2xyCaiV4u9K2sGXUr2NgwGMSBN1jT0AiqdSJlmNW', NULL, NULL, NULL, 1, 0, 0),
(14, 'Ferras', 'ferras@gmail.com', '$2y$10$wJT0kbveeId7zC7gEnLE8uhBLpebR0clZXBJjubXqVKGf62TDJcZa', NULL, NULL, NULL, 1, 0, 0),
(28, 'Tester3', 'tester3@gmail.com', '$2y$10$VGlaFovEmFx3DU7o.q/woO.iscJZSQuIvo6oHoTxsW9yYnsC4OH6C', NULL, NULL, NULL, 1, 0, 0),
(33, 'hugo2006alm', 'hugo2006almeida2006@gmail.com', '$2y$10$qWSX7U3GqMrVeS5//zgf/eozONL7PPRWLD2IQ46bR3cm9nJusSeEW', NULL, NULL, NULL, 1, 0, 0),
(34, 'batman69gamer', 'batman@gmail.com', '$2y$10$/cKB.Xukk96UrdfunWfoxuHqhiwpzwZ.dUsjtz4bzUCB0wudI.EoG', 'ProfilePic-667dc1fa266f6.jpeg', 'miguelson', 'não ao racismo (tirando com os que não forem brancos)', 1, 0, 0),
(48, 'ORafaGamouMeAConta', 'penis@gmail.com', '$2y$10$uCwosrDqAJ3EbxnptYnPfuRYk/WG5wBNglBVZAPv4PJK/WhdOuZcC', '', '', NULL, 1, 0, 0),
(67, 'BelgasMelgas', 'becas3353@gmail.com', '$2y$10$Mb4wF9CYliDYKe6CP6eQCOHdhZKpIAB4IT2u./6psPvaymZDFoPpO', 'ProfilePic-667dc8802501e.jpg', 'Becas', 'Dona Maria José, se estiver a ler isto por favor dê-me 20 na PAP. Obrigado ☺', 1, 0, 0),
(68, 'sw33t', 'goncasgmatos@gmail.com', '$2y$10$NRLglvM84QBM.WXNE5ZZhuLSX/24eOMI2xxz/w8Fdy8IWEMC8iJma', 'ProfilePic-667dc7a313eb0.jpg', NULL, NULL, 1, 0, 0),
(69, 'enzo', 'enzonascentess@gmail.com', '$2y$10$m1lv66QgxNPPZztlENuWxuYYQB1Aa9yOEQm5hsu/REwHeNw3dFdK.', NULL, NULL, NULL, 1, 0, 0),
(70, 'CriarConta', 'gameplaysrafinha0@gmail.com', '$2y$10$e3u4eoe5HPF4iUkLfBJMt.bqCidCohbFuUKnBEFv.bIKf5NCv8W06', '', '', NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Structure for view `accountrankings`
--
DROP TABLE IF EXISTS `accountrankings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankings`  AS SELECT row_number()  ( order by ifnull(count(`l`.`post_id`),0) desc) AS `over` FROM ((`users` `u` left join `posts` `p` on(`u`.`id_users` = `p`.`id_users`)) left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) GROUP BY `u`.`id_users`, `u`.`user_name`, `u`.`user_profilePic` ORDER BY ifnull(count(`l`.`post_id`),0) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `accountrankingstype`
--
DROP TABLE IF EXISTS `accountrankingstype`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountrankingstype`  AS SELECT row_number()  ( partition by `p`.`post_type` order by count(`l`.`post_id`) desc) AS `over` FROM ((`users` `u` left join `posts` `p` on(`u`.`id_users` = `p`.`id_users`)) left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) WHERE `p`.`post_type` is not null GROUP BY `p`.`post_type`, `u`.`id_users`, `u`.`user_name`, `u`.`user_profilePic` ORDER BY `p`.`post_type` ASC, count(`l`.`post_id`) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `rankingposts`
--
DROP TABLE IF EXISTS `rankingposts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingposts`  AS SELECT `p`.`post_id` AS `PostId`, row_number()  ( partition by `t`.`id_theme` order by count(`l`.`post_id`) desc) AS `over` FROM (((`posts` `p` left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) left join `users` `u` on(`p`.`id_users` = `u`.`id_users`)) left join `theme` `t` on(`p`.`id_theme` = `t`.`id_theme`)) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`id_theme`, `t`.`is_finished` ORDER BY `t`.`id_theme` ASC, row_number()  ( partition by `t`.`id_theme` order by count(`l`.`post_id`) desc) AS `over` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `rankingpostsall`
--
DROP TABLE IF EXISTS `rankingpostsall`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpostsall`  AS SELECT row_number()  ( order by count(`l`.`post_id`) desc) AS `over` FROM (((`posts` `p` left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) left join `users` `u` on(`p`.`id_users` = `u`.`id_users`)) left join `theme` `t` on(`p`.`id_theme` = `t`.`id_theme`)) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`id_theme`, `t`.`is_finished` ORDER BY count(`l`.`post_id`) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `rankingpoststype`
--
DROP TABLE IF EXISTS `rankingpoststype`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpoststype`  AS SELECT row_number()  ( partition by `t`.`id_theme`,`p`.`post_type` order by count(`l`.`post_id`) desc) AS `over` FROM (((`posts` `p` left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) left join `users` `u` on(`p`.`id_users` = `u`.`id_users`)) left join `theme` `t` on(`p`.`id_theme` = `t`.`id_theme`)) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`id_theme`, `t`.`is_finished` ORDER BY `t`.`id_theme` ASC, row_number()  ( partition by `t`.`id_theme`,`p`.`post_type` order by count(`l`.`post_id`) desc) AS `over` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `rankingpoststypeall`
--
DROP TABLE IF EXISTS `rankingpoststypeall`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `rankingpoststypeall`  AS SELECT row_number()  ( partition by `p`.`post_type` order by count(`l`.`post_id`) desc) AS `over` FROM (((`posts` `p` left join `likes` `l` on(`p`.`post_id` = `l`.`post_id`)) left join `users` `u` on(`p`.`id_users` = `u`.`id_users`)) left join `theme` `t` on(`p`.`id_theme` = `t`.`id_theme`)) GROUP BY `p`.`post_id`, `p`.`post_url`, `p`.`caption`, `p`.`post_type`, `u`.`user_name`, `t`.`id_theme`, `t`.`is_finished` ORDER BY `p`.`post_type` ASC, row_number()  ( partition by `p`.`post_type` order by count(`l`.`post_id`) desc) AS `over` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `database_status`
--
ALTER TABLE `database_status`
  ADD PRIMARY KEY (`id_database_status`);

--
-- Indexes for table `follow`
--
ALTER TABLE `follow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `user_id` (`id_users`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`id_users`),
  ADD KEY `id_theme` (`id_theme`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id_report`),
  ADD KEY `sender` (`sender`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`id_theme`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `database_status`
--
ALTER TABLE `database_status`
  MODIFY `id_database_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `follow`
--
ALTER TABLE `follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1705;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=863;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id_report` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `theme`
--
ALTER TABLE `theme`
  MODIFY `id_theme` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id_users`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`id_theme`) REFERENCES `theme` (`id_theme`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `CheckThemeIsFinished` ON SCHEDULE EVERY 1 SECOND STARTS '2024-05-02 15:44:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL CheckThemeIsFinished()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
