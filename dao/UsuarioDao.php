<?php
require_once 'Conexao.php';
require_once __DIR__.'/../classes/Usuario.php';

class UsuarioDao {

    public function criarCliente(Usuario $usuario) {
        $pdo = Conexao::getInstance();
        try {
            $pdo->beginTransaction();
            $sqlUsuario = 'INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)';
            $senhaHash = password_hash($usuario->getSenha(), PASSWORD_BCRYPT);
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindValue(1, $usuario->getNome());
            $stmtUsuario->bindValue(2, $usuario->getEmail());
            $stmtUsuario->bindValue(3, $senhaHash);
            $stmtUsuario->bindValue(4, 'cliente');
            $stmtUsuario->execute();
            $usuarioId = $pdo->lastInsertId();
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

    public function buscarPorEmail($email) {
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
            return $usuario;
        }
        return null;
    }

    public function buscarDetalhesDoUsuario($id) {
        $sql = 'SELECT u.*, c.telefone, c.endereco, c.cep, c.cidade, c.uf
                FROM usuarios AS u
                LEFT JOIN clientes AS c ON u.id = c.usuario_id
                WHERE u.id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function listarTodosComDetalhes() {
        $sql = 'SELECT u.*, c.telefone, c.endereco, u.cpf, c.cep, c.cidade, c.uf
                FROM usuarios AS u
                LEFT JOIN clientes AS c ON u.id = c.usuario_id
                ORDER BY u.nome ASC';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function atualizarUsuario(Usuario $usuario, $cpf) {
        $sql = 'UPDATE usuarios SET nome = ?, email = ?, tipo = ?, cpf = ? WHERE id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $usuario->getNome());
            $stmt->bindValue(2, $usuario->getEmail());
            $stmt->bindValue(3, $usuario->getTipo());
            $stmt->bindValue(4, $cpf);
            $stmt->bindValue(5, $usuario->getId());
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function atualizarCliente($usuario_id, $telefone, $endereco, $cep, $cidade, $uf) {
        $sql = 'UPDATE clientes SET telefone = ?, endereco = ?, cep = ?, cidade = ?, uf = ? WHERE usuario_id = ?';
        try {
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $telefone);
            $stmt->bindValue(2, $endereco);
            $stmt->bindValue(3, $cep);
            $stmt->bindValue(4, $cidade);
            $stmt->bindValue(5, $uf);
            $stmt->bindValue(6, $usuario_id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function excluir($id) {
        $pdo = Conexao::getInstance();
        try {
            $pdo->beginTransaction();
            $sqlIdCliente = 'SELECT id FROM clientes WHERE usuario_id = ?';
            $stmtIdCliente = $pdo->prepare($sqlIdCliente);
            $stmtIdCliente->bindValue(1, $id);
            $stmtIdCliente->execute();
            $clienteId = $stmtIdCliente->fetchColumn();

            if ($clienteId) {
                $sqlItens = 'DELETE FROM itens_encomenda WHERE encomenda_id IN (SELECT id FROM encomendas WHERE cliente_id = ?)';
                $stmtItens = $pdo->prepare($sqlItens);
                $stmtItens->bindValue(1, $clienteId);
                $stmtItens->execute();
                
                $sqlEncomendas = 'DELETE FROM encomendas WHERE cliente_id = ?';
                $stmtEncomendas = $pdo->prepare($sqlEncomendas);
                $stmtEncomendas->bindValue(1, $clienteId);
                $stmtEncomendas->execute();
            }

            $sqlCliente = 'DELETE FROM clientes WHERE usuario_id = ?';
            $stmtCliente = $pdo->prepare($sqlCliente);
            $stmtCliente->bindValue(1, $id);
            $stmtCliente->execute();
            
            $sqlUsuario = 'DELETE FROM usuarios WHERE id = ?';
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindValue(1, $id);
            $stmtUsuario->execute();

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }
}