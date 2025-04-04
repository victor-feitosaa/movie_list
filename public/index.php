<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Filmes</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-[#111111] text-white">

    <header>
        <div class="flex justify-between items-center p-7 container mx-auto ">
            <div>
                <a href="index.php">

                    <img src="../assets/clapperboard.png" class="h-[60px] w-[60px]">
                </a>
            </div>


            <div class="flex gap-10 pr-4">
                <a href="" id="tableIcon">
                    <img class="w-[30px] h-[30px]" src="../assets/table-white.png" alt="table view">
                </a>

                <a href="" id="cardsIcon">
                    <img class="w-[30px] h-[30px]" src="../assets/grid-white.png" alt="cards view">
                </a>
            </div>

        </div>
    </header>


    <main>

        <div class="container mx-auto p-6" data-table>
            <h1 class="text-3xl font-bold text-center mb-6"> Lista de Filmes</h1>

            <div class="flex gap-5 items-center justify-between mb-8 pb-2 border-b-white border-b-[1.5px]">

                <?php
                require_once 'conexao.php';
                $pdo = Conexao::conectar();

                // Consulta para contar o total de filmes
                $stmtCount = $pdo->query("SELECT COUNT(*) AS total_filmes FROM filmes");
                $totalFilmes = $stmtCount->fetch(PDO::FETCH_ASSOC)['total_filmes'];

                ?>

                <div class="flex gap-6">

                    <p class="text-white font-semibold">Total de filmes cadastrados: <span class="text-[#9400FF] text-xl font-bold"><?php echo $totalFilmes; ?></span></p>


                </div>


                <div class="mb-4 flex gap-4">
                    <a href="cadastro.php" class=" text-white border-[1.5px] border-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-gray-500">➕ Editar Gêneros</a>

                    <a href="cadastro.php" class="bg-white text-black font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-gray-300">➕ Cadastrar Filme</a>
                </div>
            </div>

            <div class="bg-[#111111] p-4 rounded-lg shadow-lg">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-600">
                            <th class="p-2 text-left">Título</th>

                            <th class="p-2 text-left">Gênero</th>

                            <th class="p-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'conexao.php';
                        $pdo = Conexao::conectar();

                        $stmt = $pdo->query("SELECT filmes.id, filmes.titulo, filmes.sinopse, filmes.duracao, g.nome AS genero 
                                             FROM filmes 
                                             JOIN filme_genero fg ON filmes.id = fg.filme_id 
                                             JOIN generos g ON fg.genero_id = g.id");

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr class='border-b border-gray-700'>";
                            echo "<td class='p-2'>{$row['titulo']}</td>";
                            echo "<td class='p-2'>{$row['genero']}</td>";
                            echo "<td class='p-2'>
                            <a href='editar.php?id={$row['id']}' class='text-blue-400 hover:underline'>Editar</a> |
                            <a href='excluir.php?id={$row['id']}' class='text-red-400 hover:underline'>Excluir</a>
                            </td>";

                            echo "</tr>";
                        }
                        Conexao::desconectar();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


        <section class="container mx-auto min-h-screen flex flex-col justify-between p-8" data-cards style="display: none;">
            <div class="">

                <div class="flex  items-center justify-between mb-8 pb-2 border-b-white border-b-[1.5px]">

                    <?php
                    require_once 'conexao.php';
                    $pdo = Conexao::conectar();

                    // Consulta para contar o total de filmes
                    $stmtCount = $pdo->query("SELECT COUNT(*) AS total_filmes FROM filmes");
                    $totalFilmes = $stmtCount->fetch(PDO::FETCH_ASSOC)['total_filmes'];

                    ?>

                    <div class="flex gap-6">

                        <p class="text-white font-semibold">Total de filmes cadastrados: <span class="text-[#9400FF] text-xl font-bold"><?php echo $totalFilmes; ?></span></p>


                    </div>


                    <div class="procurar-filme flex items-center border-b-[1.5px] border-white">
                        <form action="" method="GET" class="flex items-center">
                            <input type="text" name="search" placeholder="Procure um filme..."
                                class="w-[500px] outline-none p-2 bg-[#111111] text-white"
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <button type="submit"><img src="../assets/search.png" alt="" class="h-[20px] w-[20px]"></button>
                        </form>

                        <button type="button" id="btn-abrir-filtro"><img src="../assets/filter.png" alt="" class="btn-filtro h-[25px] w-[25px] mx-4 border-0"></button>
                    </div>


                    <div class="mb-2 flex gap-4">
                        <a href="cadastroGenero.php" class=" text-white border-[1.5px] border-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-gray-500">➕ Editar Gêneros</a>

                        <a href="cadastro.php" class="bg-white text-black font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-gray-300">➕ Cadastrar Filme</a>
                    </div>
                </div>

                <div>
                    <h1 class="text-2xl font-bold mb-4">Catálogo: </h1>
                    <div class="min-w-full flex flex-wrap gap-[4rem]">
                        <?php
                        require_once 'conexao.php';
                        $pdo = Conexao::conectar();

                        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $generosSelecionados = isset($_GET['filtro_genero']) ? $_GET['filtro_genero'] : [];
                        
                        // Base da consulta
                        $sql = "SELECT filmes.id, filmes.titulo, filmes.sinopse, filmes.duracao, filmes.imagem, filmes.trailer_link, g.nome AS genero 
                                FROM filmes 
                                JOIN filme_genero fg ON filmes.id = fg.filme_id 
                                JOIN generos g ON fg.genero_id = g.id";
                        
                        // Adicionar condição de busca por título e filtro de gênero
                        $conditions = [];
                        $params = [];
                        
                        if (!empty($search)) {
                            $conditions[] = "filmes.titulo LIKE :search";
                            $params[':search'] = "%$search%";
                        }
                        
                        if (!empty($generosSelecionados)) {
                            $placeholders = implode(',', array_fill(0, count($generosSelecionados), '?'));
                            $conditions[] = "g.id IN ($placeholders)";
                        }
                        
                        if (!empty($conditions)) {
                            $sql .= " WHERE " . implode(" AND ", $conditions);
                        }
                        
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(array_merge(array_values($generosSelecionados), $params));
                        


                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                            <div class="card w-[300px] max-h-[490px] bg-[#1A1A1A] border-[1.5px] border-white px-4 flex flex-col justify-center rounded-lg shadow-[rgba(0,_0,_0,_0.24)_0px_3px_8px] overflow-hidden">
                                <div class="text-xs font-semibold py-1.5 w-full flex justify-between">
                                    <span>ID: <?php echo $row['id']; ?></span>
                                </div>

                                <div class="flex justify-center py-2 bg-blue-500 rounded-lg h-[252px]">
                                    <img src="<?= $row['imagem'] ?>" alt="<?= $row['titulo'] ?>" class="w-full object-cover rounded">
                                </div>

                                <div class="card-container--info">
                                    <div class="nomeFilme py-5 font-semibold">
                                        <h4 class="text-lg"><?php echo $row['titulo']; ?></h4>
                                        <div class="flex gap-2 pt-3">
                                            <span class="font-medium text-xs text-gray-300 genero"><?php echo $row['genero']; ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="py-6 flex gap-5 justify-between">

                                    <button id="btn-modal" class="ver-detalhes p-2 w-1/2 border-2 border-white rounded-lg text-[14px]">Ver detalhes</button>

                                    <div class="gap-2 flex">
                                        <a href="editar.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">
                                            <img class="h-[30px] w-[30px]" src="../assets/edit.png" alt="Editar">
                                        </a> |
                                        <a href="excluir.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:underline">
                                            <img class="h-[30px] w-[30px]" src="../assets/delete.png" alt="Excluir">
                                        </a>
                                    </div>
                                </div>

                                <!-- Adicionando sinopse e duração com classes específicas -->
                                <div class="sinopse hidden"><?php echo $row['sinopse']; ?></div>
                                <div class="duracao hidden"><?php echo $row['duracao']; ?> min</div>
                                <div class="trailer_link hidden"><?php echo $row['trailer_link']; ?> </div>
                            </div>


                        <?php
                        }
                        Conexao::desconectar();
                        ?>


                    </div>
                </div>

            </div>
            </div>
        </section>



    </main>



    <!-- Modal do filme -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-[#222222] text-white px-16 py-3 rounded-lg shadow-lg w-[1200px] h-[650px] max-w-full flex flex-col gap-5 overflow-hidden">

            <img class="h-[30px] w-[30px] cursor-pointer my-4 " id="close-modal" src="../assets/back-arrow.png" alt="">

            <div class="flex flex-wrap gap-[20%]">

                <div class="">
                    <h2 id="modal-title" class="text-2xl font-bold"></h2>
                    <img id="modal-image" src="" alt="" class="w-[200px] h-60 object-cover rounded my-4 border-2 border-white">
                </div>

                <div class="h-60 ">
                    <h4 class="font-bold text-xl mb-4">Trailer:</h4>
                    <iframe id="modal-trailer"
                        class="border-[1.5px] border-white rounded-lg"
                        width="420"
                        height="260"
                        src="">
                    </iframe>

                </div>

            </div>

            <p class=" mt-2 font-bold ">
                Gênero: <span id="modal-genre" class="font-semibold"></span><br>
                Duração: <span id="modal-duration" class="font-semibold"></span>
            </p>

            <div class="flex flex-col gap2 ">
                <h4 class="font-bold">Sinopse:</h4>
                <p id="modal-synopsis" class="text-sm text-gray-400 overflow-y-scroll h-[80px]"></p>
            </div>

        </div>
    </div>


    <!-- Modal de Filtro -->
    <div id="modal-filtro" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden ">
        <div class="bg-[#222222] text-white px-8 py-6  rounded-lg shadow-lg w-[400px]">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold">Filtrar por Gênero</h2>
                <button id="btn-fechar-filtro" class="text-red-500 text-xl font-bold">✖</button>
            </div>

            <form action="" method="GET" class="mt-4">
                <?php
                require_once 'conexao.php';
                $pdo = Conexao::conectar();

                // Consulta para obter todos os gêneros
                $stmt = $pdo->query("SELECT id, nome FROM generos");
                while ($genero = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<label class='block my-2'>
                            <input type='checkbox' name='filtro_genero[]' value='{$genero['id']}' " . 
                            (isset($_GET['filtro_genero']) && in_array($genero['id'], $_GET['filtro_genero']) ? "checked" : "") . ">
                            {$genero['nome']}
                        </label>";
                }

                Conexao::desconectar();
                ?>
                <button type="submit" class="mt-4 bg-[#9400FF] px-4 py-2 rounded-lg w-full text-white font-semibold">Filtrar</button>
            </form>
        </div>
    </div>
    <script>
    document.getElementById("btn-abrir-filtro").addEventListener("click", function () {
        document.getElementById("modal-filtro").classList.remove("hidden");
    });

    document.getElementById("btn-fechar-filtro").addEventListener("click", function () {
        document.getElementById("modal-filtro").classList.add("hidden");
    });
    </script>


    
    <script src="../js/view.js"></script>
</body>

</html>