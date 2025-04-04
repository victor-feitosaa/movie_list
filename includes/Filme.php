<?php
// /includes/Filme.php

include_once 'db.php';

class Filme {
    private $conn;
    private $table_name = "filmes";

    public $id;
    public $titulo;
    public $sinopse;
    public $data_lancamento;
    public $duracao;
    public $imagem;
    public $trailer_link;
    public $genero;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para cadastrar um novo filme
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (titulo, sinopse, data_lancamento, duracao, imagem, trailer_link) 
                  VALUES (:titulo, :sinopse, :data_lancamento, :duracao, :imagem, :trailer_link)";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":sinopse", $this->sinopse);
        $stmt->bindParam(":duracao", $this->duracao);
        $stmt->bindParam(":data_lancamento", $this->data_lancamento);
        $stmt->bindParam(":imagem", $this->imagem);
        $stmt->bindParam(":trailer_link", $this->trailer_link);
    
        return $stmt->execute();
    }
    
    

    // Método para listar todos os filmes
    public function read() {
        $query = "SELECT id, titulo, sinopse, data_lancamento, duracao, imagem, trailer_link ,genero FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
