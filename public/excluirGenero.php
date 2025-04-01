<?php
require_once '../includes/db.php';
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$pdo = Conexao::conectar();
$stmt = $pdo->prepare("SELECT nome FROM generos WHERE id = ?");
$stmt->execute([$id]);
$genero = $stmt->fetch(PDO::FETCH_ASSOC);
Conexao::desconectar();

if (!$genero) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Gênero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111111] text-white">
    <div class=" w-full min-h-screen flex flex-col justify-center items-center p-6 text-center">
        <h1 class="text-3xl font-bold mb-4">⚠️ Excluir Gênero</h1>
        <p class="text-lg">Tem certeza que deseja excluir <strong><?= $genero['nome'] ?></strong>?</p>

        <div class="mt-6 flex justify-center gap-4">
            <a href="cadastroGenero.php" class="bg-gray-600 px-4 py-2 rounded">Cancelar</a>
            <a href="deletarGenero.php?id=<?= $id ?>" class="bg-red-600 px-4 py-2 rounded text-white">Sim, Excluir</a>
        </div>
    </div>
</body>
</html>
