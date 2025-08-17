<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Servico.php';

class ServicoDao {

    public function listarTodos() {
        // Retorna arrays associativos para compatibilidade com as views
        $sql = 'SELECT id, nome, descricao, preco_base, imagem_url, tipo_servico FROM servicos ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorId($id) {
        $sql = 'SELECT id, nome, descricao, preco_base, imagem_url, tipo_servico FROM servicos WHERE id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ?: null;
    }

    /**
     * Busca serviços aplicando filtros opcionais.
     * Aceita filtros: 'id' (int), 'nome' (string, busca parcial) e 'tipo' (string, busca parcial/exata).
     * Retorna array de resultados (associativos).
     */
    public function buscar(array $filtros = []) {
        $sql = 'SELECT id, nome, descricao, preco_base, imagem_url, tipo_servico FROM servicos WHERE 1=1';
        $params = [];

        if (!empty($filtros['id'])) {
            $sql .= ' AND id = :id';
            $params[':id'] = (int) $filtros['id'];
        }

        if (!empty($filtros['nome'])) {
            $sql .= ' AND nome LIKE :nome';
            $params[':nome'] = '%' . $filtros['nome'] . '%';
        }

        if (!empty($filtros['tipo'])) {
            $sql .= ' AND tipo_servico LIKE :tipo';
            $params[':tipo'] = '%' . $filtros['tipo'] . '%';
        }

        $sql .= ' ORDER BY nome ASC';

        $stmt = Conexao::getInstance()->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retorna a lista de tipos de serviço existentes (distinct) para popular selects.
     */
    public function listarTipos() {
        $sql = 'SELECT DISTINCT tipo_servico FROM servicos ORDER BY tipo_servico ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $tipos = [];
        foreach ($rows as $r) {
            if (!empty($r['tipo_servico'])) $tipos[] = $r['tipo_servico'];
        }
        return $tipos;
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