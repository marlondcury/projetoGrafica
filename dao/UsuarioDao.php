<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Usuario.php';

class UsuarioDao {

    public function criarCliente(Usuario $usuario) {
        $pdo = Conexao::getInstance();
        try {
            $pdo->beginTransaction();

            // 1. Insere na tabela 'usuarios'
            $sqlUsuario = 'INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)';
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_BCRYPT);
            
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindValue(1, $usuario->getNome());
            $stmtUsuario->bindValue(2, $usuario->getEmail());
            $stmtUsuario->bindValue(3, $senhaHash);
            $stmtUsuario->bindValue(4, 'cliente');
            $stmtUsuario->execute();
            
            $usuarioId = $pdo->lastInsertId();

            // 2. Insere na tabela 'clientes' USANDO O MESMO ID
            // AQUI ESTÁ A CORREÇÃO PRINCIPAL
            $sqlCliente = 'INSERT INTO clientes (id, usuario_id) VALUES (?, ?)';
            $stmtCliente = $pdo->prepare($sqlCliente);
            $stmtCliente->bindValue(1, $usuarioId);
            $stmtCliente->bindValue(2, $usuarioId);
            $stmtCliente->execute();
            
            $pdo->commit();
            return true;

        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Erro ao criar cliente: " . $e->getMessage());
            return false;
        }
    }
    // ... o método buscarPorEmail() continua igual ...
    public function buscarPorEmail($email) {
        // (código do método buscarPorEmail sem alterações)
        $sql = 'SELECT * FROM usuarios WHERE email = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $email);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            $usuario = new Usuario();
            $usuario->setId($resultado['id']);
            $usuario->setNome($resultado['nome']);
            $usuario->setEmail($resultado['email']);
            $usuario->setSenha($resultado['senha']);
            $usuario->setTipo($resultado['tipo']);
            return $usuario;
        }
        return null;
    }
}
?>