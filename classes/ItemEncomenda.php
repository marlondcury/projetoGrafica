<?php
// We no longer need to require Servico.php because we are only using its ID.
// require_once 'Servico.php';

class ItemEncomenda {
    private $id;
    // CHANGED: From a Servico object to just the ID
    private $servico_id; 
    private $quantidade;
    private $valor_unitario;
    private $atributos;

    // --- Getters and Setters ---

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    // CHANGED: Methods now get/set the service ID
    public function getServicoId() {
        return $this->servico_id;
    }
    public function setServicoId($servico_id) {
        $this->servico_id = $servico_id;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function getValorUnitario() {
        return $this->valor_unitario;
    }
    public function setValorUnitario($valor_unitario) {
        $this->valor_unitario = $valor_unitario;
    }

    public function getAtributos() {
        return $this->atributos;
    }
    public function setAtributos($atributos) {
        $this->atributos = $atributos;
    }
}
?>