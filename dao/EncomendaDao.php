<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Encomenda.php';

// A classe é declarada apenas UMA VEZ
class EncomendaDao {

    /**
     * MÉTODO 1: Para criar uma nova encomenda.
     */
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

    /**
     * MÉTODO 2: Para listar as encomendas de um cliente.
     */
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
            // 1. Busca os detalhes da encomenda principal
            $sqlEncomenda = 'SELECT * FROM encomendas WHERE id = ?';
            $stmtEncomenda = $pdo->prepare($sqlEncomenda);
            $stmtEncomenda->bindValue(1, $id, PDO::PARAM_INT);
            $stmtEncomenda->execute();
            $dadosEncomenda = $stmtEncomenda->fetch(PDO::FETCH_ASSOC);

            if (!$dadosEncomenda) {
                return null;
            }

            // 2. Busca os itens da encomenda
            $sqlItens = 'SELECT * FROM itens_encomenda WHERE encomenda_id = ?';
            $stmtItens = $pdo->prepare($sqlItens);
            $stmtItens->bindValue(1, $id, PDO::PARAM_INT);
            $stmtItens->execute();
            $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

            // Retorna um array com os dados da encomenda e seus itens
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
           // Adicione esta linha para ver a mensagem de erro exata
           die("Erro ao atualizar status: " . $e->getMessage()); 
           return false;
        }
    }
} // A chave que fecha a classe fica aqui no final.
?>