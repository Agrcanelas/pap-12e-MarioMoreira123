-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 12-Jan-2026 às 10:59
-- Versão do servidor: 9.1.0
-- versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ajudarpap`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `associacoes`
--

DROP TABLE IF EXISTS `associacoes`;
CREATE TABLE IF NOT EXISTS `associacoes` (
  `A_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `local` varchar(200) DEFAULT NULL,
  `data_criacao` date DEFAULT NULL,
  `NIF` int DEFAULT NULL,
  PRIMARY KEY (`A_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `associacoes`
--

INSERT INTO `associacoes` (`A_id`, `nome`, `local`, `data_criacao`, `NIF`) VALUES
(1, 'Ajuda Solidária', 'Lisboa', '2010-05-10', 501234567),
(2, 'Mãos Unidas', 'Porto', '2012-03-22', 502345678),
(3, 'Coração Aberto', 'Braga', '2015-07-15', 503456789),
(4, 'Esperança Viva', 'Coimbra', '2008-11-02', 504567891),
(5, 'Vida Melhor', 'Faro', '2016-09-18', 505678912),
(6, 'Sorriso Feliz', 'Aveiro', '2013-01-30', 506789123),
(7, 'Apoio Total', 'Viseu', '2011-06-12', 507891234),
(8, 'Solidariedade Já', 'Setúbal', '2017-10-08', 508912345),
(9, 'União Social', 'Évora', '2014-04-19', 509123456),
(10, 'Amigos do Bem', 'Leiria', '2009-12-05', 510234567);

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacoes`
--

DROP TABLE IF EXISTS `doacoes`;
CREATE TABLE IF NOT EXISTS `doacoes` (
  `D_id` int NOT NULL AUTO_INCREMENT,
  `U_id` int DEFAULT NULL,
  `A_id` int DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`D_id`),
  KEY `fk_doacoes_utilizador` (`U_id`),
  KEY `fk_doacoes_associacoes` (`A_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `doacoes`
--

INSERT INTO `doacoes` (`D_id`, `U_id`, `A_id`, `tipo`, `quantidade`, `data`) VALUES
(31, 1, 1, 'Dinheiro', 50.00, '2024-01-10'),
(32, 2, 2, 'Roupa', 10.00, '2024-01-12'),
(33, 3, 3, 'Alimentos', 25.50, '2024-01-15'),
(34, 4, 4, 'Dinheiro', 100.00, '2024-01-18'),
(35, 5, 5, 'Medicamentos', 15.00, '2024-01-20'),
(36, 6, 6, 'Roupa', 8.00, '2024-01-22'),
(37, 7, 7, 'Alimentos', 30.00, '2024-01-25'),
(38, 8, 8, 'Dinheiro', 75.00, '2024-01-28'),
(39, 9, 9, 'Brinquedos', 12.00, '2024-02-01'),
(40, 10, 10, 'Alimentos', 40.00, '2024-02-05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizador`
--

DROP TABLE IF EXISTS `utilizador`;
CREATE TABLE IF NOT EXISTS `utilizador` (
  `U_id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `telefone` int DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `NIF` int DEFAULT NULL,
  PRIMARY KEY (`U_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizador`
--

INSERT INTO `utilizador` (`U_id`, `nome`, `email`, `senha`, `telefone`, `data_nasc`, `NIF`) VALUES
(1, 'Ana Silva', 'ana@gmail.com', '1234', 912345678, '1995-03-12', 123456789),
(2, 'Bruno Costa', 'bruno@gmail.com', '1234', 913456789, '1992-06-21', 234567891),
(3, 'Carla Mendes', 'carla@gmail.com', '1234', 914567891, '1990-01-10', 345678912),
(4, 'Daniel Rocha', 'daniel@gmail.com', '1234', 915678912, '1998-09-05', 456789123),
(5, 'Eduarda Lima', 'eduarda@gmail.com', '1234', 916789123, '1996-12-30', 567891234),
(6, 'Fábio Martins', 'fabio@gmail.com', '1234', 917891234, '1989-07-18', 678912345),
(7, 'Gisela Pires', 'gisela@gmail.com', '1234', 918912345, '1994-04-22', 789123456),
(8, 'Hugo Teixeira', 'hugo@gmail.com', '1234', 919123456, '1991-11-14', 891234567),
(9, 'Inês Duarte', 'ines@gmail.com', '1234', 911234567, '1997-08-09', 912345678),
(10, 'João Ferreira', 'joao@gmail.com', '1234', 922345678, '1993-02-25', 923456789);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `doacoes`
--
ALTER TABLE `doacoes`
  ADD CONSTRAINT `fk_doacoes_associacoes` FOREIGN KEY (`A_id`) REFERENCES `associacoes` (`A_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_doacoes_utilizador` FOREIGN KEY (`U_id`) REFERENCES `utilizador` (`U_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
