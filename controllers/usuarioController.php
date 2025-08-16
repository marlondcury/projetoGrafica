<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../dao/UsuarioDao.php';
require_once __DIR__.'/../classes/Usuario.php';

// Ação padrão caso a opção não seja definida
$opcao = $_REQUEST['opcao'] ?? 'listar';

$usuarioDao = new UsuarioDao();

switch ($opcao) {
    case 'cadastrar':
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($nome) || empty($email) || empty($senha)) {
            header('Location: /grafica_web/cadastro.php?status=erro_campos');
            exit();
        }
        
        if ($usuarioDao->buscarPorEmail($email)) {
            header('Location: /grafica_web/cadastro.php?status=erro_email_existente');
            exit();
        }
        
        $novoUsuario = new Usuario();
        $novoUsuario->setNome($nome);
        $novoUsuario->setEmail($email);
        $novoUsuario->setSenha($senha);

        if ($usuarioDao->criarCliente($novoUsuario)) {
            header('Location: /grafica_web/login.php?status=sucesso_cadastro');
        } else {
            header('Location: /grafica_web/cadastro.php?status=erro_cadastro');
        }
        break;

    case 'login':
        $email = $_POST['email'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (empty($email) || empty($senha)) {
            header('Location: /grafica_web/login.php?status=erro_campos');
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
                header('Location: /grafica_web/admin/index.php');
                exit();
            } else {
                header('Location: /grafica_web/cliente/index.php');
                exit();
            }
        } else {
            // Retorna ao formulário de login com status de erro
            header('Location: /grafica_web/login.php?status=erro_login');
            exit();
        }
        break;

    case 'logout':
    session_destroy();
    header('Location: /grafica_web/index.php');
        exit();
        break;

    // --- NOVAS AÇÕES PARA A ÁREA ADMINISTRATIVA ---

    case 'alterar':
        // VERIFICAÇÃO DE SEGURANÇA: apenas admin pode alterar usuários
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            header('Location: /grafica_web/login.php?erro=acesso_negado');
            exit();
        }

        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $email = $_POST['email'] ?? null;
        $tipo = $_POST['tipo'] ?? null;

        if (!$id || empty($nome) || empty($email) || empty($tipo)) {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=erro&acao=alteracao_dados');
            exit();
        }

        $usuario = $usuarioDao->buscarPorId($id);
        if (!$usuario) {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=erro&acao=usuario_nao_encontrado');
            exit();
        }

        $usuario->setNome($nome);
        $usuario->setEmail($email);
        $usuario->setTipo($tipo);
        
        if ($usuarioDao->atualizar($usuario)) {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=sucesso&acao=alterado');
        } else {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=erro&acao=alteracao');
        }
        break;

        case 'atualizar_dados':
            // Garante que o usuário está logado
            if (!isset($_SESSION['usuario_id'])) {
                header('Location: /grafica_web/login.php');
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
                header('Location: /grafica_web/cliente/meus_dados.php?status=sucesso_update');
            } else {
                header('Location: /grafica_web/cliente/meus_dados.php?status=erro_update');
            }
            break;
        
    case 'excluir':
        // VERIFICAÇÃO DE SEGURANÇA: apenas admin pode excluir usuários
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            header('Location: /grafica_web/login.php?erro=acesso_negado');
            exit();
        }
        
        $id = $_GET['id'] ?? null;

        if ($usuarioDao->excluir($id)) {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=sucesso&acao=excluido');
        } else {
            header('Location: /grafica_web/admin/gerenciar_clientes.php?status=erro&acao=exclusao');
        }
        break;

    case 'listar':
    default:
    header('Location: /grafica_web/admin/gerenciar_clientes.php');
        break;
}
?>