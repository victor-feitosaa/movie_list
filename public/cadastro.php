<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111111] text-white">
    <div class="container mx-auto p-6 flex flex-col justify-center min-h-screen">
        <h1 class="text-3xl font-bold text-left mb-4">Cadastrar Filme</h1>

        <div class="bg-[#111111] py-6 rounded-lg shadow-lg">
            <form action="salvar.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block font-semibold">Título:</label>
                    <input type="text" name="titulo" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Sinopse:</label>
                    <textarea name="sinopse" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white resize-none"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Duração (minutos):</label>
                    <input type="number" name="duracao" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>


                <div class="mb-4">
                    <label class="block">Gênero:</label>
                    <select name="genero" id="genero" class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                        <option value="">Selecione um gênero</option> <!-- Deixa o primeiro item vazio -->
                        <?php
                        require_once 'conexao.php';
                        $pdo = Conexao::conectar();
                        $stmt = $pdo->query("SELECT id, nome FROM generos");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['id']}'>{$row['nome']}</option>";
                        }
                        Conexao::desconectar();
                        ?>
                    </select>
                </div>


                <div class="mb-4">
                    <label class="block font-semibold">Imagem do Filme:</label>
                    <input type="file" name="imagem" accept="image/*" required class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white">
                </div>

                <div class="mb-4">
                    <label class="block">URL do Trailer (YouTube):</label>
                    <input type="url" name="trailer" class="w-full p-2 rounded bg-[#111111] border-[1.5px] border-white text-white" placeholder="URL do trailer">

                </div>

                <div class="flex justify-between">
                    <a href="index.php" class="bg-[#111111] border-[1.5px] border-white px-4 py-2 rounded hover:bg-gray-900 font-medium px-8">Voltar</a>
                    <button type="submit" class="bg-white text-black px-4 py-2 rounded hover:bg-gray-300 font-medium px-6">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
