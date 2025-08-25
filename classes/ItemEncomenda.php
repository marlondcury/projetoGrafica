<?php

class ItemEncomenda {
    private $id;
    private $servico_id; 
    private $quantidade;
    private $valor_unitario;
    private $atributos;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

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