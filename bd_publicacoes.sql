-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/11/2025 às 19:24
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
-- Banco de dados: `bd_publicacoes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `autor`
--

CREATE TABLE `autor` (
  `id_autor` int(11) NOT NULL,
  `nm_autor` varchar(150) NOT NULL,
  `nm_email` varchar(150) NOT NULL,
  `nm_instituicao` varchar(100) NOT NULL,
  `cd_orcid` varchar(20) NOT NULL,
  `id_classificacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `autor`
--

INSERT INTO `autor` (`id_autor`, `nm_autor`, `nm_email`, `nm_instituicao`, `cd_orcid`, `id_classificacao`) VALUES
(9, 'Machado de Assis', 'machado@outlook.com', 'Academia Brasileira de Letras', '12345780', 1),
(10, 'William Shakespeare', 'willian@gmai.com', 'Stratford-upon-Avon', '581911219', 2),
(12, 'Sérgio Sacani', 'serjao@foguetes.com', 'USP', '12141415', 1),
(13, 'Leonardo da Vinci', 'leonardo@vinci.com', 'Anchiano', '214345', 1),
(14, 'Quentin Tarantino', 'tarantido@filmes.com', 'Rolling Thunder Pictures', '21145345', 3),
(15, 'David Fincher', 'fincher@diretor.com', 'Colorado', '1341', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `classificacao`
--

CREATE TABLE `classificacao` (
  `id_classificacao` int(11) NOT NULL,
  `ds_classificacao` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `classificacao`
--

INSERT INTO `classificacao` (`id_classificacao`, `ds_classificacao`) VALUES
(1, 'Principal'),
(2, 'Coautor'),
(3, 'Correspondente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `convidado`
--

CREATE TABLE `convidado` (
  `id_convidado` int(11) NOT NULL,
  `nm_convidado` varchar(100) NOT NULL,
  `nm_email` varchar(100) NOT NULL,
  `nm_instituicao` varchar(100) NOT NULL,
  `nm_cargo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `convidado`
--

INSERT INTO `convidado` (`id_convidado`, `nm_convidado`, `nm_email`, `nm_instituicao`, `nm_cargo`) VALUES
(6, 'Vitor', 'vitor@fatec', 'FATEC', 'Desenvolvedor'),
(7, 'Giovanna', 'giovanna@gmail.com', 'FATEC', 'Dentista'),
(11, 'Nathalia', 'nathalia@fatec.com', 'FATEC', 'Estudante'),
(12, 'Eduarda', 'eduarda@fatec.com', 'FATEC', 'Estudante'),
(13, 'Julia', 'julia@fatec.com', 'FATEC', 'Estudante');

-- --------------------------------------------------------

--
-- Estrutura para tabela `divulgacao`
--

CREATE TABLE `divulgacao` (
  `id_divulgacao` int(11) NOT NULL,
  `nm_evento` varchar(100) NOT NULL,
  `nm_local` varchar(100) NOT NULL,
  `id_tipo_publicacao` int(11) NOT NULL,
  `dt_evento` date NOT NULL,
  `id_convidado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `divulgacao`
--

INSERT INTO `divulgacao` (`id_divulgacao`, `nm_evento`, `nm_local`, `id_tipo_publicacao`, `dt_evento`, `id_convidado`) VALUES
(1, 'Feira do Livro', 'Praia Grande', 1, '2025-10-04', 6),
(2, 'Live Youtube', 'youtube.com', 4, '2025-11-01', 7),
(3, 'Premiação Oscar', 'Hollywood', 0, '2025-11-29', 11),
(4, 'Exposição Artística', 'MASP', 0, '2025-11-03', 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `publicacao`
--

CREATE TABLE `publicacao` (
  `id_publicacao` int(11) NOT NULL,
  `nm_titulo` varchar(100) NOT NULL,
  `ds_resumo` text NOT NULL,
  `dt_publicacao` date NOT NULL,
  `nm_palavra_chave` varchar(100) NOT NULL,
  `id_divulgacao` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL,
  `id_tipo_publicacao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `publicacao`
--

INSERT INTO `publicacao` (`id_publicacao`, `nm_titulo`, `ds_resumo`, `dt_publicacao`, `nm_palavra_chave`, `id_divulgacao`, `id_autor`, `id_tipo_publicacao`) VALUES
(1, 'Memórias Póstumas de Brás Cubas', 'história', '2025-10-18', 'Romance', 1, 9, 1),
(2, 'Telescópio Espacial James Webb', 'artigo sobre o telescópio', '2025-09-12', 'Ciência', 2, 12, 4),
(3, 'Pulp Fiction: Tempo de Violência', 'filmes classicos', '2025-11-03', 'ação', 3, 14, 3),
(4, 'Mona Lisa', 'tela mona lisa', '2025-11-04', 'pintura', 4, 13, 2),
(5, 'Clube da Luta(1999)', 'filme', '2025-11-05', 'ação', 3, 15, 3),
(6, 'dsfasdfa', 'dfasfasdfsdf', '2025-11-12', 'dsafs', 5, 17, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_publicacao`
--

CREATE TABLE `tipo_publicacao` (
  `id_tipo_publicacao` int(11) NOT NULL,
  `ds_tipo_publicacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipo_publicacao`
--

INSERT INTO `tipo_publicacao` (`id_tipo_publicacao`, `ds_tipo_publicacao`) VALUES
(1, 'Livro'),
(2, 'Obra de arte'),
(3, 'Filme'),
(4, 'Artigo');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id_autor`),
  ADD KEY `id_classificacao` (`id_classificacao`);

--
-- Índices de tabela `classificacao`
--
ALTER TABLE `classificacao`
  ADD PRIMARY KEY (`id_classificacao`);

--
-- Índices de tabela `convidado`
--
ALTER TABLE `convidado`
  ADD PRIMARY KEY (`id_convidado`);

--
-- Índices de tabela `divulgacao`
--
ALTER TABLE `divulgacao`
  ADD PRIMARY KEY (`id_divulgacao`),
  ADD KEY `id_convidado` (`id_convidado`),
  ADD KEY `nm_tipo` (`id_tipo_publicacao`);

--
-- Índices de tabela `publicacao`
--
ALTER TABLE `publicacao`
  ADD PRIMARY KEY (`id_publicacao`),
  ADD KEY `id_tipo` (`id_tipo_publicacao`),
  ADD KEY `id_autor` (`id_autor`),
  ADD KEY `id_divulgacao` (`id_divulgacao`);

--
-- Índices de tabela `tipo_publicacao`
--
ALTER TABLE `tipo_publicacao`
  ADD PRIMARY KEY (`id_tipo_publicacao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `classificacao`
--
ALTER TABLE `classificacao`
  MODIFY `id_classificacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `convidado`
--
ALTER TABLE `convidado`
  MODIFY `id_convidado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `divulgacao`
--
ALTER TABLE `divulgacao`
  MODIFY `id_divulgacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `publicacao`
--
ALTER TABLE `publicacao`
  MODIFY `id_publicacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tipo_publicacao`
--
ALTER TABLE `tipo_publicacao`
  MODIFY `id_tipo_publicacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
