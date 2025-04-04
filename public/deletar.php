<?php
require_once 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo = Conexao::conectar();
    $stmt = $pdo->prepare("DELETE FROM filmes WHERE id = ?");
    $stmt->execute([$id]);
    Conexao::desconectar();

    header("Location: index.php");
    exit;
}
?>
