-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/10/2025 às 03:48
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `redbox`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `client_profiles`
--

CREATE TABLE `client_profiles` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `tipo_cliente` enum('PF','PJ') NOT NULL DEFAULT 'PF',
  `razao_social` varchar(120) DEFAULT NULL,
  `nome_fantasia` varchar(120) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `site` varchar(200) DEFAULT NULL,
  `contato_nome` varchar(80) DEFAULT NULL,
  `contato_telefone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `client_profiles`
--

INSERT INTO `client_profiles` (`user_id`, `tipo_cliente`, `razao_social`, `nome_fantasia`, `cnpj`, `site`, `contato_nome`, `contato_telefone`, `created_at`, `updated_at`) VALUES
(2, 'PF', NULL, NULL, NULL, NULL, 'Leia', '222333', '2025-09-28 22:07:37', '2025-09-28 22:07:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `hacker_profiles`
--

CREATE TABLE `hacker_profiles` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `apelido` varchar(60) NOT NULL,
  `bio` text DEFAULT NULL,
  `especialidades` text DEFAULT NULL,
  `valor_hora` decimal(10,2) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `github` varchar(200) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `disponibilidade` enum('FULLTIME','PARTTIME','FREELANCER','A_COMBINAR') NOT NULL DEFAULT 'FREELANCER',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `hacker_profiles`
--

INSERT INTO `hacker_profiles` (`user_id`, `apelido`, `bio`, `especialidades`, `valor_hora`, `website`, `github`, `linkedin`, `disponibilidade`, `created_at`, `updated_at`) VALUES
(1, 'Ironman', 'Vingador mais poderoso.', 'Armas, voar.', 165.00, NULL, NULL, NULL, 'FREELANCER', '2025-09-28 21:38:26', '2025-09-28 22:01:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_usuarios`
--

CREATE TABLE `perfil_usuarios` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tipo` enum('CLIENTE','HACKER') DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `cnpj` varchar(20) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `habilidades` varchar(255) DEFAULT NULL,
  `site_portfolio` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `empresa` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil_usuarios`
--

INSERT INTO `perfil_usuarios` (`id`, `user_id`, `tipo`, `cpf`, `cnpj`, `cidade`, `telefone`, `habilidades`, `site_portfolio`, `bio`, `empresa`, `created_at`, `updated_at`) VALUES
(3, 3, 'CLIENTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 12:53:32', NULL),
(4, 4, 'CLIENTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 12:57:59', NULL),
(7, 7, 'CLIENTE', '', '1234567', 'CAMPINAS', '2113456', '', '', '', 'Empresa do Bem', '2025-10-05 13:29:56', '2025-10-05 13:30:57'),
(9, 9, 'HACKER', '', '', '', '', '', '', '', '', '2025-10-05 15:45:20', '2025-10-05 16:08:36'),
(11, 11, 'CLIENTE', '', '123456', 'CAMPINAS', '11123456', '', 'https://www.uol.com.br/', 'Casa, residência ou moradia é, no seu sentido mais comum, um conjunto de paredes, cômodos e teto construídos pelo ser humano com a finalidade de constituir um espaço de habitação para um indivíduo', 'Empresa do Bem', '2025-10-05 16:05:36', '2025-10-05 16:06:08'),
(12, 12, 'CLIENTE', '', '12345678912', 'Nova York', '91234567', '', 'https://www.marvel.com/', 'Tony Stark, o Homem de Ferro, é um dos super-heróis mais icônicos da Marvel, famoso por sua personalidade de \"gênio, bilionário, playboy e filantropo\" e seu intelecto brilhante. Ele é o CEO da Stark Industries, um inventor genial que, após ser sequestrado, cria sua primeira armadura de alta tecnologia para escapar, redefinindo sua vida para combater o crime e proteger o mundo. Essa jornada de redenção e altruísmo o transforma em um membro fundador e líder dos Vingadores, culminando em seu sacrifício épico em Vingadores: Ultimato para salvar o universo de Thanos, consolidando-o como um dos pilares e o coração emocional do Universo Cinematográfico Marvel (MCU).', 'Stark Industries', '2025-10-05 16:32:47', '2025-10-05 16:36:41'),
(15, 15, 'CLIENTE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 23:10:23', NULL),
(16, 16, 'HACKER', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 23:11:21', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipo_usuario` enum('HACKER','CLIENTE') NOT NULL DEFAULT 'CLIENTE',
  `nome_completo` varchar(80) NOT NULL,
  `endereco` varchar(120) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `profissao` varchar(50) DEFAULT NULL,
  `rg` varchar(12) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `login` varchar(40) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `status` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `tipo_usuario`, `nome_completo`, `endereco`, `data_nascimento`, `profissao`, `rg`, `cpf`, `login`, `email`, `senha_hash`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HACKER', 'Tony Stark', 'NY', '2001-01-01', 'Vingador', '123456789', '12345678900', 'tony', 'ironman@avengers.com', '$2y$10$x9IpVYpxktxUbdSDdR62fe5wj/U0Qz/Qj3y2CKPEpsi/AAJ3HGPZW', 'ATIVO', '2025-09-28 15:08:14', '2025-09-28 15:08:14'),
(2, 'CLIENTE', 'Luke Skywalker', 'luke@starwars.com', '2000-01-01', 'Jedi', '654789123', '45678912345', 'luke', 'luke@starwars.com', '$2y$10$12JZst/P2naNlAHx9z/azO5bpggDLTvdUB0dP.9xw6cnypOVFp53y', 'ATIVO', '2025-09-28 19:10:53', '2025-09-28 19:10:53');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo` enum('CLIENTE','HACKER') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `created_at`) VALUES
(3, 'Jose Maria1', 'jose@maria1.com', '$2y$10$RZvcCGMfhCa63lBQcwen4OEtjEWrLkgJvCRJMS2HbvWvO4jrRA1CC', 'CLIENTE', '2025-10-05 12:53:32'),
(4, 'Jose Maria2', 'jose@maria2.com', '$2y$10$M9WrEXIHsiBwHXvRQSY2Huwn.V2gnyKIwUR/79vpXYGyE8QoVWfaK', 'CLIENTE', '2025-10-05 12:57:59'),
(7, 'Jose Maria5', 'jose@maria5.com', '$2y$10$SzUW9MF2tn3RIYZZ3.ivH.61r5a/wUKJQxUOxQkxPcwhUA7CiW6Te', 'CLIENTE', '2025-10-05 13:29:56'),
(9, 'Jose Maria4', 'jose@maria4.com', '$2y$10$2WHXKaqhngaS/VxvMWGZbOOx1fnzGV1/EliGJfkLZhxIrYnxW3Bci', 'HACKER', '2025-10-05 15:45:20'),
(11, 'Jose Maria3', 'jose@maria3.com', '$2y$10$2AgBjGj5m/baAeIVUO/kHOSmqvWqtMO0dSHkKTpdpTMzZfbeQ8Ze.', 'CLIENTE', '2025-10-05 16:05:36'),
(12, 'Tony Stark', 'ironman@avengers.com', '$2y$10$0EzssYRvGfCDB7fQIyt3IewZu5XpgzVsR8V2L3pz06cBjhHL9FEiG', 'CLIENTE', '2025-10-05 16:32:47'),
(15, 'Teste', 'teste@teste.com', '$2y$10$0dHuk2Q8lopVHiBELNn9nuvy/49YhSGKLGfJ5eCCTahQX7vtCPhHK', 'CLIENTE', '2025-10-12 23:10:23'),
(16, 'kentoi', 'kento@kento.com', '$2y$10$aOAKjpw1msxJMjrNK6eWle8XbPHDRlF8lI4XnKHm7mQF7xhUJ5WZi', 'HACKER', '2025-10-12 23:11:21');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_client_cnpj` (`cnpj`);

--
-- Índices de tabela `hacker_profiles`
--
ALTER TABLE `hacker_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Índices de tabela `perfil_usuarios`
--
ALTER TABLE `perfil_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_login` (`login`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD UNIQUE KEY `uq_users_cpf` (`cpf`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `perfil_usuarios`
--
ALTER TABLE `perfil_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD CONSTRAINT `fk_client_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `hacker_profiles`
--
ALTER TABLE `hacker_profiles`
  ADD CONSTRAINT `fk_hacker_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `perfil_usuarios`
--
ALTER TABLE `perfil_usuarios`
  ADD CONSTRAINT `perfil_usuarios_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
