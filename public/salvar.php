<?php
session_start();
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtendo os dados do formulário
    $titulo = trim($_POST["titulo"] ?? "");
    $sinopse = trim($_POST["sinopse"] ?? "");
    $duracao = trim($_POST["duracao"] ?? "");
    $data_lancamento = trim($_POST["data_lancamento"] ?? "");
    $trailer_link = trim($_POST["trailer"] ?? "");
    $genero_id = $_POST["genero"] ?? "";
    $novo_genero = trim($_POST["novo_genero"] ?? "");

    // Verifica se o campo imagem foi preenchido
    if (empty($_FILES["imagem"]["name"])) {
        die("Erro: O campo imagem é obrigatório.");
    }
    

    // Tratando o upload da imagem
    $diretorioDestino = "uploads/";  // Caminho relativo a partir da raiz pública
    $nomeArquivo = basename($_FILES["imagem"]["name"]);  // Obtém o nome do arquivo
    $caminhoArquivo = $diretorioDestino . $nomeArquivo;  // Caminho relativo no banco de dados
    
    // Verifica se o arquivo foi movido corretamente
    if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoArquivo)) {
        die("Erro ao fazer upload da imagem.");
    }

    // Se nenhum gênero foi escolhido e nenhum novo gênero foi digitado, mostra erro
    if (empty($genero_id) && empty($novo_genero)) {
        die("Erro: Selecione um gênero ou digite um novo.");
    }

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (empty($titulo) || empty($sinopse) || empty($duracao) || empty($trailer_link)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    $pdo = Conexao::conectar();

    try {
        $pdo->beginTransaction();

        // Se o usuário digitou um novo gênero, adiciona ao banco e pega o ID
        if (!empty($novo_genero)) {
            $stmt = $pdo->prepare("INSERT INTO generos (nome) VALUES (?)");
            $stmt->execute([$novo_genero]);
            $genero_id = $pdo->lastInsertId();
        }

        // Insere o filme na tabela filmes, com o caminho correto da imagem
        $stmt = $pdo->prepare("INSERT INTO filmes (titulo, sinopse, duracao, data_lancamento, imagem, trailer_link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $sinopse, $duracao, $data_lancamento, $caminhoArquivo, $trailer_link]);
        $filme_id = $pdo->lastInsertId();

        // Insere a relação na tabela filme_genero
        $stmt = $pdo->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
        $stmt->execute([$filme_id, $genero_id]);

        $pdo->commit();

        // Definir uma variável de sessão para sucesso
        $_SESSION['filme_cadastrado'] = 'Filme cadastrado com sucesso!';
        
        // Redireciona para a página de listagem de filmes (index.php)
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao salvar: " . $e->getMessage());
    }

    Conexao::desconectar();
}
?>
