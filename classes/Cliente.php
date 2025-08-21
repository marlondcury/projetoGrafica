<?php
require_once 'Usuario.php';

class Cliente {
    private $id; 
    private $usuario; 
    private $telefone;
    private $endereco;

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