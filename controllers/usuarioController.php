<?php
session_start();
require_once __DIR__.'/../dao/UsuarioDao.php';
require_once __DIR__.'/../classes/Usuario.php';

$opcao = $_REQUEST['opcao'];

switch ($opcao) {
    case 'cadastrar':
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: ../cadastro.php?status=erro_campos');
            exit();
        }
        
        $usuarioDao = new UsuarioDao();
        
        if ($usuarioDao->buscarPorEmail($email)) {
            header('Location: ../cadastro.php?status=erro_email_existente');
            exit();
        }
        
        $novoUsuario = new Usuario();
        $novoUsuario->setNome($nome);
        $novoUsuario->setEmail($email);
        $novoUsuario->setSenha($senha);

        // MUDANÇA: Chame o novo método que cria em ambas as tabelas
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

        $usuarioDao = new UsuarioDao();
        $usuario = $usuarioDao->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario->getSenha())) {
            // Login bem-sucedido, armazena dados na sessão
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nome'] = $usuario->getNome();
            $_SESSION['usuario_tipo'] = $usuario->getTipo();

            // Redireciona com base no tipo de usuário
            if ($usuario->getTipo() == 'admin') {
                header('Location: ../admin/index.php');
            } else {
                header('Location: ../cliente/index.php');
            }
        } else {
            // Falha no login
            header('Location: ../login.php?status=erro_login');
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: ../index.php');
        break;

        case 'atualizar_dados':
            // Garante que o usuário está logado
            if (!isset($_SESSION['usuario_id'])) {
                header('Location: ../login.php');
                exit();
            }
    
            // Pega os dados do formulário
            $usuario_id = $_SESSION['usuario_id'];
            $nome = $_POST['nome'] ?? '';
            $telefone = $_POST['telefone'] ?? '';
            $endereco = $_POST['endereco'] ?? '';
    
            $usuarioDao = new UsuarioDao();
            if ($usuarioDao->atualizarCliente($usuario_id, $nome, $telefone, $endereco)) {
                // Atualiza o nome na sessão para refletir a mudança imediatamente no menu
                $_SESSION['usuario_nome'] = $nome;
                // Redireciona de volta com uma mensagem de sucesso
                header('Location: ../cliente/meus_dados.php?status=sucesso_update');
            } else {
                header('Location: ../cliente/meus_dados.php?status=erro_update');
            }
            break;
        
    default:
        header('Location: ../index.php');
        break;
}
?>