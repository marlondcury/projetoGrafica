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

    public function listarTodos() {
        $sql = 'SELECT * FROM usuarios ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        
        $usuarios = [];
        while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario = new Usuario();
            $usuario->setId($dados['id']);
            $usuario->setNome($dados['nome']);
            $usuario->setEmail($dados['email']);
            $usuario->setTipo($dados['tipo']);
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    public function buscarPorId($id) {
        $sql = 'SELECT * FROM usuarios WHERE id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($dados) {
            $usuario = new Usuario();
            $usuario->setId($dados['id']);
            $usuario->setNome($dados['nome']);
            $usuario->setEmail($dados['email']);
            $usuario->setTipo($dados['tipo']);
            // Nota: a senha não é buscada por segurança
            return $usuario;
        }
        return null;
    }
    
    public function atualizar(Usuario $usuario) {
        $sql = 'UPDATE usuarios SET nome = ?, email = ?, tipo = ? WHERE id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getEmail());
            $stmt->bindValue(3, $usuario->getTipo());
            $stmt->bindValue(4, $usuario->getId());
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
   public function excluir($id) {
    $pdo = Conexao::getInstance();
    try {
        $pdo->beginTransaction();

        // 1. Encontra o ID da tabela 'clientes' para o usuário
        // Isso é necessário porque a tabela 'encomendas' se refere a 'clientes'
        $sqlIdCliente = 'SELECT id FROM clientes WHERE usuario_id = ?';
        $stmtIdCliente = $pdo->prepare($sqlIdCliente);
        $stmtIdCliente->bindValue(1, $id);
        $stmtIdCliente->execute();
        $clienteId = $stmtIdCliente->fetchColumn();

        // Se o usuário for um cliente, ele terá um ID na tabela 'clientes'
        if ($clienteId) {
            // 2. Exclui primeiro os itens de encomenda.
            // Isso evita que a tabela 'encomendas' seja bloqueada pela tabela 'itens_encomenda'.
            $sqlItens = 'DELETE FROM itens_encomenda WHERE encomenda_id IN (SELECT id FROM encomendas WHERE cliente_id = ?)';
            $stmtItens = $pdo->prepare($sqlItens);
            $stmtItens->bindValue(1, $clienteId);
            $stmtItens->execute();

            // 3. Exclui as encomendas do cliente.
            $sqlEncomendas = 'DELETE FROM encomendas WHERE cliente_id = ?';
            $stmtEncomendas = $pdo->prepare($sqlEncomendas);
            $stmtEncomendas->bindValue(1, $clienteId);
            $stmtEncomendas->execute();
        }

        // 4. Exclui da tabela 'clientes'.
        $sqlCliente = 'DELETE FROM clientes WHERE usuario_id = ?';
        $stmtCliente = $pdo->prepare($sqlCliente);
        $stmtCliente->bindValue(1, $id);
        $stmtCliente->execute();

        // 5. Exclui da tabela principal 'usuarios'.
        $sqlUsuario = 'DELETE FROM usuarios WHERE id = ?';
        $stmtUsuario = $pdo->prepare($sqlUsuario);
        $stmtUsuario->bindValue(1, $id);
        $stmtUsuario->execute();

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        $pdo->rollBack();
        // Para depuração, você pode usar:
        // die("Erro ao excluir usuário: " . $e->getMessage()); 
        return false;
    }
}
   

}
?>