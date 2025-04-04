<?php
require_once 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$pdo = Conexao::conectar();

// Buscar dados do filme
$stmt = $pdo->prepare("SELECT * FROM filmes WHERE id = ?");
$stmt->execute([$id]);
$filme = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar todos os gêneros
$stmt = $pdo->query("SELECT id, nome FROM generos");
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar gêneros do filme
$stmt = $pdo->prepare("SELECT genero_id FROM filme_genero WHERE filme_id = ?");
$stmt->execute([$id]);
$generos_filme = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

Conexao::desconectar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111111] text-white">
    <div class="container mx-auto p-6 flex flex-col justify-center min-h-screen">
        <h1 class="text-3xl font-bold text-left py-4 mb-4">Editar Filme</h1>

        <div class="bg-[#111111] py-6 rounded-lg shadow-lg ">
            <form action="atualizar.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $filme['id'] ?>">

                <div class="mb-4">
                    <label class="block py-2 font-semibold">Título:</label>
                    <input type="text" name="titulo" value="<?= $filme['titulo'] ?>" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>

                <div class="mb-4">
                    <label class="block py-2 font-semibold">Sinopse:</label>
                    <textarea name="sinopse" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white resize-none"><?= $filme['sinopse'] ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block py-2 font-semibold">Duração (minutos):</label>
                    <input type="number" name="duracao" value="<?= $filme['duracao'] ?>" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>


                <div class="mb-4">
                    <label class="block py-2 font-semibold">URL do Trailer (YouTube):</label>
                    <input type="url" name="trailer" value="<?= $filme['trailer_link'] ?? '' ?>" class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>
                
                <div class="mb-4">
                    <label class="block py-2 font-semibold">Imagem do Filme:</label>
                    <input type="file" name="imagem" class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                    <!-- Exibir imagem atual -->
                    <?php if (empty($filme['imagem'])): ?>
                        <div class="mt-2">
                            <img src="uploads/<?= $filme['imagem'] ?>" alt="Imagem do filme" class="w-32 h-32 object-cover">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="block py-2 font-semibold">Gênero:</label>
                    <select name="generos[]" class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white" multiple required>
                        <option value="" disabled>Selecione o gênero</option>
                        <?php
                        require_once 'conexao.php';
                        $pdo = Conexao::conectar();
                        $stmt = $pdo->query("SELECT id, nome FROM generos");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Verifica se o gênero está marcado para o filme
                            $selected = in_array($row['id'], $generos_filme) ? "selected" : "";
                            echo "<option value='{$row['id']}' $selected>{$row['nome']}</option>";
                        }
                        Conexao::desconectar();
                        ?>
                    </select>
                </div>


                <div class="flex justify-between py-2">
                    <a href="index.php" class="bg-[#111111] border-[1.5px] border-white font-medium hover:bg-gray-900 px-8 py-2 rounded">Voltar</a>
                    <button type="submit" class="bg-white font-medium hover:bg-gray-300 text-black px-6 py-2 rounded ">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
