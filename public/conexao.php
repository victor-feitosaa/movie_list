<?php
class Conexao {
    private static $dbNome = 'movie_list';
    private static $dbHost = 'localhost';
    private static $dbUsuario = 'root';
    private static $dbSenha = '';
    private static $cont = null;

    public static function conectar() {
        if (self::$cont == null) {
            try {
                self::$cont = new PDO("mysql:host=" . self::$dbHost . ";dbname=" . self::$dbNome, self::$dbUsuario, self::$dbSenha);
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                die("Erro na conexão: " . $exception->getMessage());
            }
        }
        return self::$cont;
    }

    public static function desconectar() {
        self::$cont = null;
    }
}
?>
