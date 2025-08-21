<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../dao/UsuarioDao.php';
require_once __DIR__.'/../classes/Usuario.php';

$opcao = $_REQUEST['opcao'];

$usuarioDao = new UsuarioDao();

switch ($opcao) {
    case 'cadastrar':
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: ../cadastro.php?status=erro_campos');
            exit();
        }
        
        if ($usuarioDao->buscarPorEmail($email)) {
            header('Location: ../cadastro.php?status=erro_email_existente');
            exit();
        }
        
        $novoUsuario = new Usuario();
        $novoUsuario->setNome($nome);
        $novoUsuario->setEmail($email);
        $novoUsuario->setSenha($senha);

        if ($usuarioDao->criarCliente($novoUsuario)) {
            header('Location: ../login.php?status=sucesso_cadastro');
        } else {
            header('Location: ../cadastro.php?status=erro_cadastro');
        }
        break;

    case 'login':
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($email) || empty($senha)) {
            header('Location: ../login.php?status=erro_campos');
            exit();
        }

        $usuario = $usuarioDao->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario->getSenha())) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nome'] = $usuario->getNome();
            $_SESSION['usuario_tipo'] = $usuario->getTipo();

            if ($usuario->getTipo() == 'admin') {
                header('Location: ../admin/index.php');
            } else {
                header('Location: ../cliente/index.php');
            }
        } else {
            header('Location: ../login.php?status=erro_login');
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: ../index.php');
        break;

    case 'atualizar':


        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        
        $cpf = $_POST['cpf'] ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $endereco = $_POST['endereco'] ?? null;
        $cep = $_POST['cep'] ?? null;
        $cidade = $_POST['cidade'] ?? null;
        $uf = $_POST['uf'] ?? null;

        if (!$id || empty($nome) || empty($email) || empty($tipo)) {
            header('Location: ../admin/clienteForm.php?id='.$id.'&status=erro&acao=alteracao_dados');
            exit();
        }

        $usuario = $usuarioDao->buscarPorId($id);
        if (!$usuario) {
            header('Location: ../admin/gerenciar_clientes.php?status=erro&acao=usuario_nao_encontrado');
            exit();
        }

        $usuario->setNome($nome);
        $usuario->setEmail($email);
        $usuario->setTipo($tipo);
        
        $sucessoUsuario = $usuarioDao->atualizarUsuario($usuario, $cpf);
        $sucessoCliente = $usuarioDao->atualizarCliente($id, $telefone, $endereco, $cep, $cidade, $uf);

        if ($sucessoUsuario && $sucessoCliente) {
            header('Location: ../admin/gerenciar_clientes.php?status=sucesso&acao=alterado');
        } else {
            header('Location: ../admin/gerenciar_clientes.php?status=erro&acao=alteracao');
        }
        break;
        
    case 'excluir':
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            header('Location: ../login.php?erro=acesso_negado');
            exit();
        }
        
        $id = $_GET['id'] ?? null;

        if ($usuarioDao->excluir($id)) {
            header('Location: ../admin/gerenciar_clientes.php?status=sucesso&acao=excluido');
        } else {
            header('Location: ../admin/gerenciar_clientes.php?status=erro&acao=exclusao');
        }
        break;

    case 'listar':
    default:
        header('Location: ../admin/gerenciar_clientes.php');
        break;
        case 'atualizar_meus_dados':
    
            $id = $_SESSION['usuario_id']; 
            $nome = $_POST['nome'] ?? null;
            $email = $_POST['email'] ?? null;
            $cpf = $_POST['cpf'] ?? null;
            $telefone = $_POST['telefone'] ?? null;
            $endereco = $_POST['endereco'] ?? null;
            $cep = $_POST['cep'] ?? null;
            $cidade = $_POST['cidade'] ?? null;
            $uf = $_POST['uf'] ?? null;
            
            if (empty($nome) || empty($email)) {
                header('Location: /grafica_web/cliente/meus_dados.php?status=erro&acao=dados_invalidos');
                exit();
            }
    
            $usuario = $usuarioDao->buscarPorId($id);
            if (!$usuario) {
                header('Location: /grafica_web/cliente/meus_dados.php?status=erro&acao=usuario_nao_encontrado');
                exit();
            }
    
            $usuario->setNome($nome);
            $usuario->setEmail($email);
            
            $sucessoUsuario = $usuarioDao->atualizarUsuario($usuario, $cpf);
            $sucessoCliente = $usuarioDao->atualizarCliente($id, $telefone, $endereco, $cep, $cidade, $uf);
    
            if ($sucessoUsuario && $sucessoCliente) {
                $_SESSION['usuario_nome'] = $nome; 
                header('Location: /grafica_web/cliente/meus_dados.php?status=sucesso&acao=alterado');
            } else {
                header('Location: /grafica_web/cliente/meus_dados.php?status=erro&acao=alteracao');
            }
            break;
}
?>