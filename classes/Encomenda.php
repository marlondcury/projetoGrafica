<?php
require_once 'Cliente.php';
require_once 'ItemEncomenda.php';

class Encomenda {
    private $id;
    private $cliente; // Objeto da classe Cliente
    private $itens_encomenda = []; // Array de objetos ItemEncomenda
    private $data_encomenda;
    private $valor_total;
    private $status; // Ex: 'em_aberto', 'pago', 'concluido'
    private $data_vencimento_boleto;
    
    // --- Getters e Setters ---

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getCliente() {
        return $this->cliente;
    }
    public function setCliente(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function getItensEncomenda() {
        return $this->itens_encomenda;
    }
    // Método para adicionar um item à encomenda
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
    
    // Método para calcular o valor total da encomenda com base nos itens
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