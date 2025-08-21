<?php
require_once 'Conexao.php';
require_once '../classes/Encomenda.php';


class EncomendaDao {

    public function criar(Encomenda $encomenda) {
        $pdo = Conexao::getInstance();
        
        try {
            $pdo->beginTransaction();

            $sqlEncomenda = 'INSERT INTO encomendas (cliente_id, data_encomenda, valor_total, status) VALUES (?, ?, ?, ?)';
            $stmtEncomenda = $pdo->prepare($sqlEncomenda);
            $stmtEncomenda->bindValue(1, $encomenda->getClienteId());
            $stmtEncomenda->bindValue(2, $encomenda->getDataEncomenda());
            $stmtEncomenda->bindValue(3, $encomenda->getValorTotal());
            $stmtEncomenda->bindValue(4, $encomenda->getStatus());
            $stmtEncomenda->execute();
            
            $encomendaId = $pdo->lastInsertId();

            $sqlItem = 'INSERT INTO itens_encomenda (encomenda_id, servico_id, quantidade, valor_unitario, atributos) VALUES (?, ?, ?, ?, ?)';
            $stmtItem = $pdo->prepare($sqlItem);

            foreach ($encomenda->getItensEncomenda() as $item) {
                $stmtItem->bindValue(1, $encomendaId);
                $stmtItem->bindValue(2, $item->getServicoId());
                $stmtItem->bindValue(3, $item->getQuantidade());
                $stmtItem->bindValue(4, $item->getValorUnitario());
                $stmtItem->bindValue(5, $item->getAtributos());
                $stmtItem->execute();
            }

            $pdo->commit();
            
            return $encomendaId;

        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Erro ao criar encomenda: " . $e->getMessage());
            return false;
        }
    }

    public function listarPorClienteId($cliente_id) {
        $sql = 'SELECT * FROM encomendas WHERE cliente_id = ? ORDER BY data_encomenda DESC';
        
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Erro ao listar encomendas por cliente: " . $e->getMessage());
        }
    }

    public function listarTodos() {
        $sql = 'SELECT * FROM encomendas ORDER BY data_encomenda DESC';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro ao listar todas as encomendas: " . $e->getMessage());
        }
    }
    
    public function buscarDetalhesPorId($id) {
        $pdo = Conexao::getInstance();
        try {
            $sqlEncomenda = 'SELECT * FROM encomendas WHERE id = ?';
            $stmtEncomenda = $pdo->prepare($sqlEncomenda);
            $stmtEncomenda->bindValue(1, $id, PDO::PARAM_INT);
            $stmtEncomenda->execute();
            $dadosEncomenda = $stmtEncomenda->fetch(PDO::FETCH_ASSOC);

            if (!$dadosEncomenda) {
                return null;
            }

            $sqlItens = 'SELECT * FROM itens_encomenda WHERE encomenda_id = ?';
            $stmtItens = $pdo->prepare($sqlItens);
            $stmtItens->bindValue(1, $id, PDO::PARAM_INT);
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

            return [
                'encomenda' => $dadosEncomenda,
                'itens' => $itens
            ];
            
        } catch (PDOException $e) {
            die("Erro ao buscar detalhes da encomenda: " . $e->getMessage());
        }
    }

    public function atualizarStatus($id, $novoStatus) {
        $sql = 'UPDATE encomendas SET status = ? WHERE id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $novoStatus);
            $stmt->bindValue(2, $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
           die("Erro ao atualizar status: " . $e->getMessage()); 
           return false;
        }
    }

        
    public function buscarPorIntervaloDeDatas($dataInicio, $dataFim) {
        $sql = 'SELECT e.*, u.nome AS cliente_nome 
                FROM encomendas AS e
                JOIN clientes AS c ON e.cliente_id = c.id
                JOIN usuarios AS u ON c.usuario_id = u.id
                WHERE e.data_encomenda BETWEEN ? AND ?
                ORDER BY e.data_encomenda DESC';
        
        try {
            $pdo = Conexao::getInstance();
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $dataInicio . ' 00:00:00');
            $stmt->bindValue(2, $dataFim . ' 23:59:59');
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            die("Erro ao buscar relatório de vendas: " . $e->getMessage());
        }
    }
} 
?>