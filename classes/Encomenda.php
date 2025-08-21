<?php
require_once 'ItemEncomenda.php';

class Encomenda {
    private $id;
    private $cliente_id; 
    private $itens_encomenda = [];
    private $data_encomenda;
    private $valor_total;
    private $status;
    private $data_vencimento_boleto;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getClienteId() {
        return $this->cliente_id;
    }
    public function setClienteId($cliente_id) {
        $this->cliente_id = $cliente_id;
    }

    public function getItensEncomenda() {
        return $this->itens_encomenda;
    }
    public function adicionarItem(ItemEncomenda $item) {
        $this->itens_encomenda[] = $item;
    }

    public function getDataEncomenda() {
        return $this->data_encomenda;
    }
    public function setDataEncomenda($data_encomenda) {
        $this->data_encomenda = $data_encomenda;
    }

    public function getValorTotal() {
        return $this->valor_total;
    }
    public function setValorTotal($valor_total) {
        $this->valor_total = $valor_total;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function getDataVencimentoBoleto() {
        return $this->data_vencimento_boleto;
    }
    public function setDataVencimentoBoleto($data_vencimento_boleto) {
        $this->data_vencimento_boleto = $data_vencimento_boleto;
    }
    
    public function calcularValorTotal() {
        $total = 0;
        foreach ($this->itens_encomenda as $item) {
            $total += $item->getValorUnitario() * $item->getQuantidade();
        }
        $this->valor_total = $total;
        return $total;
    }
}
?>