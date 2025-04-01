<?php
// /api/filmes.php

header("Content-Type: application/json");
include_once '../includes/db.php';
include_once '../includes/Filme.php';

// Criando a conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Instanciando a classe Filme
$filme = new Filme($db);

// Verificando o método HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Listar todos os filmes com seus gêneros
        $query = "
            SELECT f.id, f.titulo, f.sinopse, f.imagem, f.data_lancamento, 
                   f.duracao, f.trailer_link, 
                   GROUP_CONCAT(g.nome SEPARATOR ', ') AS generos
            FROM filmes f
            LEFT JOIN filme_genero fg ON f.id = fg.filme_id
            LEFT JOIN generos g ON fg.genero_id = g.id
            GROUP BY f.id
        ";
    
        $stmt = $db->prepare($query);
        $stmt->execute();
        $filmes_arr = array();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $filme_item = array(
                'id' => $id,
                'titulo' => $titulo,
                'sinopse' => $sinopse,
                'imagem' => $imagem,
                'duracao' => $duracao,
                'data_lancamento' => $data_lancamento,
                'trailer_link' => $trailer_link,
                'generos' => $generos  
            );
            array_push($filmes_arr, $filme_item);
        }
    
        echo json_encode($filmes_arr);
        break;
    

        case 'POST':
            // Cadastrar um novo filme
            $data = json_decode(file_get_contents("php://input"), true);
        
            if (!empty($data['titulo']) && !empty($data['sinopse']) && !empty($data['data_lancamento']) && 
                !empty($data['duracao']) && !empty($data['imagem']) && !empty($data['trailer_link']) && !empty($data['generos'])) {
                
                $filme->titulo = $data['titulo'];
                $filme->sinopse = $data['sinopse'];
                $filme->imagem = $data['imagem'];
                $filme->duracao = $data['duracao'];
                $filme->data_lancamento = $data['data_lancamento'];
                $filme->trailer_link = $data['trailer_link'];
        
                if ($filme->create()) {
                    $filme_id = $db->lastInsertId(); // Pegamos o ID do filme recém-cadastrado
                    
                    // Inserir os gêneros na tabela filme_genero
                    foreach ($data['generos'] as $genero_nome) {
                        // Verifica se o gênero já existe
                        $stmt = $db->prepare("SELECT id FROM generos WHERE nome = ?");
                        $stmt->execute([$genero_nome]);
                        $genero = $stmt->fetch(PDO::FETCH_ASSOC);
        
                        if (!$genero) {
                            // Se não existir, cria um novo gênero
                            $stmt = $db->prepare("INSERT INTO generos (nome) VALUES (?)");
                            $stmt->execute([$genero_nome]);
                            $genero_id = $db->lastInsertId();
                        } else {
                            $genero_id = $genero['id'];
                        }
        
                        // Associa o filme ao gênero
                        $stmt = $db->prepare("INSERT INTO filme_genero (filme_id, genero_id) VALUES (?, ?)");
                        $stmt->execute([$filme_id, $genero_id]);
                    }
        
                    echo json_encode(["message" => "Filme cadastrado com sucesso."]);
                } else {
                    echo json_encode(["message" => "Erro ao cadastrar filme."]);
                }
            } else {
                echo json_encode(["message" => "Dados incompletos."]);
            }
            break;
        

    default:
        echo json_encode(["message" => "Método não permitido."]);
        break;
}
?>
