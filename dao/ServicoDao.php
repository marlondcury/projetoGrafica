<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Servico.php';

class ServicoDao {

    public function listarTodos() {
        $sql = 'SELECT * FROM servicos ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        
        $servicos = [];
        // Mapear resultado para objetos Servico
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $servico = new Servico();
            $servico->setId($dados['id']);
            $servico->setNome($dados['nome']);
            $servico->setDescricao($dados['descricao']);
            $servico->setPrecoBase($dados['preco_base']);
            $servico->setImagemUrl($dados['imagem_url']);
            $servico->setTipoServico($dados['tipo_servico']);
            $servicos[] = $servico;
        }
        return $servicos;
    }
    
    public function buscarPorId($id) {
        $sql = 'SELECT * FROM servicos WHERE id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dados) {
            $servico = new Servico();
            $servico->setId($dados['id']);
            $servico->setNome($dados['nome']);
            $servico->setDescricao($dados['descricao']);
            $servico->setPrecoBase($dados['preco_base']);
            $servico->setImagemUrl($dados['imagem_url']);
            $servico->setTipoServico($dados['tipo_servico']);
            return $servico;
        }
        return null;
    }

    public function criar(Servico $servico) {
        $sql = 'INSERT INTO servicos (nome, descricao, preco_base, imagem_url, tipo_servico) VALUES (?, ?, ?, ?, ?)';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $servico->getNome());
            $stmt->bindValue(2, $servico->getDescricao());
            $stmt->bindValue(3, $servico->getPrecoBase());
            $stmt->bindValue(4, $servico->getImagemUrl());
            $stmt->bindValue(5, $servico->getTipoServico());
            return $stmt->execute();
        } catch (PDOException $e) {
            // Em caso de erro, você pode registrar o erro ou retornar false
            return false;
        }
    }

    public function atualizar(Servico $servico) {
        $sql = 'UPDATE servicos SET nome = ?, descricao = ?, preco_base = ?, imagem_url = ?, tipo_servico = ? WHERE id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $servico->getNome());
            $stmt->bindValue(2, $servico->getDescricao());
            $stmt->bindValue(3, $servico->getPrecoBase());
            $stmt->bindValue(4, $servico->getImagemUrl());
            $stmt->bindValue(5, $servico->getTipoServico());
            $stmt->bindValue(6, $servico->getId());
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function excluir($id) {
        $sql = 'DELETE FROM servicos WHERE id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>