-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 31/03/2026 às 19:58
-- Versão do servidor: 5.7.44-48
-- Versão do PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `univ7652_orthocrinbd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `access_history`
--

CREATE TABLE `access_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('success','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'success',
  `message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `visible_franchise_only` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `banner_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaigns`
--

INSERT INTO `campaigns` (`id`, `name`, `description`, `start_date`, `end_date`, `visible_franchise_only`, `status`, `thumbnail_path`, `is_featured`, `banner_path`, `created_at`, `updated_at`) VALUES
(1, 'Mês do Consumidor', NULL, '2026-03-01', '2026-03-31', 1, 'active', 'private/campaigns/1/thumb/Rz9vKLFuBrQ3635tkyAFNUzlKcCWYWP5rR5yrLbR.jpg', 1, 'private/campaigns/1/banner/fd1nGjn5DFXPbd3hscFCi4b9w8rP5M4C8UW5DOze.jpg', '2026-03-10 01:15:20', '2026-03-10 02:23:12'),
(2, 'Cuide Orthocrin', 'A campanha de abril traz o conceito “Cuide com Orthocrin”, antecipando o tema do cuidado que será aprofundado no mês das mães.\r\nEssa estratégia considera uma jornada de compra superior a 30 dias, funcionando como uma sugestão antecipada de presente para o Dia das Mães.\r\nAo mesmo tempo, reforça que o colchão faz parte do cuidado diário de todos, com foco em carinho, bem-estar e saúde.', '2026-04-01', '2026-04-30', 1, 'active', 'private/campaigns/2/thumb/9JpRqyRKWL7vK5z1z2gIBDHsh11l4YjEmd9nKFji.jpg', 1, 'private/campaigns/2/banner/RuDcFRieZvcLGbMXT3mMO7pqOzapDRI2NrnOj3vO.jpg', '2026-04-01 00:50:09', '2026-04-01 03:06:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_folders`
--

CREATE TABLE `campaign_folders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `state` enum('MG/SP','DF/ES','RJ','RS','SC','PR','BA','CE','PE','GO','MT','MS','RO','AC','AP','AM','PA','RR','TO','PI','MA','RN','PB','AL','SE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MG/SP',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_folders`
--

INSERT INTO `campaign_folders` (`id`, `campaign_id`, `name`, `description`, `state`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 1, 'FOLHETO-MG.pdf', NULL, 'MG/SP', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(2, 1, 'FOLHETO-OUTROS-ESTADOS.pdf', NULL, 'DF/ES', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(3, 2, 'Folheto Cuide com Orthocrin.pdf', NULL, 'MG/SP', 'active', '2026-04-01 00:50:09', '2026-04-01 00:50:09', NULL),
(4, 2, 'Frente.jpg', NULL, 'MG/SP', 'active', '2026-04-01 00:50:09', '2026-04-01 00:50:09', NULL),
(5, 2, 'Verso.jpg', NULL, 'MG/SP', 'active', '2026-04-01 00:50:09', '2026-04-01 00:50:09', NULL),
(6, 2, 'Folheto Cuide com Orthocrin.pdf', NULL, 'DF/ES', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(7, 2, 'Frente.jpg', NULL, 'DF/ES', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(8, 2, 'Verso.jpg', NULL, 'DF/ES', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_folder_files`
--

CREATE TABLE `campaign_folder_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_folder_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_folder_files`
--

INSERT INTO `campaign_folder_files` (`id`, `campaign_folder_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 281, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'pdf', 0, 1),
(2, 2, 282, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'pdf', 0, 1),
(3, 3, 701, '2026-04-01 00:50:09', '2026-04-01 00:50:09', 'pdf', 0, 1),
(4, 4, 702, '2026-04-01 00:50:09', '2026-04-01 00:50:09', 'image', 0, 1),
(5, 5, 703, '2026-04-01 00:50:09', '2026-04-01 00:50:09', 'image', 0, 1),
(6, 6, 721, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'pdf', 0, 1),
(7, 7, 722, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(8, 8, 723, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_miscellaneous`
--

CREATE TABLE `campaign_miscellaneous` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('spot','tag','sticker','script','adesivo','banner','faixa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'spot',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_miscellaneous`
--

INSERT INTO `campaign_miscellaneous` (`id`, `campaign_id`, `name`, `description`, `type`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 1, 'Orthocrin-Março-A-19-02.mp3.mp3', NULL, 'spot', 'active', '2026-03-10 01:49:20', '2026-03-10 01:49:20', NULL),
(2, 1, 'ADESIVO.pdf', NULL, 'adesivo', 'active', '2026-03-10 01:49:20', '2026-03-10 01:49:20', NULL),
(3, 1, 'BANNER.pdf', NULL, 'banner', 'active', '2026-03-10 01:49:20', '2026-03-10 01:49:20', NULL),
(4, 1, 'FAIXA.pdf', NULL, 'faixa', 'active', '2026-03-10 01:49:20', '2026-03-10 01:49:20', NULL),
(5, 2, 'Orthocrin - Abril Mês do Cuidado A 18 03.mp3', NULL, 'spot', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL),
(6, 2, 'Orthocrin - Abril Mês do Cuidado B 18 03.mp3', NULL, 'spot', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL),
(7, 2, 'Adesivo Campanha Orthocrin - PADRÃO.pdf', NULL, 'adesivo', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL),
(8, 2, 'Orthocrin -_lojatotal.png', NULL, 'adesivo', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL),
(9, 2, 'Cuide com Orthocrin-0,9 x 1,20.pdf', NULL, 'banner', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL),
(10, 2, 'Cuide com Orthocrin-3,00 x 0,65 m.pdf', NULL, 'faixa', 'active', '2026-04-01 02:54:45', '2026-04-01 02:54:45', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_miscellaneous_files`
--

CREATE TABLE `campaign_miscellaneous_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_miscellaneous_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_miscellaneous_files`
--

INSERT INTO `campaign_miscellaneous_files` (`id`, `campaign_miscellaneous_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 324, '2026-03-10 01:49:20', '2026-03-10 01:49:20', 'audio', 0, 1),
(2, 2, 325, '2026-03-10 01:49:20', '2026-03-10 01:49:20', 'pdf', 0, 1),
(3, 3, 326, '2026-03-10 01:49:20', '2026-03-10 01:49:20', 'pdf', 0, 1),
(4, 4, 327, '2026-03-10 01:49:20', '2026-03-10 01:49:20', 'pdf', 0, 1),
(5, 5, 772, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'audio', 0, 1),
(6, 6, 773, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'audio', 0, 1),
(7, 7, 774, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'pdf', 0, 1),
(8, 8, 775, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'image', 0, 1),
(9, 9, 776, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'pdf', 0, 1),
(10, 10, 777, '2026-04-01 02:54:45', '2026-04-01 02:54:45', 'pdf', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_posts`
--

CREATE TABLE `campaign_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('feeds','stories_mg_sp','stories_df_es') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'feeds',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_posts`
--

INSERT INTO `campaign_posts` (`id`, `campaign_id`, `name`, `description`, `type`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 1, 'Feed-301-2.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(2, 1, 'Feed-301-3.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(3, 1, 'Feed-507-1.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(4, 1, 'Feed-507-2.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(5, 1, 'Feed-507-3.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(6, 1, 'Feed-Humanizado-1.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(7, 1, 'Feed-Humanizado-2.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(8, 1, 'Feed-Humanizado-3.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(9, 1, 'Feed-Humanizado-4.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(10, 1, 'Feed-Humanizado-5.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(11, 1, 'Feed-Humanizado-6.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(12, 1, 'Feed-Morpheu-1.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(13, 1, 'Feed-Morpheu-2.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(14, 1, 'Feed-Morpheu-3.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(15, 1, 'Feed-Morpheu-4.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(16, 1, 'Feed-Morpheu-5.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(17, 1, 'Feed-Polaris-2.jpg', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(18, 1, 'Feed-Polaris-3.png', NULL, 'feeds', 'active', '2026-03-10 01:18:55', '2026-03-10 01:18:55', NULL),
(19, 1, '301-1.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(20, 1, '301-2.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(21, 1, '301-3.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(22, 1, '301-5.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(23, 1, '507-1.jpg', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(24, 1, '507-2.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(25, 1, '507-3.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(26, 1, 'Humanizado-1.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(27, 1, 'Humanizado-2.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(28, 1, 'Humanizado-3.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(29, 1, 'Humanizado-4.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(30, 1, 'Humanizado-5.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(31, 1, 'Humanizado-6.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(32, 1, 'Morpheu-1.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(33, 1, 'Morpheu-2.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(34, 1, 'Morpheu-3.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(35, 1, 'Morpheu-4.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(36, 1, 'Morpheu-5.png', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(37, 1, 'Polaris-2_1.jpg', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(38, 1, 'Polaris-3.jpg', NULL, 'stories_mg_sp', 'active', '2026-03-10 01:20:25', '2026-03-10 01:20:25', NULL),
(39, 1, '301-1.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(40, 1, '301-2.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(41, 1, '301-3.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(42, 1, '301-4.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(43, 1, '507-1.jpg', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(44, 1, '507-2.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(45, 1, '507-3.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(46, 1, 'Humanizado-1.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(47, 1, 'Humanizado-2.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:21', '2026-03-10 01:30:21', NULL),
(48, 1, 'Humanizado-3.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(49, 1, 'Humanizado-4.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(50, 1, 'Humanizado-5.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(51, 1, 'Humanizado-6.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(52, 1, 'Morpheu-1.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(53, 1, 'Morpheu-2.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(54, 1, 'Morpheu-3.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(55, 1, 'Morpheu-4.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(56, 1, 'Morpheu-5.png', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(57, 1, 'Polaris-2.jpg', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(58, 1, 'Polaris-2_1.jpg', NULL, 'stories_df_es', 'active', '2026-03-10 01:30:22', '2026-03-10 01:30:22', NULL),
(59, 2, 'Feed-301-1.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(60, 2, 'Feed-301-2.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(61, 2, 'Feed-301-3.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(62, 2, 'Feed-301-4.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(63, 2, 'Feed-501-1.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(64, 2, 'Feed-501-2.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(65, 2, 'Feed-501-3.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(66, 2, 'Feed-501-4.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(67, 2, 'Feed-503-2.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(68, 2, 'Feed-503-3.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(69, 2, 'Feed-503-5.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(70, 2, 'Feed-505-1.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(71, 2, 'Feed-505-2.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(72, 2, 'Feed-505-3.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(73, 2, 'Feed-507-1.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(74, 2, 'Feed-507-2.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(75, 2, 'Feed-507-3.png', NULL, 'feeds', 'active', '2026-04-01 01:15:38', '2026-04-01 01:15:38', NULL),
(76, 2, '301-1.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(77, 2, '301-2.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(78, 2, '301-3.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(79, 2, '301-4.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(80, 2, '501-1.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(81, 2, '501-2.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(82, 2, '501-3.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(83, 2, '501-4.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(84, 2, '503-1.jpg', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(85, 2, '503-2.jpg', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(86, 2, '503-3.jpg', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(87, 2, '503-4.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(88, 2, '505-1.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(89, 2, '505-2.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(90, 2, '505-3.jpg', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(91, 2, '507-1.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(92, 2, '507-2.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(93, 2, '507-3.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(94, 2, '509-1.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(95, 2, '509-2.png', NULL, 'stories_mg_sp', 'active', '2026-04-01 01:21:05', '2026-04-01 01:21:05', NULL),
(96, 2, '301-1.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(97, 2, '301-2.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(98, 2, '301-3.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(99, 2, '301-4.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(100, 2, '501-1.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(101, 2, '501-2.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(102, 2, '501-3.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(103, 2, '501-4.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(104, 2, '503-1.jpg', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(105, 2, '503-2.jpg', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(106, 2, '503-3.jpg', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(107, 2, '503-4.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(108, 2, '505-1.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(109, 2, '505-2.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(110, 2, '505-3.jpg', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(111, 2, '507-1.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(112, 2, '507-2.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(113, 2, '507-3.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(114, 2, '509-1.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL),
(115, 2, '509-2.png', NULL, 'stories_df_es', 'active', '2026-04-01 01:25:53', '2026-04-01 01:25:53', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_post_files`
--

CREATE TABLE `campaign_post_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_post_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_post_files`
--

INSERT INTO `campaign_post_files` (`id`, `campaign_post_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 263, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(2, 2, 264, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(3, 3, 265, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(4, 4, 266, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(5, 5, 267, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(6, 6, 268, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(7, 7, 269, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(8, 8, 270, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(9, 9, 271, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(10, 10, 272, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(11, 11, 273, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(12, 12, 274, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(13, 13, 275, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(14, 14, 276, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(15, 15, 277, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(16, 16, 278, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(17, 17, 279, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(18, 18, 280, '2026-03-10 01:18:55', '2026-03-10 01:18:55', 'image', 0, 1),
(19, 19, 283, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(20, 20, 284, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(21, 21, 285, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(22, 22, 286, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(23, 23, 287, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(24, 24, 288, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(25, 25, 289, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(26, 26, 290, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(27, 27, 291, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(28, 28, 292, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(29, 29, 293, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(30, 30, 294, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(31, 31, 295, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(32, 32, 296, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(33, 33, 297, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(34, 34, 298, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(35, 35, 299, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(36, 36, 300, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(37, 37, 301, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(38, 38, 302, '2026-03-10 01:20:25', '2026-03-10 01:20:25', 'image', 0, 1),
(39, 39, 303, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(40, 40, 304, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(41, 41, 305, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(42, 42, 306, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(43, 43, 307, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(44, 44, 308, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(45, 45, 309, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(46, 46, 310, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(47, 47, 311, '2026-03-10 01:30:21', '2026-03-10 01:30:21', 'image', 0, 1),
(48, 48, 312, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(49, 49, 313, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(50, 50, 314, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(51, 51, 315, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(52, 52, 316, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(53, 53, 317, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(54, 54, 318, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(55, 55, 319, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(56, 56, 320, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(57, 57, 321, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(58, 58, 322, '2026-03-10 01:30:22', '2026-03-10 01:30:22', 'image', 0, 1),
(59, 59, 704, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(60, 60, 705, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(61, 61, 706, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(62, 62, 707, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(63, 63, 708, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(64, 64, 709, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(65, 65, 710, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(66, 66, 711, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(67, 67, 712, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(68, 68, 713, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(69, 69, 714, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(70, 70, 715, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(71, 71, 716, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(72, 72, 717, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(73, 73, 718, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(74, 74, 719, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(75, 75, 720, '2026-04-01 01:15:38', '2026-04-01 01:15:38', 'image', 0, 1),
(76, 76, 724, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(77, 77, 725, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(78, 78, 726, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(79, 79, 727, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(80, 80, 728, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(81, 81, 729, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(82, 82, 730, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(83, 83, 731, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(84, 84, 732, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(85, 85, 733, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(86, 86, 734, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(87, 87, 735, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(88, 88, 736, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(89, 89, 737, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(90, 90, 738, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(91, 91, 739, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(92, 92, 740, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(93, 93, 741, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(94, 94, 742, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(95, 95, 743, '2026-04-01 01:21:05', '2026-04-01 01:21:05', 'image', 0, 1),
(96, 96, 744, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(97, 97, 745, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(98, 98, 746, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(99, 99, 747, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(100, 100, 748, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(101, 101, 749, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(102, 102, 750, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(103, 103, 751, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(104, 104, 752, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(105, 105, 753, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(106, 106, 754, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(107, 107, 755, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(108, 108, 756, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(109, 109, 757, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(110, 110, 758, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(111, 111, 759, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(112, 112, 760, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(113, 113, 761, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(114, 114, 762, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1),
(115, 115, 763, '2026-04-01 01:25:53', '2026-04-01 01:25:53', 'image', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_videos`
--

CREATE TABLE `campaign_videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('reels','marketing_campaigns') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'reels',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_videos`
--

INSERT INTO `campaign_videos` (`id`, `campaign_id`, `name`, `description`, `type`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 1, 'VIDEO-CAMPANHA.mp4', NULL, 'marketing_campaigns', 'active', '2026-03-10 01:41:50', '2026-03-10 01:41:50', NULL),
(2, 1, 'VÍDEO 01 – MORPHEU - Reels.MP4', NULL, 'reels', 'active', '2026-03-10 02:17:31', '2026-03-10 02:17:31', NULL),
(3, 1, 'Campanha de Março - Generico - Reels.MP4', NULL, 'reels', 'active', '2026-03-10 02:22:32', '2026-03-10 02:22:32', NULL),
(4, 1, 'VÍDEO 02 – Colchão Série 301 Plus  - Reels.MP4', NULL, 'reels', 'active', '2026-03-10 02:22:32', '2026-03-10 02:22:32', NULL),
(5, 1, 'VÍDEO 03 – Colchão Polaris Ultra D33 - Reels.MP4', NULL, 'reels', 'active', '2026-03-10 02:22:32', '2026-03-10 02:22:32', NULL),
(6, 1, 'VÍDEO 04 – Travesseiros - Reels.MP4', NULL, 'reels', 'active', '2026-03-10 02:22:32', '2026-03-10 02:22:32', NULL),
(7, 2, 'VÍDEO 01 – GENÉRICO  - Reels - Legendado.MP4', NULL, 'reels', 'active', '2026-04-01 01:37:47', '2026-04-01 01:37:47', NULL),
(8, 2, 'VÍDEO 02 – CHAMADA CAMPANHA - Reels - Legendado.MP4', NULL, 'reels', 'active', '2026-04-01 01:37:47', '2026-04-01 01:37:47', NULL),
(9, 2, 'VÍDEO 03 – SÉRIE 503 - Reels - Legendado.MP4', NULL, 'reels', 'active', '2026-04-01 02:14:38', '2026-04-01 02:14:38', NULL),
(10, 2, 'VÍDEO 04 – SÉRIE 301 Plus - Reels - Legendado.MP4', NULL, 'reels', 'active', '2026-04-01 02:20:03', '2026-04-01 02:20:03', NULL),
(11, 2, 'VÍDEO 05 – MULTIRELAX VISCO - Reels - Legendado.MP4', NULL, 'reels', 'active', '2026-04-01 02:20:03', '2026-04-01 02:20:03', NULL),
(12, 2, 'Campanha de abril - TV.avi', NULL, 'marketing_campaigns', 'active', '2026-04-01 02:29:31', '2026-04-01 02:29:31', NULL),
(13, 2, 'Campanha de abril - TV.mov', NULL, 'marketing_campaigns', 'active', '2026-04-01 02:44:57', '2026-04-01 02:44:57', NULL),
(14, 2, 'Campanha de abril - TV.mp4', NULL, 'marketing_campaigns', 'active', '2026-04-01 02:49:08', '2026-04-01 02:49:08', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `campaign_video_files`
--

CREATE TABLE `campaign_video_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `campaign_video_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `campaign_video_files`
--

INSERT INTO `campaign_video_files` (`id`, `campaign_video_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 323, '2026-03-10 01:41:50', '2026-03-10 01:41:50', 'video', 0, 1),
(2, 2, 328, '2026-03-10 02:17:31', '2026-03-10 02:17:31', 'video', 0, 1),
(3, 3, 329, '2026-03-10 02:22:32', '2026-03-10 02:22:32', 'video', 0, 1),
(4, 4, 330, '2026-03-10 02:22:32', '2026-03-10 02:22:32', 'video', 0, 1),
(5, 5, 331, '2026-03-10 02:22:32', '2026-03-10 02:22:32', 'video', 0, 1),
(6, 6, 332, '2026-03-10 02:22:32', '2026-03-10 02:22:32', 'video', 0, 1),
(7, 7, 764, '2026-04-01 01:37:47', '2026-04-01 01:37:47', 'video', 0, 1),
(8, 8, 765, '2026-04-01 01:37:47', '2026-04-01 01:37:47', 'video', 0, 1),
(9, 9, 766, '2026-04-01 02:14:38', '2026-04-01 02:14:38', 'video', 0, 1),
(10, 10, 767, '2026-04-01 02:20:03', '2026-04-01 02:20:03', 'video', 0, 1),
(11, 11, 768, '2026-04-01 02:20:03', '2026-04-01 02:20:03', 'video', 0, 1),
(12, 12, 769, '2026-04-01 02:29:31', '2026-04-01 02:29:31', 'video', 0, 1),
(13, 13, 770, '2026-04-01 02:44:57', '2026-04-01 02:44:57', 'video', 0, 1),
(14, 14, 771, '2026-04-01 02:49:08', '2026-04-01 02:49:08', 'video', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `download_options`
--

CREATE TABLE `download_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resource_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `estimated_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'c7566a2b-574a-45e5-a68d-f1c300f37d2f', 'database', 'default', '{\"uuid\":\"c7566a2b-574a-45e5-a68d-f1c300f37d2f\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";N;}\"},\"createdAt\":1760560370,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(337): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->runNextJob()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:51:36'),
(2, '65f26b8c-5606-4487-bdf9-867305534c42', 'database', 'default', '{\"uuid\":\"65f26b8c-5606-4487-bdf9-867305534c42\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";N;}\"},\"createdAt\":1760560370,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51'),
(3, '9029a5ac-dfef-4e47-ab9c-3888b04fae45', 'database', 'default', '{\"uuid\":\"9029a5ac-dfef-4e47-ab9c-3888b04fae45\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/videos\\/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Products\\/15\\/videos\\/campaign-reel-03.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";N;}\"},\"createdAt\":1760560371,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/videos/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51'),
(4, '099314a9-ea8f-4fb3-b6cc-8b2ae2f0b051', 'database', 'default', '{\"uuid\":\"099314a9-ea8f-4fb3-b6cc-8b2ae2f0b051\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/lL51jkIRJHCm1zNYOjLpmHGgj9BhTsxlzCc0yFuD.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:2;}\"},\"createdAt\":1760561687,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/lL51jkIRJHCm1zNYOjLpmHGgj9BhTsxlzCc0yFuD.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51'),
(5, '777d013d-7845-41cf-bca7-743677df17ae', 'database', 'default', '{\"uuid\":\"777d013d-7845-41cf-bca7-743677df17ae\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:3;}\"},\"createdAt\":1760561687,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51'),
(6, '663618b5-0fe0-4738-8bdb-6e8544b3d162', 'database', 'default', '{\"uuid\":\"663618b5-0fe0-4738-8bdb-6e8544b3d162\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:4;}\"},\"createdAt\":1760561687,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51'),
(7, 'ca95608b-e95e-455d-833a-8bcb227aa02e', 'database', 'default', '{\"uuid\":\"ca95608b-e95e-455d-833a-8bcb227aa02e\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/videos\\/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Products\\/15\\/videos\\/campaign-reel-03.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:5;}\"},\"createdAt\":1760561687,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/videos/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-15 23:55:51');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(8, '41027c81-61cb-4c14-a8ba-4859a381a920', 'database', 'default', '{\"uuid\":\"41027c81-61cb-4c14-a8ba-4859a381a920\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/lL51jkIRJHCm1zNYOjLpmHGgj9BhTsxlzCc0yFuD.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:6;}\"},\"createdAt\":1760562007,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/lL51jkIRJHCm1zNYOjLpmHGgj9BhTsxlzCc0yFuD.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 00:00:09'),
(9, '693e4480-d201-4763-939a-06bb76be593c', 'database', 'default', '{\"uuid\":\"693e4480-d201-4763-939a-06bb76be593c\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:7;}\"},\"createdAt\":1760562007,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/g2ZjDS8dbXwm2CSwFOh8fIha0mVB7Sfm4gbzPxDk.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 00:00:09'),
(10, 'a76fdabd-f62a-44ab-b842-04089943808a', 'database', 'default', '{\"uuid\":\"a76fdabd-f62a-44ab-b842-04089943808a\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/images\\/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:44:\\\"Products\\/15\\/images\\/campaign-post-feed-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:8;}\"},\"createdAt\":1760562007,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/images/c1u3pTegkMMKTFk3oLwJJ5k2yoZVPwrbv3XGebsv.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 00:00:09'),
(11, 'c4104e59-719b-4c90-9d58-bdee79b3b96f', 'database', 'default', '{\"uuid\":\"c4104e59-719b-4c90-9d58-bdee79b3b96f\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/products\\/15\\/videos\\/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Products\\/15\\/videos\\/campaign-reel-03.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:9;}\"},\"createdAt\":1760562007,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/products/15/videos/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 00:00:09'),
(12, '033889d2-9cea-456b-83cb-777e87a51cd3', 'database', 'default', '{\"uuid\":\"033889d2-9cea-456b-83cb-777e87a51cd3\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:18;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(13, '0049f269-1c57-470f-a82b-3d3588a80d2d', 'database', 'default', '{\"uuid\":\"0049f269-1c57-470f-a82b-3d3588a80d2d\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:19;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(14, '9a1b1519-fc40-4113-b120-2cd0258c0d4d', 'database', 'default', '{\"uuid\":\"9a1b1519-fc40-4113-b120-2cd0258c0d4d\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:20;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(15, 'c723bf50-a6b9-4c82-949e-8703558959b4', 'database', 'default', '{\"uuid\":\"c723bf50-a6b9-4c82-949e-8703558959b4\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-04.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:21;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(16, '232dbed7-dc12-4db7-82e9-390721704789', 'database', 'default', '{\"uuid\":\"232dbed7-dc12-4db7-82e9-390721704789\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-dfes-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:22;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(17, 'e00b1859-1ca2-47d2-85ae-aa9859a10a88', 'database', 'default', '{\"uuid\":\"e00b1859-1ca2-47d2-85ae-aa9859a10a88\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:23;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(18, '61e70db4-73da-4528-940b-5e4603b1ba2f', 'database', 'default', '{\"uuid\":\"61e70db4-73da-4528-940b-5e4603b1ba2f\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:24;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(19, '194da104-fb5c-4e7a-89ef-d4edac4c8dce', 'database', 'default', '{\"uuid\":\"194da104-fb5c-4e7a-89ef-d4edac4c8dce\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-04.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:25;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(20, '3d3a7fa4-d59f-4fbe-aa5a-33138a18550e', 'database', 'default', '{\"uuid\":\"3d3a7fa4-d59f-4fbe-aa5a-33138a18550e\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:138:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/folders\\/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:37:\\\"Campaigns\\/18\\/campaign-folder-mgsp.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:26;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(21, 'f49d0d71-237a-485a-9865-e4637ef5c9f3', 'database', 'default', '{\"uuid\":\"f49d0d71-237a-485a-9865-e4637ef5c9f3\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:138:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/folders\\/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:37:\\\"Campaigns\\/18\\/campaign-folder-dfes.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:27;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(22, '57549167-69a7-49d2-a664-84f8a58cfb86', 'database', 'default', '{\"uuid\":\"57549167-69a7-49d2-a664-84f8a58cfb86\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:33:\\\"Campaigns\\/18\\/campaign-reel-01.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:28;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(23, '01c8b545-4ec4-456f-b65c-7f345d331a74', 'database', 'default', '{\"uuid\":\"01c8b545-4ec4-456f-b65c-7f345d331a74\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:33:\\\"Campaigns\\/18\\/campaign-reel-02.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:29;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(24, '4d5e2cf6-ddf7-4c57-b4df-b594b80ad519', 'database', 'default', '{\"uuid\":\"4d5e2cf6-ddf7-4c57-b4df-b594b80ad519\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:34:\\\"Campaigns\\/18\\/campaign-video-02.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:30;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(25, 'a5f2069c-b3e6-437a-bf41-d7d3e2c3d5a4', 'database', 'default', '{\"uuid\":\"a5f2069c-b3e6-437a-bf41-d7d3e2c3d5a4\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/file_example_MP3_700KB.mp3\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:31;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(26, 'e5367309-404b-4a54-a674-889513c2ca03', 'database', 'default', '{\"uuid\":\"e5367309-404b-4a54-a674-889513c2ca03\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:20:\\\"Campaigns\\/18\\/tag.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:32;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(27, '2be7fa43-9441-49c8-b20b-51cc2cb86c8a', 'database', 'default', '{\"uuid\":\"2be7fa43-9441-49c8-b20b-51cc2cb86c8a\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:23:\\\"Campaigns\\/18\\/script.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:33;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49'),
(28, 'be57821b-6bfb-4639-bf0d-48c82a009c49', 'database', 'default', '{\"uuid\":\"be57821b-6bfb-4639-bf0d-48c82a009c49\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:24:\\\"Campaigns\\/18\\/sticker.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:34;}\"},\"createdAt\":1760569307,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:01:49');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(29, '30a4ca82-7693-46c1-a485-9a2e23874012', 'database', 'default', '{\"uuid\":\"30a4ca82-7693-46c1-a485-9a2e23874012\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:18;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(30, 'f01d3454-55dc-4d46-a116-fdabf810439e', 'database', 'default', '{\"uuid\":\"f01d3454-55dc-4d46-a116-fdabf810439e\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:19;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(31, 'aa1d9ab1-c94f-45ae-b956-ce786152e497', 'database', 'default', '{\"uuid\":\"aa1d9ab1-c94f-45ae-b956-ce786152e497\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:20;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(32, 'e80f3477-9f03-423d-a10d-5c3ef078c111', 'database', 'default', '{\"uuid\":\"e80f3477-9f03-423d-a10d-5c3ef078c111\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:38:\\\"Campaigns\\/18\\/campaign-post-feed-04.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:21;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(33, 'ab451fe9-c525-4ee8-b77c-6a3544ea0448', 'database', 'default', '{\"uuid\":\"ab451fe9-c525-4ee8-b77c-6a3544ea0448\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-dfes-01.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:22;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(34, '7e7e090b-731a-494c-ada0-f6e0f9b155a8', 'database', 'default', '{\"uuid\":\"7e7e090b-731a-494c-ada0-f6e0f9b155a8\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-02.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:23;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(35, '5cad1da5-6f5a-4dca-a2a9-419d570c7ce7', 'database', 'default', '{\"uuid\":\"5cad1da5-6f5a-4dca-a2a9-419d570c7ce7\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-03.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:24;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(36, 'c45f5edf-b2b8-4369-8075-5492708adcd7', 'database', 'default', '{\"uuid\":\"c45f5edf-b2b8-4369-8075-5492708adcd7\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:136:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/campaign-story-mgsp-04.jpg\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:25;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(37, '769c2b93-4809-4175-a980-c3eb76950806', 'database', 'default', '{\"uuid\":\"769c2b93-4809-4175-a980-c3eb76950806\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:138:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/folders\\/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:37:\\\"Campaigns\\/18\\/campaign-folder-mgsp.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:26;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(38, 'e62c30b5-6084-40e8-a0de-f819f44e8e31', 'database', 'default', '{\"uuid\":\"e62c30b5-6084-40e8-a0de-f819f44e8e31\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:138:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/folders\\/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:37:\\\"Campaigns\\/18\\/campaign-folder-dfes.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:27;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(39, 'b9a08cc4-743d-478d-af99-40bb9bddf16e', 'database', 'default', '{\"uuid\":\"b9a08cc4-743d-478d-af99-40bb9bddf16e\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:33:\\\"Campaigns\\/18\\/campaign-reel-01.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:28;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(40, '5cc05644-5b32-41d3-a5d5-f6c3a31c9618', 'database', 'default', '{\"uuid\":\"5cc05644-5b32-41d3-a5d5-f6c3a31c9618\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:33:\\\"Campaigns\\/18\\/campaign-reel-02.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:29;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(41, 'e7a57e2d-30f7-4667-85c0-4290b8e6dcae', 'database', 'default', '{\"uuid\":\"e7a57e2d-30f7-4667-85c0-4290b8e6dcae\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:137:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/videos\\/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:34:\\\"Campaigns\\/18\\/campaign-video-02.mp4\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:30;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(42, '2662ce02-8f3d-4440-b829-7db0ed4bba03', 'database', 'default', '{\"uuid\":\"2662ce02-8f3d-4440-b829-7db0ed4bba03\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:39:\\\"Campaigns\\/18\\/file_example_MP3_700KB.mp3\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:31;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3 in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(43, '6579e409-1881-4a7e-9353-922b0c46f5af', 'database', 'default', '{\"uuid\":\"6579e409-1881-4a7e-9353-922b0c46f5af\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:20:\\\"Campaigns\\/18\\/tag.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:32;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(44, 'f595c484-f808-450d-8562-5cdedc9fa57e', 'database', 'default', '{\"uuid\":\"f595c484-f808-450d-8562-5cdedc9fa57e\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:23:\\\"Campaigns\\/18\\/script.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:33;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07'),
(45, '6ee34c6f-b617-4df8-b89d-2df54e36e84b', 'database', 'default', '{\"uuid\":\"6ee34c6f-b617-4df8-b89d-2df54e36e84b\",\"displayName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":600,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\UploadToOneDrive\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\UploadToOneDrive\\\":3:{s:36:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000localPath\\\";s:144:\\\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/miscellaneous\\/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf\\\";s:37:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000remotePath\\\";s:24:\\\"Campaigns\\/18\\/sticker.pdf\\\";s:33:\\\"\\u0000App\\\\Jobs\\\\UploadToOneDrive\\u0000syncId\\\";i:34;}\"},\"createdAt\":1760569506,\"delay\":null}', 'RuntimeException: Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf in /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/app/Jobs/UploadToOneDrive.php:66\nStack trace:\n#0 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\UploadToOneDrive->handle()\n#1 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#2 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#3 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#4 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#5 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Container\\Container->call()\n#6 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}()\n#7 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#8 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(136): Illuminate\\Pipeline\\Pipeline->then()\n#9 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(134): Illuminate\\Bus\\Dispatcher->dispatchNow()\n#10 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}()\n#11 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}()\n#12 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(127): Illuminate\\Pipeline\\Pipeline->then()\n#13 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(68): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware()\n#14 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call()\n#15 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\\Queue\\Jobs\\Job->fire()\n#16 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\\Queue\\Worker->process()\n#17 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\\Queue\\Worker->runJob()\n#18 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->daemon()\n#19 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker()\n#20 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#21 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#22 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure()\n#23 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod()\n#24 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Container/Container.php(836): Illuminate\\Container\\BoundMethod::call()\n#25 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call()\n#26 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Command/Command.php(318): Illuminate\\Console\\Command->execute()\n#27 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run()\n#28 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(1110): Illuminate\\Console\\Command->run()\n#29 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(359): Symfony\\Component\\Console\\Application->doRunCommand()\n#30 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/symfony/console/Application.php(194): Symfony\\Component\\Console\\Application->doRun()\n#31 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\\Component\\Console\\Application->run()\n#32 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle()\n#33 /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/artisan(16): Illuminate\\Foundation\\Application->handleCommand()\n#34 {main}', '2025-10-16 02:05:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('image','video','pdf','audio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `files`
--

INSERT INTO `files` (`id`, `name`, `type`, `path`, `size`, `extension`, `mime_type`, `order`, `created_at`, `updated_at`) VALUES
(1, 'product-sample01.jpg', 'image', 'private/products/1/product-sample01.jpg', 1648427, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(2, 'product-sample02.jpg', 'image', 'private/products/1/product-sample02.jpg', 1668213, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(3, 'product-sample03.jpg', 'image', 'private/products/1/product-sample03.jpg', 7892879, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(4, 'product-sample04.jpg', 'image', 'private/products/1/product-sample04.jpg', 8291593, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(5, 'product-video01.mp4', 'video', 'private/products/1/product-video01.mp4', 102672028, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(6, 'product-video02.mp4', 'video', 'private/products/1/product-video02.mp4', 109502885, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(7, 'product-video03.mp4', 'video', 'private/products/1/product-video03.mp4', 70107187, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(8, 'product-sample01.jpg', 'image', 'private/products/2/product-sample01.jpg', 1648427, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(9, 'product-sample02.jpg', 'image', 'private/products/2/product-sample02.jpg', 1668213, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(10, 'product-sample03.jpg', 'image', 'private/products/2/product-sample03.jpg', 7892879, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(11, 'product-sample04.jpg', 'image', 'private/products/2/product-sample04.jpg', 8291593, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(12, 'product-video01.mp4', 'video', 'private/products/2/product-video01.mp4', 102672028, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(13, 'product-video02.mp4', 'video', 'private/products/2/product-video02.mp4', 109502885, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(14, 'product-video03.mp4', 'video', 'private/products/2/product-video03.mp4', 70107187, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(15, 'product-sample01.jpg', 'image', 'private/products/3/product-sample01.jpg', 1648427, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(16, 'product-sample02.jpg', 'image', 'private/products/3/product-sample02.jpg', 1668213, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(17, 'product-sample03.jpg', 'image', 'private/products/3/product-sample03.jpg', 7892879, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(18, 'product-sample04.jpg', 'image', 'private/products/3/product-sample04.jpg', 8291593, 'jpg', 'image/jpeg', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(19, 'product-video01.mp4', 'video', 'private/products/3/product-video01.mp4', 102672028, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(20, 'product-video02.mp4', 'video', 'private/products/3/product-video02.mp4', 109502885, 'mp4', 'video/mp4', 0, '2025-08-29 22:00:59', '2025-08-29 22:00:59'),
(21, 'product-video03.mp4', 'video', 'private/products/3/product-video03.mp4', 70107187, 'mp4', 'video/mp4', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(22, 'product-sample01.jpg', 'image', 'private/products/4/product-sample01.jpg', 1648427, 'jpg', 'image/jpeg', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(23, 'product-sample02.jpg', 'image', 'private/products/4/product-sample02.jpg', 1668213, 'jpg', 'image/jpeg', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(24, 'product-sample03.jpg', 'image', 'private/products/4/product-sample03.jpg', 7892879, 'jpg', 'image/jpeg', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(25, 'product-sample04.jpg', 'image', 'private/products/4/product-sample04.jpg', 8291593, 'jpg', 'image/jpeg', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(26, 'product-video01.mp4', 'video', 'private/products/4/product-video01.mp4', 102672028, 'mp4', 'video/mp4', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(27, 'product-video02.mp4', 'video', 'private/products/4/product-video02.mp4', 109502885, 'mp4', 'video/mp4', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(28, 'product-video03.mp4', 'video', 'private/products/4/product-video03.mp4', 70107187, 'mp4', 'video/mp4', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(41, 'folders', 'image', 'private/campaing/1/folders', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(42, 'miscellaneous', 'image', 'private/campaing/1/miscellaneous', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(43, 'posts', 'image', 'private/campaing/1/posts', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(44, 'videos', 'image', 'private/campaing/1/videos', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(45, 'folders', 'image', 'private/campaing/2/folders', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(46, 'miscellaneous', 'image', 'private/campaing/2/miscellaneous', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(47, 'posts', 'image', 'private/campaing/2/posts', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(48, 'videos', 'image', 'private/campaing/2/videos', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(49, 'folders', 'image', 'private/campaing/3/folders', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(50, 'miscellaneous', 'image', 'private/campaing/3/miscellaneous', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(51, 'posts', 'image', 'private/campaing/3/posts', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(52, 'videos', 'image', 'private/campaing/3/videos', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(53, 'folders', 'image', 'private/campaing/4/folders', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(54, 'miscellaneous', 'image', 'private/campaing/4/miscellaneous', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(55, 'posts', 'image', 'private/campaing/4/posts', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(56, 'videos', 'image', 'private/campaing/4/videos', 4096, '', 'directory', 0, '2025-08-29 22:01:00', '2025-08-29 22:01:00'),
(164, 'produto-01_imagem_01.jpg', 'image', 'private/products/5/images/EEv52xh6ZDZOlErykqaSoefv7gPgu7w2hJ9kS6bS.jpg', 1376446, 'jpg', 'image/jpeg', 1, '2025-10-14 21:02:35', '2025-10-14 21:02:35'),
(165, 'produto-01_imagem_02.jpg', 'image', 'private/products/5/images/gi6toDXP8UVTuvhWlxk4y0fgYxgmOd4OPichTiuC.jpg', 733127, 'jpg', 'image/jpeg', 2, '2025-10-14 21:02:35', '2025-10-14 21:02:35'),
(166, 'produto-01_imagem_03.jpg', 'image', 'private/products/5/images/8CTUdXnuV0FPjzDVsqen9Wfk5tAU1XHSQ8u47HrO.jpg', 1078231, 'jpg', 'image/jpeg', 3, '2025-10-14 21:02:35', '2025-10-14 21:02:35'),
(167, 'produto-01_video_01.mp4', 'video', 'private/products/5/videos/azkztcfIS0yg8u19JtVwzcwL8Ez794b2NkBiFAHD.mp4', 17537537, 'mp4', 'video/mp4', 1, '2025-10-14 21:02:35', '2025-10-14 21:02:35'),
(168, 'produto-01_video_02.mp4', 'video', 'private/products/5/videos/blkX7h2UZbrEqscO1JR8OD55tnW19aSgK8Ojdevp.mp4', 3868280, 'mp4', 'video/mp4', 2, '2025-10-14 21:02:35', '2025-10-14 21:02:35'),
(169, 'teste.jpg', 'image', 'private/products/6/images/teste.jpg', 1024, 'jpg', 'image/jpeg', 1, '2025-10-14 21:06:30', '2025-10-14 21:06:30'),
(170, 'produto-01_imagem_01.jpg', 'image', 'private/products/7/images/tF25Gd2KkwH4azOr8508U2YU9NZv9hgjaT47W3NY.jpg', 1376446, 'jpg', 'image/jpeg', 1, '2025-10-14 21:09:27', '2025-10-14 21:09:27'),
(171, 'produto-01_imagem_02.jpg', 'image', 'private/products/7/images/mP2FXR4vxlhZuE1hJBiat1WDY0EtpZe2xmhJD3Gc.jpg', 733127, 'jpg', 'image/jpeg', 2, '2025-10-14 21:09:27', '2025-10-14 21:09:27'),
(172, 'produto-01_imagem_03.jpg', 'image', 'private/products/7/images/nU18QYrx5Ydym6K52s0RzaYXGraRqF4PoNZHr518.jpg', 1078231, 'jpg', 'image/jpeg', 3, '2025-10-14 21:09:27', '2025-10-14 21:09:27'),
(173, 'produto-01_video_01.mp4', 'video', 'private/products/7/videos/q7bDekm4Y68X1xhhIi0u9eWaHHCLmU1fa0sI1LAB.mp4', 17537537, 'mp4', 'video/mp4', 1, '2025-10-14 21:09:27', '2025-10-14 21:09:27'),
(174, 'produto-01_video_02.mp4', 'video', 'private/products/7/videos/07fSQPsbcnFyluf2u29NRQDDGpiFoeCsbWAzzxgO.mp4', 3868280, 'mp4', 'video/mp4', 2, '2025-10-14 21:09:27', '2025-10-14 21:09:27'),
(175, 'produto-01_imagem_01.jpg', 'image', 'private/products/8/images/J6OFvHBJ5bT6zSmYTzcUinIRTturMXCt4QdIX2MZ.jpg', 1376446, 'jpg', 'image/jpeg', 1, '2025-10-14 21:12:22', '2025-10-14 21:12:22'),
(176, 'produto-01_imagem_02.jpg', 'image', 'private/products/8/images/gp2a9PkyUDI8ZwcLsTpInuWTtsKXzjxGjNx0RXVg.jpg', 733127, 'jpg', 'image/jpeg', 2, '2025-10-14 21:12:22', '2025-10-14 21:12:22'),
(177, 'produto-01_imagem_03.jpg', 'image', 'private/products/8/images/6P9mYhG7bNSVugwhb25GXXxjufJ5NO7YxgwMUtpH.jpg', 1078231, 'jpg', 'image/jpeg', 3, '2025-10-14 21:12:22', '2025-10-14 21:12:22'),
(178, 'produto-01_video_01.mp4', 'video', 'private/products/8/videos/k6kvDd1DAKkpRo6rDet4HpDv0VN6xks9IrgVhsO6.mp4', 17537537, 'mp4', 'video/mp4', 1, '2025-10-14 21:12:22', '2025-10-14 21:12:22'),
(179, 'produto-01_video_02.mp4', 'video', 'private/products/8/videos/qPcuzIs1algVBSqnr52jiJSx852kfbTwpmZ4K17c.mp4', 3868280, 'mp4', 'video/mp4', 2, '2025-10-14 21:12:22', '2025-10-14 21:12:22'),
(182, 'produto-03_imagem_01.jpg', 'image', 'private/products/9/images/lT08jHW321mNRGhLUNUw7jijYd13AAuwrCnRFXGP.png', 1980470, 'jpg', 'image/png', 1, '2025-10-15 01:42:04', '2025-10-15 01:42:04'),
(183, 'produto-03_imagem_02.jpg', 'image', 'private/products/9/images/hbTBkpkAfaAErLEuSiovGeE9l5GKwTn5ihJzF1PQ.png', 2172084, 'jpg', 'image/png', 2, '2025-10-15 01:42:04', '2025-10-15 01:42:04'),
(184, 'produto-03_imagem_03.jpg', 'image', 'private/products/9/images/t2scQwNf6KfoRKF4Ut58gKWLRynsgho3ejKj7P0c.png', 2177651, 'jpg', 'image/png', 3, '2025-10-15 01:42:04', '2025-10-15 01:42:04'),
(185, 'produto-03_imagem_04.jpg', 'image', 'private/products/9/images/x9jcCqnb1KSSKpCWI9lZQWZDzfYiqbcW2yzTfDCn.png', 2100566, 'jpg', 'image/png', 4, '2025-10-15 01:42:04', '2025-10-15 01:42:04'),
(186, 'produto-03_video_01.mp4', 'video', 'private/products/9/videos/nlmI9yEQgp5G26TZnhbawALjDynF00t1sw57pZaI.mp4', 7524005, 'mp4', 'video/mp4', 1, '2025-10-15 01:42:05', '2025-10-15 01:42:05'),
(187, 'produto-03_video_02.mp4', 'video', 'private/products/9/videos/yEDeTUsPar9w9T8yqdfeaMecNfTpdUxWjItDtqKQ.mp4', 14990444, 'mp4', 'video/mp4', 2, '2025-10-15 01:42:05', '2025-10-15 01:42:05'),
(189, 'produto-03_imagem_01.jpg', 'image', 'private/products/11/images/4onYUEaRREQ7iU0EjHErN96jLZqqT3KXiMkvsUF3.png', 1980470, 'jpg', 'image/png', 1, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(190, 'produto-03_imagem_02.jpg', 'image', 'private/products/11/images/k52b1XSDqoe1zejKWRSzBT2i8HyLRkLHpjN8bPvs.png', 2172084, 'jpg', 'image/png', 2, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(191, 'produto-03_imagem_03.jpg', 'image', 'private/products/11/images/JO5HAMvZuE3OEliGTBRfeoYG9SXlQh1bdEnC7RdR.png', 2177651, 'jpg', 'image/png', 3, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(192, 'produto-03_imagem_04.jpg', 'image', 'private/products/11/images/3uS2qZM6r49THZ8xXbWtZWAiujJaE4HC1fMRrUGr.png', 2100566, 'jpg', 'image/png', 4, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(193, 'produto-03_video_01.mp4', 'video', 'private/products/11/videos/ZiSghz0R1iZoNNqFWOb8uyUkWhG63rzU0JzqWszV.mp4', 7524005, 'mp4', 'video/mp4', 1, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(194, 'produto-03_video_02.mp4', 'video', 'private/products/11/videos/ZSrkTs6bEjMbCR5LtJEaV34JwHJyvJ1ZEYmxf8R4.mp4', 14990444, 'mp4', 'video/mp4', 2, '2025-10-15 20:42:03', '2025-10-15 20:42:03'),
(195, 'produto-03_imagem_01.jpg', 'image', 'private/products/12/images/fr8BPCnb0lvVnUP4At2BBnYA7emj7d7BwPFlv0Sq.png', 1980470, 'jpg', 'image/png', 1, '2025-10-15 21:09:27', '2025-10-15 21:09:27'),
(196, 'produto-03_imagem_02.jpg', 'image', 'private/products/12/images/uXO8uKQxcZxIwgyUhoYWmVEf2AxWw7Ri9VRh3YIK.png', 2172084, 'jpg', 'image/png', 2, '2025-10-15 21:09:33', '2025-10-15 21:09:33'),
(197, 'produto-03_imagem_03.jpg', 'image', 'private/products/12/images/mzx5MHf8QDwCesAVglejxuhdBpNqaEbUIbIVpLrP.png', 2177651, 'jpg', 'image/png', 3, '2025-10-15 21:09:34', '2025-10-15 21:09:34'),
(198, 'produto-03_video_01.mp4', 'video', 'private/products/12/videos/8ilPB4I1XaLD1a6NkKdOGF9YqssFQjhgOxo1Wgx0.mp4', 7524005, 'mp4', 'video/mp4', 1, '2025-10-15 21:09:36', '2025-10-15 21:09:36'),
(199, 'produto-03_video_02.mp4', 'video', 'private/products/12/videos/ovcN5cX3pM4MYhTfXTxICbj2Yc9UTppvR5gYJyKF.mp4', 14990444, 'mp4', 'video/mp4', 2, '2025-10-15 21:09:40', '2025-10-15 21:09:40'),
(253, 'campaign-video-01.mp4', 'video', 'private/products/13/videos/7iYEYSMkEVBt6mpA859cEXnUe3IgcJ0vR1rmDuCA.mp4', 102672028, 'mp4', 'video/mp4', 1, '2025-10-15 23:01:14', '2025-10-15 23:01:14'),
(254, 'campaign-post-feed-01.jpg', 'image', 'private/products/14/images/ydnYBkFG8nzqXDetnERIt7maDlNsVzAYCdNpB0LK.jpg', 1648427, 'jpg', 'image/jpeg', 1, '2025-10-15 23:18:21', '2025-10-15 23:18:21'),
(255, 'campaign-post-feed-02.jpg', 'image', 'private/products/14/images/yLaWTWHm4sTFMUrzuzeuA6bfL1iC3hmAeIYceD6u.jpg', 1668213, 'jpg', 'image/jpeg', 2, '2025-10-15 23:18:27', '2025-10-15 23:18:27'),
(256, 'campaign-post-feed-03.jpg', 'image', 'private/products/14/images/jHZiLBnyHUn5AEtHC0a73zEXxRlNfrH5EiAQxTRS.jpg', 7892879, 'jpg', 'image/jpeg', 3, '2025-10-15 23:18:33', '2025-10-15 23:18:33'),
(257, 'campaign-reel-03.mp4', 'video', 'private/products/14/videos/GaXVFhITCrELqxIdi59OSF3vGtXtlLkjI1WcsqaW.mp4', 70107187, 'mp4', 'video/mp4', 1, '2025-10-15 23:18:38', '2025-10-15 23:18:38'),
(258, 'campaign-video-01.mp4', 'video', 'private/products/14/videos/IQgoECPIJi6oQ9DDFqztMDkrZhSqEL9ekke0vk9i.mp4', 102672028, 'mp4', 'video/mp4', 2, '2025-10-15 23:19:01', '2025-10-15 23:19:01'),
(259, 'campaign-post-feed-01.jpg', 'image', 'private/campaigns/1/posts/campaign-post-feed-01.jpg', 1648427, 'jpg', 'image/jpeg', 1, '2025-10-15 23:32:50', '2025-10-16 00:05:53'),
(260, 'campaign-post-feed-02.jpg', 'image', 'private/campaigns/1/posts/campaign-post-feed-02.jpg', 1668213, 'jpg', 'image/jpeg', 2, '2025-10-15 23:32:50', '2025-10-16 00:05:53'),
(261, 'campaign-post-feed-03.jpg', 'image', 'private/campaigns/1/posts/campaign-post-feed-03.jpg', 7892879, 'jpg', 'image/jpeg', 3, '2025-10-15 23:32:50', '2025-10-16 00:05:53'),
(262, 'campaign-reel-03.mp4', 'video', 'private/products/15/videos/WrOCQk3Zkn9R5mFBif8jhbX1GLD7l2IaYKTeZ0tf.mp4', 70107187, 'mp4', 'video/mp4', 1, '2025-10-15 23:32:51', '2025-10-15 23:32:51'),
(263, 'Feed-301-2.png', 'image', 'private/campaigns/1/posts/CDhV9JoEjGtRraW1ohJ4n8eiIXoLEj4XHrZ8xI6w.png', 371078, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(264, 'Feed-301-3.png', 'image', 'private/campaigns/1/posts/HHK0dA62GrmA8mZc8J6IIymLCm8Usei9cFlbNUaT.png', 409062, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(265, 'Feed-507-1.png', 'image', 'private/campaigns/1/posts/WZEtN2EhEygPMh1mllpuXA0XIwjnBHl0sqgLX2Bk.png', 438792, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(266, 'Feed-507-2.png', 'image', 'private/campaigns/1/posts/PaIE9ZOkAEpCcIu5SPTqMIHOzw7Wf2TdrAu6C5H0.png', 486770, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(267, 'Feed-507-3.png', 'image', 'private/campaigns/1/posts/eJ8Yq3XVcyqskBFvbvkcdvIdcp0GvRRcQG9nwaQx.png', 671194, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(268, 'Feed-Humanizado-1.png', 'image', 'private/campaigns/1/posts/nH3M522Xdab8xr0mZTQDa973LQuX8x2mbOcxerTw.png', 899659, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(269, 'Feed-Humanizado-2.png', 'image', 'private/campaigns/1/posts/lBZEOG6COKbTmFDrctEeiZ4d2fXSmbNGJ5lAmKsF.png', 608983, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(270, 'Feed-Humanizado-3.png', 'image', 'private/campaigns/1/posts/3zLrwM4ij4L9KwvxdfcTKM1uwVQxNtwcJznPPNL9.png', 827746, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(271, 'Feed-Humanizado-4.png', 'image', 'private/campaigns/1/posts/pbMj1DarxFZXTRnRvCHM5a1PG3aToOmrr4Xm78ev.png', 594514, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(272, 'Feed-Humanizado-5.png', 'image', 'private/campaigns/1/posts/1SFSQZ57emHGwf052cPEo5W90iaCuIPEAHEP5xEo.png', 655087, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(273, 'Feed-Humanizado-6.png', 'image', 'private/campaigns/1/posts/9dzdHB0oRPc7Q2REdHlAx7yuytAkhCTqW5sZM4Av.png', 828247, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(274, 'Feed-Morpheu-1.png', 'image', 'private/campaigns/1/posts/2vHjByQOwdoM47d3XkQdYcVRkOeKmzbRrFVnJLur.png', 581752, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(275, 'Feed-Morpheu-2.png', 'image', 'private/campaigns/1/posts/nyMJ3zoLayULAK5YS7gmMSSKnr81HGrgykR2dRdg.png', 304961, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(276, 'Feed-Morpheu-3.png', 'image', 'private/campaigns/1/posts/Ugus0QXHsklUWg39kvwrua106noTLa7wW8DGb8hy.png', 405138, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(277, 'Feed-Morpheu-4.png', 'image', 'private/campaigns/1/posts/uBW8lmUdDKgXyhHBuJCAWANrCCPt471xZ21OLST4.png', 388760, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(278, 'Feed-Morpheu-5.png', 'image', 'private/campaigns/1/posts/ebfnVCv2YymAbUnhDV5Da8IXpD5GcURo3gS1ckKy.png', 351122, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(279, 'Feed-Polaris-2.jpg', 'image', 'private/campaigns/1/posts/BCMc5BMMrlVlqwaysZTSvDoGU1QwfBDkraDuUZ2m.jpg', 606578, 'jpg', 'image/jpeg', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(280, 'Feed-Polaris-3.png', 'image', 'private/campaigns/1/posts/nnszcFLKYdE5HmNIeCrKgNTARFfwD6dRs2mrNrhW.png', 419173, 'png', 'image/png', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(281, 'FOLHETO-MG.pdf', 'pdf', 'private/campaigns/1/folders/EGE1hrLQDyRKm3RPcOMQIyowO1pcqXYs07Q1XPZh.pdf', 7577124, 'pdf', 'application/pdf', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(282, 'FOLHETO-OUTROS-ESTADOS.pdf', 'pdf', 'private/campaigns/1/folders/PuI6PmONnOtEFhcsBfiGGjyV9ycv4C7iA8TYgrxg.pdf', 7577220, 'pdf', 'application/pdf', 0, '2026-03-10 01:18:55', '2026-03-10 01:18:55'),
(283, '301-1.png', 'image', 'private/campaigns/1/posts/kvQK113MA7U4644q4Bz6RWJWOzroEFjMVuSPwq98.png', 957655, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(284, '301-2.png', 'image', 'private/campaigns/1/posts/vVI7WX0UWjaFnFPC4ekTOUkxePsyAMFrkBFJjkEU.png', 569760, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(285, '301-3.png', 'image', 'private/campaigns/1/posts/pREdlFYztAybokPuIw7r53oDR7OUZCxJEWcli9kr.png', 536281, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(286, '301-5.png', 'image', 'private/campaigns/1/posts/nLt4a67zY70FZmgCcIOKuM69P6ciH36mFTXgQVyC.png', 575237, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(287, '507-1.jpg', 'image', 'private/campaigns/1/posts/tVN3kbOvUvF2mk4uf5EXP0BI3VccIKLMj8aHOex8.jpg', 683263, 'jpg', 'image/jpeg', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(288, '507-2.png', 'image', 'private/campaigns/1/posts/byOccyLaJOUXilPWCmCvybREBWgISJeIMvOXVVV9.png', 738012, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(289, '507-3.png', 'image', 'private/campaigns/1/posts/SCNhBnJ7p1t3hHOgO8iInAP5hjQy7ql8AKwYZBIV.png', 868157, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(290, 'Humanizado-1.png', 'image', 'private/campaigns/1/posts/kZSa9sYzj56HJHeIqkjaD4D3ejs0zVtHrdERVfwr.png', 971480, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(291, 'Humanizado-2.png', 'image', 'private/campaigns/1/posts/ohulnu91XhsOWXZQ33GCbR6REwaAvSf2LkaQGTG2.png', 661331, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(292, 'Humanizado-3.png', 'image', 'private/campaigns/1/posts/Z4kb4rOY4GOVZi16ihMwKqWnxMX5sFQymUk49ZE6.png', 925445, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(293, 'Humanizado-4.png', 'image', 'private/campaigns/1/posts/zyKWF7RSKS6HxU78H0oQxKXbc4AaGrTG8l5ShI8Q.png', 635979, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(294, 'Humanizado-5.png', 'image', 'private/campaigns/1/posts/e2K3ViLupB1pF2Uilxsql8s0slikKhHblDmQaGum.png', 728944, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(295, 'Humanizado-6.png', 'image', 'private/campaigns/1/posts/rbFgw8v7WNXdgBdEZWeWcfLEZuZuXvYbIFzXUqCS.png', 870545, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(296, 'Morpheu-1.png', 'image', 'private/campaigns/1/posts/6XNfzfvVBSIAF7ai0A8tbvT7wi69ouqoaLjzuT2R.png', 775783, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(297, 'Morpheu-2.png', 'image', 'private/campaigns/1/posts/d6jh4zbtUVOJ198pdYvGpydn3tU3eejRxUmSUOJV.png', 443460, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(298, 'Morpheu-3.png', 'image', 'private/campaigns/1/posts/N6XUziBc5KGUVD0kUZCebmcZG5agbumV6CWFhx6R.png', 767346, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(299, 'Morpheu-4.png', 'image', 'private/campaigns/1/posts/nDexnBbvMZ415zJuQC2bUO7J9NKwDe4ksTRgHSzu.png', 685620, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(300, 'Morpheu-5.png', 'image', 'private/campaigns/1/posts/DA8wm2Wcpg1Bb6jYZinhIUKmDQxCnvMQbcwiuomm.png', 637872, 'png', 'image/png', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(301, 'Polaris-2_1.jpg', 'image', 'private/campaigns/1/posts/T3a8UdNdZsrcTENhI2mfdlZvIymIZp5F2OncOG9i.jpg', 846631, 'jpg', 'image/jpeg', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(302, 'Polaris-3.jpg', 'image', 'private/campaigns/1/posts/ZYYbd2EltyLNny97PBNKi4p80w6gpn2MkADNdbKD.jpg', 592136, 'jpg', 'image/jpeg', 0, '2026-03-10 01:20:25', '2026-03-10 01:20:25'),
(303, '301-1.png', 'image', 'private/campaigns/1/posts/cY2Ok7LSufQZSpQM9kEINrej7hsG5vGfwXYN1K15.png', 957655, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(304, '301-2.png', 'image', 'private/campaigns/1/posts/WrukZ9T8opdIOWzJJs2lEMge1gJNjaxofMdp06VW.png', 569760, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(305, '301-3.png', 'image', 'private/campaigns/1/posts/ZR1wCiMq5p7irverVAr8lZ2GkiRibwksYR640z6A.png', 536281, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(306, '301-4.png', 'image', 'private/campaigns/1/posts/ywUVaupUZ0cmYuNIaNVUiH83wyUwijVydvpHq3c6.png', 577082, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(307, '507-1.jpg', 'image', 'private/campaigns/1/posts/Ac9BYSNMk2PvlTxpCQGOX62ZCW3sYuxdTZgxeSb3.jpg', 683263, 'jpg', 'image/jpeg', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(308, '507-2.png', 'image', 'private/campaigns/1/posts/PGX17Vk7zPKTfjlQFuRGxqO2F2YYtXTgnG8wn4wO.png', 738012, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(309, '507-3.png', 'image', 'private/campaigns/1/posts/ZUsTF0kBznylGOFoTYiR5qE2Yx0v4uGUidhj80Th.png', 868157, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(310, 'Humanizado-1.png', 'image', 'private/campaigns/1/posts/80VEQm3TsfNzEDakb9kh50dhAl6RAquNO2d03Hbg.png', 971480, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(311, 'Humanizado-2.png', 'image', 'private/campaigns/1/posts/uv220hnb971eFG27gxMb3sMpuxV19khqjmNbzheE.png', 661331, 'png', 'image/png', 0, '2026-03-10 01:30:21', '2026-03-10 01:30:21'),
(312, 'Humanizado-3.png', 'image', 'private/campaigns/1/posts/CzcdvKzAOIo3OAmKWgk4T4By76Xx2ddy9RJx96OM.png', 925445, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(313, 'Humanizado-4.png', 'image', 'private/campaigns/1/posts/fJ1NwJw24CTZRQFmvPvijPY8h0vR9luQsyR2Aqrc.png', 635979, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(314, 'Humanizado-5.png', 'image', 'private/campaigns/1/posts/mFrgfqlyIbPyhByq14mL9KZ0fZV5AkIdHgSJowkt.png', 728944, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(315, 'Humanizado-6.png', 'image', 'private/campaigns/1/posts/a2b7xdGrxcwkTY6VK7E0wn7vXVYembDpquj8DM6i.png', 870545, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(316, 'Morpheu-1.png', 'image', 'private/campaigns/1/posts/6NrdjNDDCPMgFJdwGFpIJQVClo4gJZym9lGmjady.png', 775783, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(317, 'Morpheu-2.png', 'image', 'private/campaigns/1/posts/12M6CZhRDvwsetVKi2Kk2toKhtQrbFBvADzINYua.png', 443460, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(318, 'Morpheu-3.png', 'image', 'private/campaigns/1/posts/LEdSrlNlpXCgHDhjt7LR8Jiwm1myAqryUeTyZq5B.png', 767346, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(319, 'Morpheu-4.png', 'image', 'private/campaigns/1/posts/hxfgQdk1xxIFyILXngohfbAIzNp9oadn5C5Dof72.png', 685620, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(320, 'Morpheu-5.png', 'image', 'private/campaigns/1/posts/lohlDES3I1DUSg0h2ym4bIR3CPNjRCofmzuxhTpz.png', 637872, 'png', 'image/png', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(321, 'Polaris-2.jpg', 'image', 'private/campaigns/1/posts/1H8JkbLgxKvCpd48COIDL7qJ8kq5zd6Uayf4myuO.jpg', 592216, 'jpg', 'image/jpeg', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(322, 'Polaris-2_1.jpg', 'image', 'private/campaigns/1/posts/Rn1pbGLX11WVMzDwNn0VWGNdUoUttGHoS0mv5T3T.jpg', 846631, 'jpg', 'image/jpeg', 0, '2026-03-10 01:30:22', '2026-03-10 01:30:22'),
(323, 'VIDEO-CAMPANHA.mp4', 'video', 'private/campaigns/1/videos/gnPFXWXLW3dyLJIP3lUVIcB4Pny8S47eTuo4VDrE.mp4', 34017598, 'mp4', 'video/mp4', 0, '2026-03-10 01:41:50', '2026-03-10 01:41:50'),
(324, 'Orthocrin-Março-A-19-02.mp3.mp3', 'audio', 'private/campaigns/1/miscellaneous/0xjMhVpqvSQKDIBRumUf7a4Uwk00QWFQFDr7Fy96.mp3', 932701, 'mp3', 'audio/mpeg', 0, '2026-03-10 01:49:20', '2026-03-10 01:49:20'),
(325, 'ADESIVO.pdf', 'pdf', 'private/campaigns/1/miscellaneous/Ynq2j70uOu4FiGVDM4bOzgUc87grgYsizdMHoUam.pdf', 470296, 'pdf', 'application/pdf', 0, '2026-03-10 01:49:20', '2026-03-10 01:49:20'),
(326, 'BANNER.pdf', 'pdf', 'private/campaigns/1/miscellaneous/n8fMEVt9MHpOPotyuBMIVPfZkxRnnMTG6YvO9VkY.pdf', 2973989, 'pdf', 'application/pdf', 0, '2026-03-10 01:49:20', '2026-03-10 01:49:20'),
(327, 'FAIXA.pdf', 'pdf', 'private/campaigns/1/miscellaneous/SWLz8QoIX8kfXTNHaWO1GZ8qFchX8yPFtMhBXONh.pdf', 566576, 'pdf', 'application/pdf', 0, '2026-03-10 01:49:20', '2026-03-10 01:49:20'),
(328, 'VÍDEO 01 – MORPHEU - Reels.MP4', 'video', 'private/campaigns/1/videos/xpfkMycJGIAqO0HPI6dtDVlBVqIETLKcED1FcxIf.mp4', 58557452, 'mp4', 'video/mp4', 0, '2026-03-10 02:17:31', '2026-03-10 02:17:31'),
(329, 'Campanha de Março - Generico - Reels.MP4', 'video', 'private/campaigns/1/videos/0pDdhFkiHSMpUe0HBZjrYt0rA81qgdKt0f8pnVmS.mp4', 56100539, 'mp4', 'video/mp4', 0, '2026-03-10 02:22:32', '2026-03-10 02:22:32'),
(330, 'VÍDEO 02 – Colchão Série 301 Plus  - Reels.MP4', 'video', 'private/campaigns/1/videos/3yNdayAqWTux5wLmD9h9CgV8sKRNmoOnPslnHo5v.mp4', 59486341, 'mp4', 'video/mp4', 0, '2026-03-10 02:22:32', '2026-03-10 02:22:32'),
(331, 'VÍDEO 03 – Colchão Polaris Ultra D33 - Reels.MP4', 'video', 'private/campaigns/1/videos/tKjAsqYvoQbLh0YU0ZJGO1rAFHSLH11wqC7cXTEY.mp4', 22920495, 'mp4', 'video/mp4', 0, '2026-03-10 02:22:32', '2026-03-10 02:22:32'),
(332, 'VÍDEO 04 – Travesseiros - Reels.MP4', 'video', 'private/campaigns/1/videos/sXuVMQBuAJnwLs2F5eU0JuQrc97KJml79YCoSCMo.mp4', 10129168, 'mp4', 'video/mp4', 0, '2026-03-10 02:22:32', '2026-03-10 02:22:32'),
(334, 'TREINAMENTO PRODUTO E NOVIDADES BOX-20250910 103522-Gravação de Reunião.mp4', 'video', 'private/trainings/1/wHomxm8xZSTNDuQdL6x8y68WE8gu4qmYhYUxfT4n.mp4', 125835166, 'mp4', 'video/mp4', 0, '2026-03-10 05:48:06', '2026-03-10 05:48:06'),
(335, 'ESPUMAS-POLARIS-PLUS-PT.pdf', 'pdf', 'private/trainings/1/8xiYGEk6Y4kxpTfglCGjv8dRRK0ds5nfNKE7GgsE.pdf', 12809334, 'pdf', 'application/pdf', 0, '2026-03-10 05:57:15', '2026-03-10 05:57:15'),
(336, 'TECNOLOGIAS SÉRIE 703 e SERIE 509-20250821.mp4', 'video', 'private/trainings/2/uUf6yNJRQHyxeOEEYEloPkHJDfxlM1dBrRL5IPpm.mp4', 151808437, 'mp4', 'video/mp4', 0, '2026-03-10 07:05:07', '2026-03-10 07:05:07'),
(337, '06 jan - Inicio Serie 101 e polaris Plus com Acaro Combat.jpg.jpeg', 'image', 'private/news/2/gJSvL9k5yh5cnq7kIjaldVzEXx2oho83Qnig8sWA.jpg', 427995, 'jpeg', 'image/jpeg', 0, '2026-03-10 18:08:43', '2026-03-10 18:08:43'),
(338, '06 jan - Inicio Serie 101 e polaris Plus com Acaro Combat.jpg.jpeg', 'image', 'private/news/3/K4V7I64dpr3nJXFXvGOYpqga7TI3EXWWVpfHgD29.jpg', 427995, 'jpeg', 'image/jpeg', 0, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(339, '26 jan - Suspensao Protetor de Uniao.jpg.jpeg', 'image', 'private/news/4/LjxZlbQTk9YzEpg5TWSBxf7drLKyviwzEbWVCe1G.jpg', 341854, 'jpeg', 'image/jpeg', 0, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(340, '10 fev - Nova Configuracao Col Comfort Visco.jpg.jpeg', 'image', 'private/news/5/zyD1fbHwhAGCeZx7s75Ar6aOXviiy71BSNXd41UO.jpg', 317525, 'jpeg', 'image/jpeg', 0, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(341, '19 fev - Mudanca Bordado Serie 101.jpg.jpeg', 'image', 'private/news/6/nJJOANMQZLVGLLunk9SeqMgejf6jDRWWJR25jERJ.jpg', 334126, 'jpeg', 'image/jpeg', 0, '2026-03-10 18:19:00', '2026-03-10 18:19:00'),
(342, '309-CASAL.jpg', 'image', 'private/products/1/images/OaUa1Fwukjxmi6KrrxoXhFZtCc7vlMEIUkU0XDUc.jpg', 317064, 'jpg', 'image/jpeg', 1, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(343, '309-CASAL-2.jpg', 'image', 'private/products/1/images/5JunVNukFa63P9om8crysHcmaIxY0pOzVUmoRJ7g.jpg', 232232, 'jpg', 'image/jpeg', 2, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(344, '309-CASAL-3.jpg', 'image', 'private/products/1/images/H873qjqQW8CS1VFPmwnE0N6wTcbPoZvCbAtBZ4ju.jpg', 231248, 'jpg', 'image/jpeg', 3, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(345, '309-CASAL-4.jpg', 'image', 'private/products/1/images/ergcgjEWLqMZ13OwWzWe3XEL4SiGZ7tRFaxZsnaV.jpg', 111541, 'jpg', 'image/jpeg', 4, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(346, '309-CASAL-5.jpg', 'image', 'private/products/1/images/4zb6PQiK5ncUNQWyuOTsp6pDkVxSq5qtWYINIShi.jpg', 116698, 'jpg', 'image/jpeg', 5, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(347, '309-CASAL-COLCHÃO.jpg', 'image', 'private/products/1/images/ld94Gi6x23vKCyfJcrrWlRRztAks44kN7NYGVeg2.jpg', 98701, 'jpg', 'image/jpeg', 6, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(348, '309-KING.jpg', 'image', 'private/products/1/images/XBpaExZ4Lc7CMOndnKTiXn9M9AGoCCAUcUHUSBnD.jpg', 141691, 'jpg', 'image/jpeg', 7, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(349, '309-KING-COLCHÃO.jpg', 'image', 'private/products/1/images/HexCmZOoqd8WeUYHHfv1ICtI2xTNr7ve6CAY3PKR.jpg', 111160, 'jpg', 'image/jpeg', 8, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(350, '309-QUEEN.jpg', 'image', 'private/products/1/images/gCzQgNZ6VOVmzPPKVeCctr3ucJM89v9bqC1PCHzd.jpg', 120388, 'jpg', 'image/jpeg', 9, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(351, '309-QUEEN-COLCHÃO.jpg', 'image', 'private/products/1/images/tTHlKyhn2lfOe2ErVI7RTgCWFxbjDd1iXwSuihGD.jpg', 106343, 'jpg', 'image/jpeg', 10, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(352, '309-SOLTEIRO.jpg', 'image', 'private/products/1/images/8Xkq7lXe4xdpUjkP5i1i8ZC4vaZolEHtDH4taMFq.jpg', 324830, 'jpg', 'image/jpeg', 11, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(353, '309-SOLTEIRO-2.jpg', 'image', 'private/products/1/images/CpwnMLFtv6HHCPa6Jcy3smRyoaDgBMqr65nilQYE.jpg', 82446, 'jpg', 'image/jpeg', 12, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(354, '309-SOLTEIRO-3.jpg', 'image', 'private/products/1/images/xNUPOQml1KrlSLugXXPKkEtpzlcIQ59mcPOcJYe5.jpg', 110440, 'jpg', 'image/jpeg', 13, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(355, '309-SOLTEIRO-COLCHÃO.jpg', 'image', 'private/products/1/images/Ebe2laj07farm1CWo6ODRhEM8boM5SqVdF52PGgP.jpg', 94237, 'jpg', 'image/jpeg', 14, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(356, 'Serie 309 Plus - Corte 2 - Reels.mp4', 'video', 'private/products/1/videos/CmBk0cffi9pJ04bKqUlnTztyP7Uns36pWcCwfJ6o.mp4', 226470724, 'mp4', 'video/mp4', 1, '2026-03-10 21:26:06', '2026-03-10 21:26:06'),
(357, 'Serie 309 Plus - Corte 2.mp4', 'video', 'private/products/1/videos/d4coOOIeyLvmRL12jp0qnDXcyaUWH3YfbW2rRqJ6.mp4', 91967825, 'mp4', 'video/mp4', 1, '2026-03-10 21:29:49', '2026-03-10 21:29:49'),
(358, 'MEMORIAL DESCRITIVO - MANUAL ESPECIFICACOES FRANQUIAS.pdf', 'pdf', 'private/library/1/yd9bnLA8g6qRBWiLMDfdMJ6SfGvCnazsXUWfEfNQ.pdf', 11113251, 'pdf', 'application/pdf', 0, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(359, 'CHECKLIST-ATIVIDADES.pdf', 'pdf', 'private/library/2/Vr7JcPZ79jdw5AtbSze6gdkIVPIOCAYPtIoky7xb.pdf', 2720481, 'pdf', 'application/pdf', 0, '2026-03-11 20:10:06', '2026-03-11 20:10:06'),
(360, 'ORTHOCRIN-FORCAS.pdf', 'pdf', 'private/library/3/OkUE5Z1wtACwTkZfG2tmqb0lJUhII4PetdeLTIYS.pdf', 6309356, 'pdf', 'application/pdf', 0, '2026-03-11 20:28:43', '2026-03-11 20:28:43'),
(361, '´TECNICAS-RESUMIDAS-A-PONTE.pdf', 'pdf', 'private/library/4/fp0sdk6pmkD6J9Y7qc3CYQs7RUOCWV1SSCLgJW4n.pdf', 16415125, 'pdf', 'application/pdf', 0, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(362, 'EXPOSITOR DE COLCHÃO_LEGENDADO.mp4', 'video', 'private/library/5/YDSDDLa2Re1I56tlvIuduyfl8IhirJ1eDuACsxtn.mp4', 63674883, 'mp4', 'video/mp4', 0, '2026-03-11 20:38:10', '2026-03-11 20:38:10'),
(363, 'EXPOSITORDETRAVESSEIRO_LEGENDADO.mp4', 'video', 'private/library/5/V2ptJraJNPNNP5f3rSwVU2qG7z21TZMz8tiHvPZ4.mp4', 79653640, 'mp4', 'video/mp4', 0, '2026-03-11 20:43:31', '2026-03-11 20:43:31'),
(364, 'PLÁSTICO_COLCHÃO_LEGENDADO.mp4', 'video', 'private/library/5/qxYJIHoKF6kGHP8IxNYhYoEzjtZkmCFypo8zf7sM.mp4', 71877165, 'mp4', 'video/mp4', 0, '2026-03-11 20:43:31', '2026-03-11 20:43:31'),
(365, 'AVATAR - COM SLOGAN.png', 'image', 'private/library/6/i1ndI90dEmuF4C4TpL30ikyCNjS78nC6yJO5XDcd.png', 32799, 'png', 'image/png', 0, '2026-03-11 20:49:33', '2026-03-11 20:49:33'),
(366, 'AVATAR - SEM SLOGAN .png', 'image', 'private/library/6/QgtozHxYPNJYHnaIHhjvg7E68DNAfPNKtICBTKr4.png', 12935, 'png', 'image/png', 0, '2026-03-11 20:49:33', '2026-03-11 20:49:33'),
(367, 'logo-60-anos.zip', 'pdf', 'private/library/7/821hIom55NclRl5ixfOZwm7CfU4H5syIoVYpDOUS.zip', 378572, 'zip', 'application/zip', 0, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(368, 'LOGOS.zip', 'pdf', 'private/library/8/JG2sSZWRq74GHg0aRaiiYXnh31cxrswBAVDTPid1.zip', 9552695, 'zip', 'application/zip', 0, '2026-03-11 20:53:21', '2026-03-11 20:53:21'),
(369, '01.jpg', 'image', 'private/products/2/images/oCBvzJgxn3cdghhCWsXbPnRLRFIfw2hx6QNy5MUm.jpg', 6839871, 'jpg', 'image/jpeg', 1, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(370, '02.jpg', 'image', 'private/products/2/images/8WHGGuMEgWIolglsgvpUAHfMFml5ITJSgSIGUuU6.jpg', 8854092, 'jpg', 'image/jpeg', 2, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(371, '03.jpg', 'image', 'private/products/2/images/xpKQzlKYi23Bq3MNVjeoel2c3ernJtL9MjZhvD6Y.jpg', 8500265, 'jpg', 'image/jpeg', 3, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(372, '04.jpg', 'image', 'private/products/2/images/a7yzl1ZnM8LZQeXGu9Xz4mnyno2iyi00nN1AZ6QR.jpg', 7990125, 'jpg', 'image/jpeg', 4, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(373, '05.jpg', 'image', 'private/products/2/images/xXX0XR7TTuMKb4IBWXPHvg24S8LjdDCNxSgI351f.jpg', 10538648, 'jpg', 'image/jpeg', 1, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(374, 'FI-01.jpg', 'image', 'private/products/2/images/KC7ViysmvkQmjIhivXy1G011YsmznVp9bEn28XNl.jpg', 1757495, 'jpg', 'image/jpeg', 2, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(375, 'FI-02.jpg', 'image', 'private/products/2/images/6B03n9P8ksMaZ91LMqAHeqsnyiVKAiOywI5J8beK.jpg', 2484314, 'jpg', 'image/jpeg', 3, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(376, 'FI-03.jpg', 'image', 'private/products/2/images/bddcEo8bkxxjEfSHc4XT80fiTz4WbGCX5WB2UJK2.jpg', 2059341, 'jpg', 'image/jpeg', 4, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(377, 'FI-04.jpg', 'image', 'private/products/2/images/JLy50MPxghMHIL7DTRgXmz7np98lWRPW886RNrml.jpg', 3585837, 'jpg', 'image/jpeg', 5, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(378, 'FI-05.jpg', 'image', 'private/products/2/images/IwQZpDPUuWHxqcxUc41L3S0MecD0rZGP4nLe8mVB.jpg', 4485106, 'jpg', 'image/jpeg', 6, '2026-03-11 23:11:22', '2026-03-11 23:11:22'),
(379, 'Cama Articulada-13.jpg', 'image', 'private/products/2/images/wzYsPqm5mQqaKU1srXjRX7UpjW0g8WnkMYYjYki0.jpg', 978312, 'jpg', 'image/jpeg', 1, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(380, 'Cama Articulada-18.jpg', 'image', 'private/products/2/images/ZYFI1eYkQOXiPytJVN2vS8nBbPoFHJFrY3vxWE7w.jpg', 1001440, 'jpg', 'image/jpeg', 2, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(381, 'Cama Articulada-20.jpg', 'image', 'private/products/2/images/vUgQTs3ufZmUdLCCfrWGL58vlApuzWU4swYu9JYR.jpg', 1009731, 'jpg', 'image/jpeg', 3, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(382, 'Cama Articulada-23.jpg', 'image', 'private/products/2/images/96ezVkfdmuxoqX4FXEOrLFw3xJc9h4qvFRFNT3X6.jpg', 989903, 'jpg', 'image/jpeg', 4, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(383, 'Cama Articulada-24.jpg', 'image', 'private/products/2/images/atsJLPpGBxD2i40tdCr2RHeD5y9iuWkFOb1T45Hc.jpg', 993599, 'jpg', 'image/jpeg', 5, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(384, 'Cama Articulada-25.jpg', 'image', 'private/products/2/images/Et0Snyru4VvBHtyyXGS968cnSxzKcVWgVHFSM4lR.jpg', 1482228, 'jpg', 'image/jpeg', 6, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(385, 'Cama Articulada-26.jpg', 'image', 'private/products/2/images/qcbvTHnmaINTKxXrBp5JTbSA45XFtnPtA4qgt9UN.jpg', 1318449, 'jpg', 'image/jpeg', 7, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(386, 'Cama Articulada-27.jpg', 'image', 'private/products/2/images/5vWredCBSIVREs7YT9F4Ae33v4UKmMus4RRqM7nY.jpg', 1418814, 'jpg', 'image/jpeg', 8, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(387, 'Cama Articulada-28.jpg', 'image', 'private/products/2/images/nUvhayT0Ae9QsaCXSLssUpxe7iMOL5SCUuiYc0vs.jpg', 1073560, 'jpg', 'image/jpeg', 9, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(388, 'Cama Articulada-29.jpg', 'image', 'private/products/2/images/GohfALaYog5kBeRVXmvGGD7PDRqfekwZWDvyGNSY.jpg', 1238997, 'jpg', 'image/jpeg', 10, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(389, 'Cama Articulada-30.jpg', 'image', 'private/products/2/images/HPBuyS1JkHlCZWyKOqaHh8GR5zXwHaJtPGgj2lCv.jpg', 1498883, 'jpg', 'image/jpeg', 11, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(390, 'DSC04136.jpg', 'image', 'private/products/3/images/tKTaslOijlt04wsTE60xuV7Uo5v9u2g01zc2KKbo.jpg', 6720103, 'jpg', 'image/jpeg', 1, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(391, 'DSC04138.jpg', 'image', 'private/products/3/images/iY6UEEu92R4JGVNGSar69R6mKdR3iyXRg107ApgT.jpg', 6259529, 'jpg', 'image/jpeg', 2, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(392, 'DSC04140.jpg', 'image', 'private/products/3/images/fJB74Gm6hNaMFnzJeJlmCLmZn238Uvz7xDXkQ3JL.jpg', 7183664, 'jpg', 'image/jpeg', 3, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(393, 'DSC04143.jpg', 'image', 'private/products/3/images/2t8Dl5IxnF2VLfMLpo1BI2O59ZkEBcU45VxGeNLZ.jpg', 6953176, 'jpg', 'image/jpeg', 4, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(394, 'DSC04144.jpg', 'image', 'private/products/3/images/Z0Q7rfubpVC1W26kkNmJFrgpskwCntFwk5PFnD1G.jpg', 6891031, 'jpg', 'image/jpeg', 5, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(395, 'DSC04146.jpg', 'image', 'private/products/3/images/HCZhvZSrn60sUvUuPzpDdhSPXusyeFQdyczc5svM.jpg', 6366735, 'jpg', 'image/jpeg', 6, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(396, 'DSC04148.jpg', 'image', 'private/products/3/images/aiQTzNhghTpifRtcMOrJA0FhXDnfHnZB5BzgIPx2.jpg', 7765579, 'jpg', 'image/jpeg', 7, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(397, 'DSC04150.jpg', 'image', 'private/products/3/images/X7llRxR7HxFXBZPVAIhN159fMiRZ5q17MC87t5rv.jpg', 5774566, 'jpg', 'image/jpeg', 8, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(398, 'DSC04152.jpg', 'image', 'private/products/3/images/mTJRPPrfoSpjBAjxLuN0yjXp6RobYw2L4VBoS1dn.jpg', 5249967, 'jpg', 'image/jpeg', 9, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(399, 'DSC04153.jpg', 'image', 'private/products/3/images/zK1Po798Kjzbernz0K4AXjGo9APswxRh8tBAq4lJ.jpg', 6313533, 'jpg', 'image/jpeg', 10, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(400, 'DSC04155.jpg', 'image', 'private/products/3/images/vuGQaaWjZa4SR4mhZnZRE3SRD3G1ANj3N1ZcvOWS.jpg', 6886577, 'jpg', 'image/jpeg', 11, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(401, 'DSC04156.jpg', 'image', 'private/products/3/images/G9iVHKL628tFUtrFg28uU7Cfd2b2vJkcJrIgm1Um.jpg', 5624718, 'jpg', 'image/jpeg', 12, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(402, 'DSC04159.jpg', 'image', 'private/products/3/images/MCEHPkJTNGSaOyJriP3oBYNHA1jYheKljknZVmxn.jpg', 7679047, 'jpg', 'image/jpeg', 1, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(403, 'DSC04160.jpg', 'image', 'private/products/3/images/nBWGZeIiNeIE3dnwO2J9acANq0Ci1a5VW3SaaeB4.jpg', 7265598, 'jpg', 'image/jpeg', 2, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(404, 'DSC04164.jpg', 'image', 'private/products/3/images/lRR4cXhDyb4gK9okm5hseIWRpBUNWVVMlJrdO1lW.jpg', 7440481, 'jpg', 'image/jpeg', 3, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(405, 'DSC04167.jpg', 'image', 'private/products/3/images/UiCuPvsIrqbo1gon79Z17pKwTZI88QrranTiAQmw.jpg', 6555929, 'jpg', 'image/jpeg', 4, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(406, 'DSC04169.jpg', 'image', 'private/products/3/images/OQY9sA3pOrcn9yubhVdNvkoSMSsuo0NsFM47GgzF.jpg', 6221275, 'jpg', 'image/jpeg', 5, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(407, 'DSC04171.jpg', 'image', 'private/products/3/images/TjKoeXMBTPKwzUgkMmPzBXphGGoiIpLWS7F2SXlF.jpg', 6810524, 'jpg', 'image/jpeg', 6, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(408, 'DSC04172.jpg', 'image', 'private/products/3/images/7AE6CbBzrRzfZ1oW9Kuvd2d0E9NGbvUgLKs9RMSs.jpg', 6645742, 'jpg', 'image/jpeg', 7, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(409, 'DSC04174.jpg', 'image', 'private/products/3/images/23KXnkxBMZ0aVOOkcXEzi3cv3fflR6K5TB3iggs7.jpg', 6739604, 'jpg', 'image/jpeg', 8, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(410, 'DSC04176.jpg', 'image', 'private/products/3/images/8gGr5yEWY6gMx4up0HOmtW6gmbQJhUlGrsARMhou.jpg', 7458853, 'jpg', 'image/jpeg', 9, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(411, 'DSC04177.jpg', 'image', 'private/products/3/images/lDyQ4ZdebIxLTAMBuZgCMu0xQ5Eimtqug1unovzO.jpg', 7294460, 'jpg', 'image/jpeg', 10, '2026-03-12 00:29:14', '2026-03-12 00:29:14'),
(412, 'V1-0002_Serie 703.mp4', 'video', 'private/products/3/videos/eCX8WspQaBAPT6bt3dc080xe59wXVPQPkiCSM7Vv.mp4', 34297317, 'mp4', 'video/mp4', 1, '2026-03-12 00:35:17', '2026-03-12 00:35:17'),
(413, 'DSC04073.jpg', 'image', 'private/products/4/images/qQQxJBmXjtoX0z1WSwsoBJ3PQTDmW7RmFuIPFLPT.jpg', 6270625, 'jpg', 'image/jpeg', 1, '2026-03-12 00:39:15', '2026-03-12 00:39:15'),
(414, 'DSC04074.jpg', 'image', 'private/products/4/images/aY3glE9wTHgr8WqnrAcnYkqIiJXzh8r5JV0z4EAn.jpg', 6692021, 'jpg', 'image/jpeg', 2, '2026-03-12 00:39:15', '2026-03-12 00:39:15'),
(415, 'DSC04075.jpg', 'image', 'private/products/4/images/Y4rOmW7JtSRTWDIvjKs2vC1IFcGkCH8gIgeYTi0Z.jpg', 6950413, 'jpg', 'image/jpeg', 3, '2026-03-12 00:39:15', '2026-03-12 00:39:15'),
(416, 'DSC04077.jpg', 'image', 'private/products/4/images/mU497KNWiJPeqtMOGZhl38DT83Y42Xc5VvbcdxdD.jpg', 5830275, 'jpg', 'image/jpeg', 4, '2026-03-12 00:39:15', '2026-03-12 00:39:15'),
(417, 'DSC04078.jpg', 'image', 'private/products/4/images/qHBjTmEsj2sceGLDnO1Fhus5mxVxLzkeT4bQuIfG.jpg', 6818006, 'jpg', 'image/jpeg', 5, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(418, 'DSC04079.jpg', 'image', 'private/products/4/images/AUL3pvuYAbDmKmeyT0lrvYQbIUs5a9XwWrbldcmf.jpg', 6273606, 'jpg', 'image/jpeg', 6, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(419, 'DSC04080.jpg', 'image', 'private/products/4/images/1GkdQus2wjSqfk1kdPGZZt9VS7kR0NWpW9OX7IAs.jpg', 7309240, 'jpg', 'image/jpeg', 7, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(420, 'DSC04081.jpg', 'image', 'private/products/4/images/5Pu1ZFIoL0CyfqNcUt6wLIBkteT38qfi5WH7v7X2.jpg', 7335147, 'jpg', 'image/jpeg', 8, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(421, 'DSC04082.jpg', 'image', 'private/products/4/images/PReBOhuvTtRa6ugwLDSbbDmDIhk0Yo0HxY2iiNuV.jpg', 7450645, 'jpg', 'image/jpeg', 9, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(422, 'DSC04083.jpg', 'image', 'private/products/4/images/wvP0cvurQSn8T2hVrelZpzfGTniyp5WNaPAzcmZj.jpg', 7481168, 'jpg', 'image/jpeg', 10, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(423, 'DSC04085.jpg', 'image', 'private/products/4/images/Y601Y4WkD1GwoZAudV6tID8Oe1RTcV0Oy2JtQhtu.jpg', 7366460, 'jpg', 'image/jpeg', 11, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(424, 'DSC04087.jpg', 'image', 'private/products/4/images/FWSJpSIqAGeaGJSJGM7LwQfZhzxlGGzKCswglm8C.jpg', 6000416, 'jpg', 'image/jpeg', 12, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(425, 'DSC04090.jpg', 'image', 'private/products/4/images/UtbGd84tWugFaJ2HodQyvK2GtuXOWFTESDiag9Da.jpg', 7070293, 'jpg', 'image/jpeg', 1, '2026-03-12 00:45:08', '2026-03-12 00:45:08'),
(426, 'DSC04092.jpg', 'image', 'private/products/4/images/nsBrzcnXZJOA6oNMB4Jpe3Nwprj55tRxXHvpcIeH.jpg', 8800542, 'jpg', 'image/jpeg', 2, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(427, 'DSC04095.jpg', 'image', 'private/products/4/images/m18gGjnr2eyVNGGyeEx7gARbjBKhX0lBayjH7set.jpg', 6261087, 'jpg', 'image/jpeg', 3, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(428, 'DSC04098.jpg', 'image', 'private/products/4/images/Wd7tBgAx36a38bnmQvtB4m6trp62vEVJybGAq5bR.jpg', 6098918, 'jpg', 'image/jpeg', 4, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(429, 'DSC04099.jpg', 'image', 'private/products/4/images/w5wIcLs8DA7AwfrjfAGP8ZlOt4BUwLnLcgrBzz7v.jpg', 7059765, 'jpg', 'image/jpeg', 5, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(430, 'DSC04101.jpg', 'image', 'private/products/4/images/yWjkMlfsjj7ROb9BAccLKu7hL3ZimXAJtwq6tyh4.jpg', 6807650, 'jpg', 'image/jpeg', 6, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(431, 'DSC04102.jpg', 'image', 'private/products/4/images/4jsFy2eBy6F9HMOFgxjTdGqAZnOHTm30zamJch2Y.jpg', 7329824, 'jpg', 'image/jpeg', 7, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(432, 'DSC04103.jpg', 'image', 'private/products/4/images/rX0gZlVknaHYdtTcEXs54aQ0Fi909O32n7SpLw54.jpg', 6049125, 'jpg', 'image/jpeg', 8, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(433, 'DSC04105.jpg', 'image', 'private/products/4/images/w8ObXaPTdJdzeYZqpv1wuOJi9SH9rZmFOVSuVv9a.jpg', 6624290, 'jpg', 'image/jpeg', 9, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(434, 'DSC04106.jpg', 'image', 'private/products/4/images/vWJjN0tFHJuzldyEg4Dgju4UiccEjhl9fzyo42sn.jpg', 7597486, 'jpg', 'image/jpeg', 10, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(435, 'DSC04107.jpg', 'image', 'private/products/4/images/CdyybfCVsntUrtacbNivglL7EBptw2kUC2lCg5qp.jpg', 8803798, 'jpg', 'image/jpeg', 11, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(436, 'DSC04109.jpg', 'image', 'private/products/4/images/XDqtDMupVzFfYHac4QPyQ7M3dhh0j6x4oj6XfzeS.jpg', 8213672, 'jpg', 'image/jpeg', 12, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(437, 'DSC04110.jpg', 'image', 'private/products/4/images/QvQhqwEIUlBi6jVoeoWtsClgC4xHnrPrRfaz5B5c.jpg', 7622275, 'jpg', 'image/jpeg', 13, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(438, 'DSC04111.jpg', 'image', 'private/products/4/images/ys6d8ZKOsASIanM7R6xiYdacGJdBVExumfhHm24h.jpg', 5545289, 'jpg', 'image/jpeg', 14, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(439, 'DSC04113.jpg', 'image', 'private/products/4/images/fFjgAOLk6ZaALDAIILopfCWp37yJAV17i6SIR9H9.jpg', 7259586, 'jpg', 'image/jpeg', 15, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(440, 'DSC04115.jpg', 'image', 'private/products/4/images/XiCJkcwmd9YL405V66Op0mhDn0moyRnXVTt6oU0j.jpg', 6266676, 'jpg', 'image/jpeg', 16, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(441, 'DSC04120.jpg', 'image', 'private/products/4/images/zufRuhCUxvyR5pxziX1VJ3uNCfWn2EPxTxzBmKFf.jpg', 6988381, 'jpg', 'image/jpeg', 17, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(442, 'DSC04173.jpg', 'image', 'private/products/4/images/4GHvpkSOdlKWyuVvAoFQ0Fa2hRoAJ5BQ3eXHSP3J.jpg', 6022941, 'jpg', 'image/jpeg', 18, '2026-03-12 00:45:09', '2026-03-12 00:45:09'),
(443, 'colchaoorthofoam.png', 'image', 'private/products/5/images/ua80tclXAEHGgfS3HWSGo3Zpcf8KQZAkbsgiCZ7r.png', 550291, 'png', 'image/png', 1, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(444, 'colchaoorthofoam1.png', 'image', 'private/products/5/images/rPDBDcdF02JVsrN2cgW8Rt4YmQcOpygDMl4USltL.png', 571597, 'png', 'image/png', 2, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(445, 'colchaoorthofoam2.png', 'image', 'private/products/5/images/sQGE3TJlilW813OaJWsowhZ90hANeR15wZd1IDRw.png', 914877, 'png', 'image/png', 3, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(446, 'colchaoorthofoam3.png', 'image', 'private/products/5/images/pf4vG0yC0BBMWz90wG9Nj9VfHjR9W5vgAmFcWEHu.png', 833701, 'png', 'image/png', 4, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(447, 'VEGA.jpg', 'image', 'private/products/6/images/ZuS4ym9inlA6maxu1kJNZdtbtoENpgTYjjVQot5T.jpg', 118579, 'jpg', 'image/jpeg', 1, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(448, 'vega-casal-16 e 18.jpg', 'image', 'private/products/6/images/bMNxEu1NWAWosA7BTGkAqyUTzApn7ftjF1DK7IjQ.jpg', 122907, 'jpg', 'image/jpeg', 2, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(449, 'vega-casal-25.jpg', 'image', 'private/products/6/images/YveIcBjTATuPDTVyKLcBitZIi22jyFGPKROfopfr.jpg', 138452, 'jpg', 'image/jpeg', 3, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(450, 'vega-queen-16 e 18.jpg', 'image', 'private/products/6/images/hkNjRxve5TKAAnlaitcnIj2diUCe4dwlVm85U1WC.jpg', 131002, 'jpg', 'image/jpeg', 4, '2026-03-30 05:38:54', '2026-03-30 05:38:54');
INSERT INTO `files` (`id`, `name`, `type`, `path`, `size`, `extension`, `mime_type`, `order`, `created_at`, `updated_at`) VALUES
(451, 'vega-queen-20 e 25.jpg', 'image', 'private/products/6/images/r2R4TrjMXEtA9OdG4HtouLYgQnbqIrdqTSBdMnSJ.jpg', 128934, 'jpg', 'image/jpeg', 5, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(452, 'vega-solteiro-16 e 18.jpg', 'image', 'private/products/6/images/fg8geiY1VpRuYxoy3dumeDQ3sPcuJ4IDe9s14s8e.jpg', 133575, 'jpg', 'image/jpeg', 6, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(453, 'vega-solteiro-25.jpg', 'image', 'private/products/6/images/bd4zKNlDHIqbsepmLWPndkQJLpWvA1YNhS7FbxHs.jpg', 134655, 'jpg', 'image/jpeg', 7, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(454, 'colchao-casal-hipnos.png', 'image', 'private/products/7/images/Wb7w0EpJifkrTg5mTLhDDse1XGM1rbboos4c44gm.png', 564655, 'png', 'image/png', 1, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(455, 'colchao-solteiro-hipnos.png', 'image', 'private/products/7/images/NeEEFg6JrLQwootxiwR32ybapooIPhRUHWN1RUn0.png', 590640, 'png', 'image/png', 2, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(456, 'conjunto-casal-hipnos.png', 'image', 'private/products/7/images/v2dRsNIhfY0cpAi4tAE0rKMFxnZcSGNvRUT0prgg.png', 668257, 'png', 'image/png', 3, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(457, 'detalhe-hipnos.png', 'image', 'private/products/7/images/dIzFOGZNcN3V4R8lBLWdWct31ZHsSTXpj8eabfOu.png', 1789982, 'png', 'image/png', 4, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(458, 'colchao-casal-morpheu.jpg', 'image', 'private/products/8/images/V5chO0OMiTAPsJSScqUVmrSitAiDRrXySYyEFLdD.jpg', 83201, 'jpg', 'image/jpeg', 1, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(459, 'colchao-solteiro-morpheu.jpg', 'image', 'private/products/8/images/xJW16cBgqbzupenQtpvZ9Y0mogb5TXqMohHx0Os6.jpg', 87721, 'jpg', 'image/jpeg', 2, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(460, 'conjunto-bipartido-morpheu.jpg', 'image', 'private/products/8/images/SJrNYKr5TKAWrHAEkzqigtoDHYu8mfeKNp4Zp5HG.jpg', 325090, 'jpg', 'image/jpeg', 3, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(461, 'conjunto-casal-morpheu.jpg', 'image', 'private/products/8/images/OnrCADvIPbklNwGA99GOsdZWJhL8pS67FtzupvL9.jpg', 99189, 'jpg', 'image/jpeg', 4, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(462, 'conjunto-solteiro-morpheu.jpg', 'image', 'private/products/8/images/7gpQw8p4OMO3MsNih9C8no8xaAIAcZlr5Z70H9jj.jpg', 109492, 'jpg', 'image/jpeg', 5, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(463, 'detalhe-morpheu.jpg', 'image', 'private/products/8/images/5iwi1W0RCDOTDVRXewjzOkUKE5Ikto7M8oNJiTtS.jpg', 144921, 'jpg', 'image/jpeg', 6, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(464, 'frente-bipartido-morpheu.jpg', 'image', 'private/products/8/images/OzrHlNzh0oOINKvuaam7xRn3tNiHmgdkZtsp299p.jpg', 55931, 'jpg', 'image/jpeg', 7, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(465, 'frente-bipartido-morpheu.png', 'image', 'private/products/8/images/RXdea2KmA67BzFvWnicCSSIdJfX1WjdHLoCPxlPe.png', 19516013, 'png', 'image/png', 8, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(466, 'frente-casal-morpheu.jpg', 'image', 'private/products/8/images/gPuqahqoSxtjAOQ4zZzDXsTgMlrtCXDSqXBam3HJ.jpg', 71284, 'jpg', 'image/jpeg', 9, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(467, 'frente-solteiro-morpheu.jpg', 'image', 'private/products/8/images/xtziiMZbsuAz1FaWX71gDLj9WlMeAe3y2z8Hk1M1.jpg', 81940, 'jpg', 'image/jpeg', 10, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(468, 'tampo-morpheu.jpg', 'image', 'private/products/8/images/X07HP7ypWZJhG6J4zxZ8grHyuiHGz3cgy70zOkoZ.jpg', 141699, 'jpg', 'image/jpeg', 11, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(469, 'polaris plus.jpg', 'image', 'private/products/9/images/sfewldRJrtPB6GlRbzxdyTYeDEwRvZyXrdWc4ZU3.jpg', 86254, 'jpg', 'image/jpeg', 1, '2026-03-30 05:49:23', '2026-03-30 05:49:23'),
(470, 'Polaris Plus  PT - CASAL.jpg', 'image', 'private/products/10/images/e2tUUQpoZN2Vbtw3GWu0pFsWiFzANsIVX2T21F9F.jpg', 42227, 'jpg', 'image/jpeg', 1, '2026-03-30 05:53:42', '2026-03-30 05:53:42'),
(471, 'Polaris Plus  PT CASAL.png', 'image', 'private/products/10/images/50k7vcUXO1kdtyFjAaYRTaudIcNhLaqnHR68250U.png', 233295, 'png', 'image/png', 2, '2026-03-30 05:53:42', '2026-03-30 05:53:42'),
(472, 'Polaris Plus  PT.jpg', 'image', 'private/products/10/images/6PkkWcWluCWpgIksKXiXktFwSmuiGyQeijhzevRo.jpg', 89512, 'jpg', 'image/jpeg', 3, '2026-03-30 05:53:42', '2026-03-30 05:53:42'),
(473, 'polaris-ultra-colchao-casal.jpg', 'image', 'private/products/11/images/Qbh7ZOqzqGCtgpNwtpRvL5yhNBqA1a3gSLGhZWcG.jpg', 195629, 'jpg', 'image/jpeg', 1, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(474, 'polaris-ultra-colchao-detalhe.jpg', 'image', 'private/products/11/images/tBQxdgCVnGyxK4ncNoCDCgeOmXA1t8XUT9IiGwZV.jpg', 651165, 'jpg', 'image/jpeg', 2, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(475, 'polaris-ultra-colchao-solteiro.jpg', 'image', 'private/products/11/images/I2NiPDtGwp0F2JAen2YUlFsljANh4d286hKU1Emw.jpg', 234420, 'jpg', 'image/jpeg', 3, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(476, 'polaris-ultra-colchao-tampo.jpg', 'image', 'private/products/11/images/5Zz92IyOh6sRCqJ9XLAvDmWRE7p89ouHSHoYmC4T.jpg', 201958, 'jpg', 'image/jpeg', 4, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(477, 'polaris-ultra-conjunto-bipartido.jpg', 'image', 'private/products/11/images/m1f5W6osnGig6b9FukIf7slgZWeV0zbrGY8iH4dy.jpg', 233928, 'jpg', 'image/jpeg', 5, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(478, 'polaris-ultra-conjunto-casal.jpg', 'image', 'private/products/11/images/vpQzAjTvnfC5St8fQxtuWGJIDRWBYHyAHqcSsTK6.jpg', 233052, 'jpg', 'image/jpeg', 6, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(479, 'polaris-ultra-conjunto-solteiro.jpg', 'image', 'private/products/11/images/8EFcMEo0FXvVEqIZ4MqYzaWmeTxvt1XXIhqKVg7t.jpg', 270067, 'jpg', 'image/jpeg', 7, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(480, 'DSC02470.png', 'image', 'private/products/12/images/mg3eSSAo0fVgqE2tWJLQsrZMc9MOiDeFQfWextd0.png', 248271, 'png', 'image/png', 1, '2026-03-30 05:58:52', '2026-03-30 05:58:52'),
(481, 'DSC09030.jpg', 'image', 'private/products/12/images/KXUcs3mW5YaLkJi30bEy43a6XCqQiRvj1eGqGAkL.jpg', 2807753, 'jpg', 'image/jpeg', 2, '2026-03-30 05:58:52', '2026-03-30 05:58:52'),
(482, 'POLARIS BABY DSC02470.png', 'image', 'private/products/12/images/89SWbvTzatKhLqDpBj1hNkmQW1jRxqOen8MpvXEs.png', 513932, 'png', 'image/png', 3, '2026-03-30 05:58:52', '2026-03-30 05:58:52'),
(483, 'ambientado.jpg', 'image', 'private/products/13/images/vYss1o0IyR2odxD8TpaA5HKHh1xPjTHeiL7Lw6xC.jpg', 535702, 'jpg', 'image/jpeg', 1, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(484, 'casal.jpg', 'image', 'private/products/13/images/odcXwTyDz5q1223ukH49WzLtdHt3KlT436oTfvoM.jpg', 166303, 'jpg', 'image/jpeg', 2, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(485, 'casal-bipartido.jpg', 'image', 'private/products/13/images/nEe92JlGU3Ca2K8z3lK3Gj26PnDzg4d8ovqxlRfu.jpg', 181646, 'jpg', 'image/jpeg', 3, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(486, 'colchão-casal (1000px x 1000px).jpg', 'image', 'private/products/13/images/jEpVvYM3trIj8txEplp7BYlff3ZM7IyTokrE5f4c.jpg', 66499, 'jpg', 'image/jpeg', 4, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(487, 'colchão-casal.jpg', 'image', 'private/products/13/images/qJ8J1hyOvUfNPAHKHnzG5KNP5Y0UCwyd9ZwKa3qM.jpg', 120987, 'jpg', 'image/jpeg', 5, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(488, 'colchão-king.jpg', 'image', 'private/products/13/images/2l9SOT1kMK7yVOSliMiyr7DjUQvv0mdBGIZmmuU3.jpg', 176800, 'jpg', 'image/jpeg', 6, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(489, 'colchão-queen.jpg', 'image', 'private/products/13/images/D9AUVtfAkZRUdImgCluwEXkGTRvBmODdbVCd2Bfi.jpg', 142203, 'jpg', 'image/jpeg', 7, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(490, 'colchão-solteiro.jpg', 'image', 'private/products/13/images/Tg8PBBr9vI3gEbQyPAPHjYbVS3m1Bn2ncMZ9TUFz.jpg', 136499, 'jpg', 'image/jpeg', 8, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(491, 'detalhe.jpg', 'image', 'private/products/13/images/87FHtizSUCTmUCDPpj1SsgSibUBAFemeO0IUpIgq.jpg', 416803, 'jpg', 'image/jpeg', 9, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(492, 'detalhe-tecido.jpg', 'image', 'private/products/13/images/AK8i1PTNnJmJBejH2l7GDDKCBnrld8oCVcAANNFA.jpg', 336262, 'jpg', 'image/jpeg', 10, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(493, 'frente.jpg', 'image', 'private/products/13/images/5TTC4sSXEFGN97xJRyCEP2ERYWSvm9BjC1Hqjc88.jpg', 212640, 'jpg', 'image/jpeg', 11, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(494, 'frente-2.jpg', 'image', 'private/products/13/images/Fq9eqUlpvxMwexbb7eU7qwIkAga0kazDfAKvx5Hf.jpg', 164989, 'jpg', 'image/jpeg', 12, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(495, 'solteiro - box.jpg', 'image', 'private/products/13/images/KtZTlYqueDjCUwxCPFyuDu8YOhBdFAJVjysvJKLu.jpg', 190815, 'jpg', 'image/jpeg', 13, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(496, 'colchão-121-bipartido.jpg', 'image', 'private/products/14/images/QKBplIqDU6MhJebiMFgB6lJGxbhqnBKDRwwN9fg1.jpg', 174157, 'jpg', 'image/jpeg', 1, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(497, 'colchão-121-bipartido-frente.jpg', 'image', 'private/products/14/images/oWNPlagxlLRoSWVTW931jI8vCMmux6zRKSHACZG8.jpg', 131994, 'jpg', 'image/jpeg', 2, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(498, 'colchão-121-casal.jpg', 'image', 'private/products/14/images/yIYwJ6l6D5jf99NF4RYL8hanQVWBpHMuiI5uyn9h.jpg', 160040, 'jpg', 'image/jpeg', 3, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(499, 'colchão-121-casal-box.jpg', 'image', 'private/products/14/images/tDedocXbVw1rp278a5r1eMNKegBfHwq8FFfR4GPD.jpg', 129537, 'jpg', 'image/jpeg', 4, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(500, 'colchão-121-detalhes.jpg', 'image', 'private/products/14/images/hW2PuNFPHtRev7B3Kej4CxA5APDv9iy45wY9kzCf.jpg', 175939, 'jpg', 'image/jpeg', 5, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(501, 'colchão-121-solteiro.jpg', 'image', 'private/products/14/images/bya0ehE8Wj6vVqo2atz8ASoS1eEbIfU7fkw2zQSR.jpg', 155416, 'jpg', 'image/jpeg', 6, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(502, 'tampo- colchão-121.jpg', 'image', 'private/products/14/images/9E5hFqAbuiM8bH9tDD3pAD4qEEM9OgA4iAILwJmq.jpg', 237152, 'jpg', 'image/jpeg', 7, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(503, 'DSC01056.jpg', 'image', 'private/products/15/images/RgEj4j2pWKnF7Qvh4PxMWLVub4ViNNwdf8P1wAnj.jpg', 219790, 'jpg', 'image/jpeg', 1, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(504, 'DSC01056.png', 'image', 'private/products/15/images/rOe18gXULimgN4VM9i9zxytGZ8rc8X7N5kPnUb37.png', 416895, 'png', 'image/png', 2, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(505, 'DSC01057.jpg', 'image', 'private/products/15/images/Lj9M5k6mcwLUjATe29xVSfgM7ZI85vdBgab29IoK.jpg', 172033, 'jpg', 'image/jpeg', 3, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(506, 'DSC01060.jpg', 'image', 'private/products/15/images/G5qxDRSrIPzBSMeXW9F4oczFnmO1HNG3wUEtq3Nz.jpg', 231452, 'jpg', 'image/jpeg', 4, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(507, 'DSC01062.jpg', 'image', 'private/products/15/images/Kd4ZNdnE37MRcPWr2DEiUwJxihbizdeUmiHaPAyI.jpg', 123535, 'jpg', 'image/jpeg', 5, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(508, 'DSC01078.jpg', 'image', 'private/products/15/images/WYaZU1pXlOYjjdrVUzTMI5G1XphlZkrD013sGiHp.jpg', 96373, 'jpg', 'image/jpeg', 6, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(509, 'DSC01079.jpg', 'image', 'private/products/15/images/Ki9wmrRnpWWTyX66VLFyEQ1fITIpantZUb8A8afJ.jpg', 104552, 'jpg', 'image/jpeg', 7, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(510, 'DSC01080.jpg', 'image', 'private/products/15/images/gA0KnXCW3Nx96syNDIOSZocqgx7pfonEEVSVfl6e.jpg', 175284, 'jpg', 'image/jpeg', 8, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(511, 'DSC01082.jpg', 'image', 'private/products/15/images/IBhrCEMu3Cz64QDUkEGHnHaY3SYGlfCi2lrEHpbJ.jpg', 173349, 'jpg', 'image/jpeg', 9, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(512, 'DSC01090.jpg', 'image', 'private/products/15/images/BVdJc2Mx74TQedpR7JFG0zo8fNw3V00ilGteyCSM.jpg', 339125, 'jpg', 'image/jpeg', 10, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(513, 'DSC01092.jpg', 'image', 'private/products/15/images/LI66takRV45ZzUjjwaL4dmiam8CTquiGXMsNBa42.jpg', 382245, 'jpg', 'image/jpeg', 11, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(514, 'DSC01099.jpg', 'image', 'private/products/15/images/CW0qEg78AzgXEwtQrHp9WHWtOGGiPuuRlHvw6MCe.jpg', 305468, 'jpg', 'image/jpeg', 12, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(515, 'DSC01101.jpg', 'image', 'private/products/15/images/2LRhyEB5IzjngJZjf2VBeM6OyRHyfF1t5F84LvAK.jpg', 303470, 'jpg', 'image/jpeg', 13, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(516, 'Gemini_Generated_Image_2dbup02dbup02dbu.png', 'image', 'private/products/15/images/lDa2K8BVuDGXxT8P1xXogD421tRmUYkNOgtOVsPn.png', 1015139, 'png', 'image/png', 14, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(517, '503 + CABECEIRA GARNET DARK.jpg', 'image', 'private/products/16/images/kejRol46JJ1gianhqTc2X5zbp9VmJHI8AydtTzAo.jpg', 421547, 'jpg', 'image/jpeg', 1, '2026-03-30 07:06:02', '2026-03-30 07:06:02'),
(518, 'DSC03541-detalhe-1.png', 'image', 'private/products/16/images/DOPf4bSKjrV1vi7yXxA6T6NYWgCCT2cO9E2wsFjD.png', 557833, 'png', 'image/png', 2, '2026-03-30 07:06:02', '2026-03-30 07:06:02'),
(519, 'DSC03541-detalhe-2.png', 'image', 'private/products/16/images/diRd635VpZAhmc8GUYgeNwUtfwAI7o1gpGFQLKP8.png', 1303627, 'png', 'image/png', 3, '2026-03-30 07:06:02', '2026-03-30 07:06:02'),
(520, 'freepik__ambiente-o-colcho-box-e-cabeceira-img1-em-um-abien__67861.jpeg', 'image', 'private/products/16/images/qihzwSaLjfBRxe8fTU7akzUTSyFwUarfpYNLvWss.jpg', 475261, 'jpeg', 'image/jpeg', 4, '2026-03-30 07:06:02', '2026-03-30 07:06:02'),
(521, 'I_AM_ORTOCHRIN_COLCHAO_503.jpg', 'image', 'private/products/16/images/5NbC0OOnLDIK8xTs4CQI2xKf50vULvgteKlZ42N0.jpg', 493641, 'jpg', 'image/jpeg', 5, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(522, 'Serie-503.png', 'image', 'private/products/16/images/za63MrfeVzjpC70RsBZizkX3kLibJrvOxi3rktdX.png', 283868, 'png', 'image/png', 6, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(523, 'Serie-503-frente.png', 'image', 'private/products/16/images/njD0XpeZYRQ46FxsuGP93b4m07dYmk5DUbl6mKy0.png', 319862, 'png', 'image/png', 7, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(524, 'Serie-503-tampo.png', 'image', 'private/products/16/images/pqc3AI1SAsEXLwvacAhjEV83o5TkFiMzEx3qOOc9.png', 1110812, 'png', 'image/png', 8, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(525, 'Serie 507 det faixa DSC05923.jpg', 'image', 'private/products/17/images/ptinWaoLklE8osvWxA0Hv2LPZv1sIiHn3CXYgfSU.jpg', 521599, 'jpg', 'image/jpeg', 1, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(526, 'Serie 507 det tampo DSC05918.jpg', 'image', 'private/products/17/images/vOppWqteZU6s0LqeyTIXAwXMzC2T9eIMDQzWtkqZ.jpg', 382348, 'jpg', 'image/jpeg', 2, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(527, 'Serie 507 det tampo DSC05920.jpg', 'image', 'private/products/17/images/a4fc89Cz8eR9QLlzUSjg1KBDlOvLjARniVEPbnoU.jpg', 389114, 'jpg', 'image/jpeg', 3, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(528, 'Serie 507 det tampo DSC05929.jpg', 'image', 'private/products/17/images/sG2W2L7qfUg6rBcFZxyYqWCMizLv2PVuiVvFzHk8.jpg', 376324, 'jpg', 'image/jpeg', 4, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(529, 'Serie 507 det tampoDSC05931.jpg', 'image', 'private/products/17/images/tOyHye8hb3pQq8mt5fYUcqlIdX01BPBRCctQLHNh.jpg', 387233, 'jpg', 'image/jpeg', 5, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(530, 'Serie 507 DSC05939.png', 'image', 'private/products/17/images/NR2BCUiXgN1oUy9zjbuQfOS9KmwdQBWjh6XmfknN.png', 316423, 'png', 'image/png', 6, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(531, 'Serie 507 DSC05961.png', 'image', 'private/products/17/images/0tXBrKD0VyqvEpegilWOLUhkWaLk2dzMYqloVTWF.png', 286007, 'png', 'image/png', 7, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(532, 'Serie-507---Ambientada.jpg', 'image', 'private/products/17/images/byBKrfxYKS43UAuaGgyV4yvNA59WA1h1LagZX7zW.jpg', 246209, 'jpg', 'image/jpeg', 8, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(533, 'colchao-casal-501.png', 'image', 'private/products/18/images/MqUPRlsEa5O3CJRpRBgRxc0K5GHGaPIUvGuORlSC.png', 1836154, 'png', 'image/png', 1, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(534, 'colchao-frente-casal-501.png', 'image', 'private/products/18/images/mLQWZRdpK5geTiv9Hp0noimh8XXOib3tZqjIHOXC.png', 1092899, 'png', 'image/png', 2, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(535, 'colchao-frente-solteiro-501.png', 'image', 'private/products/18/images/qXMFVC0sjYn6QMgXImieZcrQcCuScSliwtRq1cwS.png', 1601524, 'png', 'image/png', 3, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(536, 'colchao-solteiro-501.png', 'image', 'private/products/18/images/BcW6EZZtUIZzXoeQLibxoJ4t9SGfHV3JLojy8jaq.png', 1767078, 'png', 'image/png', 4, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(537, 'conjunto-bipartido-501.png', 'image', 'private/products/18/images/BHxSaeLW3hOvngNMpjwL27cRSKE1ACO2v7g9oVgD.png', 2284545, 'png', 'image/png', 5, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(538, 'conjunto-casal-501.png', 'image', 'private/products/18/images/1dKDiaixhbWBqhd8pkD0CLfkNV5ldoSdqTF1k8xl.png', 2301643, 'png', 'image/png', 1, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(539, 'conjunto-solteiro-501.png', 'image', 'private/products/18/images/53iT5x80Qo0jYyUYdm72nibWD6QiPTq8ioKUj0zx.png', 2242647, 'png', 'image/png', 2, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(540, 'detalhe-501.png', 'image', 'private/products/18/images/WIagQiMvPb5xASWKtxzloSPpDS3VgNEiea1L9gAI.png', 5115366, 'png', 'image/png', 3, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(541, 'frente-bipartido-501.png', 'image', 'private/products/18/images/6yYjZ8WLlrWbBBSQJ0QbzQa0Naj9ZnUX7jbk7AHT.png', 1891412, 'png', 'image/png', 4, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(542, 'frente-solteiro-501.png', 'image', 'private/products/18/images/jljxaUnues2YUcG1cgqdjQ3tXP2JasyZDnXFvbNY.png', 3156052, 'png', 'image/png', 5, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(543, 'tampo-501.png', 'image', 'private/products/18/images/AglrYXzIsPlKOEUvFLUCzrvle9JAi9eYZc0vft8L.png', 2617180, 'png', 'image/png', 6, '2026-03-30 18:43:22', '2026-03-30 18:43:22'),
(544, '505-ambientada-scenario.jpg', 'image', 'private/products/19/images/4zVTt43S6IhgKrfLBLHP1piEtpI51fUUmrUXIps1.jpg', 796921, 'jpg', 'image/jpeg', 1, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(545, 'Serie-505.png', 'image', 'private/products/19/images/xNVdNEN77UXroY7ShWwSQTROycNi7PJk9obvabym.png', 237464, 'png', 'image/png', 2, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(546, 'Serie-505-frente.png', 'image', 'private/products/19/images/39hzdi6rZaKJ20eGOyWuJCA8BSboZOfLCc0HLlZh.png', 307045, 'png', 'image/png', 3, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(547, 'Serie-505-tampo.png', 'image', 'private/products/19/images/SXRPa42xGjhYqerq9fIkjJt850HuVL8tomfMO7Ay.png', 1282047, 'png', 'image/png', 4, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(548, 'DSC06935.jpg', 'image', 'private/products/19/images/HSgPfpQtMUnTsHT1GAvqUdEtTdT5w2VtLnLOJ7vI.jpg', 541224, 'jpg', 'image/jpeg', 1, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(549, 'DSC06938.jpg', 'image', 'private/products/19/images/4jtw0zrPz0153yG78N2xcbBk9Klsdibw90luMzOR.jpg', 541902, 'jpg', 'image/jpeg', 2, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(550, 'DSC06939.jpg', 'image', 'private/products/19/images/LYg97yn3h1dbIoZYPZI1aHzUbIct6Ce7O4KEvfz6.jpg', 568336, 'jpg', 'image/jpeg', 3, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(551, 'DSC06941.jpg', 'image', 'private/products/19/images/d2jSY0WR20bWkkRzIcjkfUBhkSYT8PfGR5uiRdMn.jpg', 587872, 'jpg', 'image/jpeg', 4, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(552, 'DSC06945.jpg', 'image', 'private/products/19/images/JHfs4w2zLT6JKuFJXhXoNRwThWWsaHbjwWV8rP0z.jpg', 570480, 'jpg', 'image/jpeg', 5, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(553, 'DSC06947.jpg', 'image', 'private/products/19/images/O8LtUQjmpFGy3a9y265RS7il7SId7LBdEVNwwksI.jpg', 593043, 'jpg', 'image/jpeg', 6, '2026-03-30 18:49:38', '2026-03-30 18:49:38'),
(554, 'DSC06949.jpg', 'image', 'private/products/19/images/Pi7nCR3EjWZD1j09zxGAiSTVfotR79j3IpjktAjo.jpg', 522564, 'jpg', 'image/jpeg', 1, '2026-03-30 18:50:23', '2026-03-30 18:50:23'),
(555, 'DSC06951.jpg', 'image', 'private/products/19/images/HIWpjA2icvDJSzeKeiaV6E6f6b1fOS8a9tWokm1h.jpg', 501171, 'jpg', 'image/jpeg', 2, '2026-03-30 18:50:23', '2026-03-30 18:50:23'),
(556, 'DSC06953.jpg', 'image', 'private/products/19/images/hjXRBVXgydLYYEELRvryyCnBDbYUfWhuSeISKLE4.jpg', 486739, 'jpg', 'image/jpeg', 3, '2026-03-30 18:50:23', '2026-03-30 18:50:23'),
(557, 'DSC06954.jpg', 'image', 'private/products/19/images/icSONTGnyrGGgsDNSAm2GMdcKjXJiiFLcfm4INHn.jpg', 516088, 'jpg', 'image/jpeg', 4, '2026-03-30 18:50:23', '2026-03-30 18:50:23'),
(558, 'DSC06960.jpg', 'image', 'private/products/19/images/LiQwmjG89wiqS3snw0RRBSJD3Q35SDA9kym2zg4r.jpg', 518001, 'jpg', 'image/jpeg', 5, '2026-03-30 18:50:23', '2026-03-30 18:50:23'),
(559, 'COLCHAO_509.png', 'image', 'private/products/20/images/VGsaVjpporW3I1JicaQkAKKCu4FoGnXivxLhyrrn.png', 1166280, 'png', 'image/png', 1, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(560, 'COLCHAO_509-COM-CABECEIRA-VERBENA-DARK.jpg', 'image', 'private/products/20/images/5pdEbGzqzkbyqg6VxQfjN8QDH7VbIpQnp8IN0fFw.jpg', 555597, 'jpg', 'image/jpeg', 2, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(561, 'colchao-casal-509.png', 'image', 'private/products/20/images/h5Ra6GwU51bPrp9OcHUTEm6XIy6HJOl90kbqEvoc.png', 659368, 'png', 'image/png', 3, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(562, 'conjunto-casal-509.png', 'image', 'private/products/20/images/xpCQarsuPjS3RZEizKO5jnLieovqwfuso0zLiGkj.png', 839376, 'png', 'image/png', 4, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(563, 'frente-casal-509.png', 'image', 'private/products/20/images/rDsZ6oK4tT9duoDYVTOivBm8BPoK31x9bGEzpWDa.png', 565944, 'png', 'image/png', 5, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(564, 'tampo-509.png', 'image', 'private/products/20/images/pbApR0C77XbKGVeEmzS87oPRUY7VPPjZS9tC8WW5.png', 969718, 'png', 'image/png', 6, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(565, 'Serie-551-AMBIENTADA.jpg', 'image', 'private/products/21/images/eOGDSLAYvrjcK982IVsiiviIPewFUfGL7eBYNKd3.jpg', 495440, 'jpg', 'image/jpeg', 1, '2026-03-30 19:09:09', '2026-03-30 19:09:09'),
(566, 'Serie-551.png', 'image', 'private/products/21/images/LPHvtFapBOvJZM9UTsEGsdXkedDB1qYtocfMHRH5.png', 402390, 'png', 'image/png', 1, '2026-03-30 19:09:38', '2026-03-30 19:09:38'),
(567, 'Serie-551-frente.png', 'image', 'private/products/21/images/jPjswbhs0qMCbEmKUYms6uSFWHjEKXviav2DGuFN.png', 239530, 'png', 'image/png', 2, '2026-03-30 19:09:38', '2026-03-30 19:09:38'),
(568, 'Serie-551-tampo.jpg', 'image', 'private/products/21/images/CnNiHNQazvWROAwwNM377thtmIUlc4MihkuMYVwE.jpg', 345552, 'jpg', 'image/jpeg', 3, '2026-03-30 19:09:38', '2026-03-30 19:09:38'),
(569, '907---ambientada-cabeceira-garnet-facto-sepia.jpg', 'image', 'private/products/22/images/tosKhl4OJGFZjfsMpaE3N9ArUou0eu5VViNcPTAB.jpg', 247416, 'jpg', 'image/jpeg', 1, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(570, '907-ECOMMERCE.png', 'image', 'private/products/22/images/62vO6cSmR8hT58rUZ6DAHKLQjLKqJAI1T82U9ESA.png', 886694, 'png', 'image/png', 2, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(571, 'COL SERIE 907 2025.png', 'image', 'private/products/22/images/RPY9lTAKRkYAEXDYrP94IMdUpsvmQJobFjXSGfWw.png', 402538, 'png', 'image/png', 3, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(572, 'SERIE 907 2025.png', 'image', 'private/products/22/images/ZHhqWMc6obzNK1DPDImVIbnkbq2NMzaxhjC7Bcfh.png', 561221, 'png', 'image/png', 4, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(573, 'SERIE 907 DET ALOEVERA.jpg', 'image', 'private/products/22/images/qKKLm2CeHrvs3WXt04fDMkxdskCzv1nOBvIt1ncl.jpg', 285393, 'jpg', 'image/jpeg', 5, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(574, 'SERIE 907 DSC05971.png', 'image', 'private/products/22/images/abfTZLubEdVTTyQui4pT7DBtOc4egaPuLTHYj2BD.png', 221940, 'png', 'image/png', 6, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(575, 'SERIE 907DSC05977.png', 'image', 'private/products/22/images/EPTSIVdGXm4bLjeUhh2BZMRdkSA8qhedxxobop6F.png', 335521, 'png', 'image/png', 7, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(576, '907 Slim 1.png', 'image', 'private/products/23/images/VQDzEMb0IcNGdpp3AjuCkXN5WYij3D8KZ3uujY0s.png', 2022957, 'png', 'image/png', 1, '2026-03-30 19:19:26', '2026-03-30 19:19:26'),
(577, '907 Slim 2.png', 'image', 'private/products/23/images/0ma6br61yNPbqnetoFgvVnBR86Ey7jzz7B0Btqwn.png', 2306117, 'png', 'image/png', 2, '2026-03-30 19:19:26', '2026-03-30 19:19:26'),
(578, '907 Slim.png', 'image', 'private/products/23/images/hiqtW4U8gSQ46GkHTbgL15j9mX06AC6Yvvjb7ocf.png', 1393803, 'png', 'image/png', 3, '2026-03-30 19:19:26', '2026-03-30 19:19:26'),
(579, '1.jpg', 'image', 'private/products/24/images/VjRILjaWNZxSwk3fGBLuihxT9BbB005QuPmatycF.jpg', 456181, 'jpg', 'image/jpeg', 1, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(580, '2.png', 'image', 'private/products/24/images/A7sTm9GaiG7FIuibDi0PvTPGOmpAPnMPswVofSlM.png', 237845, 'png', 'image/png', 2, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(581, '3.png', 'image', 'private/products/24/images/MmBNZGJtTGtze3hYJeZtfBn2kx94EILlG0IDxFWI.png', 402360, 'png', 'image/png', 3, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(582, '4.jpg', 'image', 'private/products/24/images/9TYkiCP5hlYDjNXmOEPekyhqUOAXSZkzbeAxoZJf.jpg', 187680, 'jpg', 'image/jpeg', 4, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(583, '5.jpg', 'image', 'private/products/24/images/qAgTePsX8C5JXcUD7WgGoBW0sglIkOqVB0OcdAD4.jpg', 238627, 'jpg', 'image/jpeg', 5, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(584, 'PERSONA RELAX_MG_5134.png', 'image', 'private/products/25/images/hn0TxnougTml4NviDykYbZ6nPYMOgwgfRONH2Gaa.png', 266772, 'png', 'image/png', 1, '2026-03-30 19:32:21', '2026-03-30 19:32:21'),
(585, 'PERSONA RELAX_MG_5135.png', 'image', 'private/products/25/images/ZXAp5rU4tSkMssLj3Eices5diVM23mm2BfgJAwnj.png', 216187, 'png', 'image/png', 2, '2026-03-30 19:32:21', '2026-03-30 19:32:21'),
(586, 'PERSONA MAGNETIC_MG_5150-2.png', 'image', 'private/products/26/images/OF5koTvTXnqUKQsD5sKlhdWXHGaYtU90KCjf7IwZ.png', 214993, 'png', 'image/png', 1, '2026-03-30 19:36:30', '2026-03-30 19:36:30'),
(587, 'PERSONA MAGNETIC_MG_5152.png', 'image', 'private/products/26/images/qP2QcNfm8oQjQQUB3UMM5JFuIvMWEgWRjPUQgKoK.png', 367474, 'png', 'image/png', 2, '2026-03-30 19:36:30', '2026-03-30 19:36:30'),
(588, 'box-pet-orthocrin-perspectiva.jpg', 'image', 'private/products/27/images/qDXgaJ6Ia9P9n7LgnvossodzWp4Kh64UmAt1lRSb.jpg', 207909, 'jpg', 'image/jpeg', 1, '2026-03-30 19:47:43', '2026-03-30 19:47:43'),
(589, 'ambientado-quadrado-vertical-orthocrin-pet-comfort.jpg', 'image', 'private/products/28/images/ostuxf1N0ucrD4vGUUC46rKuy3uX1bujxg9WYrO1.jpg', 671991, 'jpg', 'image/jpeg', 1, '2026-03-30 19:53:02', '2026-03-30 19:53:02'),
(590, 'PET COMFORT CINZA.jpg', 'image', 'private/products/28/images/o3e6lWVGfDRHu96z6mH2x1KLy5Bl7dsmC11AzGA8.jpg', 958231, 'jpg', 'image/jpeg', 2, '2026-03-30 19:53:02', '2026-03-30 19:53:02'),
(591, 'COLCHONETE FITNESS-square-logo-nova.jpg', 'image', 'private/products/29/images/saqzn0F5WBlEQXyH20Fwiq8KxMnZfWn6wxI6Tfht.jpg', 639563, 'jpg', 'image/jpeg', 1, '2026-03-30 19:56:35', '2026-03-30 19:56:35'),
(592, 'Colchonete multiuso BX.png', 'image', 'private/products/30/images/8M2BT9vRpDNGaxSifCjNiyRZ1fSQID0Ez9HjxbUR.png', 947566, 'png', 'image/png', 1, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(593, 'Colchonete multiuso.png', 'image', 'private/products/30/images/xENCLk0FwBgWsU0c1MyHLIjf1mRhNqlUFAfM72Kl.png', 954097, 'png', 'image/png', 2, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(594, 'colchonete preto 1.png', 'image', 'private/products/30/images/zPMqnPGl3KRE1DhRQymyoVnpGSbJVIdsDTZ9EPbm.png', 1022410, 'png', 'image/png', 3, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(595, 'embalagem.png', 'image', 'private/products/30/images/o8K1rr9I7T8YFSw4M1r6L7F3OUIg8PvefGoOjheO.png', 452908, 'png', 'image/png', 4, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(596, 'freepik__create-a-realistic-camping-scene-inside-a-tent-fea__47521.png', 'image', 'private/products/30/images/M80S1UcDvoZJxjfILGGptCe7bmGJaBwSfc9bkVal.png', 2149594, 'png', 'image/png', 5, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(597, 'freepik__create-a-realistic-lifestyle-outdoor-scene-featuri__47519.png', 'image', 'private/products/30/images/l45hSVoWcFzqkJIfbaO8j7eRt9JK5p1tz50LaqHr.png', 1102080, 'png', 'image/png', 6, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(598, 'freepik__create-a-realistic-lifestyle-outdoor-scene-featuri__47520.png', 'image', 'private/products/30/images/epvcOC0dhYhpZm4qllQr464hrYl8J0z18VBOPjcI.png', 2465856, 'png', 'image/png', 7, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(599, '1.jpg', 'image', 'private/products/31/images/Wcj92SNlxALBq4s9vqnC80aYZYyv4wiQf59d057C.jpg', 289732, 'jpg', 'image/jpeg', 1, '2026-03-30 20:13:04', '2026-03-30 20:13:04'),
(600, '2.jpg', 'image', 'private/products/31/images/PplyBJ6OqylKFXx739onPx8LOlGeh7nJmMOGkPnx.jpg', 150172, 'jpg', 'image/jpeg', 2, '2026-03-30 20:13:04', '2026-03-30 20:13:04'),
(601, '3.jpg', 'image', 'private/products/31/images/CmPO1f4xzbjABOEbSSjeZPf1unuFeYDukCkcxGNI.jpg', 135587, 'jpg', 'image/jpeg', 3, '2026-03-30 20:13:04', '2026-03-30 20:13:04'),
(602, 'col OURO AZUL copy.png', 'image', 'private/products/32/images/OHhx0zxpcCKnCDHnokFuTQDAoph47fGASTxSjzUs.png', 638661, 'png', 'image/png', 1, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(603, 'OURO AZUL PERSPECT CASAL col.jpg', 'image', 'private/products/32/images/Y51m3jarCQqXPxtXfPqQYLVpuxanYi9h9HLcvVsO.jpg', 136447, 'jpg', 'image/jpeg', 2, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(604, 'OURO AZUL PERSPECT CASAL.jpg', 'image', 'private/products/32/images/h4jA1YN9fQMIQwoBO7Aq2f3cjsO5wRQkPyKQnEvJ.jpg', 160633, 'jpg', 'image/jpeg', 3, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(605, 'OURO AZUL PERSPECT.png', 'image', 'private/products/32/images/aEx8QYT8C4kZnC35U7VoTHxGj4u2PSbIH2wyEPlB.png', 639870, 'png', 'image/png', 4, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(606, 'ortopedico-ouro-eurotop-casal.jpg', 'image', 'private/products/33/images/M5Cz5Kmu7UCQljfEG6rrYOPsQBSRXqO4bGX8Qgem.jpg', 24065, 'jpg', 'image/jpeg', 1, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(607, 'ortopedico-ouro-eurotop-solteiro.jpg', 'image', 'private/products/33/images/hbzfmm8eaaavLK9Dus04zOD9M3TITmHy3dJLFNhY.jpg', 21905, 'jpg', 'image/jpeg', 2, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(608, 'ouro-eurotop-orthocrin-conjunto-solteiro.jpg', 'image', 'private/products/33/images/Ozxca9dMTHcWgyvVh3Y8hhcuRO0kBUEhHyizB6Al.jpg', 27846, 'jpg', 'image/jpeg', 3, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(609, 'ouro-eurotop-orthocrin-detalhe.jpg', 'image', 'private/products/33/images/j7Rlxc2qoRcKJPhtOgo7sRnzAEagAFNWif08zMZ2.jpg', 54746, 'jpg', 'image/jpeg', 4, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(610, '189.png', 'image', 'private/products/34/images/0snC54uxop381EauqDqRR9OsXwDBTDJ9l5yj5O2Y.png', 730040, 'png', 'image/png', 1, '2026-03-30 20:20:40', '2026-03-30 20:20:40'),
(611, '190.png', 'image', 'private/products/34/images/Mh5NpdSzjfekan87ioITWc1odHOWbBhootDHWFUb.png', 276901, 'png', 'image/png', 2, '2026-03-30 20:20:40', '2026-03-30 20:20:40'),
(612, '191.png', 'image', 'private/products/34/images/6lD8a3uktDXFZV0kcNlIq70K9f3LqC9NLQqz2Myk.png', 562773, 'png', 'image/png', 3, '2026-03-30 20:20:40', '2026-03-30 20:20:40'),
(613, 'Cabeceira-Garnet-Boucle-Cinza1.png', 'image', 'private/products/35/images/xae9bOSyOgpNoZ1194Cl6wR1LLlP8wLauxHE98lV.png', 1088789, 'png', 'image/png', 1, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(614, 'CabeceiraGarnetBoucleCinza2.png', 'image', 'private/products/35/images/RaAnpNNEkoUnkBMENNKMGOkJP9CWKKe8pVkTbB6M.png', 915899, 'png', 'image/png', 2, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(615, 'CabeceiraGarnetBoucleCinza3.png', 'image', 'private/products/35/images/nPWNkKaCA0qv0DSlAA5aG4UyYLVjCAVh1wNTBkot.png', 1948656, 'png', 'image/png', 3, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(616, 'CabeceiraGarnetBoucleCinza4.png', 'image', 'private/products/35/images/kSyVZuiBy1HpdcEv48yi8sUu35vKq9nEWeRtVq0K.png', 2118959, 'png', 'image/png', 4, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(617, 'GARNET FACTO SOLTEIRO.jpg', 'image', 'private/products/36/images/v1Do6HAZfySnxRO7Fnl2hum8HpgfUZsb4dDzSDGy.jpg', 47182, 'jpg', 'image/jpeg', 1, '2026-03-30 20:35:24', '2026-03-30 20:35:24'),
(618, 'GARNET FACTO_CINZA_CASAL.jpg', 'image', 'private/products/36/images/7x3cluIhxu0P8EFMMK7xq1mAsZ2xDROBCx9V9YJm.jpg', 48726, 'jpg', 'image/jpeg', 2, '2026-03-30 20:35:24', '2026-03-30 20:35:24'),
(619, 'GARNET FACTO_CINZA_QUEEN.jpg', 'image', 'private/products/36/images/lp7XfCgvSNbbzdVkGJWtBGW80PsROAqM9wkz9OKA.jpg', 52817, 'jpg', 'image/jpeg', 3, '2026-03-30 20:35:24', '2026-03-30 20:35:24'),
(620, 'GARNET FACTO_SEPIA_CASAL.jpg', 'image', 'private/products/37/images/DHLGd88kQVpF6c5gyUjP75WSZRLPsBpTjhDJg0d4.jpg', 87147, 'jpg', 'image/jpeg', 1, '2026-03-30 20:36:13', '2026-03-30 20:36:13'),
(621, 'GARNET FACTO_SEPIA_QUEEN.jpg', 'image', 'private/products/37/images/9lGYN9EDXdLpqWEaCuzXICArIWhWlAY7KCHbI8yH.jpg', 79023, 'jpg', 'image/jpeg', 2, '2026-03-30 20:36:13', '2026-03-30 20:36:13'),
(622, 'GARNET FACTO_SEPIA_SOLTEIRO.jpg', 'image', 'private/products/37/images/kSmyHn1aqxNy2HO1kY2uiaoEzfmnkrJM4LyKoTHY.jpg', 102512, 'jpg', 'image/jpeg', 3, '2026-03-30 20:36:13', '2026-03-30 20:36:13'),
(623, 'GARNET DARK solteiro.png', 'image', 'private/products/38/images/igCfgVyY4T0Mvp5Nn3Z0pVXdUgWKXkbiZ7QT1Cjh.png', 2291182, 'png', 'image/png', 1, '2026-03-30 20:37:47', '2026-03-30 20:37:47'),
(624, 'GARNET DARK.png', 'image', 'private/products/38/images/DnmqMa4E6oUX2l1IitAeKtVJ9eusDnFXTUJjff2U.png', 624471, 'png', 'image/png', 2, '2026-03-30 20:37:47', '2026-03-30 20:37:47'),
(625, 'topaziobouclecinza.png', 'image', 'private/products/39/images/HrXmfmzwrO9s5MUkSncCj9xb0s79RE8e4MfFHNGV.png', 1007226, 'png', 'image/png', 1, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(626, 'topaziobouclecinza2.png', 'image', 'private/products/39/images/AM8gFNZd9b0IivmD4bYkLiUfAwVqQzC4oXxYJFgc.png', 942652, 'png', 'image/png', 2, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(627, 'topaziobouclecinza3.png', 'image', 'private/products/39/images/ltyyKQ5ACAmcn0ZLj8oWcGUj0CUKaVDfmIIriQvD.png', 1936765, 'png', 'image/png', 3, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(628, 'topaziobouclecinza4.png', 'image', 'private/products/39/images/M1i6oqWOmJSSskdpOXK6gbzA2R7im3fQJo5GtD5t.png', 2307239, 'png', 'image/png', 4, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(629, 'cabeceiratopaziofacto1.png', 'image', 'private/products/40/images/mzN1J1TAQHer2w93E2JYz9heygOZBFYbEnPYNGIE.png', 501325, 'png', 'image/png', 1, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(630, 'cabeceiratopaziofacto2.png', 'image', 'private/products/40/images/ffnDdd2Usu95aPhmmnwGK21TrZKX5suhC9qWl40V.png', 792226, 'png', 'image/png', 2, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(631, 'cabeceiratopaziofacto3.png', 'image', 'private/products/40/images/q0RPc8CeLxawulJes0f2LbUiT7vImJXDQ7gnMoCu.png', 609830, 'png', 'image/png', 3, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(632, 'cabeceiratopaziofacto4.png', 'image', 'private/products/40/images/ow831QNWIkDOWSSkRTP67yoId1AndCFE9Z4S7OQQ.png', 674013, 'png', 'image/png', 4, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(633, 'cabeceiratopaziofacto5.png', 'image', 'private/products/40/images/B7CdaxOx6fS6myyzB8vMNxNqXtTyMQNZ9RBo1ktC.png', 967124, 'png', 'image/png', 5, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(634, 'cabeceiratopaziofacto6.png', 'image', 'private/products/40/images/1KUxERs1dKPEVVNCbgbzvZWGp9oLGD72POMD4T6u.png', 791496, 'png', 'image/png', 6, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(635, 'TOPAZIO AREIA_MG_2262 BX copy.png', 'image', 'private/products/41/images/Dk5NHAnEAtNxeUS0ZogKvQUZ6rS3StKp1EqamXdh.png', 799796, 'png', 'image/png', 1, '2026-03-30 23:17:12', '2026-03-30 23:17:12'),
(636, 'TOPAZIO AREIA_MG_2262 copy.png', 'image', 'private/products/41/images/kgwOV90OyZeofw4TN5ZNIWb8tJatpdZdqQmYMIjx.png', 815838, 'png', 'image/png', 2, '2026-03-30 23:17:12', '2026-03-30 23:17:12'),
(637, 'dark.png', 'image', 'private/products/42/images/DSsc3GJK7K36kdtSmFlhrpNHpzSObttpryVdLla9.png', 685137, 'png', 'image/png', 1, '2026-03-30 23:19:09', '2026-03-30 23:19:09'),
(638, 'Cabeceira topázio facto cinza.jpg', 'image', 'private/products/43/images/9KxxOZ2GFZlXyyuDYJw8rjs2YV9zgUPTVWq1fOFx.jpg', 63805, 'jpg', 'image/jpeg', 1, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(639, 'topazio-cinza.jpg', 'image', 'private/products/43/images/Nwa7P0jVN3YSmKcm7ou0XvZ0ZdRDI2mrqmkJsrrV.jpg', 59983, 'jpg', 'image/jpeg', 2, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(640, 'topazio-cinza-2.jpg', 'image', 'private/products/43/images/vu2jJKU0wMqFP5sjY11QSSANAEy7yY3M4b6aYR7t.jpg', 73741, 'jpg', 'image/jpeg', 3, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(641, 'topazio-cinza-3.jpg', 'image', 'private/products/43/images/T8WokagguuPykyMScvFWL7nsoJ4Byiq765qy6Cdi.jpg', 88617, 'jpg', 'image/jpeg', 4, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(642, 'topazio-cinza-4.jpg', 'image', 'private/products/43/images/64MYNw0AcAX6ddzDPdPUPOWwnIEIJrWT6M3Zfzsc.jpg', 98930, 'jpg', 'image/jpeg', 5, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(643, 'Cabeceira-ambar-boucle-cinza1.png', 'image', 'private/products/44/images/YAlIgQnn9bdlX8Fitncni3eVJKcSQ9GwIZHTirxX.png', 1068882, 'png', 'image/png', 1, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(644, 'Cabeceira-ambar-boucle-cinza2.png', 'image', 'private/products/44/images/bgekO4exP6BBI7mZkIEKgwiruW8bRYSxaGbqHcng.png', 896282, 'png', 'image/png', 2, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(645, 'Cabeceira-ambar-boucle-cinza3.png', 'image', 'private/products/44/images/q91xFerIVgXcQN4WZ6uRbMyNXsmjTzT956Hd9hJ6.png', 1955232, 'png', 'image/png', 3, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(646, 'Cabeceira-ambar-boucle-cinza4.png', 'image', 'private/products/44/images/HaTT3U4JckKjysyesIdigJCn71gGpsArYZNtgs3j.png', 2071826, 'png', 'image/png', 4, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(647, 'Cabeceira-Ambar-Facto1.png', 'image', 'private/products/45/images/9g6ri7AcP1IVan0uduo1vnrbM7FZjhmtwGSxfDKx.png', 512044, 'png', 'image/png', 1, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(648, 'Cabeceira-Ambar-Facto2.png', 'image', 'private/products/45/images/P6ohbVmy85TNZ1lmUvjAm6BYhlEM0Ne0FaHYdpp8.png', 443423, 'png', 'image/png', 2, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(649, 'Cabeceira-Ambar-Facto3.png', 'image', 'private/products/45/images/3zV2qvezAZWE16ZxNxluNC2tjnFtv1oNtSCMEZs5.png', 1008750, 'png', 'image/png', 3, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(650, 'Cabeceira-Ambar-Facto4.png', 'image', 'private/products/45/images/9myoWQKuk9UBCDtP3HNrYMvy6RQ6H5ysadgMwBBi.png', 1003906, 'png', 'image/png', 4, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(651, 'AMBAR DARK.png', 'image', 'private/products/46/images/ZBvJ3v9TgnFK0yWDKr8IFJQY0OkPSeUrUbLF6733.png', 797366, 'png', 'image/png', 1, '2026-03-31 03:05:20', '2026-03-31 03:05:20'),
(652, 'CABECEIRA AMBAR AREIA_BX_MG_2273.png', 'image', 'private/products/47/images/PjTRE6YVkcV1ZkDJnzTgGUfJTHGPlw9MFe64ZpNu.png', 740528, 'png', 'image/png', 1, '2026-03-31 03:06:29', '2026-03-31 03:06:29'),
(653, 'CABECEIRA AMBAR AREIA_MG_2273 copy.png', 'image', 'private/products/47/images/cC43pikEIWJi7r7tOyNcyp0iRt4EiqoEnOGOh4KH.png', 764813, 'png', 'image/png', 2, '2026-03-31 03:06:29', '2026-03-31 03:06:29'),
(654, 'Cabeceira Diamond Bouclê Cinza 1.png', 'image', 'private/products/48/images/VSg0NUeUkNHTUaPeiZ2qNZrb5cRczPgDw3g42l1A.png', 992910, 'png', 'image/png', 1, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(655, 'Cabeceira Diamond Bouclê Cinza 2.png', 'image', 'private/products/48/images/18yV4a2XfWPju0vpjbw9vNswO9s59kkr8SyleNGE.png', 1116570, 'png', 'image/png', 2, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(656, 'Cabeceira Diamond Bouclê Cinza 3.png', 'image', 'private/products/48/images/4iwgSxb1aUHj9TlY36w6c1pOTuRI9jFzXmk2EZVk.png', 2157361, 'png', 'image/png', 3, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(657, 'Cabeceira Diamond Bouclê Cinza 4.png', 'image', 'private/products/48/images/nPibIIcLTMNdzxSlJDtRI5rIrKdWrqcYROxXIaQy.png', 2171286, 'png', 'image/png', 4, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(658, 'Cabeceira Diamond Areia 1 DSC06064.png', 'image', 'private/products/49/images/2P9UdzJfynAqOBfTmriVXh1vxPfyKdY1WzDCVoKy.png', 258220, 'png', 'image/png', 1, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(659, 'Cabeceira Diamond Areia 2 DSC06067.png', 'image', 'private/products/49/images/bTX8D1yaaVQ6yYomn9pEvS2SD1jxpE4SCHdy7qo4.png', 200997, 'png', 'image/png', 2, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(660, 'Cabeceira Diamond Areia 3 DSC06070.png', 'image', 'private/products/49/images/KHOr5GwvgySUZYiKLFOtc9aEAKB6Si7CqJY2eslO.png', 483367, 'png', 'image/png', 3, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(661, 'Cabeceira Diamond Areia 4 DSC06075.png', 'image', 'private/products/49/images/3KbEhCCpPi1XcGoAmLzCBE1eqVWNTApYo8jh3Xrc.png', 443768, 'png', 'image/png', 4, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(662, 'Cabeceira Diamond Areia 5 DSC06077.jpg', 'image', 'private/products/49/images/f50ixHbSjD32ZJpBbrZF2NeIXAyT8EsIvlN2RkUL.jpg', 219972, 'jpg', 'image/jpeg', 5, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(663, 'dark.png', 'image', 'private/products/50/images/mquxJzYtBjuR1FfggGalHcQL3TmNFCEyy4ZDJzGH.png', 692445, 'png', 'image/png', 1, '2026-03-31 03:14:41', '2026-03-31 03:14:41'),
(664, 'Cabeceira-Verbena-Boucle-Cinza1.png', 'image', 'private/products/51/images/dWuzEfgnQENYClCgrlgGG9VGUdIe6ZfFlPBPXbHE.png', 1019028, 'png', 'image/png', 1, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(665, 'Cabeceira-Verbena-Boucle-Cinza2.png', 'image', 'private/products/51/images/GUnePJLMDgZxK7kcHF8qXVNf1bDLhZATOkx77G1l.png', 933567, 'png', 'image/png', 2, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(666, 'Cabeceira-Verbena-Boucle-Cinza3.png', 'image', 'private/products/51/images/X6h3gG7lp74a5NFm2debUkHBe72dgIe63oKrjuJo.png', 1962998, 'png', 'image/png', 3, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(667, 'Cabeceira-Verbena-Boucle-Cinza4.png', 'image', 'private/products/51/images/QAGDEkDcLBonzQ7aqP7JUqw0zQuXOPm6Ugqm0TNI.png', 2100195, 'png', 'image/png', 4, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(668, '_MG_6512_verbena areia BX.png', 'image', 'private/products/52/images/mEdlTvSnSfwLk0TjIYoRrT39Zlk1IqHhzTZWkXEy.png', 708539, 'png', 'image/png', 1, '2026-03-31 03:21:58', '2026-03-31 03:21:58'),
(669, '_MG_6512_verbena areia.png', 'image', 'private/products/52/images/Af3pzf9pDvYbxWrg7iJPLvGhdMlQ3GUQ0yFqEyJk.png', 752025, 'png', 'image/png', 2, '2026-03-31 03:21:58', '2026-03-31 03:21:58'),
(670, '_MG_6527_verbena areia.png', 'image', 'private/products/52/images/mZPi458d98yDFtGnoD3afUj8jGh6xcrj0AOFRO8a.png', 361539, 'png', 'image/png', 3, '2026-03-31 03:21:58', '2026-03-31 03:21:58'),
(671, '_MG_6515_verbena dark.png', 'image', 'private/products/53/images/xEXGrfGMLi9U6hXAwvHeZrHczDqwk9m3wzOM8aLe.png', 628006, 'png', 'image/png', 1, '2026-03-31 03:22:36', '2026-03-31 03:22:36'),
(672, '_MG_6522_verbena dark.png', 'image', 'private/products/53/images/ZKoQKuHhYBRMotH8SISH7ELmeN5jPrdkql5CFzQu.png', 401331, 'png', 'image/png', 2, '2026-03-31 03:22:36', '2026-03-31 03:22:36'),
(673, 'CabeceiraLotusBoucle1.png', 'image', 'private/products/54/images/jnUVqHALiciSoF5vSJyBEXPADatw761aD3eTkpMa.png', 1114354, 'png', 'image/png', 1, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(674, 'CabeceiraLotusBoucle2.png', 'image', 'private/products/54/images/UE8lrAY4RXPdLJBhlEt0nmBnV4EWBiieKASgd610.png', 952513, 'png', 'image/png', 2, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(675, 'CabeceiraLotusBoucle3.png', 'image', 'private/products/54/images/23sxiIYc45W8xGMqY0Dqjdjd94F1obrap5BHnrQd.png', 1939729, 'png', 'image/png', 3, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(676, 'CabeceiraLotusBoucle4.png', 'image', 'private/products/54/images/1CZBiMpp4fS0NR5eAu7g5k9NaWKBH6jHtHCKc4aC.png', 2161933, 'png', 'image/png', 4, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(677, 'LOTUS AREIA _ PERSPECTIVA.png', 'image', 'private/products/55/images/pocuKyStCHITNjhmrErLLF7XgavyeYztgaeJYA7e.png', 413510, 'png', 'image/png', 1, '2026-03-31 03:46:19', '2026-03-31 03:46:19'),
(678, 'LOTUS AREIA _FRENTE.jpg', 'image', 'private/products/55/images/9umdb7LA6aauqP9rbJdMQroyU51lAD724zMg87nL.jpg', 427255, 'jpg', 'image/jpeg', 2, '2026-03-31 03:46:19', '2026-03-31 03:46:19'),
(679, 'LOTUS AREIA _FRENTE.png', 'image', 'private/products/55/images/8fHKcxeADxUJMYA9nMRTrSdcLParoWpmRwCgVpLH.png', 874709, 'png', 'image/png', 3, '2026-03-31 03:46:19', '2026-03-31 03:46:19'),
(680, 'LOTUS DARK _ PERSPECTIVA.png', 'image', 'private/products/56/images/yi1JRNZTJOsWlsFglzxcr7tNsMMZpTjNo3OCdVlY.png', 326139, 'png', 'image/png', 1, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(681, 'LOTUS DARK _FRENTE.png', 'image', 'private/products/56/images/7BXWzp3VjGFYNwx20TaQY6qJW9gB2vpZzrj16BhA.png', 680544, 'png', 'image/png', 2, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(682, 'LOTUS DARK frente.png', 'image', 'private/products/56/images/7Luifa3a9ivOnUtVmXaeqzwZwcBJsjHykifOF5eC.png', 463881, 'png', 'image/png', 3, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(683, 'LOTUS DARK perspect.png', 'image', 'private/products/56/images/eO4r6Mp3v0AZpNHdMF2qzFmlgxHFKMAdHXQmpbDk.png', 492523, 'png', 'image/png', 4, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(684, 'PERSONA RELAX 4K_v2.mp4', 'video', 'private/products/25/videos/Q0JR642K3x2d787lJnqTyTqMHMei4Z64at6gmcC2.mp4', 17510869, 'mp4', 'video/mp4', 1, '2026-03-31 07:15:53', '2026-03-31 07:15:53'),
(685, 'PERSONA RELAX VERT_v2.mp4', 'video', 'private/products/25/videos/vz5xqEEa2ewiqsljw3cdn8pRlQ0CL6A1kkgp9JQb.mp4', 36642395, 'mp4', 'video/mp4', 1, '2026-03-31 07:18:30', '2026-03-31 07:18:30'),
(686, 'PERSONA MAGNETIC 4K_v2.mp4', 'video', 'private/products/26/videos/EfH0sOgsYOM6dEpLpnexBqZTlAUvYXHg94zSdAMp.mp4', 19925640, 'mp4', 'video/mp4', 1, '2026-03-31 07:24:56', '2026-03-31 07:24:56'),
(687, 'PERSONA MAGNETIC VERT_v2.mp4', 'video', 'private/products/26/videos/k66Awht15o9oyWBdnbIoqd6CUXBXqt85ODwpknV8.mp4', 37034645, 'mp4', 'video/mp4', 1, '2026-03-31 07:28:17', '2026-03-31 07:28:17'),
(688, 'Colchonete_v2.mp4', 'video', 'private/products/31/videos/uEJTV0oCGsnT5hlMwVscqj1giu2ejlE18uh0bn7a.mp4', 34099571, 'mp4', 'video/mp4', 1, '2026-03-31 07:35:04', '2026-03-31 07:35:04'),
(689, 'HIPNOS ORTHOCRIN STORIES.mp4', 'video', 'private/products/7/videos/OutLWtlDT0VFBLn0HtC2JGZfNQK3gxnaQcgAYJ2j.mp4', 10683044, 'mp4', 'video/mp4', 1, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(690, 'HIPNOS ORTHOCRIN YOUTUBE.mp4', 'video', 'private/products/7/videos/LGfPPVVVG8rfXz3jFyhUPSi3W2bFyBj71IAr1f5y.mp4', 10682922, 'mp4', 'video/mp4', 2, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(691, 'HIPNOS ORTHOCRIN ZAP.mp4', 'video', 'private/products/7/videos/QKUMRUwZZnxxvVtZ2Llvnbeo64PYqVO3IwB2syOB.mp4', 15983764, 'mp4', 'video/mp4', 3, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(692, 'COLCHAO MORPHEU REELS.mp4', 'video', 'private/products/8/videos/WX2SR4tXKMq65wCcQ1Kh7byAJPrcXCv7EBYa7ek3.mp4', 6756863, 'mp4', 'video/mp4', 1, '2026-03-31 07:48:17', '2026-03-31 07:48:17'),
(693, 'COLCHAO MORPHEU.mp4', 'video', 'private/products/8/videos/i78DdY5iyf6Ueu6qWWlPWtaWUK88tMxk2SHsZNzJ.mp4', 7739191, 'mp4', 'video/mp4', 2, '2026-03-31 07:48:17', '2026-03-31 07:48:17'),
(694, 'PilowTop (novo).mp4', 'video', 'private/products/10/videos/fEEaRdHvPELEvQQIEqMu90mY6wPXaNkLer1WVQyv.mp4', 12314470, 'mp4', 'video/mp4', 1, '2026-03-31 07:48:49', '2026-03-31 07:48:49'),
(695, 'Polaris Baby Reels_v2.mp4', 'video', 'private/products/12/videos/GwkZdlJwkwj2bBSQoF86Ha02AtsmVlEFHEuILv9q.mp4', 37696317, 'mp4', 'video/mp4', 1, '2026-03-31 08:01:33', '2026-03-31 08:01:33'),
(696, 'Polaris Baby_v2.mp4', 'video', 'private/products/12/videos/nwReewBnG1yMwfweIR1LXWSkXfx32m885nlhXkqB.mp4', 18372397, 'mp4', 'video/mp4', 2, '2026-03-31 08:01:33', '2026-03-31 08:01:33'),
(697, '301 Reels.mp4', 'video', 'private/products/15/videos/PvAg6Htsc58SXeP4afXETBiTCzovcSN7c7QyQOk9.mp4', 36450418, 'mp4', 'video/mp4', 1, '2026-03-31 08:06:26', '2026-03-31 08:06:26'),
(698, 'Serie 301.mp4', 'video', 'private/products/15/videos/OLf7OYvwHXtco9Yi1ER2TPyoaKWCk9jZcvt7EyEb.mp4', 14184103, 'mp4', 'video/mp4', 2, '2026-03-31 08:06:26', '2026-03-31 08:06:26'),
(699, '501 Reels.mp4', 'video', 'private/products/18/videos/ulHJqvw5yJYunC6Cjv66WIQNgL3HehOvTZxnZgbB.mp4', 43583168, 'mp4', 'video/mp4', 1, '2026-03-31 08:09:13', '2026-03-31 08:09:13'),
(700, 'Serie 501.mp4', 'video', 'private/products/18/videos/3EzbxAsrngklDl5DcIwo3f8tk5sLXcBsSt033yg4.mp4', 18977097, 'mp4', 'video/mp4', 2, '2026-03-31 08:09:13', '2026-03-31 08:09:13'),
(701, 'Folheto Cuide com Orthocrin.pdf', 'pdf', 'private/campaigns/2/folders/l3Zz6jskSA5vhXNPxjxeWJN774ck97DksOvaRDH2.pdf', 4077907, 'pdf', 'application/pdf', 0, '2026-04-01 00:50:09', '2026-04-01 00:50:09'),
(702, 'Frente.jpg', 'image', 'private/campaigns/2/folders/gtpGMM277rDdbEGYDwseQGVRje6fsShRKvXGqd3Z.jpg', 2657490, 'jpg', 'image/jpeg', 0, '2026-04-01 00:50:09', '2026-04-01 00:50:09'),
(703, 'Verso.jpg', 'image', 'private/campaigns/2/folders/Cz7tcNTbRZAG43LdEPCztw2LOD6uJuM2hYQtvUvv.jpg', 1525873, 'jpg', 'image/jpeg', 0, '2026-04-01 00:50:09', '2026-04-01 00:50:09'),
(704, 'Feed-301-1.png', 'image', 'private/campaigns/2/posts/VJ55kT6PH1qrnsbiVs2CLPoeStDmcV7Q5UraZ13N.png', 730675, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(705, 'Feed-301-2.png', 'image', 'private/campaigns/2/posts/eBzfzpbR4qwij80p1qLzSwpGX0BvFkfFIIkD6qAL.png', 504267, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(706, 'Feed-301-3.png', 'image', 'private/campaigns/2/posts/kGDyWCpIQqezOixGmQ6NOjDMbHhSaawP0hFbhNYY.png', 538733, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38');
INSERT INTO `files` (`id`, `name`, `type`, `path`, `size`, `extension`, `mime_type`, `order`, `created_at`, `updated_at`) VALUES
(707, 'Feed-301-4.png', 'image', 'private/campaigns/2/posts/ZEbKmbrKjjfSxJHeSDwtlVT2MkFCq1v3T1s9Vp5D.png', 593383, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(708, 'Feed-501-1.png', 'image', 'private/campaigns/2/posts/FtFOwPnJEXeU4fOk1glvXIizAs7i9RJcRh9UKtk1.png', 561887, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(709, 'Feed-501-2.png', 'image', 'private/campaigns/2/posts/yGC3gpEn82PPHf6UXLWXm8bf5dsNSVibqZQb53Xh.png', 585881, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(710, 'Feed-501-3.png', 'image', 'private/campaigns/2/posts/Cxi5T2uAEeDFVehGP4ByWqQnYg8KshZM2tvDorPV.png', 552883, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(711, 'Feed-501-4.png', 'image', 'private/campaigns/2/posts/HUFvLBRzZvE88KuVptpruhqDSq6VtDXUhLXQfPoO.png', 870141, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(712, 'Feed-503-2.png', 'image', 'private/campaigns/2/posts/0Hdog2QvBriIddKNrLspBDn7pNZQmuhTLoJJN7uz.png', 876396, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(713, 'Feed-503-3.png', 'image', 'private/campaigns/2/posts/1mM0n3tLzkeIJXsBXib1rL9u6B1j2RbdW4J02s0m.png', 398792, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(714, 'Feed-503-5.png', 'image', 'private/campaigns/2/posts/XCAPxxdd0E4nCuxEG31Ee1pxd9MrrjLQwaFIQIBn.png', 512435, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(715, 'Feed-505-1.png', 'image', 'private/campaigns/2/posts/KykijGDgBOP589waq160YKZjEGr6lZH5ZYnu1gHz.png', 615188, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(716, 'Feed-505-2.png', 'image', 'private/campaigns/2/posts/Q8Ql29tAWOLqxO1xdKy3pBsYkmMC4BlFAXZj0Qz2.png', 771170, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(717, 'Feed-505-3.png', 'image', 'private/campaigns/2/posts/SI3jTtfHYVNiqYD6nDOCjWJnuulrOpHbQPxYvFsS.png', 741819, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(718, 'Feed-507-1.png', 'image', 'private/campaigns/2/posts/YE5oGQKvPrM0u1QtWNbxNXapKfaN5tf9ME0KeYPJ.png', 550566, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(719, 'Feed-507-2.png', 'image', 'private/campaigns/2/posts/78rljfsOSOqmeycbfzYKfPs9OrQoWfc0RFKTAhLp.png', 490038, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(720, 'Feed-507-3.png', 'image', 'private/campaigns/2/posts/roaoZbV9qhvU6dgVe7e3ItKtd01FLGaI91wzlS1B.png', 688538, 'png', 'image/png', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(721, 'Folheto Cuide com Orthocrin.pdf', 'pdf', 'private/campaigns/2/folders/jYU5F4QLTcRHFYrkTCMhc2HJBttMjo2e0lCHVw2I.pdf', 4077907, 'pdf', 'application/pdf', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(722, 'Frente.jpg', 'image', 'private/campaigns/2/folders/eBp8SfkrIqWlU3lHr2Ck8mrBe5QmsKdF7AnE2ZTJ.jpg', 2657490, 'jpg', 'image/jpeg', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(723, 'Verso.jpg', 'image', 'private/campaigns/2/folders/Z5kefuSGyeYi4FB5f9iWtwRvxSoNb03l7hgdgU7z.jpg', 1525873, 'jpg', 'image/jpeg', 0, '2026-04-01 01:15:38', '2026-04-01 01:15:38'),
(724, '301-1.png', 'image', 'private/campaigns/2/posts/VC1pG2FDvs3xPjAHrrfc8AKTsQjQU6aJWH3QBuVw.png', 934466, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(725, '301-2.png', 'image', 'private/campaigns/2/posts/Jsm8LU42Sa1rb6g4UdJHgLVX3kHqS3rm4oWENuOv.png', 685306, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(726, '301-3.png', 'image', 'private/campaigns/2/posts/aAKCw0H4u8T09tQGIXMrFZqSB7YWe8Gmyhxekoh8.png', 602928, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(727, '301-4.png', 'image', 'private/campaigns/2/posts/D6VXyamovu8cwsVHgeLpuhxOxeD0eMKveyOWSUkA.png', 1131590, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(728, '501-1.png', 'image', 'private/campaigns/2/posts/mifT10ZLbIsvpxiFrQdmNMdjUCegdJNgB27bzltE.png', 651342, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(729, '501-2.png', 'image', 'private/campaigns/2/posts/N7Clsn0RUVFmrnB5tmpLsNydWr7DjcQVTucAggyb.png', 876662, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(730, '501-3.png', 'image', 'private/campaigns/2/posts/PW2G4vcvxWbdJbxQKUKwTaNOGjUXaybUqQbqtvkE.png', 905319, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(731, '501-4.png', 'image', 'private/campaigns/2/posts/4oPtWLjSZ8LhhB7FlRaziGc7vUCKYZ1lhqxMEupZ.png', 1067387, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(732, '503-1.jpg', 'image', 'private/campaigns/2/posts/Qa3lA8y2SrM5gOvoMl3LoEBPv77Wxo0znugjyWBe.jpg', 599555, 'jpg', 'image/jpeg', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(733, '503-2.jpg', 'image', 'private/campaigns/2/posts/QgJUUTfWQh3Wd3b123gs0XHI0ndAxLnCYlx5rmKl.jpg', 793615, 'jpg', 'image/jpeg', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(734, '503-3.jpg', 'image', 'private/campaigns/2/posts/ZDtKJhozCptMKIaUjesqWYwOrF13gXEcrmtSbpJc.jpg', 517582, 'jpg', 'image/jpeg', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(735, '503-4.png', 'image', 'private/campaigns/2/posts/RXNO2NhV5dy3NGt2eAapwRxlmoMxT8itd3s3Gkq6.png', 701180, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(736, '505-1.png', 'image', 'private/campaigns/2/posts/TmXM3EztC4wT61cozIjvYnFiKtp06SeK4GoJVFOK.png', 627911, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(737, '505-2.png', 'image', 'private/campaigns/2/posts/tIu8JL7Gl6IbIliTUECoB2csDcpgHF5R6kJJu1eH.png', 949126, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(738, '505-3.jpg', 'image', 'private/campaigns/2/posts/hUa23lBeKiUtrA7oR74Z4tukhKU79BZvGu4TgB39.jpg', 716842, 'jpg', 'image/jpeg', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(739, '507-1.png', 'image', 'private/campaigns/2/posts/DHIx5XBw95y6fz1gbH24XjuZUBkx9w2y6sA4eqlh.png', 795739, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(740, '507-2.png', 'image', 'private/campaigns/2/posts/wezBeRvelbU1RPhgxJBiXdwZdU4HwKfJ6ZZFaAeT.png', 794171, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(741, '507-3.png', 'image', 'private/campaigns/2/posts/jkDvXgPz4V6ePyZhIsKcf3f9XNEMG3Yf2hSBugFM.png', 1048816, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(742, '509-1.png', 'image', 'private/campaigns/2/posts/yLHAw0MsXxok6AEboTLnHJrJZdpobNoRX0OVrcRL.png', 1022306, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(743, '509-2.png', 'image', 'private/campaigns/2/posts/Dala9logGH4Wehsc6Pbx2JfYPhFqZjkc34IZFf4p.png', 962135, 'png', 'image/png', 0, '2026-04-01 01:21:05', '2026-04-01 01:21:05'),
(744, '301-1.png', 'image', 'private/campaigns/2/posts/OUWg7Mrsx4NRy0LzMqotjOdGhsUW8xI89oIN1U60.png', 934466, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(745, '301-2.png', 'image', 'private/campaigns/2/posts/y0CoIi8JlW3kBv0MW39kPC5yhQRoMKfmbdnBC8pM.png', 685306, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(746, '301-3.png', 'image', 'private/campaigns/2/posts/siqSOCSLX19IYGxI0Ow1HdhXAtHgmyHlsKSrzWSw.png', 602928, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(747, '301-4.png', 'image', 'private/campaigns/2/posts/mb2x9iFme7rh6avpd1w3NCqIfzFXZEmPCxEoHj0M.png', 1131590, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(748, '501-1.png', 'image', 'private/campaigns/2/posts/qjg2WE3wFITwxPsrC6q2Wiioe0CXOBElcLbywAXJ.png', 651342, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(749, '501-2.png', 'image', 'private/campaigns/2/posts/6ZwmD97I9gZKJorltzZ9QQEwMJe2Xd9JT3a2CWo6.png', 876662, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(750, '501-3.png', 'image', 'private/campaigns/2/posts/ZJGzF3lawhMnyju7rtSdaoTzJpkne2hFGbmtZfH4.png', 905319, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(751, '501-4.png', 'image', 'private/campaigns/2/posts/RCP5JIIj9twugq0BAMxs8FkZAayqEoxxNXanhWSd.png', 1067387, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(752, '503-1.jpg', 'image', 'private/campaigns/2/posts/gvJmCTEYOuKpYfwdwEba3ooP3ex4RvDeAAN9binW.jpg', 599555, 'jpg', 'image/jpeg', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(753, '503-2.jpg', 'image', 'private/campaigns/2/posts/S9tRKRGgRK0pfeGCJ5hD0sZZwP2h8fEHUwimbfw2.jpg', 793615, 'jpg', 'image/jpeg', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(754, '503-3.jpg', 'image', 'private/campaigns/2/posts/Enz1YowAID3S21uy9ntBir8BA3hj6Am6JmrhiugU.jpg', 517582, 'jpg', 'image/jpeg', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(755, '503-4.png', 'image', 'private/campaigns/2/posts/8Vr0hqOXwV3AaSi1a7boQOe881LPqGhWo0Yaemo1.png', 701180, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(756, '505-1.png', 'image', 'private/campaigns/2/posts/TXJBSCzd806SiPxKrt14sMldZixwkVDXlyOLZah7.png', 627911, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(757, '505-2.png', 'image', 'private/campaigns/2/posts/e8A3cNAEqiTAqX9FeUtsfCoAYkQk6MBXm414NQUz.png', 949126, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(758, '505-3.jpg', 'image', 'private/campaigns/2/posts/HAiqFeBxoQOLeEl7CU3SuYkmEdBF1oawZcujyfPs.jpg', 716842, 'jpg', 'image/jpeg', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(759, '507-1.png', 'image', 'private/campaigns/2/posts/LPBYMnqDqqNhGffVO6RFYauw4A4d7cK7cFstkKjW.png', 795739, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(760, '507-2.png', 'image', 'private/campaigns/2/posts/mnBXaOhkxJHvYSZqn69fsmAOOfuFalIKra25dwfC.png', 794171, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(761, '507-3.png', 'image', 'private/campaigns/2/posts/Nd9EkU5TItshscZE2HQXcHTeovniILE8XErjW3ZR.png', 1048816, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(762, '509-1.png', 'image', 'private/campaigns/2/posts/MAs8OIRpGsGBkcO3It3z0H6vtWv6jvJ3v4QqSncc.png', 1022306, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(763, '509-2.png', 'image', 'private/campaigns/2/posts/Dd19Cmf4ds0HRC75pSk10tCv1lF6bGfBEHPWrjEu.png', 962135, 'png', 'image/png', 0, '2026-04-01 01:25:53', '2026-04-01 01:25:53'),
(764, 'VÍDEO 01 – GENÉRICO  - Reels - Legendado.MP4', 'video', 'private/campaigns/2/videos/qQFpvrUICvoAokiiiDx57cjkkejt908hSXeB82ok.mp4', 29125461, 'mp4', 'video/mp4', 0, '2026-04-01 01:37:47', '2026-04-01 01:37:47'),
(765, 'VÍDEO 02 – CHAMADA CAMPANHA - Reels - Legendado.MP4', 'video', 'private/campaigns/2/videos/bRZbGZeTPXBom5TWbQNAazXqXtjZWabmPoV2DT6A.mp4', 34065304, 'mp4', 'video/mp4', 0, '2026-04-01 01:37:47', '2026-04-01 01:37:47'),
(766, 'VÍDEO 03 – SÉRIE 503 - Reels - Legendado.MP4', 'video', 'private/campaigns/2/videos/aLmofZo6yUvIgiQGAUoWmkqedPW60wvwJICsEVKH.mp4', 41692230, 'mp4', 'video/mp4', 0, '2026-04-01 02:14:38', '2026-04-01 02:14:38'),
(767, 'VÍDEO 04 – SÉRIE 301 Plus - Reels - Legendado.MP4', 'video', 'private/campaigns/2/videos/zWPHuEvrAwFejbl3ny8t8QEiHVGREirlda6wW2Gt.mp4', 34064432, 'mp4', 'video/mp4', 0, '2026-04-01 02:20:03', '2026-04-01 02:20:03'),
(768, 'VÍDEO 05 – MULTIRELAX VISCO - Reels - Legendado.MP4', 'video', 'private/campaigns/2/videos/DoRauUFYsCI3n9h50thrwPlncr86lGWLSlkTiPqx.mp4', 13852605, 'mp4', 'video/mp4', 0, '2026-04-01 02:20:03', '2026-04-01 02:20:03'),
(769, 'Campanha de abril - TV.avi', 'video', 'private/campaigns/2/videos/IXvW0o7ZIaFLUqJyxOOK4iiT8VF8WCPWOYZZSvPI.avi', 79492298, 'avi', 'video/x-msvideo', 0, '2026-04-01 02:29:31', '2026-04-01 02:29:31'),
(770, 'Campanha de abril - TV.mov', 'video', 'private/campaigns/2/videos/3ReL0owW6YmgQUudmYr7VDLvNIRgIyLAFtTmVz57.mov', 79415155, 'mov', 'video/quicktime', 0, '2026-04-01 02:44:57', '2026-04-01 02:44:57'),
(771, 'Campanha de abril - TV.mp4', 'video', 'private/campaigns/2/videos/Y9brMbOYt64DVTCT75E4fLe28UaDgJhSyCFElQUB.mp4', 39792653, 'mp4', 'video/mp4', 0, '2026-04-01 02:49:08', '2026-04-01 02:49:08'),
(772, 'Orthocrin - Abril Mês do Cuidado A 18 03.mp3', 'audio', 'private/campaigns/2/miscellaneous/hBt7o5DgVjf476oXCrAC0neisOc9hr1kexeR8HHK.mp3', 1219500, 'mp3', 'audio/mpeg', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45'),
(773, 'Orthocrin - Abril Mês do Cuidado B 18 03.mp3', 'audio', 'private/campaigns/2/miscellaneous/ZM7CxLDzmFYP0rnn8FECqG6aqg7yhIItMWz8A0wC.mp3', 1218456, 'mp3', 'audio/mpeg', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45'),
(774, 'Adesivo Campanha Orthocrin - PADRÃO.pdf', 'pdf', 'private/campaigns/2/miscellaneous/d3bBFfvt8lzDxMmweNP3nkWQTqypM1mKgESljToW.pdf', 821906, 'pdf', 'application/pdf', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45'),
(775, 'Orthocrin -_lojatotal.png', 'image', 'private/campaigns/2/miscellaneous/1dXBHGY0oGUgCOJIHHaTxhOVnqYV1eMe6KzmjGI1.png', 7726866, 'png', 'image/png', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45'),
(776, 'Cuide com Orthocrin-0,9 x 1,20.pdf', 'pdf', 'private/campaigns/2/miscellaneous/4MK6DpieJN7t8Co7N77GovQtwDZMX9LmqkaUfVZu.pdf', 1727161, 'pdf', 'application/pdf', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45'),
(777, 'Cuide com Orthocrin-3,00 x 0,65 m.pdf', 'pdf', 'private/campaigns/2/miscellaneous/J2E4o33HJpoPbbwQykyJ97rKTVHGRUrAnWBOjv7E.pdf', 1720696, 'pdf', 'application/pdf', 0, '2026-04-01 02:54:45', '2026-04-01 02:54:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `library`
--

CREATE TABLE `library` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `library_category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `library`
--

INSERT INTO `library` (`id`, `name`, `library_category_id`, `description`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 'MEMORIAL DESCRITIVO 2025', 1, NULL, 'active', '2026-03-11 20:09:25', '2026-03-11 20:09:25', NULL),
(2, 'CHECKLIST ATIVIDADES DA LOJA', 1, NULL, 'active', '2026-03-11 20:10:06', '2026-03-11 20:10:20', NULL),
(3, 'FORÇAS DA MARCA', 2, NULL, 'active', '2026-03-11 20:28:43', '2026-03-11 20:28:43', NULL),
(4, 'Métodos de Vendas Orthocrin', 1, NULL, 'active', '2026-03-11 20:30:13', '2026-03-11 20:30:13', NULL),
(5, 'ORGANIZANDO A FRANQUIA PARA VENDA', 1, NULL, 'active', '2026-03-11 20:38:10', '2026-03-11 20:38:10', NULL),
(6, 'Logo para perfil do Instagram', 3, NULL, 'active', '2026-03-11 20:49:33', '2026-03-11 20:50:55', NULL),
(7, 'Logo Aniversário 60 anos', 3, NULL, 'active', '2026-03-11 20:50:31', '2026-03-11 20:50:31', NULL),
(8, 'LOGO ORTHOCRIN', 3, NULL, 'active', '2026-03-11 20:53:21', '2026-03-11 20:53:21', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `library_categories`
--

CREATE TABLE `library_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `library_categories`
--

INSERT INTO `library_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Manuais e Planilhas', 'Manuais técnicos e planilhas', 'active', '2025-08-27 22:31:44', '2025-08-27 22:31:44'),
(2, 'Papelaria', 'Material de papelaria', 'active', '2025-08-27 22:31:44', '2025-08-27 22:31:44'),
(3, 'Logomarca', 'Logos e identidade visual', 'active', '2025-08-27 22:31:44', '2025-08-27 22:31:44'),
(4, 'Peças de PDV', 'Material para ponto de venda', 'active', '2025-08-27 22:31:44', '2025-08-27 22:31:44'),
(5, 'Ações de Loja', 'Materiais para ações promocionais', 'active', '2025-08-27 22:31:44', '2025-08-27 22:31:44');

-- --------------------------------------------------------

--
-- Estrutura para tabela `library_files`
--

CREATE TABLE `library_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `library_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `library_files`
--

INSERT INTO `library_files` (`id`, `library_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 57, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(2, 1, 58, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(3, 1, 59, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(4, 2, 60, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'pdf', 0, 0),
(5, 3, 61, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(6, 3, 62, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(7, 3, 63, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'video', 0, 0),
(8, 4, 64, '2025-08-29 22:01:01', '2025-08-29 22:01:01', 'pdf', 0, 0),
(9, 6, 180, NULL, NULL, 'image', 0, 1),
(10, 6, 181, NULL, NULL, 'image', 0, 1),
(11, 7, 200, NULL, NULL, 'pdf', 0, 1),
(12, 7, 201, NULL, NULL, 'pdf', 0, 1),
(13, 7, 202, NULL, NULL, 'pdf', 0, 1),
(14, 8, 203, NULL, NULL, 'pdf', 0, 1),
(15, 8, 204, NULL, NULL, 'pdf', 0, 1),
(16, 8, 205, NULL, NULL, 'pdf', 0, 1),
(17, 9, 206, NULL, NULL, 'pdf', 0, 1),
(18, 9, 207, NULL, NULL, 'pdf', 0, 1),
(19, 9, 208, NULL, NULL, 'pdf', 0, 1),
(20, 9, 209, NULL, NULL, 'pdf', 0, 1),
(21, 9, 210, NULL, NULL, 'pdf', 0, 1),
(22, 10, 211, NULL, NULL, 'pdf', 0, 1),
(23, 10, 212, NULL, NULL, 'pdf', 0, 1),
(24, 11, 213, NULL, NULL, 'pdf', 0, 1),
(25, 11, 214, NULL, NULL, 'pdf', 0, 1),
(26, 11, 215, NULL, NULL, 'pdf', 0, 1),
(27, 11, 216, NULL, NULL, 'pdf', 0, 1),
(28, 11, 217, NULL, NULL, 'image', 0, 1),
(29, 11, 218, NULL, NULL, 'pdf', 0, 1),
(30, 12, 219, NULL, NULL, 'pdf', 0, 1),
(31, 12, 220, NULL, NULL, 'pdf', 0, 1),
(32, 12, 221, NULL, NULL, 'pdf', 0, 1),
(33, 12, 222, NULL, NULL, 'pdf', 0, 1),
(34, 12, 223, NULL, NULL, 'pdf', 0, 1),
(35, 13, 224, NULL, NULL, 'pdf', 0, 1),
(36, 13, 225, NULL, NULL, 'pdf', 0, 1),
(37, 13, 226, NULL, NULL, 'image', 0, 1),
(38, 13, 227, NULL, NULL, 'image', 0, 1),
(39, 13, 228, NULL, NULL, 'pdf', 0, 1),
(40, 13, 229, NULL, NULL, 'pdf', 0, 1),
(41, 13, 230, NULL, NULL, 'image', 0, 1),
(42, 13, 231, NULL, NULL, 'image', 0, 1),
(43, 14, 232, NULL, NULL, 'pdf', 0, 1),
(44, 14, 233, NULL, NULL, 'pdf', 0, 1),
(45, 14, 234, NULL, NULL, 'pdf', 0, 1),
(46, 14, 235, NULL, NULL, 'pdf', 0, 1),
(47, 14, 236, NULL, NULL, 'pdf', 0, 1),
(48, 15, 237, NULL, NULL, 'pdf', 0, 1),
(49, 15, 238, NULL, NULL, 'pdf', 0, 1),
(50, 15, 239, NULL, NULL, 'pdf', 0, 1),
(51, 15, 240, NULL, NULL, 'image', 0, 1),
(52, 15, 241, NULL, NULL, 'image', 0, 1),
(53, 15, 242, NULL, NULL, 'pdf', 0, 1),
(54, 16, 243, NULL, NULL, 'pdf', 0, 1),
(55, 16, 244, NULL, NULL, 'pdf', 0, 1),
(56, 16, 245, NULL, NULL, 'pdf', 0, 1),
(57, 16, 246, NULL, NULL, 'image', 0, 1),
(58, 17, 247, NULL, NULL, 'pdf', 0, 1),
(59, 17, 248, NULL, NULL, 'pdf', 0, 1),
(60, 17, 249, NULL, NULL, 'pdf', 0, 1),
(61, 17, 250, NULL, NULL, 'pdf', 0, 1),
(62, 17, 251, NULL, NULL, 'image', 0, 1),
(63, 17, 252, NULL, NULL, 'image', 0, 1),
(64, 1, 358, NULL, NULL, 'pdf', 0, 1),
(65, 2, 359, NULL, NULL, 'pdf', 0, 1),
(66, 3, 360, NULL, NULL, 'pdf', 0, 1),
(67, 4, 361, NULL, NULL, 'pdf', 0, 1),
(68, 5, 362, NULL, NULL, 'video', 0, 1),
(69, 5, 363, NULL, NULL, 'video', 0, 1),
(70, 5, 364, NULL, NULL, 'video', 0, 1),
(71, 6, 365, NULL, NULL, 'image', 0, 1),
(72, 6, 366, NULL, NULL, 'image', 0, 1),
(73, 7, 367, NULL, NULL, 'pdf', 0, 1),
(74, 8, 368, NULL, NULL, 'pdf', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `library_permissions`
--

CREATE TABLE `library_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `library_id` bigint(20) UNSIGNED NOT NULL,
  `user_type_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `can_download` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `library_permissions`
--

INSERT INTO `library_permissions` (`id`, `library_id`, `user_type_id`, `can_view`, `can_download`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(2, 1, 2, 1, 1, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(3, 1, 3, 0, 0, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(4, 1, 4, 0, 0, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(5, 2, 1, 1, 1, '2026-03-11 20:10:06', '2026-03-11 20:10:06'),
(10, 2, 2, 1, 1, '2026-03-11 20:10:20', '2026-03-11 20:10:20'),
(11, 2, 3, 0, 0, '2026-03-11 20:10:20', '2026-03-11 20:10:20'),
(12, 2, 4, 0, 0, '2026-03-11 20:10:20', '2026-03-11 20:10:20'),
(13, 3, 1, 1, 1, '2026-03-11 20:28:43', '2026-03-11 20:28:43'),
(14, 3, 2, 1, 1, '2026-03-11 20:28:43', '2026-03-11 20:28:43'),
(15, 3, 3, 0, 0, '2026-03-11 20:28:43', '2026-03-11 20:28:43'),
(16, 3, 4, 0, 0, '2026-03-11 20:28:43', '2026-03-11 20:28:43'),
(17, 4, 1, 1, 1, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(18, 4, 2, 1, 1, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(19, 4, 3, 0, 0, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(20, 4, 4, 0, 0, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(21, 5, 1, 1, 1, '2026-03-11 20:38:10', '2026-03-11 20:38:10'),
(26, 5, 2, 1, 1, '2026-03-11 20:43:31', '2026-03-11 20:43:31'),
(27, 5, 3, 0, 0, '2026-03-11 20:43:31', '2026-03-11 20:43:31'),
(28, 5, 4, 0, 0, '2026-03-11 20:43:31', '2026-03-11 20:43:31'),
(29, 6, 1, 1, 1, '2026-03-11 20:49:33', '2026-03-11 20:49:33'),
(33, 7, 1, 1, 1, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(34, 7, 2, 1, 1, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(35, 7, 3, 0, 0, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(36, 7, 4, 0, 0, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(38, 6, 2, 1, 1, '2026-03-11 20:50:55', '2026-03-11 20:50:55'),
(39, 6, 3, 0, 0, '2026-03-11 20:50:55', '2026-03-11 20:50:55'),
(40, 6, 4, 0, 0, '2026-03-11 20:50:55', '2026-03-11 20:50:55'),
(41, 8, 1, 1, 1, '2026-03-11 20:53:21', '2026-03-11 20:53:21'),
(42, 8, 2, 1, 1, '2026-03-11 20:53:21', '2026-03-11 20:53:21'),
(43, 8, 3, 0, 0, '2026-03-11 20:53:21', '2026-03-11 20:53:21'),
(44, 8, 4, 0, 0, '2026-03-11 20:53:21', '2026-03-11 20:53:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_06_13_130000_create_users_table', 1),
(2, '2025_06_13_142555_create_library_categories_table', 1),
(3, '2025_06_13_142555_create_product_categories_table', 1),
(4, '2025_06_13_142555_create_training_categories_table', 1),
(5, '2025_06_13_142555_create_user_types_table', 1),
(6, '2025_06_13_142556_create_campaigns_table', 1),
(7, '2025_06_13_142556_create_products_table', 1),
(8, '2025_06_13_142557_create_files_table', 1),
(9, '2025_06_13_142557_create_library_table', 1),
(10, '2025_06_13_142557_create_news_table', 1),
(11, '2025_06_13_142557_create_trainings_table', 1),
(12, '2025_06_13_142558_create_library_permissions_table', 1),
(13, '2025_06_13_142558_create_news_permissions_table', 1),
(14, '2025_06_13_142558_create_product_permissions_table', 1),
(15, '2025_06_13_142559_create_access_history_table', 1),
(16, '2025_06_13_142559_create_training_permissions_table', 1),
(17, '2025_06_13_142559_create_user_notifications_table', 1),
(18, '2025_06_13_142559_create_user_views_table', 1),
(19, '2025_06_13_142600_create_download_options_table', 1),
(20, '2025_06_13_145000_add_fields_to_users_table', 1),
(21, '2025_06_13_161933_create_sessions_table', 2),
(22, '2025_06_13_183607_create_ui_visibilities_table', 3),
(23, '2025_06_13_184225_create_ui_visibilities_table', 4),
(24, '2025_08_27_193033_create_product_series_table', 5),
(25, '2025_01_27_000000_fix_products_table_structure', 6),
(26, '2025_01_27_000001_fix_trainings_table_structure', 6),
(27, '2025_01_27_000002_fix_libraries_table_structure', 7),
(28, '2025_01_27_000006_add_file_id_to_products', 8),
(29, '2025_01_27_000007_add_file_id_to_trainings', 8),
(30, '2025_01_27_000008_add_file_id_to_news', 8),
(31, '2025_01_27_000009_add_file_id_to_library', 8),
(32, '2025_01_27_000010_remove_fileable_columns_from_files', 9),
(33, '2025_01_27_000011_create_news_categories_table', 10),
(34, '2025_01_27_000013_create_campaign_folders_table', 11),
(35, '2025_01_27_000014_create_campaign_posts_table', 11),
(36, '2025_01_27_000015_create_campaign_videos_table', 11),
(37, '2025_01_27_000016_create_campaign_miscellaneous_table', 11),
(38, '2025_08_29_155853_remove_file_id_columns_from_content_tables', 12),
(39, '2025_08_29_155950_create_content_file_pivot_tables', 13),
(40, '2025_08_29_155954_add_columns_to_content_file_pivot_tables', 13),
(41, '2025_08_29_161154_add_thumbnail_columns_to_content_tables', 14),
(42, '2025_08_29_000000_remove_file_id_columns_from_content_tables', 15),
(43, '2025_08_29_184053_fix_files_table_structure', 16),
(44, '2025_01_27_000020_fix_sessions_table_structure', 17),
(45, '2025_01_27_000021_drop_access_history_table', 17),
(46, '2025_01_27_000022_fix_sessions_table_structure', 18),
(47, '2025_01_27_000023_recreate_sessions_table', 19),
(48, '2025_01_27_000024_add_business_fields_to_users_table', 20),
(49, '2025_09_03_134331_create_cache_table', 21),
(50, '2025_09_03_223606_create_permission_tables', 22),
(51, '2025_10_12_225636_create_audit_logs_table', 23),
(52, '2025_10_13_000001_add_thumbnails_and_featured_columns', 24),
(53, '2025_10_13_214540_update_last_access_column_in_users_table', 25),
(54, '2025_10_14_224552_optimize_notifications_table', 26),
(55, '2025_10_15_202118_create_jobs_table', 27),
(56, '2025_10_15_202255_create_onedrive_syncs_table', 28),
(57, '2025_10_15_204609_create_failed_jobs_table', 29);

-- --------------------------------------------------------

--
-- Estrutura para tabela `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `author_id` bigint(20) UNSIGNED NOT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `news_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `news_file_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `excerpt`, `author_id`, `published_at`, `status`, `created_at`, `updated_at`, `news_category_id`, `news_file_id`, `thumbnail_path`) VALUES
(3, 'Inicio Serie 101 e polaris Plus com Acaro Combat', NULL, NULL, 1, NULL, 'published', '2026-03-10 18:17:03', '2026-03-10 18:17:03', 5, 338, NULL),
(4, 'Suspensao Protetor de Uniao', NULL, NULL, 1, NULL, 'published', '2026-03-10 18:17:50', '2026-03-10 18:17:50', 5, 339, NULL),
(5, 'Nova Configuracao Col Comfort Visco', NULL, NULL, 1, NULL, 'published', '2026-03-10 18:18:14', '2026-03-10 18:18:14', 5, 340, NULL),
(6, 'Mudanca Bordado Serie 101.', NULL, NULL, 1, NULL, 'published', '2026-03-10 18:18:40', '2026-03-10 18:19:00', 5, 341, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `news_categories`
--

CREATE TABLE `news_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `news_categories`
--

INSERT INTO `news_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Lançamentos', 'Novidades e lançamentos de produtos', 'active', '2025-08-28 21:30:45', '2025-08-28 21:30:45'),
(2, 'Marketing', 'Campanhas e estratégias de marketing', 'active', '2025-08-28 21:30:45', '2025-08-28 21:30:45'),
(3, 'Franquias', 'Notícias sobre expansão e oportunidades de franquia', 'active', '2025-08-28 21:30:45', '2025-08-28 21:30:45'),
(4, 'Treinamentos', 'Atualizações sobre treinamentos e capacitação', 'active', '2025-08-28 21:30:45', '2025-08-28 21:30:45'),
(5, 'Produtos', NULL, 'active', '2026-03-10 17:42:16', '2026-03-10 17:42:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `news_permissions`
--

CREATE TABLE `news_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `user_type_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `news_permissions`
--

INSERT INTO `news_permissions` (`id`, `news_id`, `user_type_id`, `can_view`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(2, 3, 2, 0, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(3, 3, 3, 0, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(4, 3, 4, 0, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(5, 4, 1, 1, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(6, 4, 2, 1, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(7, 4, 3, 0, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(8, 4, 4, 0, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(9, 5, 1, 1, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(10, 5, 2, 1, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(11, 5, 3, 0, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(12, 5, 4, 0, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(13, 6, 1, 1, '2026-03-10 18:18:40', '2026-03-10 18:18:40'),
(18, 6, 2, 1, '2026-03-10 18:19:00', '2026-03-10 18:19:00'),
(19, 6, 3, 0, '2026-03-10 18:19:00', '2026-03-10 18:19:00'),
(20, 6, 4, 0, '2026-03-10 18:19:00', '2026-03-10 18:19:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notifications_optimized`
--

CREATE TABLE `notifications_optimized` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('info','success','warning','error') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` enum('all','user_types','specific_users') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_ids` json DEFAULT NULL,
  `read_by` json DEFAULT NULL,
  `related_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `notifications_optimized`
--

INSERT INTO `notifications_optimized` (`id`, `title`, `message`, `type`, `target_type`, `target_ids`, `read_by`, `related_type`, `related_id`, `created_at`, `updated_at`) VALUES
(2, 'Nova Campanha Disponível', 'Uma nova campanha foi lançada: Mês do Consumidor', 'info', 'user_types', '[2]', NULL, 'App\\Models\\Campaign', 1, '2026-03-10 01:15:20', '2026-03-10 01:15:20'),
(3, 'Teste', 'Nova Notificação', 'info', 'all', NULL, NULL, NULL, NULL, '2026-03-10 02:48:40', '2026-03-10 02:48:40'),
(4, 'Vejam', 'Novidades', 'info', 'all', NULL, NULL, NULL, NULL, '2026-03-10 03:02:24', '2026-03-10 03:02:24'),
(5, 'Novo Treinamento Disponível', 'Um novo treinamento foi adicionado: Treinamento - Franquia - Colchão Polaris Plus Pillow Top (10/09/2025)', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Training', 1, '2026-03-10 05:38:45', '2026-03-10 05:38:45'),
(6, 'Novo Treinamento Disponível', 'Um novo treinamento foi adicionado: Treinamento – Franquia – TECNOLOGIAS SÉRIE 703 e SERIE 509 (21/08/2025)', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Training', 2, '2026-03-10 06:30:47', '2026-03-10 06:30:47'),
(7, 'Nova Notícia Disponível', 'Uma nova notícia foi publicada: Inicio Serie 101 e polaris Plus com Acaro Combat', 'info', 'user_types', '[1]', NULL, 'App\\Models\\News', 3, '2026-03-10 18:17:03', '2026-03-10 18:17:03'),
(8, 'Nova Notícia Disponível', 'Uma nova notícia foi publicada: Suspensao Protetor de Uniao', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\News', 4, '2026-03-10 18:17:50', '2026-03-10 18:17:50'),
(9, 'Nova Notícia Disponível', 'Uma nova notícia foi publicada: Nova Configuracao Col Comfort Visco', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\News', 5, '2026-03-10 18:18:14', '2026-03-10 18:18:14'),
(10, 'Nova Notícia Disponível', 'Uma nova notícia foi publicada: Mudanca Bordado Serie 101.', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\News', 6, '2026-03-10 18:18:40', '2026-03-10 18:18:40'),
(11, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Série 309', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 1, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(12, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: MEMORIAL DESCRITIVO 2025', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 1, '2026-03-11 20:09:25', '2026-03-11 20:09:25'),
(13, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: CHECKLIST ATIVIDADES DA LOJA', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 2, '2026-03-11 20:10:06', '2026-03-11 20:10:06'),
(14, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: FORÇAS DA MARCA', 'info', 'user_types', '[1, 2]', '[8]', 'App\\Models\\Library', 3, '2026-03-11 20:28:43', '2026-03-25 02:21:46'),
(15, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: Métodos de Vendas Orthocrin', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 4, '2026-03-11 20:30:13', '2026-03-11 20:30:13'),
(16, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: ORGANIZANDO A FRANQUIA PARA VENDA', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 5, '2026-03-11 20:38:10', '2026-03-11 20:38:10'),
(17, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: Avatar', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 6, '2026-03-11 20:49:33', '2026-03-11 20:49:33'),
(18, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: Logo Aniversário 60 anos', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Library', 7, '2026-03-11 20:50:31', '2026-03-11 20:50:31'),
(19, 'Nova Biblioteca Disponível', 'Um novo item foi adicionado à biblioteca: LOGO ORTHOCRIN', 'info', 'user_types', '[1, 2]', '[8]', 'App\\Models\\Library', 8, '2026-03-11 20:53:21', '2026-03-25 02:20:58'),
(20, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Bend', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 2, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(21, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Série 703', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Product', 3, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(22, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Série 705', 'info', 'user_types', '[1, 2]', '[8]', 'App\\Models\\Product', 4, '2026-03-12 00:39:16', '2026-03-16 20:48:44'),
(23, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Orthofoam', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 5, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(24, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Vega', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 6, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(25, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Hipnos', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 7, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(26, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Morpheu', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 8, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(27, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Polaris', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 9, '2026-03-30 05:49:23', '2026-03-30 05:49:23'),
(28, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Polaris Plus Pillow Top', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 10, '2026-03-30 05:53:42', '2026-03-30 05:53:42'),
(29, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Polaris Ultra', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 11, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(30, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Polaris Baby', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 12, '2026-03-30 05:58:52', '2026-03-30 05:58:52'),
(31, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Visco Gel', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 13, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(32, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 121', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 14, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(33, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 309 Plus', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 15, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(34, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 503', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 16, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(35, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 507', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 17, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(36, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 501', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 18, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(37, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 505', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 19, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(38, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 509', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 20, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(39, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 551 Plus', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 21, '2026-03-30 19:09:09', '2026-03-30 19:09:09'),
(40, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 907', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 22, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(41, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 907 Slim', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 23, '2026-03-30 19:19:26', '2026-03-30 19:19:26'),
(42, 'Novo Produto Disponível', 'Um novo produto foi adicionado: 955', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 24, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(43, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Persona Relax', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 25, '2026-03-30 19:32:21', '2026-03-30 19:32:21'),
(44, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Persona Magnetic', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 26, '2026-03-30 19:36:30', '2026-03-30 19:36:30'),
(45, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Box pet cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 27, '2026-03-30 19:47:43', '2026-03-30 19:47:43'),
(46, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Pet comfort', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 28, '2026-03-30 19:53:02', '2026-03-30 19:53:02'),
(47, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Colchonete Fitness', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 29, '2026-03-30 19:56:35', '2026-03-30 19:56:35'),
(48, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Colchonete multiuso', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 30, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(49, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Colchonete multiuso comfort', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 31, '2026-03-30 20:13:04', '2026-03-30 20:13:04'),
(50, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ortopédico Azul', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 32, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(51, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ouro Plus Eurotop', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 33, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(52, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Madeira ouro', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 34, '2026-03-30 20:20:40', '2026-03-30 20:20:40'),
(53, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Garnet Bouclê Cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 35, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(54, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Garnet Factor Cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 36, '2026-03-30 20:35:24', '2026-03-30 20:35:24'),
(55, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Garnet Factor Sepia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 37, '2026-03-30 20:36:13', '2026-03-30 20:36:13'),
(56, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Garnet Linho Dark', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 38, '2026-03-30 20:37:48', '2026-03-30 20:37:48'),
(57, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Topázio Bouclê Cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 39, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(58, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Topázio Facto Sepia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 40, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(59, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Topázio Areia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 41, '2026-03-30 23:17:12', '2026-03-30 23:17:12'),
(60, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Topázio Dark', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 42, '2026-03-30 23:19:09', '2026-03-30 23:19:09'),
(61, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Topázio Facto Cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 43, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(62, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ambar Bouclê Cinza', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 44, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(63, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ambar Facto', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 45, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(64, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ambar Dark', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 46, '2026-03-31 03:05:20', '2026-03-31 03:05:20'),
(65, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Ambar Areia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 47, '2026-03-31 03:06:29', '2026-03-31 03:06:29'),
(66, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Diamond Bouclê', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 48, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(67, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Diamond Areia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 49, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(68, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Diamond Dark', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 50, '2026-03-31 03:14:41', '2026-03-31 03:14:41'),
(69, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Verbena Bouclê', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 51, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(70, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Verbena Area', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 52, '2026-03-31 03:21:58', '2026-03-31 03:21:58'),
(71, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Verbena Dark', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 53, '2026-03-31 03:22:36', '2026-03-31 03:22:36'),
(72, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Lotus Bouclê', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 54, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(73, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Lotus Areia', 'info', 'user_types', '[1]', NULL, 'App\\Models\\Product', 55, '2026-03-31 03:46:19', '2026-03-31 03:46:19'),
(74, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Lotus Dark', 'info', 'user_types', '[1, 2]', NULL, 'App\\Models\\Product', 56, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(75, 'Nova Campanha Disponível', 'Uma nova campanha foi lançada: CUIDE ORTHOCRIN', 'info', 'user_types', '[2]', NULL, 'App\\Models\\Campaign', 2, '2026-04-01 00:50:09', '2026-04-01 00:50:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `onedrive_syncs`
--

CREATE TABLE `onedrive_syncs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `syncable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `syncable_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remote_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','synced','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `onedrive_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `onedrive_syncs`
--

INSERT INTO `onedrive_syncs` (`id`, `syncable_type`, `syncable_id`, `file_path`, `remote_path`, `status`, `error_message`, `onedrive_url`, `synced_at`, `created_at`, `updated_at`) VALUES
(10, 'App\\Models\\Product', 15, 'private/test/test-file.txt', 'Products/15/test/test-file.txt', 'synced', NULL, 'https://1drv.ms/t/s!ABcFkb-CluIRgxg', '2025-10-16 00:03:52', '2025-10-16 00:03:49', '2025-10-16 00:03:52'),
(11, 'App\\Models\\Product', 2, 'private/products/2/product-sample01.jpg', 'Products/2/images/product-sample01.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgxs', '2025-10-16 00:04:55', '2025-10-16 00:04:50', '2025-10-16 00:04:55'),
(12, 'App\\Models\\Product', 2, 'private/products/2/product-sample02.jpg', 'Products/2/images/product-sample02.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgxw', '2025-10-16 00:05:01', '2025-10-16 00:04:50', '2025-10-16 00:05:01'),
(13, 'App\\Models\\Product', 2, 'private/products/2/product-sample03.jpg', 'Products/2/images/product-sample03.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgx0', '2025-10-16 00:05:07', '2025-10-16 00:04:50', '2025-10-16 00:05:07'),
(14, 'App\\Models\\Product', 2, 'private/products/2/product-sample04.jpg', 'Products/2/images/product-sample04.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgx4', '2025-10-16 00:05:13', '2025-10-16 00:04:50', '2025-10-16 00:05:13'),
(15, 'App\\Models\\Product', 2, 'private/products/2/product-video01.mp4', 'Products/2/videos/product-video01.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgyA', '2025-10-16 00:05:42', '2025-10-16 00:04:50', '2025-10-16 00:05:42'),
(16, 'App\\Models\\Product', 2, 'private/products/2/product-video02.mp4', 'Products/2/videos/product-video02.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgyE', '2025-10-16 00:06:14', '2025-10-16 00:04:50', '2025-10-16 00:06:14'),
(17, 'App\\Models\\Product', 2, 'private/products/2/product-video03.mp4', 'Products/2/videos/product-video03.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgyI', '2025-10-16 00:06:36', '2025-10-16 00:04:50', '2025-10-16 00:06:36'),
(18, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg', 'Campaigns/18/campaign-post-feed-01.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Nv1ufKj5umeucN0eCo3Jl9mYogZzdfHT2gD3LfWR.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyU', '2025-10-16 02:09:24', '2025-10-16 02:01:47', '2025-10-16 02:09:24'),
(19, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg', 'Campaigns/18/campaign-post-feed-02.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/zYD2ichSixTAFgih50xoogTB3Yj1yJvyAlXTaJjK.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyY', '2025-10-16 02:09:28', '2025-10-16 02:01:47', '2025-10-16 02:09:28'),
(20, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg', 'Campaigns/18/campaign-post-feed-03.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Un4haK2ClMcVGD14mQuloFf5y2B93ocoQ4c6fx83.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyc', '2025-10-16 02:09:33', '2025-10-16 02:01:47', '2025-10-16 02:09:33'),
(21, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg', 'Campaigns/18/campaign-post-feed-04.jpg', 'synced', 'The stream or file \"/home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/logs/laravel.log\" could not be opened in append mode: Failed to open stream: Permission denied\nThe exception occurred while attempting to log: OneDrive upload success\nContext: {\"local\":\"\\/home\\/codestackrg\\/Projects\\/UniOrthocrin\\/uniorthocrin\\/storage\\/app\\/private\\/campaigns\\/18\\/posts\\/zvTRmpcqwB1sTDsR2qYsdqUmjFaiYmv5ubl6KILV.jpg\",\"remote\":\"Campaigns\\/18\\/campaign-post-feed-04.jpg\",\"url\":\"https:\\/\\/1drv.ms\\/i\\/s!ABcFkb-CluIRgyg\"}', 'https://1drv.ms/i/s!ABcFkb-CluIRgyg', '2025-10-16 02:11:44', '2025-10-16 02:01:47', '2025-10-16 02:11:44'),
(22, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg', 'Campaigns/18/campaign-story-dfes-01.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/Op0dM2ggxPFnOZrVIepBPos8vkqbHcVCP3qCH3LW.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyk', '2025-10-16 02:09:37', '2025-10-16 02:01:47', '2025-10-16 02:09:37'),
(23, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg', 'Campaigns/18/campaign-story-mgsp-02.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/HGq0IWsAwRL4RSVboLxjQVsU8Rm945bWqgSVtPaL.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyo', '2025-10-16 02:09:44', '2025-10-16 02:01:47', '2025-10-16 02:09:44'),
(24, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg', 'Campaigns/18/campaign-story-mgsp-03.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/93v138pAdPAvZifMWVYJKLA7pyRVGAJ7bABXfySQ.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgys', '2025-10-16 02:09:48', '2025-10-16 02:01:47', '2025-10-16 02:09:48'),
(25, 'App\\Models\\Campaign', 18, 'private/campaigns/18/posts/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg', 'Campaigns/18/campaign-story-mgsp-04.jpg', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/posts/NMqUQwoMZDPqjJf9LO9yzCoZJjtk2JECUCwlKsmQ.jpg', 'https://1drv.ms/i/s!ABcFkb-CluIRgyw', '2025-10-16 02:09:52', '2025-10-16 02:01:47', '2025-10-16 02:09:52'),
(26, 'App\\Models\\Campaign', 18, 'private/campaigns/18/folders/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf', 'Campaigns/18/campaign-folder-mgsp.pdf', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/zcH5uwlaunBPnYXSrQP6NJGwxWLjD88VXS8tPC1d.pdf', 'https://1drv.ms/b/s!ABcFkb-CluIRgy0', '2025-10-16 02:09:53', '2025-10-16 02:01:47', '2025-10-16 02:09:53'),
(27, 'App\\Models\\Campaign', 18, 'private/campaigns/18/folders/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf', 'Campaigns/18/campaign-folder-dfes.pdf', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/folders/xEbPdWANz5UZVNoSfJIXucEYriVZINmL0SkkykAU.pdf', 'https://1drv.ms/b/s!ABcFkb-CluIRgy4', '2025-10-16 02:09:56', '2025-10-16 02:01:47', '2025-10-16 02:09:56'),
(28, 'App\\Models\\Campaign', 18, 'private/campaigns/18/videos/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4', 'Campaigns/18/campaign-reel-01.mp4', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/8k3XWXFlPIQS0LotEpu12EWLcgF3uFNAjq429AYw.mp4', 'https://1drv.ms/i/s!ABcFkb-CluIRgy8', '2025-10-16 02:10:24', '2025-10-16 02:01:47', '2025-10-16 02:10:24'),
(29, 'App\\Models\\Campaign', 18, 'private/campaigns/18/videos/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4', 'Campaigns/18/campaign-reel-02.mp4', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/PsSG8P8Jix5JOu2vuDb1O0AcRcncEGqHGAX06LuG.mp4', 'https://1drv.ms/i/s!ABcFkb-CluIRgzA', '2025-10-16 02:10:57', '2025-10-16 02:01:47', '2025-10-16 02:10:57'),
(30, 'App\\Models\\Campaign', 18, 'private/campaigns/18/videos/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4', 'Campaigns/18/campaign-video-02.mp4', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/videos/iA7DBvXOTJFpnCBwY6LKu5ICDfo485P9zDz52Rg9.mp4', 'https://1drv.ms/i/s!ABcFkb-CluIRgzE', '2025-10-16 02:11:26', '2025-10-16 02:01:47', '2025-10-16 02:11:26'),
(31, 'App\\Models\\Campaign', 18, 'private/campaigns/18/miscellaneous/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3', 'Campaigns/18/file_example_MP3_700KB.mp3', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/3zGznA4eTLeqsMsSaiCbcsfNrUrrQeal5aUuF8YG.mp3', 'https://1drv.ms/u/s!ABcFkb-CluIRgzI', '2025-10-16 02:11:30', '2025-10-16 02:01:47', '2025-10-16 02:11:30'),
(32, 'App\\Models\\Campaign', 18, 'private/campaigns/18/miscellaneous/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf', 'Campaigns/18/tag.pdf', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/55dfVAyuYxU45f0cKVfY6TQxeXLsNv5UXBRathyx.pdf', 'https://1drv.ms/b/s!ABcFkb-CluIRgzM', '2025-10-16 02:11:32', '2025-10-16 02:01:47', '2025-10-16 02:11:32'),
(33, 'App\\Models\\Campaign', 18, 'private/campaigns/18/miscellaneous/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf', 'Campaigns/18/script.pdf', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/gCF5dsjQWzEXhMnxQ8851o7uJk40GCar0HJXid9k.pdf', 'https://1drv.ms/b/s!ABcFkb-CluIRgzQ', '2025-10-16 02:11:36', '2025-10-16 02:01:47', '2025-10-16 02:11:36'),
(34, 'App\\Models\\Campaign', 18, 'private/campaigns/18/miscellaneous/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf', 'Campaigns/18/sticker.pdf', 'synced', 'Local file not found: /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/app/private/campaigns/18/miscellaneous/2bhIvqRRx9WzS2hYAKylVxRdFf4u5cYovIsIY4p9.pdf', 'https://1drv.ms/b/s!ABcFkb-CluIRgzU', '2025-10-16 02:11:39', '2025-10-16 02:01:47', '2025-10-16 02:11:39'),
(35, 'App\\Models\\Product', 12, 'private/products/12/images/fr8BPCnb0lvVnUP4At2BBnYA7emj7d7BwPFlv0Sq.png', 'Products/12/images/produto-03_imagem_01.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgzg', '2025-10-16 02:11:50', '2025-10-16 02:11:19', '2025-10-16 02:11:50'),
(36, 'App\\Models\\Product', 12, 'private/products/12/images/uXO8uKQxcZxIwgyUhoYWmVEf2AxWw7Ri9VRh3YIK.png', 'Products/12/images/produto-03_imagem_02.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgzk', '2025-10-16 02:11:56', '2025-10-16 02:11:19', '2025-10-16 02:11:56'),
(37, 'App\\Models\\Product', 12, 'private/products/12/images/mzx5MHf8QDwCesAVglejxuhdBpNqaEbUIbIVpLrP.png', 'Products/12/images/produto-03_imagem_03.jpg', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgzo', '2025-10-16 02:12:00', '2025-10-16 02:11:19', '2025-10-16 02:12:00'),
(38, 'App\\Models\\Product', 12, 'private/products/12/videos/8ilPB4I1XaLD1a6NkKdOGF9YqssFQjhgOxo1Wgx0.mp4', 'Products/12/videos/produto-03_video_01.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgzw', '2025-10-16 02:12:06', '2025-10-16 02:11:19', '2025-10-16 02:12:06'),
(39, 'App\\Models\\Product', 12, 'private/products/12/videos/ovcN5cX3pM4MYhTfXTxICbj2Yc9UTppvR5gYJyKF.mp4', 'Products/12/videos/produto-03_video_02.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgz0', '2025-10-16 02:12:12', '2025-10-16 02:11:19', '2025-10-16 02:12:12'),
(40, 'App\\Models\\Product', 13, 'private/products/13/videos/7iYEYSMkEVBt6mpA859cEXnUe3IgcJ0vR1rmDuCA.mp4', 'Products/13/videos/campaign-video-01.mp4', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRgz4', '2025-10-16 02:38:13', '2025-10-16 02:37:38', '2025-10-16 02:38:13'),
(41, 'App\\Models\\Library', 2, 'private/libraries/2/memorial_descritivo_2024.pdf', 'Library/2/memorial_descritivo_2024.pdf', 'synced', NULL, 'https://1drv.ms/b/s!ABcFkb-CluIRg0A', '2025-10-16 02:38:36', '2025-10-16 02:38:30', '2025-10-16 02:38:36'),
(42, 'App\\Models\\Training', 2, 'private/trainings/2/training-sample01.mov', 'Training/2/training-sample01.mov', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRg0I', '2025-10-16 02:39:32', '2025-10-16 02:39:14', '2025-10-16 02:39:32'),
(43, 'App\\Models\\Training', 2, 'private/trainings/2/training-sample02.mov', 'Training/2/training-sample02.mov', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRg0M', '2025-10-16 02:39:51', '2025-10-16 02:39:14', '2025-10-16 02:39:51'),
(44, 'App\\Models\\Training', 2, 'private/trainings/2/training-sample03.mov', 'Training/2/training-sample03.mov', 'synced', NULL, 'https://1drv.ms/i/s!ABcFkb-CluIRg0Q', '2025-10-16 02:40:15', '2025-10-16 02:39:14', '2025-10-16 02:40:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_series_id` bigint(20) UNSIGNED DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `products`
--

INSERT INTO `products` (`id`, `name`, `product_category_id`, `description`, `status`, `created_at`, `updated_at`, `product_series_id`, `thumbnail_path`) VALUES
(1, '309', 1, NULL, 'active', '2026-03-10 20:49:45', '2026-03-30 06:52:47', 4, 'private/products/1/thumb/cxxcXIwyTChwV37BbfH9wPPpHi3tDks8qpg9UKCi.jpg'),
(2, 'Bend', 3, NULL, 'active', '2026-03-11 23:07:41', '2026-03-11 23:07:41', NULL, NULL),
(3, '703', 1, NULL, 'active', '2026-03-12 00:18:28', '2026-03-30 23:14:39', 2, NULL),
(4, '705', 1, NULL, 'active', '2026-03-12 00:39:15', '2026-03-30 06:53:29', 2, NULL),
(5, 'Orthofoam', 3, NULL, 'active', '2026-03-30 05:36:17', '2026-03-30 05:36:17', NULL, NULL),
(6, 'Vega', 3, NULL, 'active', '2026-03-30 05:38:54', '2026-03-30 05:38:54', NULL, NULL),
(7, 'Hipnos', 3, NULL, 'active', '2026-03-30 05:45:07', '2026-03-30 05:45:07', NULL, NULL),
(8, 'Morpheu', 3, NULL, 'active', '2026-03-30 05:48:30', '2026-03-30 05:48:30', NULL, NULL),
(9, 'Polaris Plus', 3, NULL, 'active', '2026-03-30 05:49:23', '2026-03-30 05:49:54', NULL, NULL),
(10, 'Polaris Plus Pillow Top', 3, NULL, 'active', '2026-03-30 05:53:42', '2026-03-30 05:53:42', NULL, NULL),
(11, 'Polaris Ultra', 3, NULL, 'active', '2026-03-30 05:55:10', '2026-03-30 05:55:10', NULL, NULL),
(12, 'Polaris Baby', 3, NULL, 'active', '2026-03-30 05:58:52', '2026-03-30 05:58:52', NULL, NULL),
(13, 'Visage Gel', 3, NULL, 'active', '2026-03-30 06:00:23', '2026-03-31 07:45:07', NULL, NULL),
(14, '121', 1, NULL, 'active', '2026-03-30 06:37:45', '2026-03-30 06:37:45', 6, NULL),
(15, '301', 1, NULL, 'active', '2026-03-30 06:50:44', '2026-03-30 06:51:46', 4, NULL),
(16, '503', 1, NULL, 'active', '2026-03-30 07:06:02', '2026-03-30 07:06:02', 3, NULL),
(17, '507', 1, NULL, 'active', '2026-03-30 07:16:21', '2026-03-30 07:16:21', 3, NULL),
(18, '501', 1, NULL, 'active', '2026-03-30 18:40:44', '2026-03-30 18:40:44', 3, NULL),
(19, '505', 1, NULL, 'active', '2026-03-30 18:48:37', '2026-03-30 18:48:37', 3, NULL),
(20, '509', 1, NULL, 'active', '2026-03-30 19:05:10', '2026-03-30 19:05:10', 3, NULL),
(21, '551 Plus', 1, NULL, 'active', '2026-03-30 19:09:09', '2026-03-30 19:09:09', 3, NULL),
(22, '907', 1, NULL, 'active', '2026-03-30 19:17:32', '2026-03-30 19:17:32', 1, NULL),
(23, '907 Slim', 1, NULL, 'active', '2026-03-30 19:19:26', '2026-03-30 19:19:26', 1, NULL),
(24, '955', 1, NULL, 'active', '2026-03-30 19:22:55', '2026-03-30 19:22:55', 1, NULL),
(25, 'Persona Relax', 7, NULL, 'active', '2026-03-30 19:32:21', '2026-03-30 19:32:21', NULL, NULL),
(26, 'Persona Magnetic', 7, NULL, 'active', '2026-03-30 19:36:30', '2026-03-30 19:36:30', NULL, NULL),
(27, 'Box pet cinza', 9, NULL, 'active', '2026-03-30 19:47:43', '2026-03-30 19:47:43', NULL, NULL),
(28, 'Comfort', 9, NULL, 'active', '2026-03-30 19:53:02', '2026-03-31 06:36:55', NULL, NULL),
(29, 'Fitness', 10, NULL, 'active', '2026-03-30 19:56:35', '2026-03-31 06:36:49', NULL, NULL),
(30, 'Multiuso', 10, NULL, 'active', '2026-03-30 20:00:23', '2026-03-31 06:36:42', NULL, NULL),
(31, 'Multiuso Comfort', 10, NULL, 'active', '2026-03-30 20:13:04', '2026-03-31 06:36:36', NULL, NULL),
(32, 'Ouro Azul', 6, NULL, 'active', '2026-03-30 20:16:02', '2026-03-31 07:32:20', NULL, NULL),
(33, 'Ouro Plus Eurotop', 6, NULL, 'active', '2026-03-30 20:18:02', '2026-03-30 20:18:02', NULL, NULL),
(34, 'Madeira ouro', 6, NULL, 'active', '2026-03-30 20:20:40', '2026-03-30 20:20:40', NULL, NULL),
(35, 'Garnet Bouclê Cinza', 8, NULL, 'active', '2026-03-30 20:32:56', '2026-03-30 20:32:56', NULL, NULL),
(36, 'Garnet Facto Cinza', 8, NULL, 'active', '2026-03-30 20:35:24', '2026-03-30 20:42:34', NULL, NULL),
(37, 'Garnet Facto Sepia', 8, NULL, 'active', '2026-03-30 20:36:13', '2026-03-30 20:42:25', NULL, NULL),
(38, 'Garnet Linho Dark', 8, NULL, 'active', '2026-03-30 20:37:47', '2026-03-30 20:37:47', NULL, NULL),
(39, 'Topázio Bouclê Cinza', 8, NULL, 'active', '2026-03-30 23:12:05', '2026-03-30 23:12:05', NULL, NULL),
(40, 'Topázio Facto Sepia', 8, NULL, 'active', '2026-03-30 23:16:13', '2026-03-30 23:16:13', NULL, NULL),
(41, 'Topázio Areia', 8, NULL, 'active', '2026-03-30 23:17:12', '2026-03-30 23:17:12', NULL, NULL),
(42, 'Topázio Dark', 8, NULL, 'active', '2026-03-30 23:19:09', '2026-03-30 23:19:09', NULL, NULL),
(43, 'Topázio Facto Cinza', 8, NULL, 'active', '2026-03-30 23:19:54', '2026-03-30 23:19:54', NULL, NULL),
(44, 'Ambar Bouclê Cinza', 8, NULL, 'active', '2026-03-31 03:03:54', '2026-03-31 03:03:54', NULL, NULL),
(45, 'Ambar Facto', 8, NULL, 'active', '2026-03-31 03:04:44', '2026-03-31 03:04:44', NULL, NULL),
(46, 'Ambar Dark', 8, NULL, 'active', '2026-03-31 03:05:20', '2026-03-31 03:05:20', NULL, NULL),
(47, 'Ambar Areia', 8, NULL, 'active', '2026-03-31 03:06:29', '2026-03-31 03:06:29', NULL, NULL),
(48, 'Diamond Bouclê', 8, NULL, 'active', '2026-03-31 03:13:31', '2026-03-31 03:13:31', NULL, NULL),
(49, 'Diamond Areia', 8, NULL, 'active', '2026-03-31 03:14:14', '2026-03-31 03:14:14', NULL, NULL),
(50, 'Diamond Dark', 8, NULL, 'active', '2026-03-31 03:14:41', '2026-03-31 03:14:41', NULL, NULL),
(51, 'Verbena Bouclê', 8, NULL, 'active', '2026-03-31 03:21:20', '2026-03-31 03:21:20', NULL, NULL),
(52, 'Verbena Areia', 8, NULL, 'active', '2026-03-31 03:21:58', '2026-03-31 03:48:46', NULL, NULL),
(53, 'Verbena Dark', 8, NULL, 'active', '2026-03-31 03:22:36', '2026-03-31 03:22:36', NULL, NULL),
(54, 'Lotus Bouclê', 8, NULL, 'active', '2026-03-31 03:45:49', '2026-03-31 03:45:49', NULL, NULL),
(55, 'Lotus Areia', 8, NULL, 'active', '2026-03-31 03:46:19', '2026-03-31 03:46:19', NULL, NULL),
(56, 'Lotus Dark', 8, NULL, 'active', '2026-03-31 03:47:30', '2026-03-31 03:47:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Molas', 'Colchões com molas', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(2, 'Travesseiros', 'Travesseiros e almofadas', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(3, 'Espumas', 'Colchões de espuma', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(4, 'Acessórios e Complementos', 'Acessórios para colchões', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(5, 'Box', 'Box para colchões', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(6, 'Ortopédicos', 'Colchões ortopédicos', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(7, 'Vibroterapia', 'Colchões com vibroterapia', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(8, 'Cabeceiras', 'Cabeceiras para cama', 'active', '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(9, 'Pet', NULL, 'active', '2026-03-30 19:42:30', '2026-03-30 19:42:30'),
(10, 'Colchonete', NULL, 'active', '2026-03-30 19:43:42', '2026-03-30 19:43:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_files`
--

CREATE TABLE `product_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `product_files`
--

INSERT INTO `product_files` (`id`, `product_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(1, 1, 342, NULL, NULL, 'image', 1, 1),
(2, 1, 343, NULL, NULL, 'image', 2, 0),
(3, 1, 344, NULL, NULL, 'image', 3, 0),
(4, 1, 345, NULL, NULL, 'image', 4, 0),
(5, 1, 346, NULL, NULL, 'image', 5, 0),
(6, 1, 347, NULL, NULL, 'image', 6, 0),
(7, 1, 348, NULL, NULL, 'image', 7, 0),
(8, 1, 349, NULL, NULL, 'image', 8, 0),
(9, 1, 350, NULL, NULL, 'image', 9, 0),
(10, 1, 351, NULL, NULL, 'image', 10, 0),
(11, 1, 352, NULL, NULL, 'image', 11, 0),
(12, 1, 353, NULL, NULL, 'image', 12, 0),
(13, 1, 354, NULL, NULL, 'image', 13, 0),
(14, 1, 355, NULL, NULL, 'image', 14, 0),
(15, 1, 356, NULL, NULL, 'video', 1, 0),
(16, 1, 357, NULL, NULL, 'video', 2, 0),
(17, 2, 369, NULL, NULL, 'image', 1, 1),
(18, 2, 370, NULL, NULL, 'image', 2, 0),
(19, 2, 371, NULL, NULL, 'image', 3, 0),
(20, 2, 372, NULL, NULL, 'image', 4, 0),
(21, 2, 373, NULL, NULL, 'image', 5, 0),
(22, 2, 374, NULL, NULL, 'image', 7, 0),
(23, 2, 375, NULL, NULL, 'image', 9, 0),
(24, 2, 376, NULL, NULL, 'image', 11, 0),
(25, 2, 377, NULL, NULL, 'image', 13, 0),
(26, 2, 378, NULL, NULL, 'image', 15, 0),
(27, 2, 379, NULL, NULL, 'image', 11, 0),
(28, 2, 380, NULL, NULL, 'image', 13, 0),
(29, 2, 381, NULL, NULL, 'image', 15, 0),
(30, 2, 382, NULL, NULL, 'image', 17, 0),
(31, 2, 383, NULL, NULL, 'image', 19, 0),
(32, 2, 384, NULL, NULL, 'image', 21, 0),
(33, 2, 385, NULL, NULL, 'image', 23, 0),
(34, 2, 386, NULL, NULL, 'image', 25, 0),
(35, 2, 387, NULL, NULL, 'image', 27, 0),
(36, 2, 388, NULL, NULL, 'image', 29, 0),
(37, 2, 389, NULL, NULL, 'image', 31, 0),
(38, 3, 390, NULL, NULL, 'image', 1, 1),
(39, 3, 391, NULL, NULL, 'image', 2, 0),
(40, 3, 392, NULL, NULL, 'image', 3, 0),
(41, 3, 393, NULL, NULL, 'image', 4, 0),
(42, 3, 394, NULL, NULL, 'image', 5, 0),
(43, 3, 395, NULL, NULL, 'image', 6, 0),
(44, 3, 396, NULL, NULL, 'image', 7, 0),
(45, 3, 397, NULL, NULL, 'image', 8, 0),
(46, 3, 398, NULL, NULL, 'image', 9, 0),
(47, 3, 399, NULL, NULL, 'image', 10, 0),
(48, 3, 400, NULL, NULL, 'image', 11, 0),
(49, 3, 401, NULL, NULL, 'image', 12, 0),
(50, 3, 402, NULL, NULL, 'image', 13, 0),
(51, 3, 403, NULL, NULL, 'image', 15, 0),
(52, 3, 404, NULL, NULL, 'image', 17, 0),
(53, 3, 405, NULL, NULL, 'image', 19, 0),
(54, 3, 406, NULL, NULL, 'image', 21, 0),
(55, 3, 407, NULL, NULL, 'image', 23, 0),
(56, 3, 408, NULL, NULL, 'image', 25, 0),
(57, 3, 409, NULL, NULL, 'image', 27, 0),
(58, 3, 410, NULL, NULL, 'image', 29, 0),
(59, 3, 411, NULL, NULL, 'image', 31, 0),
(60, 3, 412, NULL, NULL, 'video', 1, 0),
(61, 4, 413, NULL, NULL, 'image', 1, 1),
(62, 4, 414, NULL, NULL, 'image', 2, 0),
(63, 4, 415, NULL, NULL, 'image', 3, 0),
(64, 4, 416, NULL, NULL, 'image', 4, 0),
(65, 4, 417, NULL, NULL, 'image', 5, 0),
(66, 4, 418, NULL, NULL, 'image', 6, 0),
(67, 4, 419, NULL, NULL, 'image', 7, 0),
(68, 4, 420, NULL, NULL, 'image', 8, 0),
(69, 4, 421, NULL, NULL, 'image', 9, 0),
(70, 4, 422, NULL, NULL, 'image', 10, 0),
(71, 4, 423, NULL, NULL, 'image', 11, 0),
(72, 4, 424, NULL, NULL, 'image', 12, 0),
(73, 4, 425, NULL, NULL, 'image', 13, 0),
(74, 4, 426, NULL, NULL, 'image', 15, 0),
(75, 4, 427, NULL, NULL, 'image', 17, 0),
(76, 4, 428, NULL, NULL, 'image', 19, 0),
(77, 4, 429, NULL, NULL, 'image', 21, 0),
(78, 4, 430, NULL, NULL, 'image', 23, 0),
(79, 4, 431, NULL, NULL, 'image', 25, 0),
(80, 4, 432, NULL, NULL, 'image', 27, 0),
(81, 4, 433, NULL, NULL, 'image', 29, 0),
(82, 4, 434, NULL, NULL, 'image', 31, 0),
(83, 4, 435, NULL, NULL, 'image', 33, 0),
(84, 4, 436, NULL, NULL, 'image', 35, 0),
(85, 4, 437, NULL, NULL, 'image', 37, 0),
(86, 4, 438, NULL, NULL, 'image', 39, 0),
(87, 4, 439, NULL, NULL, 'image', 41, 0),
(88, 4, 440, NULL, NULL, 'image', 43, 0),
(89, 4, 441, NULL, NULL, 'image', 45, 0),
(90, 4, 442, NULL, NULL, 'image', 47, 0),
(91, 5, 443, NULL, NULL, 'image', 1, 1),
(92, 5, 444, NULL, NULL, 'image', 2, 0),
(93, 5, 445, NULL, NULL, 'image', 3, 0),
(94, 5, 446, NULL, NULL, 'image', 4, 0),
(95, 6, 447, NULL, NULL, 'image', 1, 1),
(96, 6, 448, NULL, NULL, 'image', 2, 0),
(97, 6, 449, NULL, NULL, 'image', 3, 0),
(98, 6, 450, NULL, NULL, 'image', 4, 0),
(99, 6, 451, NULL, NULL, 'image', 5, 0),
(100, 6, 452, NULL, NULL, 'image', 6, 0),
(101, 6, 453, NULL, NULL, 'image', 7, 0),
(102, 7, 454, NULL, NULL, 'image', 1, 1),
(103, 7, 455, NULL, NULL, 'image', 2, 0),
(104, 7, 456, NULL, NULL, 'image', 3, 0),
(105, 7, 457, NULL, NULL, 'image', 4, 0),
(106, 8, 458, NULL, NULL, 'image', 1, 1),
(107, 8, 459, NULL, NULL, 'image', 2, 0),
(108, 8, 460, NULL, NULL, 'image', 3, 0),
(109, 8, 461, NULL, NULL, 'image', 4, 0),
(110, 8, 462, NULL, NULL, 'image', 5, 0),
(111, 8, 463, NULL, NULL, 'image', 6, 0),
(112, 8, 464, NULL, NULL, 'image', 7, 0),
(113, 8, 465, NULL, NULL, 'image', 8, 0),
(114, 8, 466, NULL, NULL, 'image', 9, 0),
(115, 8, 467, NULL, NULL, 'image', 10, 0),
(116, 8, 468, NULL, NULL, 'image', 11, 0),
(117, 9, 469, NULL, NULL, 'image', 1, 1),
(118, 10, 470, NULL, NULL, 'image', 1, 1),
(119, 10, 471, NULL, NULL, 'image', 2, 0),
(120, 10, 472, NULL, NULL, 'image', 3, 0),
(121, 11, 473, NULL, NULL, 'image', 1, 1),
(122, 11, 474, NULL, NULL, 'image', 2, 0),
(123, 11, 475, NULL, NULL, 'image', 3, 0),
(124, 11, 476, NULL, NULL, 'image', 4, 0),
(125, 11, 477, NULL, NULL, 'image', 5, 0),
(126, 11, 478, NULL, NULL, 'image', 6, 0),
(127, 11, 479, NULL, NULL, 'image', 7, 0),
(128, 12, 480, NULL, NULL, 'image', 1, 1),
(129, 12, 481, NULL, NULL, 'image', 2, 0),
(130, 12, 482, NULL, NULL, 'image', 3, 0),
(131, 13, 483, NULL, NULL, 'image', 1, 1),
(132, 13, 484, NULL, NULL, 'image', 2, 0),
(133, 13, 485, NULL, NULL, 'image', 3, 0),
(134, 13, 486, NULL, NULL, 'image', 4, 0),
(135, 13, 487, NULL, NULL, 'image', 5, 0),
(136, 13, 488, NULL, NULL, 'image', 6, 0),
(137, 13, 489, NULL, NULL, 'image', 7, 0),
(138, 13, 490, NULL, NULL, 'image', 8, 0),
(139, 13, 491, NULL, NULL, 'image', 9, 0),
(140, 13, 492, NULL, NULL, 'image', 10, 0),
(141, 13, 493, NULL, NULL, 'image', 11, 0),
(142, 13, 494, NULL, NULL, 'image', 12, 0),
(143, 13, 495, NULL, NULL, 'image', 13, 0),
(144, 14, 496, NULL, NULL, 'image', 1, 1),
(145, 14, 497, NULL, NULL, 'image', 2, 0),
(146, 14, 498, NULL, NULL, 'image', 3, 0),
(147, 14, 499, NULL, NULL, 'image', 4, 0),
(148, 14, 500, NULL, NULL, 'image', 5, 0),
(149, 14, 501, NULL, NULL, 'image', 6, 0),
(150, 14, 502, NULL, NULL, 'image', 7, 0),
(151, 15, 503, NULL, NULL, 'image', 1, 1),
(152, 15, 504, NULL, NULL, 'image', 2, 0),
(153, 15, 505, NULL, NULL, 'image', 3, 0),
(154, 15, 506, NULL, NULL, 'image', 4, 0),
(155, 15, 507, NULL, NULL, 'image', 5, 0),
(156, 15, 508, NULL, NULL, 'image', 6, 0),
(157, 15, 509, NULL, NULL, 'image', 7, 0),
(158, 15, 510, NULL, NULL, 'image', 8, 0),
(159, 15, 511, NULL, NULL, 'image', 9, 0),
(160, 15, 512, NULL, NULL, 'image', 10, 0),
(161, 15, 513, NULL, NULL, 'image', 11, 0),
(162, 15, 514, NULL, NULL, 'image', 12, 0),
(163, 15, 515, NULL, NULL, 'image', 13, 0),
(164, 15, 516, NULL, NULL, 'image', 14, 0),
(165, 16, 517, NULL, NULL, 'image', 1, 1),
(166, 16, 518, NULL, NULL, 'image', 2, 0),
(167, 16, 519, NULL, NULL, 'image', 3, 0),
(168, 16, 520, NULL, NULL, 'image', 4, 0),
(169, 16, 521, NULL, NULL, 'image', 5, 0),
(170, 16, 522, NULL, NULL, 'image', 6, 0),
(171, 16, 523, NULL, NULL, 'image', 7, 0),
(172, 16, 524, NULL, NULL, 'image', 8, 0),
(173, 17, 525, NULL, NULL, 'image', 1, 1),
(174, 17, 526, NULL, NULL, 'image', 2, 0),
(175, 17, 527, NULL, NULL, 'image', 3, 0),
(176, 17, 528, NULL, NULL, 'image', 4, 0),
(177, 17, 529, NULL, NULL, 'image', 5, 0),
(178, 17, 530, NULL, NULL, 'image', 6, 0),
(179, 17, 531, NULL, NULL, 'image', 7, 0),
(180, 17, 532, NULL, NULL, 'image', 8, 0),
(181, 18, 533, NULL, NULL, 'image', 1, 1),
(182, 18, 534, NULL, NULL, 'image', 2, 0),
(183, 18, 535, NULL, NULL, 'image', 3, 0),
(184, 18, 536, NULL, NULL, 'image', 4, 0),
(185, 18, 537, NULL, NULL, 'image', 5, 0),
(186, 18, 538, NULL, NULL, 'image', 6, 0),
(187, 18, 539, NULL, NULL, 'image', 8, 0),
(188, 18, 540, NULL, NULL, 'image', 10, 0),
(189, 18, 541, NULL, NULL, 'image', 12, 0),
(190, 18, 542, NULL, NULL, 'image', 14, 0),
(191, 18, 543, NULL, NULL, 'image', 16, 0),
(192, 19, 544, NULL, NULL, 'image', 1, 1),
(193, 19, 545, NULL, NULL, 'image', 2, 0),
(194, 19, 546, NULL, NULL, 'image', 3, 0),
(195, 19, 547, NULL, NULL, 'image', 4, 0),
(196, 19, 548, NULL, NULL, 'image', 5, 0),
(197, 19, 549, NULL, NULL, 'image', 7, 0),
(198, 19, 550, NULL, NULL, 'image', 9, 0),
(199, 19, 551, NULL, NULL, 'image', 11, 0),
(200, 19, 552, NULL, NULL, 'image', 13, 0),
(201, 19, 553, NULL, NULL, 'image', 15, 0),
(202, 19, 554, NULL, NULL, 'image', 11, 0),
(203, 19, 555, NULL, NULL, 'image', 13, 0),
(204, 19, 556, NULL, NULL, 'image', 15, 0),
(205, 19, 557, NULL, NULL, 'image', 17, 0),
(206, 19, 558, NULL, NULL, 'image', 19, 0),
(207, 20, 559, NULL, NULL, 'image', 1, 1),
(208, 20, 560, NULL, NULL, 'image', 2, 0),
(209, 20, 561, NULL, NULL, 'image', 3, 0),
(210, 20, 562, NULL, NULL, 'image', 4, 0),
(211, 20, 563, NULL, NULL, 'image', 5, 0),
(212, 20, 564, NULL, NULL, 'image', 6, 0),
(213, 21, 565, NULL, NULL, 'image', 1, 1),
(214, 21, 566, NULL, NULL, 'image', 2, 0),
(215, 21, 567, NULL, NULL, 'image', 4, 0),
(216, 21, 568, NULL, NULL, 'image', 6, 0),
(217, 22, 569, NULL, NULL, 'image', 1, 1),
(218, 22, 570, NULL, NULL, 'image', 2, 0),
(219, 22, 571, NULL, NULL, 'image', 3, 0),
(220, 22, 572, NULL, NULL, 'image', 4, 0),
(221, 22, 573, NULL, NULL, 'image', 5, 0),
(222, 22, 574, NULL, NULL, 'image', 6, 0),
(223, 22, 575, NULL, NULL, 'image', 7, 0),
(224, 23, 576, NULL, NULL, 'image', 1, 1),
(225, 23, 577, NULL, NULL, 'image', 2, 0),
(226, 23, 578, NULL, NULL, 'image', 3, 0),
(227, 24, 579, NULL, NULL, 'image', 1, 1),
(228, 24, 580, NULL, NULL, 'image', 2, 0),
(229, 24, 581, NULL, NULL, 'image', 3, 0),
(230, 24, 582, NULL, NULL, 'image', 4, 0),
(231, 24, 583, NULL, NULL, 'image', 5, 0),
(232, 25, 584, NULL, NULL, 'image', 1, 1),
(233, 25, 585, NULL, NULL, 'image', 2, 0),
(234, 26, 586, NULL, NULL, 'image', 1, 1),
(235, 26, 587, NULL, NULL, 'image', 2, 0),
(236, 27, 588, NULL, NULL, 'image', 1, 1),
(237, 28, 589, NULL, NULL, 'image', 1, 1),
(238, 28, 590, NULL, NULL, 'image', 2, 0),
(239, 29, 591, NULL, NULL, 'image', 1, 1),
(240, 30, 592, NULL, NULL, 'image', 1, 1),
(241, 30, 593, NULL, NULL, 'image', 2, 0),
(242, 30, 594, NULL, NULL, 'image', 3, 0),
(243, 30, 595, NULL, NULL, 'image', 4, 0),
(244, 30, 596, NULL, NULL, 'image', 5, 0),
(245, 30, 597, NULL, NULL, 'image', 6, 0),
(246, 30, 598, NULL, NULL, 'image', 7, 0),
(247, 31, 599, NULL, NULL, 'image', 1, 1),
(248, 31, 600, NULL, NULL, 'image', 2, 0),
(249, 31, 601, NULL, NULL, 'image', 3, 0),
(250, 32, 602, NULL, NULL, 'image', 1, 1),
(251, 32, 603, NULL, NULL, 'image', 2, 0),
(252, 32, 604, NULL, NULL, 'image', 3, 0),
(253, 32, 605, NULL, NULL, 'image', 4, 0),
(254, 33, 606, NULL, NULL, 'image', 1, 1),
(255, 33, 607, NULL, NULL, 'image', 2, 0),
(256, 33, 608, NULL, NULL, 'image', 3, 0),
(257, 33, 609, NULL, NULL, 'image', 4, 0),
(258, 34, 610, NULL, NULL, 'image', 1, 1),
(259, 34, 611, NULL, NULL, 'image', 2, 0),
(260, 34, 612, NULL, NULL, 'image', 3, 0),
(261, 35, 613, NULL, NULL, 'image', 1, 1),
(262, 35, 614, NULL, NULL, 'image', 2, 0),
(263, 35, 615, NULL, NULL, 'image', 3, 0),
(264, 35, 616, NULL, NULL, 'image', 4, 0),
(265, 36, 617, NULL, NULL, 'image', 1, 1),
(266, 36, 618, NULL, NULL, 'image', 2, 0),
(267, 36, 619, NULL, NULL, 'image', 3, 0),
(268, 37, 620, NULL, NULL, 'image', 1, 1),
(269, 37, 621, NULL, NULL, 'image', 2, 0),
(270, 37, 622, NULL, NULL, 'image', 3, 0),
(271, 38, 623, NULL, NULL, 'image', 1, 1),
(272, 38, 624, NULL, NULL, 'image', 2, 0),
(273, 39, 625, NULL, NULL, 'image', 1, 1),
(274, 39, 626, NULL, NULL, 'image', 2, 0),
(275, 39, 627, NULL, NULL, 'image', 3, 0),
(276, 39, 628, NULL, NULL, 'image', 4, 0),
(277, 40, 629, NULL, NULL, 'image', 1, 1),
(278, 40, 630, NULL, NULL, 'image', 2, 0),
(279, 40, 631, NULL, NULL, 'image', 3, 0),
(280, 40, 632, NULL, NULL, 'image', 4, 0),
(281, 40, 633, NULL, NULL, 'image', 5, 0),
(282, 40, 634, NULL, NULL, 'image', 6, 0),
(283, 41, 635, NULL, NULL, 'image', 1, 1),
(284, 41, 636, NULL, NULL, 'image', 2, 0),
(285, 42, 637, NULL, NULL, 'image', 1, 1),
(286, 43, 638, NULL, NULL, 'image', 1, 1),
(287, 43, 639, NULL, NULL, 'image', 2, 0),
(288, 43, 640, NULL, NULL, 'image', 3, 0),
(289, 43, 641, NULL, NULL, 'image', 4, 0),
(290, 43, 642, NULL, NULL, 'image', 5, 0),
(291, 44, 643, NULL, NULL, 'image', 1, 1),
(292, 44, 644, NULL, NULL, 'image', 2, 0),
(293, 44, 645, NULL, NULL, 'image', 3, 0),
(294, 44, 646, NULL, NULL, 'image', 4, 0),
(295, 45, 647, NULL, NULL, 'image', 1, 1),
(296, 45, 648, NULL, NULL, 'image', 2, 0),
(297, 45, 649, NULL, NULL, 'image', 3, 0),
(298, 45, 650, NULL, NULL, 'image', 4, 0),
(299, 46, 651, NULL, NULL, 'image', 1, 1),
(300, 47, 652, NULL, NULL, 'image', 1, 1),
(301, 47, 653, NULL, NULL, 'image', 2, 0),
(302, 48, 654, NULL, NULL, 'image', 1, 1),
(303, 48, 655, NULL, NULL, 'image', 2, 0),
(304, 48, 656, NULL, NULL, 'image', 3, 0),
(305, 48, 657, NULL, NULL, 'image', 4, 0),
(306, 49, 658, NULL, NULL, 'image', 1, 1),
(307, 49, 659, NULL, NULL, 'image', 2, 0),
(308, 49, 660, NULL, NULL, 'image', 3, 0),
(309, 49, 661, NULL, NULL, 'image', 4, 0),
(310, 49, 662, NULL, NULL, 'image', 5, 0),
(311, 50, 663, NULL, NULL, 'image', 1, 1),
(312, 51, 664, NULL, NULL, 'image', 1, 1),
(313, 51, 665, NULL, NULL, 'image', 2, 0),
(314, 51, 666, NULL, NULL, 'image', 3, 0),
(315, 51, 667, NULL, NULL, 'image', 4, 0),
(316, 52, 668, NULL, NULL, 'image', 1, 1),
(317, 52, 669, NULL, NULL, 'image', 2, 0),
(318, 52, 670, NULL, NULL, 'image', 3, 0),
(319, 53, 671, NULL, NULL, 'image', 1, 1),
(320, 53, 672, NULL, NULL, 'image', 2, 0),
(321, 54, 673, NULL, NULL, 'image', 1, 1),
(322, 54, 674, NULL, NULL, 'image', 2, 0),
(323, 54, 675, NULL, NULL, 'image', 3, 0),
(324, 54, 676, NULL, NULL, 'image', 4, 0),
(325, 55, 677, NULL, NULL, 'image', 1, 1),
(326, 55, 678, NULL, NULL, 'image', 2, 0),
(327, 55, 679, NULL, NULL, 'image', 3, 0),
(328, 56, 680, NULL, NULL, 'image', 1, 1),
(329, 56, 681, NULL, NULL, 'image', 2, 0),
(330, 56, 682, NULL, NULL, 'image', 3, 0),
(331, 56, 683, NULL, NULL, 'image', 4, 0),
(332, 25, 684, NULL, NULL, 'video', 1, 0),
(333, 25, 685, NULL, NULL, 'video', 2, 0),
(334, 26, 686, NULL, NULL, 'video', 1, 0),
(335, 26, 687, NULL, NULL, 'video', 2, 0),
(336, 31, 688, NULL, NULL, 'video', 1, 0),
(337, 7, 689, NULL, NULL, 'video', 1, 0),
(338, 7, 690, NULL, NULL, 'video', 3, 0),
(339, 7, 691, NULL, NULL, 'video', 5, 0),
(340, 8, 692, NULL, NULL, 'video', 1, 0),
(341, 8, 693, NULL, NULL, 'video', 3, 0),
(342, 10, 694, NULL, NULL, 'video', 1, 0),
(343, 12, 695, NULL, NULL, 'video', 1, 0),
(344, 12, 696, NULL, NULL, 'video', 3, 0),
(345, 15, 697, NULL, NULL, 'video', 1, 0),
(346, 15, 698, NULL, NULL, 'video', 3, 0),
(347, 18, 699, NULL, NULL, 'video', 1, 0),
(348, 18, 700, NULL, NULL, 'video', 3, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_permissions`
--

CREATE TABLE `product_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_type_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `can_download` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `product_permissions`
--

INSERT INTO `product_permissions` (`id`, `product_id`, `user_type_id`, `can_view`, `can_download`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-03-10 20:49:45', '2026-03-10 20:49:45'),
(17, 2, 1, 1, 1, '2026-03-11 23:07:41', '2026-03-11 23:07:41'),
(26, 2, 2, 1, 1, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(27, 2, 3, 0, 0, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(28, 2, 4, 0, 0, '2026-03-11 23:36:22', '2026-03-11 23:36:22'),
(29, 3, 1, 1, 1, '2026-03-12 00:18:28', '2026-03-12 00:18:28'),
(41, 4, 1, 1, 1, '2026-03-12 00:39:16', '2026-03-12 00:39:16'),
(53, 5, 1, 1, 1, '2026-03-30 05:36:17', '2026-03-30 05:36:17'),
(57, 6, 1, 1, 1, '2026-03-30 05:38:54', '2026-03-30 05:38:54'),
(61, 7, 1, 1, 1, '2026-03-30 05:45:07', '2026-03-30 05:45:07'),
(65, 8, 1, 1, 1, '2026-03-30 05:48:30', '2026-03-30 05:48:30'),
(69, 9, 1, 1, 1, '2026-03-30 05:49:23', '2026-03-30 05:49:23'),
(77, 10, 1, 1, 1, '2026-03-30 05:53:42', '2026-03-30 05:53:42'),
(81, 11, 1, 1, 1, '2026-03-30 05:55:10', '2026-03-30 05:55:10'),
(85, 12, 1, 1, 1, '2026-03-30 05:58:52', '2026-03-30 05:58:52'),
(89, 13, 1, 1, 1, '2026-03-30 06:00:23', '2026-03-30 06:00:23'),
(93, 14, 1, 1, 1, '2026-03-30 06:37:45', '2026-03-30 06:37:45'),
(97, 15, 1, 1, 1, '2026-03-30 06:50:44', '2026-03-30 06:50:44'),
(106, 1, 2, 1, 1, '2026-03-30 06:52:47', '2026-03-30 06:52:47'),
(107, 1, 3, 0, 0, '2026-03-30 06:52:47', '2026-03-30 06:52:47'),
(108, 1, 4, 0, 0, '2026-03-30 06:52:47', '2026-03-30 06:52:47'),
(110, 4, 2, 1, 1, '2026-03-30 06:53:29', '2026-03-30 06:53:29'),
(111, 4, 3, 0, 0, '2026-03-30 06:53:29', '2026-03-30 06:53:29'),
(112, 4, 4, 0, 0, '2026-03-30 06:53:29', '2026-03-30 06:53:29'),
(113, 16, 1, 1, 1, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(114, 16, 2, 0, 0, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(115, 16, 3, 0, 0, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(116, 16, 4, 0, 0, '2026-03-30 07:06:03', '2026-03-30 07:06:03'),
(117, 17, 1, 1, 1, '2026-03-30 07:16:21', '2026-03-30 07:16:21'),
(121, 18, 1, 1, 1, '2026-03-30 18:40:44', '2026-03-30 18:40:44'),
(129, 19, 1, 1, 1, '2026-03-30 18:48:37', '2026-03-30 18:48:37'),
(141, 20, 1, 1, 1, '2026-03-30 19:05:10', '2026-03-30 19:05:10'),
(145, 21, 1, 1, 1, '2026-03-30 19:09:09', '2026-03-30 19:09:09'),
(153, 22, 1, 1, 1, '2026-03-30 19:17:32', '2026-03-30 19:17:32'),
(157, 23, 1, 1, 1, '2026-03-30 19:19:26', '2026-03-30 19:19:26'),
(161, 24, 1, 1, 1, '2026-03-30 19:22:55', '2026-03-30 19:22:55'),
(165, 25, 1, 1, 1, '2026-03-30 19:32:21', '2026-03-30 19:32:21'),
(169, 26, 1, 1, 1, '2026-03-30 19:36:30', '2026-03-30 19:36:30'),
(173, 27, 1, 1, 1, '2026-03-30 19:47:43', '2026-03-30 19:47:43'),
(177, 28, 1, 1, 1, '2026-03-30 19:53:02', '2026-03-30 19:53:02'),
(181, 29, 1, 1, 1, '2026-03-30 19:56:35', '2026-03-30 19:56:35'),
(185, 30, 1, 1, 1, '2026-03-30 20:00:23', '2026-03-30 20:00:23'),
(189, 31, 1, 1, 1, '2026-03-30 20:13:04', '2026-03-30 20:13:04'),
(193, 32, 1, 1, 1, '2026-03-30 20:16:02', '2026-03-30 20:16:02'),
(197, 33, 1, 1, 1, '2026-03-30 20:18:02', '2026-03-30 20:18:02'),
(205, 34, 1, 1, 1, '2026-03-30 20:20:40', '2026-03-30 20:20:40'),
(209, 35, 1, 1, 1, '2026-03-30 20:32:56', '2026-03-30 20:32:56'),
(213, 36, 1, 1, 1, '2026-03-30 20:35:24', '2026-03-30 20:35:24'),
(217, 37, 1, 1, 1, '2026-03-30 20:36:13', '2026-03-30 20:36:13'),
(221, 38, 1, 1, 1, '2026-03-30 20:37:47', '2026-03-30 20:37:47'),
(233, 39, 1, 1, 1, '2026-03-30 23:12:05', '2026-03-30 23:12:05'),
(238, 3, 2, 1, 1, '2026-03-30 23:14:39', '2026-03-30 23:14:39'),
(239, 3, 3, 0, 0, '2026-03-30 23:14:39', '2026-03-30 23:14:39'),
(240, 3, 4, 0, 0, '2026-03-30 23:14:39', '2026-03-30 23:14:39'),
(241, 40, 1, 1, 1, '2026-03-30 23:16:13', '2026-03-30 23:16:13'),
(245, 41, 1, 1, 1, '2026-03-30 23:17:12', '2026-03-30 23:17:12'),
(249, 42, 1, 1, 1, '2026-03-30 23:19:09', '2026-03-30 23:19:09'),
(253, 43, 1, 1, 1, '2026-03-30 23:19:54', '2026-03-30 23:19:54'),
(257, 44, 1, 1, 1, '2026-03-31 03:03:54', '2026-03-31 03:03:54'),
(261, 45, 1, 1, 1, '2026-03-31 03:04:44', '2026-03-31 03:04:44'),
(265, 46, 1, 1, 1, '2026-03-31 03:05:20', '2026-03-31 03:05:20'),
(269, 47, 1, 1, 1, '2026-03-31 03:06:29', '2026-03-31 03:06:29'),
(273, 48, 1, 1, 1, '2026-03-31 03:13:31', '2026-03-31 03:13:31'),
(277, 49, 1, 1, 1, '2026-03-31 03:14:14', '2026-03-31 03:14:14'),
(281, 50, 1, 1, 1, '2026-03-31 03:14:41', '2026-03-31 03:14:41'),
(285, 51, 1, 1, 1, '2026-03-31 03:21:20', '2026-03-31 03:21:20'),
(289, 52, 1, 1, 1, '2026-03-31 03:21:58', '2026-03-31 03:21:58'),
(293, 53, 1, 1, 1, '2026-03-31 03:22:36', '2026-03-31 03:22:36'),
(297, 54, 1, 1, 1, '2026-03-31 03:45:49', '2026-03-31 03:45:49'),
(301, 55, 1, 1, 1, '2026-03-31 03:46:19', '2026-03-31 03:46:19'),
(305, 56, 1, 1, 1, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(306, 56, 2, 1, 1, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(307, 56, 3, 0, 0, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(308, 56, 4, 0, 0, '2026-03-31 03:47:30', '2026-03-31 03:47:30'),
(310, 55, 2, 1, 1, '2026-03-31 03:48:19', '2026-03-31 03:48:19'),
(311, 55, 3, 0, 0, '2026-03-31 03:48:19', '2026-03-31 03:48:19'),
(312, 55, 4, 0, 0, '2026-03-31 03:48:19', '2026-03-31 03:48:19'),
(314, 54, 2, 1, 1, '2026-03-31 03:48:28', '2026-03-31 03:48:28'),
(315, 54, 3, 0, 0, '2026-03-31 03:48:28', '2026-03-31 03:48:28'),
(316, 54, 4, 0, 0, '2026-03-31 03:48:28', '2026-03-31 03:48:28'),
(318, 53, 2, 1, 1, '2026-03-31 03:48:35', '2026-03-31 03:48:35'),
(319, 53, 3, 0, 0, '2026-03-31 03:48:35', '2026-03-31 03:48:35'),
(320, 53, 4, 0, 0, '2026-03-31 03:48:35', '2026-03-31 03:48:35'),
(322, 52, 2, 1, 1, '2026-03-31 03:48:46', '2026-03-31 03:48:46'),
(323, 52, 3, 0, 0, '2026-03-31 03:48:46', '2026-03-31 03:48:46'),
(324, 52, 4, 0, 0, '2026-03-31 03:48:46', '2026-03-31 03:48:46'),
(326, 51, 2, 1, 1, '2026-03-31 03:48:54', '2026-03-31 03:48:54'),
(327, 51, 3, 0, 0, '2026-03-31 03:48:54', '2026-03-31 03:48:54'),
(328, 51, 4, 0, 0, '2026-03-31 03:48:54', '2026-03-31 03:48:54'),
(330, 50, 2, 1, 1, '2026-03-31 03:49:00', '2026-03-31 03:49:00'),
(331, 50, 3, 0, 0, '2026-03-31 03:49:00', '2026-03-31 03:49:00'),
(332, 50, 4, 0, 0, '2026-03-31 03:49:00', '2026-03-31 03:49:00'),
(334, 49, 2, 1, 1, '2026-03-31 03:49:07', '2026-03-31 03:49:07'),
(335, 49, 3, 0, 0, '2026-03-31 03:49:07', '2026-03-31 03:49:07'),
(336, 49, 4, 0, 0, '2026-03-31 03:49:07', '2026-03-31 03:49:07'),
(338, 48, 2, 1, 1, '2026-03-31 03:49:14', '2026-03-31 03:49:14'),
(339, 48, 3, 0, 0, '2026-03-31 03:49:14', '2026-03-31 03:49:14'),
(340, 48, 4, 0, 0, '2026-03-31 03:49:14', '2026-03-31 03:49:14'),
(342, 47, 2, 1, 1, '2026-03-31 03:50:16', '2026-03-31 03:50:16'),
(343, 47, 3, 0, 0, '2026-03-31 03:50:16', '2026-03-31 03:50:16'),
(344, 47, 4, 0, 0, '2026-03-31 03:50:16', '2026-03-31 03:50:16'),
(346, 46, 2, 1, 1, '2026-03-31 03:50:20', '2026-03-31 03:50:20'),
(347, 46, 3, 0, 0, '2026-03-31 03:50:20', '2026-03-31 03:50:20'),
(348, 46, 4, 0, 0, '2026-03-31 03:50:20', '2026-03-31 03:50:20'),
(350, 45, 2, 1, 1, '2026-03-31 03:50:24', '2026-03-31 03:50:24'),
(351, 45, 3, 0, 0, '2026-03-31 03:50:24', '2026-03-31 03:50:24'),
(352, 45, 4, 0, 0, '2026-03-31 03:50:24', '2026-03-31 03:50:24'),
(354, 44, 2, 1, 1, '2026-03-31 03:50:27', '2026-03-31 03:50:27'),
(355, 44, 3, 0, 0, '2026-03-31 03:50:27', '2026-03-31 03:50:27'),
(356, 44, 4, 0, 0, '2026-03-31 03:50:27', '2026-03-31 03:50:27'),
(358, 43, 2, 1, 1, '2026-03-31 03:50:31', '2026-03-31 03:50:31'),
(359, 43, 3, 0, 0, '2026-03-31 03:50:31', '2026-03-31 03:50:31'),
(360, 43, 4, 0, 0, '2026-03-31 03:50:31', '2026-03-31 03:50:31'),
(362, 42, 2, 1, 1, '2026-03-31 03:50:35', '2026-03-31 03:50:35'),
(363, 42, 3, 0, 0, '2026-03-31 03:50:35', '2026-03-31 03:50:35'),
(364, 42, 4, 0, 0, '2026-03-31 03:50:35', '2026-03-31 03:50:35'),
(366, 41, 2, 1, 1, '2026-03-31 06:34:40', '2026-03-31 06:34:40'),
(367, 41, 3, 0, 0, '2026-03-31 06:34:40', '2026-03-31 06:34:40'),
(368, 41, 4, 0, 0, '2026-03-31 06:34:40', '2026-03-31 06:34:40'),
(370, 40, 2, 1, 1, '2026-03-31 06:34:45', '2026-03-31 06:34:45'),
(371, 40, 3, 0, 0, '2026-03-31 06:34:45', '2026-03-31 06:34:45'),
(372, 40, 4, 0, 0, '2026-03-31 06:34:45', '2026-03-31 06:34:45'),
(374, 39, 2, 1, 1, '2026-03-31 06:34:49', '2026-03-31 06:34:49'),
(375, 39, 3, 0, 0, '2026-03-31 06:34:49', '2026-03-31 06:34:49'),
(376, 39, 4, 0, 0, '2026-03-31 06:34:49', '2026-03-31 06:34:49'),
(378, 38, 2, 1, 1, '2026-03-31 06:34:53', '2026-03-31 06:34:53'),
(379, 38, 3, 0, 0, '2026-03-31 06:34:53', '2026-03-31 06:34:53'),
(380, 38, 4, 0, 0, '2026-03-31 06:34:53', '2026-03-31 06:34:53'),
(382, 37, 2, 1, 1, '2026-03-31 06:34:57', '2026-03-31 06:34:57'),
(383, 37, 3, 0, 0, '2026-03-31 06:34:57', '2026-03-31 06:34:57'),
(384, 37, 4, 0, 0, '2026-03-31 06:34:57', '2026-03-31 06:34:57'),
(386, 36, 2, 1, 1, '2026-03-31 06:35:00', '2026-03-31 06:35:00'),
(387, 36, 3, 0, 0, '2026-03-31 06:35:00', '2026-03-31 06:35:00'),
(388, 36, 4, 0, 0, '2026-03-31 06:35:00', '2026-03-31 06:35:00'),
(390, 35, 2, 1, 1, '2026-03-31 06:35:04', '2026-03-31 06:35:04'),
(391, 35, 3, 0, 0, '2026-03-31 06:35:04', '2026-03-31 06:35:04'),
(392, 35, 4, 0, 0, '2026-03-31 06:35:04', '2026-03-31 06:35:04'),
(394, 34, 2, 1, 1, '2026-03-31 06:35:09', '2026-03-31 06:35:09'),
(395, 34, 3, 0, 0, '2026-03-31 06:35:09', '2026-03-31 06:35:09'),
(396, 34, 4, 0, 0, '2026-03-31 06:35:09', '2026-03-31 06:35:09'),
(398, 33, 2, 1, 1, '2026-03-31 06:35:12', '2026-03-31 06:35:12'),
(399, 33, 3, 0, 0, '2026-03-31 06:35:12', '2026-03-31 06:35:12'),
(400, 33, 4, 0, 0, '2026-03-31 06:35:12', '2026-03-31 06:35:12'),
(422, 27, 2, 1, 1, '2026-03-31 06:35:35', '2026-03-31 06:35:35'),
(423, 27, 3, 0, 0, '2026-03-31 06:35:35', '2026-03-31 06:35:35'),
(424, 27, 4, 0, 0, '2026-03-31 06:35:35', '2026-03-31 06:35:35'),
(430, 30, 2, 1, 1, '2026-03-31 06:36:42', '2026-03-31 06:36:42'),
(431, 30, 3, 0, 0, '2026-03-31 06:36:42', '2026-03-31 06:36:42'),
(432, 30, 4, 0, 0, '2026-03-31 06:36:42', '2026-03-31 06:36:42'),
(434, 29, 2, 1, 1, '2026-03-31 06:36:49', '2026-03-31 06:36:49'),
(435, 29, 3, 0, 0, '2026-03-31 06:36:49', '2026-03-31 06:36:49'),
(436, 29, 4, 0, 0, '2026-03-31 06:36:49', '2026-03-31 06:36:49'),
(438, 28, 2, 1, 1, '2026-03-31 06:36:55', '2026-03-31 06:36:55'),
(439, 28, 3, 0, 0, '2026-03-31 06:36:55', '2026-03-31 06:36:55'),
(440, 28, 4, 0, 0, '2026-03-31 06:36:55', '2026-03-31 06:36:55'),
(450, 24, 2, 1, 1, '2026-03-31 06:39:36', '2026-03-31 06:39:36'),
(451, 24, 3, 0, 0, '2026-03-31 06:39:36', '2026-03-31 06:39:36'),
(452, 24, 4, 0, 0, '2026-03-31 06:39:36', '2026-03-31 06:39:36'),
(454, 23, 2, 1, 1, '2026-03-31 06:39:42', '2026-03-31 06:39:42'),
(455, 23, 3, 0, 0, '2026-03-31 06:39:42', '2026-03-31 06:39:42'),
(456, 23, 4, 0, 0, '2026-03-31 06:39:42', '2026-03-31 06:39:42'),
(458, 22, 2, 1, 1, '2026-03-31 06:40:46', '2026-03-31 06:40:46'),
(459, 22, 3, 0, 0, '2026-03-31 06:40:46', '2026-03-31 06:40:46'),
(460, 22, 4, 0, 0, '2026-03-31 06:40:46', '2026-03-31 06:40:46'),
(462, 21, 2, 1, 1, '2026-03-31 06:40:52', '2026-03-31 06:40:52'),
(463, 21, 3, 0, 0, '2026-03-31 06:40:52', '2026-03-31 06:40:52'),
(464, 21, 4, 0, 0, '2026-03-31 06:40:52', '2026-03-31 06:40:52'),
(466, 20, 2, 1, 1, '2026-03-31 06:40:57', '2026-03-31 06:40:57'),
(467, 20, 3, 0, 0, '2026-03-31 06:40:57', '2026-03-31 06:40:57'),
(468, 20, 4, 0, 0, '2026-03-31 06:40:57', '2026-03-31 06:40:57'),
(470, 19, 2, 1, 1, '2026-03-31 06:41:01', '2026-03-31 06:41:01'),
(471, 19, 3, 0, 0, '2026-03-31 06:41:01', '2026-03-31 06:41:01'),
(472, 19, 4, 0, 0, '2026-03-31 06:41:01', '2026-03-31 06:41:01'),
(478, 17, 2, 1, 1, '2026-03-31 06:41:13', '2026-03-31 06:41:13'),
(479, 17, 3, 0, 0, '2026-03-31 06:41:13', '2026-03-31 06:41:13'),
(480, 17, 4, 0, 0, '2026-03-31 06:41:13', '2026-03-31 06:41:13'),
(486, 14, 2, 1, 1, '2026-03-31 06:41:26', '2026-03-31 06:41:26'),
(487, 14, 3, 0, 0, '2026-03-31 06:41:26', '2026-03-31 06:41:26'),
(488, 14, 4, 0, 0, '2026-03-31 06:41:26', '2026-03-31 06:41:26'),
(498, 11, 2, 1, 1, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(499, 11, 3, 0, 0, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(500, 11, 4, 0, 0, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(506, 9, 2, 1, 1, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(507, 9, 3, 0, 0, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(508, 9, 4, 0, 0, '2026-03-31 06:43:09', '2026-03-31 06:43:09'),
(518, 6, 2, 1, 1, '2026-03-31 06:43:45', '2026-03-31 06:43:45'),
(519, 6, 3, 0, 0, '2026-03-31 06:43:45', '2026-03-31 06:43:45'),
(520, 6, 4, 0, 0, '2026-03-31 06:43:45', '2026-03-31 06:43:45'),
(522, 5, 2, 1, 1, '2026-03-31 06:43:50', '2026-03-31 06:43:50'),
(523, 5, 3, 0, 0, '2026-03-31 06:43:50', '2026-03-31 06:43:50'),
(524, 5, 4, 0, 0, '2026-03-31 06:43:50', '2026-03-31 06:43:50'),
(530, 25, 2, 1, 1, '2026-03-31 07:18:30', '2026-03-31 07:18:30'),
(531, 25, 3, 0, 0, '2026-03-31 07:18:30', '2026-03-31 07:18:30'),
(532, 25, 4, 0, 0, '2026-03-31 07:18:30', '2026-03-31 07:18:30'),
(538, 26, 2, 1, 1, '2026-03-31 07:28:17', '2026-03-31 07:28:17'),
(539, 26, 3, 0, 0, '2026-03-31 07:28:17', '2026-03-31 07:28:17'),
(540, 26, 4, 0, 0, '2026-03-31 07:28:17', '2026-03-31 07:28:17'),
(542, 32, 2, 1, 1, '2026-03-31 07:32:20', '2026-03-31 07:32:20'),
(543, 32, 3, 0, 0, '2026-03-31 07:32:20', '2026-03-31 07:32:20'),
(544, 32, 4, 0, 0, '2026-03-31 07:32:20', '2026-03-31 07:32:20'),
(546, 31, 2, 1, 1, '2026-03-31 07:35:04', '2026-03-31 07:35:04'),
(547, 31, 3, 0, 0, '2026-03-31 07:35:04', '2026-03-31 07:35:04'),
(548, 31, 4, 0, 0, '2026-03-31 07:35:04', '2026-03-31 07:35:04'),
(550, 13, 2, 1, 1, '2026-03-31 07:45:07', '2026-03-31 07:45:07'),
(551, 13, 3, 0, 0, '2026-03-31 07:45:07', '2026-03-31 07:45:07'),
(552, 13, 4, 0, 0, '2026-03-31 07:45:07', '2026-03-31 07:45:07'),
(554, 7, 2, 1, 1, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(555, 7, 3, 0, 0, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(556, 7, 4, 0, 0, '2026-03-31 07:47:41', '2026-03-31 07:47:41'),
(558, 8, 2, 1, 1, '2026-03-31 07:48:17', '2026-03-31 07:48:17'),
(559, 8, 3, 0, 0, '2026-03-31 07:48:17', '2026-03-31 07:48:17'),
(560, 8, 4, 0, 0, '2026-03-31 07:48:17', '2026-03-31 07:48:17'),
(562, 10, 2, 1, 1, '2026-03-31 07:48:49', '2026-03-31 07:48:49'),
(563, 10, 3, 0, 0, '2026-03-31 07:48:49', '2026-03-31 07:48:49'),
(564, 10, 4, 0, 0, '2026-03-31 07:48:49', '2026-03-31 07:48:49'),
(566, 12, 2, 1, 1, '2026-03-31 08:01:33', '2026-03-31 08:01:33'),
(567, 12, 3, 0, 0, '2026-03-31 08:01:33', '2026-03-31 08:01:33'),
(568, 12, 4, 0, 0, '2026-03-31 08:01:33', '2026-03-31 08:01:33'),
(570, 15, 2, 1, 1, '2026-03-31 08:06:26', '2026-03-31 08:06:26'),
(571, 15, 3, 0, 0, '2026-03-31 08:06:26', '2026-03-31 08:06:26'),
(572, 15, 4, 0, 0, '2026-03-31 08:06:26', '2026-03-31 08:06:26'),
(574, 18, 2, 1, 1, '2026-03-31 08:09:13', '2026-03-31 08:09:13'),
(575, 18, 3, 0, 0, '2026-03-31 08:09:13', '2026-03-31 08:09:13'),
(576, 18, 4, 0, 0, '2026-03-31 08:09:13', '2026-03-31 08:09:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `product_series`
--

CREATE TABLE `product_series` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `product_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `product_series`
--

INSERT INTO `product_series` (`id`, `name`, `description`, `status`, `product_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Série 900', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(2, 'Série 700', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(3, 'Série 500', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(4, 'Série 300', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(5, 'Série 200', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26'),
(6, 'Série 100', NULL, 'active', 1, '2025-08-27 22:31:26', '2025-08-27 22:31:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('lYRAWoBQWU70SdX6fhhvvmlPbw5LSUbYBVedQXzm', 5, '177.40.239.231', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFg5VU84T1AyVnVLaFRRUTU3MW9SSUpCTER3V2F4em5xaHZUOFc3bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTEyOiJodHRwczovL3d3dy51bml2ZXJzaWRhZGVvcnRob2NyaW4uY29tLmJyL3ByaXZhdGUvcHJvZHVjdHMvNTEvaW1hZ2VzL2RXdXpFZmduUUVOWUNsQ2dybGdHRzlWR1VkSWU2WmZGbFBCUFhiSEUucG5nIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9', 1774995900),
('MgfztGRDWm7U4KkNGUgpS4nIgPNrfFHNBkmBtFSB', NULL, '205.210.31.50', 'Hello from Palo Alto Networks, find out more about our scans in https://docs-cortex.paloaltonetworks.com/r/1/Cortex-Xpanse/Scanning-activity', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibXJzVUt5b2NyaFUxYWw3WHhPY0dMb0ZnU010U2NsMnRMZ3N3R1RvaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly91bml2ZXJzaWRhZGVvcnRob2NyaW4uY29tLmJyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1775002805);

-- --------------------------------------------------------

--
-- Estrutura para tabela `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `training_category_id` bigint(20) UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content_type` enum('pdf','video') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pdf',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `trainings`
--

INSERT INTO `trainings` (`id`, `name`, `training_category_id`, `description`, `content_type`, `status`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 'Treinamento - Franquia - Colchão Polaris Plus Pillow Top (10/09/2025)', 2, NULL, 'pdf', 'active', '2026-03-10 05:38:45', '2026-03-10 05:38:45', NULL),
(2, 'Treinamento – Franquia – TECNOLOGIAS SÉRIE 703 e SERIE 509 (21/08/2025)', 2, NULL, 'pdf', 'active', '2026-03-10 06:30:47', '2026-03-10 06:30:47', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `training_categories`
--

CREATE TABLE `training_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `training_categories`
--

INSERT INTO `training_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Marketing e Vendas', 'Treinamentos de marketing e técnicas de vendas', 'active', '2025-08-27 22:31:57', '2025-08-27 22:31:57'),
(2, 'Treinamentos Virtuais', 'Treinamentos online e virtuais', 'active', '2025-08-27 22:31:57', '2025-08-27 22:31:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `training_files`
--

CREATE TABLE `training_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `training_files`
--

INSERT INTO `training_files` (`id`, `training_id`, `file_id`, `created_at`, `updated_at`, `file_type`, `sort_order`, `is_primary`) VALUES
(2, 1, 334, NULL, NULL, 'video', 0, 1),
(3, 1, 335, NULL, NULL, 'pdf', 0, 1),
(4, 2, 336, NULL, NULL, 'video', 0, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `training_permissions`
--

CREATE TABLE `training_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `user_type_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `can_download` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `training_permissions`
--

INSERT INTO `training_permissions` (`id`, `training_id`, `user_type_id`, `can_view`, `can_download`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2026-03-10 05:38:45', '2026-03-10 05:38:45'),
(13, 2, 1, 1, 1, '2026-03-10 06:30:47', '2026-03-10 06:30:47'),
(22, 1, 2, 1, 0, '2026-03-13 15:33:01', '2026-03-13 15:33:01'),
(23, 1, 3, 0, 0, '2026-03-13 15:33:01', '2026-03-13 15:33:01'),
(24, 1, 4, 0, 0, '2026-03-13 15:33:01', '2026-03-13 15:33:01'),
(26, 2, 2, 1, 0, '2026-03-13 15:33:09', '2026-03-13 15:33:09'),
(27, 2, 3, 0, 0, '2026-03-13 15:33:09', '2026-03-13 15:33:09'),
(28, 2, 4, 0, 0, '2026-03-13 15:33:09', '2026-03-13 15:33:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ui_visibilities`
--

CREATE TABLE `ui_visibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `feature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type_id` bigint(20) UNSIGNED NOT NULL,
  `can_view` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ui_visibilities`
--

INSERT INTO `ui_visibilities` (`id`, `feature`, `user_type_id`, `can_view`, `created_at`, `updated_at`) VALUES
(1, 'menu_marketing', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(2, 'menu_produtos', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(3, 'menu_biblioteca', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(4, 'menu_treinamentos', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(5, 'menu_news', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(6, 'banner_marketing', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(7, 'banner_produtos', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(8, 'bloco_marketing', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(9, 'menu_marketing', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(10, 'menu_produtos', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(11, 'menu_biblioteca', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(12, 'menu_treinamentos', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(13, 'menu_news', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(14, 'banner_marketing', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(15, 'banner_produtos', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(16, 'bloco_marketing', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(17, 'menu_produtos', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(18, 'menu_produtos', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(19, 'menu_biblioteca', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(20, 'menu_biblioteca', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(21, 'menu_treinamentos', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(22, 'menu_treinamentos', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(23, 'menu_news', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(24, 'menu_news', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(25, 'menu_marketing', 3, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(26, 'menu_marketing', 4, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(27, 'banner_marketing', 3, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(28, 'banner_marketing', 4, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(29, 'bloco_marketing', 3, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(30, 'bloco_marketing', 4, 0, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(31, 'bloco_produtos', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(32, 'bloco_produtos', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(33, 'bloco_produtos', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(34, 'bloco_produtos', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(35, 'bloco_biblioteca', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(36, 'bloco_biblioteca', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(37, 'bloco_biblioteca', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(38, 'bloco_biblioteca', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(39, 'bloco_treinamentos', 1, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(40, 'bloco_treinamentos', 2, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(41, 'bloco_treinamentos', 3, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15'),
(42, 'bloco_treinamentos', 4, 1, '2025-10-13 20:31:15', '2025-10-13 20:31:15');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_access` timestamp NULL DEFAULT NULL COMMENT 'Último acesso do usuário ao sistema',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `representante_nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nome do Representante',
  `nome_fantasia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nome Fantasia',
  `razao_social` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Razão Social',
  `cpf_cnpj` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CPF ou CNPJ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `user_type_id`, `last_access`, `status`, `representante_nome`, `nome_fantasia`, `razao_social`, `cpf_cnpj`) VALUES
(1, 'Administrador', 'ronaldogademar@gmail.com', '2025-06-13 19:58:33', '$2y$12$AG/CLMycuAKYP5tXR1034.XsSsudVwr/e/OINTPBJX6KUdeRviP0m', '1A20R3g0tuYTGKjeSf23mLHGoNQQBGEoWyW0Une9kihgzF7ZJVkpqFtEC1He', '2025-06-13 19:58:33', '2026-03-09 14:32:43', 1, '2025-10-14 00:47:07', 'active', NULL, NULL, NULL, NULL),
(2, 'Franqueado', 'franqueado@uniorthocrin.com', '2025-06-13 19:58:34', '$2y$12$kXjHysmVxNKlA8WRBsXXj.xw4Qez9jxg39i6.rWpjpxXo1ga1UO7O', 'MrFsOnO7ZXSePLOiRsoyIPfONpSA3uLR3ObA0T2SgdRA4CO5mwjm0lbEd9Sd', '2025-06-13 19:58:34', '2025-08-26 15:08:04', 2, NULL, 'active', NULL, NULL, NULL, NULL),
(3, 'Lojista', 'lojista@uniorthocrin.com', '2025-06-13 19:58:34', '$2y$12$kXjHysmVxNKlA8WRBsXXj.xw4Qez9jxg39i6.rWpjpxXo1ga1UO7O', 'qychHdezHg', '2025-06-13 19:58:34', '2025-10-17 01:20:53', 3, NULL, 'active', NULL, 'Minha Loja', 'Minha Loja', '25036388000101'),
(4, 'Representante', 'representante@uniorthocrin.com', '2025-06-13 19:58:34', '$2y$12$kXjHysmVxNKlA8WRBsXXj.xw4Qez9jxg39i6.rWpjpxXo1ga1UO7O', 'gF3PRh9iwmyT09PAby73WdIPRHwJFPPPOOzx688sBmPh7dAhdR6DW9OWiP1d', '2025-06-13 19:58:34', '2025-08-26 15:08:04', 4, NULL, 'active', NULL, NULL, NULL, NULL),
(5, 'Silas Pereira', 'silas@penta.com.br', NULL, '$2y$12$PWdf9OHllt75XHtejIWEPeynFUMIriEn.xbxb1Jt17LJ28Qf.RvP.', NULL, '2026-02-26 20:39:34', '2026-02-26 20:39:34', 1, NULL, 'active', NULL, NULL, NULL, NULL),
(7, 'Marketing', 'mkt2@orthocrin.com.br', NULL, '$2y$12$j//Al4oNPqToC0P5yhoxseryJsunqkt3L5m4OLi.QxRtW5Lw84Pfa', NULL, '2026-03-12 17:46:39', '2026-03-12 17:46:39', 2, NULL, 'active', NULL, 'ORTHOCRIN', 'ORTHOCRIN', '17.245.986/0001-62'),
(8, 'ANGÉLICA RENATA MENDES MAIA', 'orthocrinsantaamelia@gmail.com', NULL, '$2y$12$EcdvQ3JqTa8b/1bXXLIOQe0od84d0MRvSTDRPUyGMKbAHv3rVc4DO', NULL, '2026-03-14 18:42:51', '2026-03-14 18:42:51', 2, NULL, 'active', NULL, NULL, NULL, NULL),
(9, 'Emily Victoria Souza Ferreira', 'victoriaferreira372@gmail.com', NULL, '$2y$12$dua/D2L1sXKy1i7BK1MmS.6rC7ONEIWSXqkXbIrMYsTZKFQkI2fP.', NULL, '2026-03-17 23:14:16', '2026-03-17 23:14:16', 3, NULL, 'active', NULL, NULL, NULL, NULL),
(10, 'Natalia cristine de souza', 'vialapamoveis@yahoo.com.br', NULL, '$2y$12$Z6CdtyfjLj4Cx4j2SXCKi.IZISfqcSxfjBgiIZquI70Cf1Oof9GYG', NULL, '2026-03-20 18:19:32', '2026-03-20 18:19:32', 2, NULL, 'active', NULL, NULL, NULL, NULL),
(11, 'solange das neves silva', 'orthocrinvisao@yahoo.com.br', NULL, '$2y$12$LDTS9LCrFQIkoABFaJIkWOHMbqH0aNUHpH1h1lJ5hue0PFIxeZvXm', NULL, '2026-03-27 02:00:03', '2026-03-27 02:00:03', 2, NULL, 'active', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `related_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint(20) UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `user_notifications`
--

INSERT INTO `user_notifications` (`id`, `user_id`, `title`, `message`, `type`, `related_type`, `related_id`, `read_at`, `created_at`, `updated_at`) VALUES
(4, 2, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Teste novo Produto', 'info', 'App\\Models\\Product', 9, '2025-10-15 02:10:46', '2025-10-15 01:42:05', '2025-10-15 02:10:46'),
(5, 3, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Teste novo Produto', 'info', 'App\\Models\\Product', 9, '2025-10-17 01:17:51', '2025-10-15 01:42:05', '2025-10-17 01:17:51'),
(6, 4, 'Novo Produto Disponível', 'Um novo produto foi adicionado: Teste novo Produto', 'info', 'App\\Models\\Product', 9, NULL, '2025-10-15 01:42:05', '2025-10-15 01:42:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_types`
--

CREATE TABLE `user_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `description`, `level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Acesso total ao sistema', 99, 'active', '2025-06-13 19:58:33', '2025-06-13 19:58:33'),
(2, 'Franqueado', 'Para franqueados', 1, 'active', '2025-06-13 19:58:33', '2025-06-13 19:58:33'),
(3, 'Lojista', 'Para lojistas', 1, 'active', '2025-06-13 19:58:33', '2025-06-13 19:58:33'),
(4, 'Representante', 'Para representantes', 1, 'active', '2025-06-13 19:58:33', '2025-06-13 19:58:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `user_views`
--

CREATE TABLE `user_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `viewable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `viewable_id` bigint(20) UNSIGNED NOT NULL,
  `first_viewed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_viewed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `view_count` int(11) NOT NULL DEFAULT '1',
  `download_count` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `access_history`
--
ALTER TABLE `access_history`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_resource_type_resource_id_index` (`resource_type`,`resource_id`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `audit_logs_status_created_at_index` (`status`,`created_at`),
  ADD KEY `audit_logs_created_at_index` (`created_at`);

--
-- Índices de tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaigns_status_visible_franchise_only_index` (`status`,`visible_franchise_only`),
  ADD KEY `campaigns_start_date_end_date_index` (`start_date`,`end_date`),
  ADD KEY `campaigns_updated_at_index` (`updated_at`);

--
-- Índices de tabela `campaign_folders`
--
ALTER TABLE `campaign_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_folders_campaign_id_foreign` (`campaign_id`);

--
-- Índices de tabela `campaign_folder_files`
--
ALTER TABLE `campaign_folder_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_folder_files_file_id_foreign` (`file_id`),
  ADD KEY `campaign_folder_files_idx` (`campaign_folder_id`,`file_id`);

--
-- Índices de tabela `campaign_miscellaneous`
--
ALTER TABLE `campaign_miscellaneous`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_miscellaneous_campaign_id_foreign` (`campaign_id`);

--
-- Índices de tabela `campaign_miscellaneous_files`
--
ALTER TABLE `campaign_miscellaneous_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_miscellaneous_files_campaign_miscellaneous_id_foreign` (`campaign_miscellaneous_id`),
  ADD KEY `campaign_miscellaneous_files_file_id_foreign` (`file_id`);

--
-- Índices de tabela `campaign_posts`
--
ALTER TABLE `campaign_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_posts_campaign_id_foreign` (`campaign_id`);

--
-- Índices de tabela `campaign_post_files`
--
ALTER TABLE `campaign_post_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_post_files_file_id_foreign` (`file_id`),
  ADD KEY `campaign_post_files_idx` (`campaign_post_id`,`file_id`);

--
-- Índices de tabela `campaign_videos`
--
ALTER TABLE `campaign_videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_videos_campaign_id_foreign` (`campaign_id`);

--
-- Índices de tabela `campaign_video_files`
--
ALTER TABLE `campaign_video_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_video_files_file_id_foreign` (`file_id`),
  ADD KEY `campaign_video_files_idx` (`campaign_video_id`,`file_id`);

--
-- Índices de tabela `download_options`
--
ALTER TABLE `download_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `download_options_resource_type_resource_id_option_name_unique` (`resource_type`,`resource_id`,`option_name`),
  ADD KEY `download_options_resource_type_resource_id_index` (`resource_type`,`resource_id`);

--
-- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices de tabela `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_type_fileable_type_index` (`type`),
  ADD KEY `files_order_index` (`order`);

--
-- Índices de tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices de tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_category_id_status_index` (`library_category_id`,`status`),
  ADD KEY `library_updated_at_index` (`updated_at`);

--
-- Índices de tabela `library_categories`
--
ALTER TABLE `library_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `library_categories_status_index` (`status`);

--
-- Índices de tabela `library_files`
--
ALTER TABLE `library_files`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `library_permissions`
--
ALTER TABLE `library_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `library_permissions_library_id_user_type_id_unique` (`library_id`,`user_type_id`),
  ADD KEY `library_permissions_user_type_id_can_view_index` (`user_type_id`,`can_view`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices de tabela `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Índices de tabela `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_status_published_at_index` (`status`,`published_at`),
  ADD KEY `news_author_id_index` (`author_id`),
  ADD KEY `news_updated_at_index` (`updated_at`),
  ADD KEY `news_news_file_id_foreign` (`news_file_id`);

--
-- Índices de tabela `news_categories`
--
ALTER TABLE `news_categories`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `news_permissions`
--
ALTER TABLE `news_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_permissions_news_id_user_type_id_unique` (`news_id`,`user_type_id`),
  ADD KEY `news_permissions_user_type_id_can_view_index` (`user_type_id`,`can_view`);

--
-- Índices de tabela `notifications_optimized`
--
ALTER TABLE `notifications_optimized`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_optimized_target_type_created_at_index` (`target_type`,`created_at`),
  ADD KEY `notifications_optimized_related_type_related_id_index` (`related_type`,`related_id`);

--
-- Índices de tabela `onedrive_syncs`
--
ALTER TABLE `onedrive_syncs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `onedrive_syncs_syncable_type_syncable_id_index` (`syncable_type`,`syncable_id`),
  ADD KEY `onedrive_syncs_status_index` (`status`);

--
-- Índices de tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices de tabela `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices de tabela `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_status_index` (`product_category_id`,`status`),
  ADD KEY `products_updated_at_index` (`updated_at`),
  ADD KEY `products_product_series_id_foreign` (`product_series_id`);

--
-- Índices de tabela `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_categories_status_index` (`status`);

--
-- Índices de tabela `product_files`
--
ALTER TABLE `product_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_files_file_id_foreign` (`file_id`),
  ADD KEY `product_files_product_id_file_id_index` (`product_id`,`file_id`);

--
-- Índices de tabela `product_permissions`
--
ALTER TABLE `product_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_permissions_product_id_user_type_id_unique` (`product_id`,`user_type_id`),
  ADD KEY `product_permissions_user_type_id_can_view_index` (`user_type_id`,`can_view`);

--
-- Índices de tabela `product_series`
--
ALTER TABLE `product_series`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_series_product_category_id_foreign` (`product_category_id`);

--
-- Índices de tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Índices de tabela `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Índices de tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices de tabela `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainings_category_id_status_index` (`training_category_id`,`status`),
  ADD KEY `trainings_content_type_status_index` (`content_type`,`status`),
  ADD KEY `trainings_updated_at_index` (`updated_at`);

--
-- Índices de tabela `training_categories`
--
ALTER TABLE `training_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_categories_status_index` (`status`);

--
-- Índices de tabela `training_files`
--
ALTER TABLE `training_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `training_files_file_id_foreign` (`file_id`),
  ADD KEY `training_files_training_id_file_id_index` (`training_id`,`file_id`);

--
-- Índices de tabela `training_permissions`
--
ALTER TABLE `training_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `training_permissions_training_id_user_type_id_unique` (`training_id`,`user_type_id`),
  ADD KEY `training_permissions_user_type_id_can_view_index` (`user_type_id`,`can_view`);

--
-- Índices de tabela `ui_visibilities`
--
ALTER TABLE `ui_visibilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ui_visibilities_feature_user_type_id_unique` (`feature`,`user_type_id`),
  ADD KEY `ui_visibilities_user_type_id_foreign` (`user_type_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_user_type_id_status_index` (`user_type_id`,`status`),
  ADD KEY `users_last_access_index` (`last_access`),
  ADD KEY `users_cpf_cnpj_index` (`cpf_cnpj`),
  ADD KEY `users_nome_fantasia_index` (`nome_fantasia`);

--
-- Índices de tabela `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `user_notifications_related_type_related_id_index` (`related_type`,`related_id`),
  ADD KEY `user_notifications_created_at_index` (`created_at`);

--
-- Índices de tabela `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_types_name_unique` (`name`);

--
-- Índices de tabela `user_views`
--
ALTER TABLE `user_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_views_user_id_viewable_type_viewable_id_unique` (`user_id`,`viewable_type`,`viewable_id`),
  ADD KEY `user_views_viewable_type_viewable_id_index` (`viewable_type`,`viewable_id`),
  ADD KEY `user_views_user_id_last_viewed_at_index` (`user_id`,`last_viewed_at`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `access_history`
--
ALTER TABLE `access_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `campaign_folders`
--
ALTER TABLE `campaign_folders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `campaign_folder_files`
--
ALTER TABLE `campaign_folder_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `campaign_miscellaneous`
--
ALTER TABLE `campaign_miscellaneous`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `campaign_miscellaneous_files`
--
ALTER TABLE `campaign_miscellaneous_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `campaign_posts`
--
ALTER TABLE `campaign_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de tabela `campaign_post_files`
--
ALTER TABLE `campaign_post_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de tabela `campaign_videos`
--
ALTER TABLE `campaign_videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `campaign_video_files`
--
ALTER TABLE `campaign_video_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `download_options`
--
ALTER TABLE `download_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=778;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `library`
--
ALTER TABLE `library`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `library_categories`
--
ALTER TABLE `library_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `library_files`
--
ALTER TABLE `library_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de tabela `library_permissions`
--
ALTER TABLE `library_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `news_categories`
--
ALTER TABLE `news_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `news_permissions`
--
ALTER TABLE `news_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `notifications_optimized`
--
ALTER TABLE `notifications_optimized`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de tabela `onedrive_syncs`
--
ALTER TABLE `onedrive_syncs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de tabela `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `product_files`
--
ALTER TABLE `product_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT de tabela `product_permissions`
--
ALTER TABLE `product_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=577;

--
-- AUTO_INCREMENT de tabela `product_series`
--
ALTER TABLE `product_series`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `training_categories`
--
ALTER TABLE `training_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `training_files`
--
ALTER TABLE `training_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `training_permissions`
--
ALTER TABLE `training_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `ui_visibilities`
--
ALTER TABLE `ui_visibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `user_types`
--
ALTER TABLE `user_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `user_views`
--
ALTER TABLE `user_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `campaign_folders`
--
ALTER TABLE `campaign_folders`
  ADD CONSTRAINT `campaign_folders_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_folder_files`
--
ALTER TABLE `campaign_folder_files`
  ADD CONSTRAINT `campaign_folder_files_campaign_folder_id_foreign` FOREIGN KEY (`campaign_folder_id`) REFERENCES `campaign_folders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_folder_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_miscellaneous`
--
ALTER TABLE `campaign_miscellaneous`
  ADD CONSTRAINT `campaign_miscellaneous_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_miscellaneous_files`
--
ALTER TABLE `campaign_miscellaneous_files`
  ADD CONSTRAINT `campaign_miscellaneous_files_campaign_miscellaneous_id_foreign` FOREIGN KEY (`campaign_miscellaneous_id`) REFERENCES `campaign_miscellaneous` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_miscellaneous_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_posts`
--
ALTER TABLE `campaign_posts`
  ADD CONSTRAINT `campaign_posts_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_post_files`
--
ALTER TABLE `campaign_post_files`
  ADD CONSTRAINT `campaign_post_files_campaign_post_id_foreign` FOREIGN KEY (`campaign_post_id`) REFERENCES `campaign_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_post_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_videos`
--
ALTER TABLE `campaign_videos`
  ADD CONSTRAINT `campaign_videos_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `campaign_video_files`
--
ALTER TABLE `campaign_video_files`
  ADD CONSTRAINT `campaign_video_files_campaign_video_id_foreign` FOREIGN KEY (`campaign_video_id`) REFERENCES `campaign_videos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_video_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `library`
--
ALTER TABLE `library`
  ADD CONSTRAINT `library_category_id_foreign` FOREIGN KEY (`library_category_id`) REFERENCES `library_categories` (`id`);

--
-- Restrições para tabelas `library_permissions`
--
ALTER TABLE `library_permissions`
  ADD CONSTRAINT `library_permissions_library_id_foreign` FOREIGN KEY (`library_id`) REFERENCES `library` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `library_permissions_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `news_news_file_id_foreign` FOREIGN KEY (`news_file_id`) REFERENCES `files` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `news_permissions`
--
ALTER TABLE `news_permissions`
  ADD CONSTRAINT `news_permissions_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `news_permissions_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`),
  ADD CONSTRAINT `products_product_series_id_foreign` FOREIGN KEY (`product_series_id`) REFERENCES `product_series` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `product_files`
--
ALTER TABLE `product_files`
  ADD CONSTRAINT `product_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_files_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `product_permissions`
--
ALTER TABLE `product_permissions`
  ADD CONSTRAINT `product_permissions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_permissions_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `product_series`
--
ALTER TABLE `product_series`
  ADD CONSTRAINT `product_series_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `trainings`
--
ALTER TABLE `trainings`
  ADD CONSTRAINT `trainings_category_id_foreign` FOREIGN KEY (`training_category_id`) REFERENCES `training_categories` (`id`);

--
-- Restrições para tabelas `training_files`
--
ALTER TABLE `training_files`
  ADD CONSTRAINT `training_files_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_files_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `training_permissions`
--
ALTER TABLE `training_permissions`
  ADD CONSTRAINT `training_permissions_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_permissions_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ui_visibilities`
--
ALTER TABLE `ui_visibilities`
  ADD CONSTRAINT `ui_visibilities_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_type_id_foreign` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `user_views`
--
ALTER TABLE `user_views`
  ADD CONSTRAINT `user_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
