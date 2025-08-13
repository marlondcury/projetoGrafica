<?php
require_once 'Usuario.php';

class Cliente {
    private $id; // ID da tabela 'clientes'
    private $usuario; // Objeto da classe Usuario
    private $telefone;
    private $endereco;

    // --- Getters e Setters ---

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }
    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function getTelefone() {
        return $this->telefone;
    }
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getEndereco() {
        return $this->endereco;
    }
    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }
}
?>