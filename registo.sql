-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 07-Jun-2023 às 19:35
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `registo`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender`, `message`, `timestamp`) VALUES
(29, 'hugo', 'oi', '2023-06-07 17:23:03'),
(30, 'Mario', 'oi', '2023-06-07 17:26:17'),
(31, 'dario123', 'ola', '2023-06-07 17:26:57'),
(32, 'gil', 'olá', '2023-06-07 17:27:05'),
(33, 'rubens2', 'alouuuu', '2023-06-07 17:27:05'),
(34, 'rubens2', 'alouuuu', '2023-06-07 17:27:11'),
(35, 'gil', 'olá', '2023-06-07 17:27:21');

-- --------------------------------------------------------

--
-- Estrutura da tabela `game_rooms`
--

CREATE TABLE `game_rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `num_players` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `game_sessions`
--

CREATE TABLE `game_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `creator_email` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `player_symbol` char(1) NOT NULL DEFAULT '',
  `room_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `player1_id` int(11) DEFAULT NULL,
  `player1_symbol` char(1) DEFAULT NULL,
  `player2_id` int(11) DEFAULT NULL,
  `player2_symbol` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `game_sessions`
--

INSERT INTO `game_sessions` (`id`, `creator_email`, `status`, `created_at`, `player_symbol`, `room_id`, `session_id`, `player1_id`, `player1_symbol`, `player2_id`, `player2_symbol`) VALUES
(1, NULL, 'active', '2023-05-23 17:14:23', '', NULL, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'active', '2023-05-23 19:19:38', '', NULL, NULL, NULL, NULL, NULL, NULL),
(3, NULL, 'active', '2023-05-23 19:54:49', '', NULL, NULL, NULL, NULL, NULL, NULL),
(19, NULL, 'active', '2023-06-07 17:17:31', '', NULL, NULL, NULL, NULL, NULL, NULL),
(20, NULL, 'active', '2023-06-07 17:19:20', '', NULL, NULL, NULL, NULL, NULL, NULL),
(21, NULL, 'active', '2023-06-07 17:19:23', '', NULL, NULL, NULL, NULL, NULL, NULL),
(22, NULL, 'active', '2023-06-07 17:19:27', '', NULL, NULL, NULL, NULL, NULL, NULL),
(23, NULL, 'active', '2023-06-07 17:20:15', '', NULL, NULL, NULL, NULL, NULL, NULL),
(24, NULL, 'active', '2023-06-07 17:20:22', '', NULL, NULL, NULL, NULL, NULL, NULL),
(25, NULL, 'active', '2023-06-07 17:20:27', '', NULL, NULL, NULL, NULL, NULL, NULL),
(26, NULL, 'active', '2023-06-07 17:21:03', '', NULL, NULL, NULL, NULL, NULL, NULL),
(27, NULL, 'active', '2023-06-07 17:22:03', '', NULL, NULL, NULL, NULL, NULL, NULL),
(28, NULL, 'active', '2023-06-07 17:26:07', '', NULL, NULL, NULL, NULL, NULL, NULL),
(29, NULL, 'active', '2023-06-07 17:27:17', '', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `status` enum('online','offline') DEFAULT 'offline',
  `player_count` int(11) NOT NULL DEFAULT 0,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `registo`
--

CREATE TABLE `registo` (
  `email` varchar(120) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT 'user',
  `image` varchar(100) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `session_id` int(11) UNSIGNED DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `registo`
--

INSERT INTO `registo` (`email`, `password`, `id`, `name`, `user_type`, `image`, `background_image`, `session_id`, `room_id`) VALUES
('rubens@rubens.pt', 'ccf66f9fb9e5d2ccda26305ecab5455e', 93, 'rubens', 'admin', '', NULL, 20, NULL),
('mariofgrodrigues2@gmail.com', '2aa8dd4c45c98a87c920953d476d8661', 94, 'Mario', 'user', '', NULL, 21, NULL),
('rozana@email.com', 'b59c67bf196a4758191e42f76670ceba', 95, 'rozana', 'user', '', NULL, 22, NULL),
('a039305@ismai.pt', 'e10adc3949ba59abbe56e057f20f883e', 96, 'Ana Melo', 'user', '', NULL, 23, NULL),
('asfasf@asfasd.com', 'e120ea280aa50693d5568d0071456460', 97, 'gil', 'user', '', 'OIP.jpg', 24, NULL),
('hugo.andre@sapo.pt', '202cb962ac59075b964b07152d234b70', 98, 'hugo', 'user', '', NULL, 25, NULL),
('dario.m.f.rodrigues@gmail.com', '68b6a57caa12f512ced21f89728fb89d', 99, 'dario123', 'user', '8ed9c5a0-3b6b-4054-abc7-82707b2f8d77.png', 'background.jpg', 26, NULL),
('rubens2@rubens2.pt', 'ccf66f9fb9e5d2ccda26305ecab5455e', 100, 'rubens2', 'user', '', NULL, 27, NULL),
('a038692@ismai.pt', '81dc9bdb52d04dc20036dbd8313ed055', 102, ':p', 'user', '', NULL, 29, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `game_rooms`
--
ALTER TABLE `game_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `game_sessions`
--
ALTER TABLE `game_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `registo`
--
ALTER TABLE `registo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `game_rooms`
--
ALTER TABLE `game_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `game_sessions`
--
ALTER TABLE `game_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `registo`
--
ALTER TABLE `registo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `registo`
--
ALTER TABLE `registo`
  ADD CONSTRAINT `registo_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `game_sessions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
