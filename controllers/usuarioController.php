<?php
session_start();

require_once '../dao/UsuarioDao.php';
require_once '../classes/Usuario.php';

$opcao = $_REQUEST['opcao'] ?? 'listar';

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

    // logging temporário removido

        if ($usuario && password_verify($senha, $usuario->getSenha())) {
            // Segurança: evita session fixation
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nome'] = $usuario->getNome();

            $tipo = strtolower($usuario->getTipo());
            if ($tipo === 'admin') {
                header('Location: ../admin/index.php');
                exit();
            } else {
                header('Location: ../cliente/index.php');
                exit();
            }
        } else {
            // Retorna ao formulário de login com status de erro
            header('Location: ../login.php?status=erro_login');
            exit();
        }
        break;

    case 'logout':
    session_destroy();
    header('Location: ../index.php');
        exit();
        break;

    case 'alterar':

        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $tipo = $_POST['tipo'] ?? null;

        if (!$id || empty($nome) || empty($email) || empty($tipo)) {
            header('Location: ../admin/gerenciar_clientes.php?status=erro&acao=alteracao_dados');
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
        
        if ($usuarioDao->atualizar($usuario)) {
            header('Location: ../admin/gerenciar_clientes.php?status=sucesso&acao=alterado');
        } else {
            header('Location: ../admin/gerenciar_clientes.php?status=erro&acao=alteracao');
        }
        break;

        case 'atualizar_dados':
    
            $usuario_id = $_SESSION['usuario_id'];
            $nome = $_POST['nome'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            $endereco = $_POST['endereco'] ?? '';
    
            $usuarioDao = new UsuarioDao();
            if ($usuarioDao->atualizarCliente($usuario_id, $nome, $telefone, $endereco)) {
                $_SESSION['usuario_nome'] = $nome;
                header('Location: ../cliente/meus_dados.php?status=sucesso_update');
            } else {
                header('Location: ../cliente/meus_dados.php?status=erro_update');
            }
            break;
        
    case 'excluir':
        
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
}
?>