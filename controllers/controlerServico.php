<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificação de segurança: apenas administradores podem acessar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: /grafica_web/login.php?erro=acesso_negado');
    exit();
}

// Inclusão das dependências
require_once __DIR__.'/../dao/ServicoDao.php';
require_once __DIR__.'/../classes/Servico.php';

$opcao = $_REQUEST['opcao'] ?? 'listar';

$servicoDao = new ServicoDao();

switch ($opcao) {
    case 'cadastrar':
        $nome = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $preco_base = $_POST['preco_base'] ?? null;
        $imagem_url = $_POST['imagem_url'] ?? null;
        $tipo_servico = $_POST['tipo_servico'] ?? null;

        $novoServico = new Servico();
        $novoServico->setNome($nome);
        $novoServico->setDescricao($descricao);
        $novoServico->setPrecoBase($preco_base);
        $novoServico->setImagemUrl($imagem_url);
        $novoServico->setTipoServico($tipo_servico);

        if ($servicoDao->criar($novoServico)) {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=sucesso&acao=cadastrado');
        } else {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=erro&acao=cadastro');
        }
        break;

    case 'alterar':
        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $preco_base = $_POST['preco_base'] ?? null;
        $imagem_url = $_POST['imagem_url'] ?? null;
        $tipo_servico = $_POST['tipo_servico'] ?? null;

        $servicoAlterar = new Servico();
        $servicoAlterar->setId($id);
        $servicoAlterar->setNome($nome);
        $servicoAlterar->setDescricao($descricao);
        $servicoAlterar->setPrecoBase($preco_base);
        $servicoAlterar->setImagemUrl($imagem_url);
        $servicoAlterar->setTipoServico($tipo_servico);

        if ($servicoDao->atualizar($servicoAlterar)) {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=sucesso&acao=alterado');
        } else {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=erro&acao=alteracao');
        }
        break;
        
    case 'excluir':
        $id = $_GET['id'] ?? null;

        if ($servicoDao->excluir($id)) {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=sucesso&acao=excluido');
        } else {
            header('Location: /grafica_web/admin/gerenciar_servicos.php?status=erro&acao=exclusao');
        }
        break;
    
    case 'listar':
    default:
        // A lógica de listagem será na página 'servicos.php', que chama este controlador
        // sem uma 'opcao' específica.
    header('Location: /grafica_web/admin/gerenciar_servicos.php');
        break;
}
?>