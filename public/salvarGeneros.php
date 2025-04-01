<?php 

session_start();
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_genero = trim($_POST["novo_genero"] ?? "");

    $pdo = Conexao::conectar();

    try {
        $pdo->beginTransaction();
    
        if (!empty($novo_genero)) {
            $stmt = $pdo->prepare("INSERT INTO generos (nome) VALUES (?)");
            $stmt->execute([$novo_genero]);
            
            // Verifica se a inserção foi bem-sucedida
            if ($stmt->rowCount() > 0) {
                // Commit da transação
                $pdo->commit();
                
                // Definir uma variável de sessão para sucesso
                $_SESSION['genero_cadastrado'] = 'Gênero cadastrado com sucesso!';
            } else {
                // Rollback em caso de falha
                $pdo->rollBack();
                $_SESSION['genero_cadastrado'] = 'Erro ao cadastrar o gênero.';
            }
        } else {
            $_SESSION['genero_cadastrado'] = 'O nome do gênero não pode estar vazio.';
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['genero_cadastrado'] = 'Erro ao salvar: ' . $e->getMessage();
    }

    Conexao::desconectar();

    // Redireciona para a página de listagem de generos (cadastroGenero.php)
    header("Location: cadastroGenero.php");
    exit();
}
?>