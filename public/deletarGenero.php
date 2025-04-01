<?php
require_once '../includes/db.php';
require_once 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $pdo = Conexao::conectar();
    $stmt = $pdo->prepare("DELETE FROM generos WHERE id = ?");
    $stmt->execute([$id]);
    Conexao::desconectar();

    header("Location: cadastroGenero.php");
    exit;
}
?>
