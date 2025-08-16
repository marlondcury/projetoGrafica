<?php

require_once '../includes/cabecalho.php';
require_once __DIR__.'/../dao/EncomendaDao.php';

$encomendaDao = new EncomendaDao();
$listaEncomendas = $encomendaDao->listarTodos();

?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4">Gerenciar Encomendas</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success">
                Encomenda <?= $_GET['acao']; ?> com sucesso!
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert alert-danger">
                Erro ao tentar <?= $_GET['acao']; ?> a encomenda.
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID da Encomenda</th>
                        <th>Cliente ID</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaEncomendas as $encomenda): ?>
                    <tr>
                        <td><?= $encomenda['id']; ?></td>
                        <td><?= $encomenda['cliente_id']; ?></td>
                        <td>R$ <?= number_format($encomenda['valor_total'], 2, ',', '.'); ?></td>
                        <td><?= ucfirst($encomenda['status']); ?></td>
                        <td><?= date('d/m/Y H:i:s', strtotime($encomenda['data_encomenda'])); ?></td>
                        <td>
                            <a href="encomendaDetalhes.php?id=<?= $encomenda['id']; ?>" class="btn btn-primary btn-sm">Visualizar Detalhes</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>