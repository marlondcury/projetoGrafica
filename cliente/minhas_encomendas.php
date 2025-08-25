<?php
require_once '../includes/cabecalho.php';
require_once '../dao/EncomendaDao.php'; 

$encomendaDao = new EncomendaDao();

$encomendas = $encomendaDao->listarPorClienteId($_SESSION['usuario_id']);
?>

<div class="row container-xl mx-auto mt-4">
    <div class="col-md-3">
    <?php require_once '../includes/menuCliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Minhas Encomendas</h2>
        <p>Acompanhe o histórico e o status de todos os seus pedidos.</p>
        <hr>
        
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nº do Pedido</th>
                        <th>Data</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($encomendas) > 0): ?>
                        <?php foreach ($encomendas as $encomenda): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($encomenda['id']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($encomenda['data_encomenda'])) ?></td>
                                <td>R$ <?= number_format($encomenda['valor_total'], 2, ',', '.') ?></td>
                                <td>
                                    <?php
                                        $status = $encomenda['status'];
                                        $badge_class = 'bg-secondary'; // Padrão
                                        if ($status == 'concluido') $badge_class = 'bg-success';
                                        if ($status == 'pago') $badge_class = 'bg-primary';
                                        if ($status == 'em_aberto') $badge_class = 'bg-warning text-dark';
                                        if ($status == 'cancelado') $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= str_replace('_', ' ', ucfirst($status)) ?></span>
                                </td>
                                <td>
                                    <a href="encomendaDetalhes.php?id=<?= $encomenda['id'] ?>" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Você ainda não fez nenhuma encomenda.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>