<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Encomenda.php';

// A classe é declarada apenas UMA VEZ
class EncomendaDao {

    /**
     * MÉTODO 1: Cria uma nova encomenda no banco de dados.
     */
    public function criar(Encomenda $encomenda) {
        $pdo = Conexao::getInstance();
        
        try {
            // Inicia a transação
            $pdo->beginTransaction();

            // Insere na tabela 'encomendas'
            $sqlEncomenda = 'INSERT INTO encomendas (cliente_id, data_encomenda, valor_total, status) VALUES (?, ?, ?, ?)';
            $stmtEncomenda = $pdo->prepare($sqlEncomenda);
            $stmtEncomenda->bindValue(1, $encomenda->getClienteId());
            $stmtEncomenda->bindValue(2, $encomenda->getDataEncomenda());
            $stmtEncomenda->bindValue(3, $encomenda->getValorTotal());
            $stmtEncomenda->bindValue(4, $encomenda->getStatus());
            $stmtEncomenda->execute();
            
            $encomendaId = $pdo->lastInsertId();

            // Insere cada item na tabela 'itens_encomenda'
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

            // Confirma a transação
            $pdo->commit();
            
            return $encomendaId;

        } catch (PDOException $e) {
            // Desfaz a transação em caso de erro
            $pdo->rollBack();
            die("Erro ao criar encomenda: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MÉTODO 2: Lista todas as encomendas de um cliente específico.
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

} // A chave que fecha a classe fica aqui no final.
?>