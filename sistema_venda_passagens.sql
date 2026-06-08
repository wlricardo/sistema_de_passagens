-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/06/2026 às 03:58
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
-- Banco de dados: `sistema_venda_passagens`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `cidade`
--

CREATE TABLE `cidade` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cidade`
--

INSERT INTO `cidade` (`id`, `nome`, `estado_id`) VALUES
(1, 'São Paulo', 1),
(2, 'Rio de Janeiro', 2),
(3, 'Curitiba', 3),
(4, 'Fortaleza', 4),
(5, 'Recife', 5),
(6, 'Jericoacoara', 4),
(7, 'Caruaru', 5),
(8, 'Foz do Iguaçu', 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `cpf`, `email`, `login`, `senha`) VALUES
(1, 'João Pedro Almeida', '111.222.333-44', 'joao@email.com', 'joao.pedro', '24c9e15e52afc47c225b757e7bee1f9d'),
(2, 'Maria Eduarda Santos', '222.333.444-55', 'maria@email.com', 'maria.eduarda', '7e58d63b60197ceb55a1c487989a3720'),
(3, 'Lucas Oliveira Costa', '333.444.554-66', 'lucas@email.com', 'lucas.costa', '92877af70a45fd6a2ed7fe81e1236b78'),
(4, 'Juliana Ribeiro Melo', '444.555.666-77', 'juliana@email.com', 'ju.ribeiro', '3f02ebe3d7929b091e3d8ccfde2f3bc6'),
(5, 'Gabriel Barbosa Lima', '555.666.777-88', 'gabriel@email.com', 'gabriel.lima', '0a791842f52a0acfbb3a783378c066b8'),
(6, 'Beatriz Souza Rocha', '666.777.888-99', 'beatriz@email.com', 'bia.rocha', 'affec3b64cf90492377a8114c86fc093'),
(7, 'Rodrigo Alves Mendes', '777.888.999-00', 'rodrigo@email.com', 'rodrigo.mendes', '3e0469fb134991f8f75a2760e409c6ed'),
(8, 'Camila Farias Nunes', '888.999.000-11', 'camila@email.com', 'camila.nunes', '7668f673d5669995175ef91b5d171945'),
(9, 'Bruno Castro Silva', '999.000.111-22', 'bruno@email.com', 'bruno.castro', '8808a13b854c2563da1a5f6cb2130868'),
(10, 'Larissa Ramos Duarte', '000.111.222-33', 'larissa@email.com', 'lari.duarte', '990d67a9f94696b1abe2dccf06900322'),
(25, 'Willem Ricardo', '159.753.456-11', 'willem@mail.com', 'wlricardo', '81dc9bdb52d04dc20036dbd8313ed055'),
(29, 'Cristiano Ronaldo', '202.020.202-00', 'cr7@gol.com', 'cr7_show', '202cb962ac59075b964b07152d234b70'),
(30, 'Messi', '030.303.030.33', 'messi@messy.com', 'la_pulga', '81dc9bdb52d04dc20036dbd8313ed055'),
(31, 'Neymar Jr.', '404.040.404-44', 'menino_ney@bet.com', 'menino_ney', '202cb962ac59075b964b07152d234b70'),
(32, 'Carlo Ancelotti', '808.080.808-88', 'carlotto@mail.com', 'carlotto', '202cb962ac59075b964b07152d234b70'),
(39, 'Gus Fring', '333.111.222-99', 'gus@mail.com', 'los_pollos_hermanos', '202cb962ac59075b964b07152d234b70');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estado`
--

CREATE TABLE `estado` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `estado`
--

INSERT INTO `estado` (`id`, `nome`) VALUES
(1, 'São Paulo'),
(2, 'Rio de Janeiro'),
(3, 'Paraná'),
(4, 'Ceará'),
(5, 'Pernambuco');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfil`
--

INSERT INTO `perfil` (`id`, `nome`) VALUES
(1, 'Gerente'),
(2, 'Consultor de Vendas'),
(3, 'Analista de TI');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reserva`
--

CREATE TABLE `reserva` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `viagem_id` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `forma_pagamento` enum('Cartao','Pix','Dinheiro') NOT NULL,
  `parcelas` int(11) DEFAULT 1,
  `valor_pago` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `reserva`
--

INSERT INTO `reserva` (`id`, `usuario_id`, `cliente_id`, `viagem_id`, `data`, `forma_pagamento`, `parcelas`, `valor_pago`) VALUES
(1, NULL, 1, 1, '2026-05-10 10:15:00', 'Pix', 1, 0.00),
(2, NULL, 2, 2, '2026-05-11 11:30:00', 'Cartao', 1, 0.00),
(3, NULL, 3, 3, '2026-05-12 15:45:00', 'Dinheiro', 1, 0.00),
(4, NULL, 4, 4, '2026-05-13 09:20:00', 'Pix', 1, 0.00),
(5, NULL, 5, 5, '2026-05-14 18:00:00', 'Cartao', 1, 0.00),
(6, NULL, 6, 6, '2026-05-15 08:45:00', 'Pix', 1, 0.00),
(7, NULL, 7, 7, '2026-05-16 11:10:00', 'Cartao', 1, 0.00),
(8, NULL, 8, 8, '2026-05-16 14:25:00', 'Dinheiro', 1, 0.00),
(9, NULL, 9, 9, '2026-05-17 16:30:00', 'Pix', 1, 0.00),
(10, NULL, 10, 10, '2026-05-18 19:15:00', 'Cartao', 1, 0.00),
(11, NULL, 9, 9, '2026-05-29 03:59:21', 'Cartao', 6, 600.00),
(13, 5, 30, 10, '2026-06-05 01:46:28', 'Dinheiro', 1, 796.50),
(14, 5, 29, 10, '2026-06-05 01:47:03', 'Cartao', 2, 885.00),
(15, 5, 7, 6, '2026-06-05 01:47:33', 'Cartao', 10, 120.00),
(16, 6, 39, 13, '2026-06-07 16:11:18', 'Dinheiro', 1, 140.40),
(17, 6, 39, 10, '2026-06-07 16:26:48', 'Pix', 1, 796.50),
(18, 6, 29, 12, '2026-06-07 16:28:08', 'Cartao', 4, 832.00),
(19, 5, 1, 13, '2026-06-07 10:15:00', 'Pix', 1, 140.40),
(20, 5, 2, 1, '2026-06-07 10:30:00', 'Dinheiro', 1, 162.00),
(21, 5, 3, 6, '2026-06-07 11:00:00', '', 1, 120.00),
(22, 5, 4, 2, '2026-06-07 11:20:00', 'Pix', 1, 99.00),
(23, 5, 5, 12, '2026-06-07 12:00:00', 'Dinheiro', 1, 748.80),
(24, 5, 6, 3, '2026-06-07 13:15:00', '', 1, 225.00),
(25, 5, 7, 8, '2026-06-07 14:00:00', 'Pix', 1, 236.25),
(26, 5, 8, 4, '2026-06-07 14:30:00', 'Dinheiro', 1, 607.50),
(27, 5, 10, 5, '2026-06-07 15:45:00', 'Pix', 1, 702.00),
(28, 6, 25, 7, '2026-06-07 09:00:00', '', 1, 180.00),
(29, 6, 29, 9, '2026-06-07 09:30:00', 'Pix', 1, 540.00),
(30, 6, 30, 1, '2026-06-07 10:00:00', 'Dinheiro', 1, 162.00),
(31, 6, 31, 13, '2026-06-07 10:45:00', 'Pix', 1, 140.40),
(32, 6, 32, 2, '2026-06-07 11:15:00', '', 1, 110.00),
(35, 6, 39, 8, '2026-06-07 13:00:00', 'Pix', 1, 236.25),
(36, 6, 1, 4, '2026-06-07 13:45:00', 'Dinheiro', 1, 607.50),
(37, 6, 2, 10, '2026-06-07 14:15:00', '', 1, 885.00),
(38, 5, 25, 10, '2026-06-07 21:55:03', 'Cartao', 6, 885.00),
(40, 5, 39, 4, '2026-06-07 22:26:53', 'Pix', 1, 607.50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `rota`
--

CREATE TABLE `rota` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cidade_origem_id` int(11) NOT NULL,
  `cidade_destino_id` int(11) NOT NULL,
  `tempo_viagem` varchar(50) NOT NULL,
  `valor_base` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `rota`
--

INSERT INTO `rota` (`id`, `nome`, `cidade_origem_id`, `cidade_destino_id`, `tempo_viagem`, `valor_base`) VALUES
(1, 'São Paulo x Rio de Janeiro', 1, 2, '06:00', 125.00),
(2, 'Curitiba x São Paulo', 3, 1, '06:30', 110.00),
(3, 'Fortaleza x Recife', 4, 5, '12:00', 180.00),
(4, 'Recife x Rio de Janeiro', 5, 2, '42:00', 452.00),
(5, 'São Paulo x Fortaleza', 1, 4, '45:00', 520.00),
(6, 'Rio de Janeiro x São Paulo', 2, 1, '06:00', 120.00),
(7, 'Recife x Fortaleza', 5, 4, '12:00', 180.00),
(8, 'Curitiba x Rio de Janeiro', 3, 2, '12:30', 210.00),
(9, 'São Paulo x Recife', 1, 5, '39:00', 480.00),
(10, 'Fortaleza x Curitiba', 4, 3, '51:00', 590.00),
(11, 'Curitiba x Jericoacoara', 3, 6, '50:20', 640.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `login`, `senha`, `perfil_id`) VALUES
(1, 'Walter White', 'heisenberg', '202cb962ac59075b964b07152d234b70', 1),
(4, 'Willem Ricardo', 'wlricardo', '12b1e42dc0746f22cf361267de07073f', 3),
(5, 'Saul Goodman', 'better_call_saul', '202cb962ac59075b964b07152d234b70', 2),
(6, 'Jesse Pinkman', 'peekaboo', '202cb962ac59075b964b07152d234b70', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculo`
--

CREATE TABLE `veiculo` (
  `id` int(11) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `poltrona` varchar(50) NOT NULL,
  `tipo` enum('Executivo','Semi-leito','Leito') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `veiculo`
--

INSERT INTO `veiculo` (`id`, `marca`, `modelo`, `poltrona`, `tipo`) VALUES
(1, 'Marcopolo', 'Paradiso G8 1200', '46', 'Executivo'),
(2, 'Mercedes-Benz', 'O500 RSD', '42', 'Semi-leito'),
(3, 'Scania', 'K400 B8x2', '26', 'Leito'),
(4, 'Volvo', 'B450R Comil', '44', 'Executivo'),
(5, 'Irizar', 'I6s Efficient', '38', 'Leito'),
(6, 'Marcopolo', 'Paradiso G8 1800 DD', '44', 'Leito'),
(7, 'Mercedes-Benz', 'O500 RSD New CC', '46', 'Executivo'),
(8, 'Scania', 'K440 IB Tourer', '42', 'Semi-leito'),
(9, 'Volvo', 'B420R Plus', '26', 'Leito'),
(10, 'Irizar', 'I8 Luxury', '36', 'Leito'),
(11, 'Marcopolo', 'Viaggio 1050', '48', 'Executivo'),
(12, 'Mercedes-Benz', 'O500 R', '46', 'Executivo'),
(13, 'Scania', 'K360 IB', '44', 'Semi-leito'),
(14, 'Volvo', 'B380R Comfort', '42', 'Semi-leito'),
(15, 'Comil', 'Campione Invictus DD', '28', 'Leito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `viagem`
--

CREATE TABLE `viagem` (
  `id` int(11) NOT NULL,
  `rota_id` int(11) NOT NULL,
  `veiculo_id` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `valor` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `viagem`
--

INSERT INTO `viagem` (`id`, `rota_id`, `veiculo_id`, `data`, `valor`) VALUES
(1, 1, 6, '2026-06-15 08:00:00', 180.00),
(2, 2, 7, '2026-06-15 14:30:00', 110.00),
(3, 3, 8, '2026-06-16 22:00:00', 225.00),
(4, 4, 9, '2026-06-17 06:15:00', 675.00),
(5, 5, 10, '2026-06-18 10:00:00', 780.00),
(6, 6, 11, '2026-06-19 13:00:00', 120.00),
(7, 7, 12, '2026-06-20 20:15:00', 180.00),
(8, 8, 13, '2026-06-21 17:00:00', 262.50),
(9, 9, 14, '2026-06-22 23:30:00', 600.00),
(10, 10, 15, '2026-06-23 05:00:00', 885.00),
(12, 11, 5, '2026-12-20 00:00:00', 832.00),
(13, 6, 9, '2026-06-13 00:00:00', 156.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `cidade`
--
ALTER TABLE `cidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cidade_estado` (`estado_id`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Índices de tabela `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reserva_usuario` (`usuario_id`),
  ADD KEY `fk_reserva_cliente` (`cliente_id`),
  ADD KEY `fk_reserva_viagem` (`viagem_id`);

--
-- Índices de tabela `rota`
--
ALTER TABLE `rota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rota_origem` (`cidade_origem_id`),
  ADD KEY `fk_rota_destino` (`cidade_destino_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `fk_usuario_perfil` (`perfil_id`);

--
-- Índices de tabela `veiculo`
--
ALTER TABLE `veiculo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `viagem`
--
ALTER TABLE `viagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_viagem_rota` (`rota_id`),
  ADD KEY `fk_viagem_veiculo` (`veiculo_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cidade`
--
ALTER TABLE `cidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `estado`
--
ALTER TABLE `estado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `rota`
--
ALTER TABLE `rota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `veiculo`
--
ALTER TABLE `veiculo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `viagem`
--
ALTER TABLE `viagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cidade`
--
ALTER TABLE `cidade`
  ADD CONSTRAINT `fk_cidade_estado` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`);

--
-- Restrições para tabelas `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_reserva_viagem` FOREIGN KEY (`viagem_id`) REFERENCES `viagem` (`id`);

--
-- Restrições para tabelas `rota`
--
ALTER TABLE `rota`
  ADD CONSTRAINT `fk_rota_destino` FOREIGN KEY (`cidade_destino_id`) REFERENCES `cidade` (`id`),
  ADD CONSTRAINT `fk_rota_origem` FOREIGN KEY (`cidade_origem_id`) REFERENCES `cidade` (`id`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`);

--
-- Restrições para tabelas `viagem`
--
ALTER TABLE `viagem`
  ADD CONSTRAINT `fk_viagem_rota` FOREIGN KEY (`rota_id`) REFERENCES `rota` (`id`),
  ADD CONSTRAINT `fk_viagem_veiculo` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
