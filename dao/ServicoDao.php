<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Servico.php';

class ServicoDao {
    public function listarTodos() {
        $sql = 'SELECT * FROM servicos ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        // Mapear resultado para objetos Servico (a ser implementado)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function buscarPorId($id) {
        $sql = 'SELECT * FROM servicos WHERE id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Implementar métodos criar, atualizar, deletar
}
?>