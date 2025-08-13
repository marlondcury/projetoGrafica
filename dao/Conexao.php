<?php
class Conexao {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            try {
                self::$instance = new PDO('mysql:host=localhost;dbname=grafica_db', 'root', '');
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
?>