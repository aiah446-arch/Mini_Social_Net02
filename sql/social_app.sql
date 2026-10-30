-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2026 at 01:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_app`
--
DROP DATABASE IF EXISTS `social_app`;
CREATE DATABASE IF NOT EXISTS `social_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `social_app`;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_type` varchar(20) DEFAULT 'post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`, `item_type`) VALUES
(6, 6, 5, 'really???', '2026-10-03 06:26:31', 'post'),
(7, 8, 5, 'weh??? di nga??', '2026-10-03 06:41:21', 'post'),
(8, 8, 4, 'fsddkdgbdf', '2026-10-03 07:25:12', 'post'),
(9, 8, 4, 'fduhl', '2026-10-03 07:50:03', 'post'),
(11, 15, 7, 'weh??? di nga??', '2026-10-07 04:11:59', 'post'),
(12, 20, 5, 'kawaii', '2026-10-11 13:20:30', 'post'),
(13, 25, 5, 'weh??? di nga??', '2026-10-20 05:07:26', 'post'),
(14, 25, 5, 'fsddkdgbdf', '2026-10-20 20:04:13', 'post'),
(16, 22, 5, 'dfasf', '2026-10-20 20:17:37', 'post'),
(18, 25, 5, 'pila?', '2026-10-20 20:32:17', 'post'),
(28, 40, 8, 'libat', '2026-10-28 07:16:57', 'post'),
(29, 40, 9, 'HAAHAHHAAH', '2026-10-28 09:21:05', 'post'),
(31, 37, 11, 'legit?', '2026-10-29 02:43:00', 'post');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `following_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `following_id`, `created_at`) VALUES
(10, 5, 7, '2026-10-20 20:00:44'),
(12, 9, 5, '2026-10-21 02:11:28'),
(13, 5, 8, '2026-10-21 02:11:51'),
(15, 5, 1, '2026-10-21 02:17:00'),
(19, 5, 9, '2026-10-26 04:12:03'),
(30, 8, 5, '2026-10-28 06:56:35'),
(31, 8, 9, '2026-10-28 07:15:44'),
(37, 9, 8, '2026-10-29 03:35:53'),
(38, 9, 12, '2026-10-29 21:52:14'),
(39, 9, 11, '2026-10-29 21:52:17'),
(40, 9, 10, '2026-10-29 21:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `item_type` varchar(20) DEFAULT 'post'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`, `item_type`) VALUES
(10, 6, 4, '2026-10-03 06:20:07', 'post'),
(12, 8, 5, '2026-10-03 06:30:53', 'post'),
(15, 8, 4, '2026-10-03 07:49:55', 'post'),
(17, 11, 6, '2026-10-04 01:54:42', 'post'),
(20, 8, 6, '2026-10-04 01:54:49', 'post'),
(21, 6, 6, '2026-10-04 01:54:52', 'post'),
(22, 11, 5, '2026-10-05 05:50:32', 'post'),
(24, 12, 5, '2026-10-07 02:03:26', 'post'),
(25, 13, 7, '2026-10-07 04:09:46', 'post'),
(27, 15, 7, '2026-10-07 04:11:31', 'post'),
(28, 15, 5, '2026-10-10 07:30:04', 'post'),
(29, 13, 5, '2026-10-10 07:30:07', 'post'),
(30, 20, 8, '2026-10-18 07:37:52', 'post'),
(32, 22, 8, '2026-10-18 08:36:50', 'post'),
(35, 22, 5, '2026-10-20 15:23:09', 'post'),
(45, 25, 5, '2026-10-20 20:32:13', 'post'),
(125, 4, 8, '2026-10-28 07:11:54', 'marketplace'),
(126, 2, 8, '2026-10-28 07:15:39', 'marketplace'),
(127, 40, 8, '2026-10-28 07:16:48', 'post'),
(130, 25, 9, '2026-10-28 09:37:45', 'post'),
(134, 4, 10, '2026-10-29 02:35:34', 'marketplace'),
(136, 25, 10, '2026-10-29 02:35:57', 'post'),
(137, 20, 10, '2026-10-29 02:36:06', 'post'),
(138, 43, 11, '2026-10-29 02:42:45', 'post'),
(139, 40, 11, '2026-10-29 02:42:48', 'post'),
(140, 37, 11, '2026-10-29 02:42:53', 'post'),
(141, 44, 11, '2026-10-29 02:44:28', 'post'),
(142, 40, 9, '2026-10-29 03:35:51', 'post'),
(144, 37, 9, '2026-10-29 23:05:15', 'post'),
(145, 47, 9, '2026-10-29 23:05:19', 'post');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace`
--

CREATE TABLE `marketplace` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace`
--

INSERT INTO `marketplace` (`id`, `user_id`, `item_name`, `price`, `description`, `image`, `created_at`) VALUES
(1, 5, 'HUAWEI MateBook D16', 33999.00, 'Device name	HUAWEI MateBook D16\r\nProcessor	13th Gen Intel(R) Core(TM) i5-13420H (2.10 GHz)\r\nInstalled RAM	16.0 GB (15.7 GB usable)\r\nGraphics card	Intel(R) UHD Graphics (128 MB)\r\nStorage	150 GB of 477 GB used\r\nDevice ID	1A6E1E39-5366-44D6-955B-390320DBB549\r\nProduct ID	00342-42373-11346-AAOEM\r\nSystem type	64-bit operating system, x64-based processor\r\nPen and touch	No pen or touch input is available for this display', 'market_5_1792507600.png', '2026-10-20 14:46:40'),
(2, 5, 'ASUS VIVOBOOK S530U', 33416.89, 'PRE-OWNED LAPTOP❗AVAILABLE NOW ONHAND❗PM lang largo. Salamat❗\r\n\r\nASUS VIVOBOOK S530U\r\n🤑15,900 (negotiable for sur buyers only)\r\n📍Quick Specs---INTEL CORE I7 8TH GEN, 2GB NVIDIA GAMING GPU, 8GB RAM, 256GB NVME SSD, 1TB HDD, LOUD AND CLEAR SPEAKER, HD CLEAR CAM, WINDOWS 11 PRO, OFFICE 2019', 'market_5_1792509579.png', '2026-10-20 15:19:39'),
(4, 8, 'BEN 10 Men\'s Brief', 150.00, 'Second Hand in Good Condition', 'market_8_1792989277.png', '2026-10-26 04:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `is_read`) VALUES
(1, 5, 8, 'hi', '2026-10-20 20:31:56', 1),
(2, 8, 5, 'hi', '2026-10-21 00:50:32', 1),
(3, 8, 9, 'how much po ang huawei na lappy?', '2026-10-21 04:37:34', 1),
(4, 8, 9, 'is it still available  po?', '2026-10-21 04:37:46', 1),
(5, 9, 8, 'hello po dear customer! yes it\'s still currently available po🥰', '2026-10-26 03:12:50', 1),
(6, 8, 9, 'oke', '2026-10-26 03:14:18', 1),
(7, 9, 8, 'would you like to purchase po? hehe uwu', '2026-10-26 03:16:31', 1),
(8, 8, 9, 'ndklfidgdn', '2026-10-26 03:34:03', 1),
(9, 9, 8, 'wtf', '2026-10-26 03:37:21', 1),
(10, 8, 9, 'sasfdgfhgjklk;', '2026-10-26 03:42:12', 1),
(11, 8, 9, 'hi', '2026-10-26 03:49:19', 1),
(12, 9, 5, 'hi', '2026-10-26 03:49:56', 1),
(13, 5, 9, 'hello', '2026-10-26 04:12:12', 1),
(14, 8, 9, 'ddfgmgshgf;alg;', '2026-10-26 04:48:39', 1),
(15, 9, 5, 'hi miss', '2026-10-28 03:44:35', 1),
(16, 5, 8, 'yo diddy', '2026-10-28 03:50:48', 1),
(17, 8, 9, 'http://localhost/NEO_Campus_Marketplace_Updated/Mini_Social_Net/public/index.php?page=feed#post-2', '2026-10-28 05:50:27', 1),
(18, 9, 5, 'http://localhost/NEO_Campus_Marketplace_Updated/Mini_Social_Net/public/index.php?page=feed#post-2', '2026-10-28 06:01:14', 1),
(19, 8, 9, 'kapoyyttyy\\', '2026-10-28 07:17:36', 1),
(20, 9, 5, 'kdvakldksbdk.asf', '2026-10-28 11:49:00', 1),
(21, 9, 8, 'bahog bilat', '2026-10-28 11:49:29', 0),
(22, 10, 9, 'hello', '2026-10-29 02:37:55', 1),
(23, 11, 5, 'hello, its still available?', '2026-10-29 02:42:25', 1),
(24, 5, 11, 'ali na kay naa na si ma\'am', '2026-10-29 03:03:14', 0),
(25, 9, 8, 'lsfjzggfdyukfgrtn', '2026-10-29 03:35:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `type`, `post_id`, `is_read`, `created_at`) VALUES
(1, 5, 8, 'follow', NULL, 1, '2026-10-20 19:33:42'),
(2, 5, 8, 'follow', NULL, 1, '2026-10-20 19:34:00'),
(3, 5, 8, 'follow', NULL, 1, '2026-10-20 19:34:03'),
(6, 7, 5, 'follow', NULL, 0, '2026-10-20 20:00:44'),
(7, 8, 5, 'follow', NULL, 1, '2026-10-20 20:41:25'),
(8, 5, 9, 'follow', NULL, 1, '2026-10-21 02:11:28'),
(9, 8, 5, 'follow', NULL, 1, '2026-10-21 02:11:51'),
(10, 9, 5, 'follow', NULL, 1, '2026-10-21 02:11:58'),
(12, 5, 8, 'follow', NULL, 1, '2026-10-21 04:37:02'),
(13, 9, 8, 'follow', NULL, 1, '2026-10-21 04:43:33'),
(14, 8, 9, 'follow', NULL, 1, '2026-10-21 04:44:25'),
(15, 9, 8, 'like', 3, 1, '2026-10-26 02:59:39'),
(16, 8, 9, 'message', NULL, 1, '2026-10-26 03:12:50'),
(17, 9, 8, 'message', NULL, 1, '2026-10-26 03:14:18'),
(18, 8, 9, 'message', NULL, 1, '2026-10-26 03:16:31'),
(19, 9, 8, 'message', NULL, 1, '2026-10-26 03:34:03'),
(20, 8, 9, 'message', NULL, 1, '2026-10-26 03:37:21'),
(21, 9, 8, 'message', NULL, 1, '2026-10-26 03:42:12'),
(22, 9, 8, 'message', NULL, 1, '2026-10-26 03:49:19'),
(23, 5, 9, 'message', NULL, 1, '2026-10-26 03:49:56'),
(24, 9, 5, 'follow', NULL, 1, '2026-10-26 04:12:03'),
(25, 9, 5, 'message', NULL, 1, '2026-10-26 04:12:12'),
(26, 9, 8, 'message', NULL, 1, '2026-10-26 04:48:39'),
(27, 8, 9, 'follow', NULL, 1, '2026-10-28 03:02:10'),
(28, 8, 9, 'follow', NULL, 1, '2026-10-28 03:02:12'),
(29, 8, 9, 'follow', NULL, 1, '2026-10-28 03:02:21'),
(30, 8, 9, 'follow', NULL, 1, '2026-10-28 03:08:23'),
(31, 8, 9, 'like', 4, 1, '2026-10-28 03:36:59'),
(32, 5, 9, 'message', NULL, 1, '2026-10-28 03:44:35'),
(33, 8, 5, 'message', NULL, 1, '2026-10-28 03:50:48'),
(34, 9, 8, 'follow', NULL, 1, '2026-10-28 03:51:17'),
(35, 9, 8, 'message', NULL, 1, '2026-10-28 05:50:27'),
(36, 8, 9, 'follow', NULL, 1, '2026-10-28 05:51:41'),
(37, 5, 9, 'message', NULL, 1, '2026-10-28 06:01:14'),
(38, 8, 9, 'follow', NULL, 1, '2026-10-28 06:09:55'),
(39, 8, 9, 'follow', NULL, 1, '2026-10-28 06:16:06'),
(41, 9, 8, 'follow', NULL, 1, '2026-10-28 06:54:44'),
(42, 9, 8, 'like', 37, 1, '2026-10-28 06:56:18'),
(43, 5, 8, 'follow', NULL, 1, '2026-10-28 06:56:35'),
(44, 9, 8, 'like', 37, 1, '2026-10-28 06:57:11'),
(45, 9, 8, 'like', 37, 1, '2026-10-28 07:01:44'),
(46, 5, 8, 'like', 2, 1, '2026-10-28 07:15:39'),
(47, 9, 8, 'follow', NULL, 1, '2026-10-28 07:15:44'),
(48, 9, 8, 'message', NULL, 1, '2026-10-28 07:17:36'),
(49, 8, 9, 'follow', NULL, 0, '2026-10-28 09:19:53'),
(50, 8, 9, 'like', 40, 0, '2026-10-28 09:20:59'),
(51, 8, 9, 'follow', NULL, 0, '2026-10-28 09:37:13'),
(52, 8, 9, 'like', 40, 0, '2026-10-28 09:37:28'),
(53, 5, 9, 'like', 25, 1, '2026-10-28 09:37:45'),
(54, 8, 9, 'follow', NULL, 0, '2026-10-28 10:20:26'),
(55, 8, 9, 'follow', NULL, 0, '2026-10-28 11:08:47'),
(56, 8, 9, 'like', 40, 0, '2026-10-28 11:08:50'),
(57, 5, 9, 'message', NULL, 1, '2026-10-28 11:49:00'),
(58, 8, 9, 'message', NULL, 0, '2026-10-28 11:49:29'),
(59, 8, 9, 'like', 40, 0, '2026-10-28 11:49:44'),
(60, 8, 10, 'like', 4, 0, '2026-10-29 02:35:34'),
(61, 5, 10, 'like', 25, 1, '2026-10-29 02:35:55'),
(62, 5, 10, 'like', 25, 1, '2026-10-29 02:35:57'),
(63, 8, 10, 'like', 20, 0, '2026-10-29 02:36:06'),
(64, 9, 10, 'message', NULL, 1, '2026-10-29 02:37:55'),
(65, 5, 11, 'message', NULL, 1, '2026-10-29 02:42:25'),
(66, 10, 11, 'like', 43, 0, '2026-10-29 02:42:45'),
(67, 8, 11, 'like', 40, 0, '2026-10-29 02:42:48'),
(68, 9, 11, 'like', 37, 1, '2026-10-29 02:42:53'),
(69, 11, 5, 'message', NULL, 0, '2026-10-29 03:03:14'),
(70, 11, 5, 'follow', NULL, 0, '2026-10-29 03:03:34'),
(71, 8, 9, 'message', NULL, 0, '2026-10-29 03:35:29'),
(72, 8, 9, 'like', 40, 0, '2026-10-29 03:35:51'),
(73, 8, 9, 'follow', NULL, 0, '2026-10-29 03:35:53'),
(74, 12, 9, 'follow', NULL, 0, '2026-10-29 21:52:14'),
(75, 11, 9, 'follow', NULL, 0, '2026-10-29 21:52:17'),
(76, 10, 9, 'follow', NULL, 0, '2026-10-29 21:52:20'),
(77, 7, 9, 'follow', NULL, 0, '2026-10-29 22:35:00'),
(78, 6, 9, 'follow', NULL, 0, '2026-10-29 22:35:02'),
(79, 7, 9, 'follow', NULL, 0, '2026-10-29 23:10:28');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_type` enum('social','product') DEFAULT 'social',
  `product_title` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_condition` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `shared_post_id` int(11) DEFAULT NULL,
  `shared_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image`, `created_at`, `post_type`, `product_title`, `price`, `product_condition`, `category`, `shared_post_id`, `shared_type`) VALUES
(6, 4, 'Gikapoy nako', NULL, '2026-10-03 06:20:03', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 5, 'I love Programming💙💙', NULL, '2026-10-03 06:26:23', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 6, 'fk.djliuedhgolidjolfd', NULL, '2026-10-04 01:54:41', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 5, 'Tabanggg', NULL, '2026-10-07 02:03:19', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 7, 'WANAKO KASABOT', NULL, '2026-10-07 02:06:17', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 7, 'SHOUT OUT KONG VECTOR', NULL, '2026-10-07 04:11:29', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 8, 'UwU!', 'post_8_1791724779_2869.jpg', '2026-10-11 13:19:39', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 8, 'gakuran w/ vector', 'post_8_1792309161_6801.png', '2026-10-18 07:39:21', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 5, 'bayot 150', NULL, '2026-10-20 03:52:22', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(37, 9, 'palit namo guys', NULL, '2026-10-28 03:37:17', 'social', NULL, NULL, NULL, NULL, 4, 'marketplace'),
(40, 8, 'oa', 'post_8_1793171795_7880.png', '2026-10-28 07:16:35', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(43, 10, 'palit namo rene butterbonia 150', NULL, '2026-10-29 02:37:07', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 11, 'It\'s okay, not to be okay.', NULL, '2026-10-29 02:41:41', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 12, 'hi everyone', NULL, '2026-10-29 03:16:15', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(46, 12, 'wassup guys', NULL, '2026-10-29 03:16:52', 'social', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 9, 'Arctic Monkeys🖤', 'post_9_1793313474_0942.png', '2026-10-29 22:37:54', 'social', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`id`, `post_id`, `image`, `position`, `created_at`) VALUES
(1, 47, 'post_9_1793313474_1142.png', 1, '2026-10-29 22:37:54');

-- --------------------------------------------------------

--
-- Table structure for table `saved_items`
--

CREATE TABLE `saved_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saved_items`
--

INSERT INTO `saved_items` (`id`, `user_id`, `item_id`, `created_at`) VALUES
(13, 5, 1, '2026-10-20 15:03:50'),
(14, 5, 2, '2026-10-20 15:19:43'),
(15, 8, 2, '2026-10-21 00:50:15'),
(17, 9, 4, '2026-10-28 06:10:05'),
(18, 9, 2, '2026-10-28 06:10:11'),
(19, 9, 1, '2026-10-28 06:10:13'),
(20, 8, 4, '2026-10-28 07:17:07'),
(21, 8, 1, '2026-10-28 07:17:10'),
(22, 11, 2, '2026-10-29 02:43:16'),
(23, 5, 4, '2026-10-29 03:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `saved_posts`
--

CREATE TABLE `saved_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `bio`, `profile_image`, `created_at`, `cover_image`) VALUES
(4, 'testuser', '$2y$10$9./.PI5kUQ0lmpz4R8S8Pethbryar9oces7yxfi0T703XH9VdGSqq', 'Test User', NULL, 'default.png', '2026-10-03 05:55:09', NULL),
(5, 'dayanbayot', '$2y$10$KQXGETIoc1ijqUyHz6.hgu5XMVL3lytBLr3LEdMsZy6thk1KLFq7S', 'Dianna T. Fabre', 'ikaw lang sapat na', 'user_5_1792510465.png', '2026-10-03 06:26:00', NULL),
(6, 'ishi67', '$2y$10$d1KTJkpqhKRjXRFz18ddN.kJm2pgsm3HpZOtoP8.BpmSvw0r71cR.', 'Ishi 67', NULL, 'default.png', '2026-10-04 01:54:34', NULL),
(7, 'Ralph', '$2y$10$P3O6RoielXy4cxqBI667tOMf2crbS6D/gtFMMgj5oQq2F/BhQzaTu', 'Ralph lian derder', NULL, 'default.png', '2026-10-07 02:05:03', NULL),
(8, 'skibiditoiletsigmapomni', '$2y$10$GRletRszxCLrxAZ7W40IKOsGmGO4VNSO6.CBRfiOfcoOlT4elbvfe', 'Charlie Kirk', '', 'user_8_1792446698.png', '2026-10-11 12:52:38', NULL),
(9, 'aiahchann', '$2y$10$VWCZw8jU1ll9.DjlJ/PQ2uNVZd4PM3aleFyZv7Qm1Fr7sZTkW9OTC', 'Alliah Faith D. Roa', 'lf zang linghe', 'user_9_1792548636.png', '2026-10-21 02:07:51', 'cover_9_1793311245.png'),
(10, 'renebaterbonia', '$2y$10$t/FB24MkM1625BS6FH9zmelY7ZItRHhwVqKdP7NUPhdHBxBAjJq9m', 'Michael Zuckerberg', 'ako bodoy', 'default.png', '2026-10-29 02:34:35', NULL),
(11, 'leean', '$2y$10$/IGY/tL/ruL2wV1ZjtQnEu7AkOhO5Zrn9XE.g249ntdJNUdktWEXe', 'ralph lian derder', NULL, 'default.png', '2026-10-29 02:40:27', NULL),
(12, 'whoisjel', '$2y$10$xsWgDwi.lHBzZSGwJBpQXu3kUX7p5gcVSkThGriZHxrmRM9ymPUJW', 'whoisjel', NULL, 'default.png', '2026-10-29 03:15:59', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `saved_items`
--
ALTER TABLE `saved_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`user_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `saved_posts`
--
ALTER TABLE `saved_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`user_id`,`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `marketplace`
--
ALTER TABLE `marketplace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saved_items`
--
ALTER TABLE `saved_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `saved_posts`
--
ALTER TABLE `saved_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace`
--
ALTER TABLE `marketplace`
  ADD CONSTRAINT `marketplace_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_items`
--
ALTER TABLE `saved_items`
  ADD CONSTRAINT `saved_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `marketplace` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;