<?php

require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $sinopse = $_POST['sinopse'];
    $duracao = $_POST['duracao'];
    $data_lancamento = date('Y-m-d', strtotime($_POST['data_lancamento']));
    $trailer_link = $_POST['trailer'];
    $generos = $_POST['generos'] ?? []; // Verifica se 'generos' foi enviado

    $pdo = Conexao::conectar();

    try {
        // Tratamento do Upload da Imagem
        if (!empty($_FILES['imagem']['name'])) {
            $diretorioDestino = "uploads/";
            $imagemNome = basename($_FILES["imagem"]["name"]);
            $targetFile = $diretorioDestino . $imagemNome;

            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $targetFile)) {
                // Atualizar o banco com a nova imagem
                $stmt = $pdo->prepare("UPDATE filmes SET titulo = ?, sinopse = ?, imagem = ?, trailer_link = ?, duracao = ?, data_lancamento = ? WHERE id = ?");
                $stmt->execute([$titulo, $sinopse, $targetFile, $trailer_link, $duracao, $data_lancamento, $id]);
            } else {
                throw new Exception("Erro ao enviar a imagem.");
            }
        } else {
            // Se nenhuma nova imagem for enviada, mantém a existente
            $stmt = $pdo->prepare("UPDATE filmes SET titulo = ?, sinopse = ?, trailer_link = ?, duracao = ?, data_lancamento = ? WHERE id = ?");
            $stmt->execute([$titulo, $sinopse, $trailer_link, $duracao, $data_lancamento, $id]);
        }

        // Remover gêneros antigos
        $stmt = $pdo->prepare("DELETE FROM filme_genero WHERE filme_id = ?");
        $stmt->execute([$id]);

        // Verifica se 'generos' está preenchido antes de inserir
        if (!empty($generos)) {
            $stmt = $pdo->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
            foreach ($generos as $genero) {
                $stmt->execute([$id, $genero]);
            }
        } else {
            throw new Exception("Erro: Nenhum gênero foi selecionado.");
        }

        // Adicionar novo gênero caso tenha sido digitado
        if (!empty($_POST['novo_genero'])) {
            $novo_genero = trim($_POST['novo_genero']);

            // Verifica se o gênero já existe
            $stmt = $pdo->prepare("SELECT id FROM generos WHERE nome = ?");
            $stmt->execute([$novo_genero]);
            $generoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$generoExistente) {
                // Insere o novo gênero
                $stmt = $pdo->prepare("INSERT INTO generos (nome) VALUES (?)");
                $stmt->execute([$novo_genero]);
                $novo_genero_id = $pdo->lastInsertId();

                // Associa o novo gênero ao filme
                $stmt = $pdo->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
                $stmt->execute([$id, $novo_genero_id]);
            } else {
                // O gênero já existe, apenas associa ao filme
                $stmt = $pdo->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
                $stmt->execute([$id, $generoExistente['id']]);
            }
        }

        Conexao::desconectar();
        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        Conexao::desconectar();
        echo "Erro: " . $e->getMessage();
        exit;
    }
}
?>
