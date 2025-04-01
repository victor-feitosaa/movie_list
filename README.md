Movie List
Este projeto é uma aplicação desenvolvida como prova prática do curso de Análise e Desenvolvimento de Sistemas (ADS), utilizando PHP e PDO para a gestão de uma lista de filmes.​

Funcionalidades
Cadastro de filmes com informações como título, diretor, gênero e ano de lançamento.​

Edição e remoção de registros de filmes existentes.​

Visualização de uma lista completa dos filmes cadastrados.​

Tecnologias Utilizadas
PHP: Linguagem principal para o desenvolvimento da aplicação.​

PDO (PHP Data Objects): API para acesso a bases de dados.​

JavaScript: Utilizado para interatividade no lado do cliente.​

Estrutura do Projeto
api/: Contém os endpoints para comunicação com o banco de dados.​

assets/: Inclui arquivos de estilo, imagens e outros recursos estáticos.​

includes/: Abriga arquivos PHP reutilizáveis, como conexões e funções.​

js/: Contém scripts JavaScript para funcionalidades no cliente.​


Script SQL:

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `filmes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `sinopse` text NOT NULL,
  `imagem` varchar(255) NOT NULL,
  `data_lancamento` date NOT NULL,
  `duracao` int(11) NOT NULL,
  `trailer_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `filme_genero` (
  `filme_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `filmes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `filme_genero`
  ADD PRIMARY KEY (`filme_id`,`genero_id`),
  ADD KEY `genero_id` (`genero_id`);

ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);


ALTER TABLE `filmes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `filme_genero`
  ADD CONSTRAINT `filme_genero_ibfk_1` FOREIGN KEY (`filme_id`) REFERENCES `filmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `filme_genero_ibfk_2` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

.​
