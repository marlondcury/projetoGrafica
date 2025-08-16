<?php
// O cabeçalho já contém session_start()
require_once '../includes/cabecalho.php'; 

// Lógica de segurança para verificar se o usuário é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: /grafica_web/login.php');
    exit();
}

// Incluir as classes DAO e Modelo
require_once __DIR__.'/../dao/ServicoDao.php';
require_once __DIR__.'/../classes/Servico.php';

// Instanciar o DAO e buscar os dados do banco
$servicoDao = new ServicoDao();
$listaServicos = $servicoDao->listarTodos();
?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gerenciar Serviços</h1>
            <a href="servicoForm.php" class="btn btn-primary">Adicionar Novo Serviço</a>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success">
                Serviço <?= $_GET['acao']; ?> com sucesso!
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert alert-danger">
                Erro ao tentar <?= $_GET['acao']; ?> o serviço.
            </div>
        <?php endif; ?>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Preço Base</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaServicos as $servico): ?>
                <tr>
                    <td><?= $servico->getId(); ?></td>
                    <td><?= $servico->getNome(); ?></td>
                    <td><?= ucfirst($servico->getTipoServico()); ?></td>
                    <td>R$ <?= number_format($servico->getPrecoBase(), 2, ',', '.') ?></td>
                    <td>
                        <a href="servicoForm.php?id=<?= $servico->getId(); ?>" class="btn btn-warning btn-sm">Alterar</a>
                        <a href="../controllers/controlerServico.php?opcao=excluir&id=<?= $servico->getId(); ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir este serviço?');" 
                           class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>