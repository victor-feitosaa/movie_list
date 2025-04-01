<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#111111] text-white">
    <div class="container mx-auto p-10 flex flex-col justify-center min-h-screen">
        <div class="flex justify-between">
            <a href="index.php" class="mb-4"><img class="h-[40px] w-[30px]" src="../assets/back-arrow.png" alt="" ></a>
        </div>

        <h1 class="text-3xl font-bold text-left mb-4">Editar Gêneros</h1>

        <!-- Exibir mensagem de sucesso ou erro -->
        <?php if (isset($_SESSION['genero_cadastrado'])): ?>
            <div class="mb-4 p-4 bg-green-500 text-white rounded-lg">
                <?php echo $_SESSION['genero_cadastrado']; ?>
            </div>
            <?php unset($_SESSION['genero_cadastrado']); ?>
        <?php endif; ?>

        <div class="bg-[#111111]  rounded-lg  flex justify-between w-full gap-10 ">
            <form action="salvarGeneros.php" method="POST" enctype="multipart/form-data" class="w-1/2">

                <div class="mb-4 flex flex-col gap-4 justify-center h-full">
                    <label class="block font-semibold text-xl">Cadastrar Gênero:</label>
                    <input type="text" name="novo_genero" required class="w-full p-2 rounded-lg bg-[#111111] border-[1.5px] border-white text-white" placeholder="Novo gênero">
                    <button type="submit" class="bg-white text-black px-4 py-2 rounded hover:bg-gray-300 font-medium px-6 mt-4">Adicionar gênero</button>
                </div>
            </form>

            <hr class="border-[1.5px] border-gray-200 h-[550px] "> 

            <div class="w-1/2 flex flex-col gap-4  ">

                <h1 class="font-semibold text-xl">Gêneros Cadastrados: </h1>

                <div class="max-h-[500px] overflow-y-scroll pr-4">

                    <?php
                    require_once 'conexao.php';
                    $pdo = Conexao::conectar();
                    $stmt = $pdo->query("SELECT generos.nome, generos.id
                                 FROM generos 
                                ");

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="">
                            <div class="w-full p-4 border-[1.5px] border-white rounded-lg mb-4 flex justify-between">
                                <span><?php echo $row['nome']; ?></span>

                                <a href="excluirGenero.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:underline">
                                    <img class="h-[20px] w-[20px]" src="../assets/delete.png" alt="Excluir">
                                </a>
                            </div>

                        </div>
                    <?php
                    }
                    Conexao::desconectar()
                    ?>
                </div>

            </div>

        </div>


    </div>
</body>

</html>