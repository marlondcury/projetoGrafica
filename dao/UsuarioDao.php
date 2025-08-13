<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Usuario.php';

class UsuarioDao {

    public function criar(Usuario $usuario) {
        $sql = 'INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)';
        
        try {
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_BCRYPT);
            
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getEmail());
            $stmt->bindValue(3, $senhaHash);
            $stmt->bindValue(4, $usuario->getTipo());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            // Em um sistema de produção, seria ideal logar o erro em vez de exibi-lo
            die("Erro ao criar usuário: " . $e->getMessage());
        }
    }

    public function buscarPorEmail($email) {
        $sql = 'SELECT * FROM usuarios WHERE email = ?';
        
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $email);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado) {
                $usuario = new Usuario();
                $usuario->setId($resultado['id']);
                $usuario->setNome($resultado['nome']);
                $usuario->setEmail($resultado['email']);
                $usuario->setSenha($resultado['senha']); // Armazena a senha com hash do banco
                $usuario->setTipo($resultado['tipo']);
                return $usuario;
            }
            
            return null; // Retorna null se não encontrar o usuário
        } catch (PDOException $e) {
            die("Erro ao buscar usuário: " . $e->getMessage());
        }
    }

    // Você pode adicionar métodos para atualizar e deletar usuários aqui (update, delete)
}
?>