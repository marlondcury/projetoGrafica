<?php
require_once '../includes/cabecalho.php';

// Lógica de segurança para verificar se o usuário é cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'cliente') {
    header('Location: /grafica_web/login.php');
    exit();
}

/*
 * Em um cenário real, estes dados viriam do banco de dados através de um EncomendaDao.
 * Ex: $encomendaDao = new EncomendaDao(); $encomendas = $encomendaDao->listarPorClienteId($_SESSION['usuario_id']);
 * Para este exemplo, usaremos um array estático.
 */
$encomendas = [
    ['id' => 201, 'data_encomenda' => '2025-08-10 10:30:00', 'valor_total' => 75.50, 'status' => 'Concluído'],
    ['id' => 205, 'data_encomenda' => '2025-08-11 14:00:00', 'valor_total' => 120.00, 'status' => 'Pago'],
    ['id' => 209, 'data_encomenda' => '2025-08-12 09:15:00', 'valor_total' => 45.00, 'status' => 'Em Aberto'],
    ['id' => 210, 'data_encomenda' => '2025-08-13 11:05:00', 'valor_total' => 350.00, 'status' => 'Cancelado']
];
?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
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
                                        if ($status == 'Concluído') $badge_class = 'bg-success';
                                        if ($status == 'Pago') $badge_class = 'bg-primary';
                                        if ($status == 'Em Aberto') $badge_class = 'bg-warning';
                                        if ($status == 'Cancelado') $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
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