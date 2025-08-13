<?php

class Servico {
    private $id;
    private $nome;
    private $descricao;
    private $preco_base;
    private $imagem_url;
    private $tipo_servico; // Ex: 'reproducao', 'banner', 'caneca'

    // --- Getters e Setters ---

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDescricao() {
        return $this->descricao;
    }
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getPrecoBase() {
        return $this->preco_base;
    }
    public function setPrecoBase($preco_base) {
        $this->preco_base = $preco_base;
    }

    public function getImagemUrl() {
        return $this->imagem_url;
    }
    public function setImagemUrl($imagem_url) {
        $this->imagem_url = $imagem_url;
    }

    public function getTipoServico() {
        return $this->tipo_servico;
    }
    public function setTipoServico($tipo_servico) {
        $this->tipo_servico = $tipo_servico;
    }
}
?>