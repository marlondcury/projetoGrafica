<?php
require_once '../includes/cabecalho.php';
// Adicione os DAOs necessários
require_once __DIR__.'/../dao/EncomendaDao.php';
require_once __DIR__.'/../dao/UsuarioDao.php';


$vendas = null;
$total_faturado = 0;

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    // Instancia o DAO e chama o novo método
    $encomendaDao = new EncomendaDao();
    $vendas = $encomendaDao->buscarPorIntervaloDeDatas($data_inicio, $data_fim);
    
    foreach ($vendas as $venda) {
        $total_faturado += $venda['valor_total'];
    }
}
?>

<div class="row container-xl mx-auto">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Relatório de Vendas</h2>
        <hr>

        <div class="card bg-light mb-4">
            <div class="card-body">
                <h5 class="card-title">Filtrar por Período</h5>
                <form method="POST" action="relatorio_vendas.php">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= $_POST['data_inicio'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label for="data_fim" class="form-label">Data de Fim</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= $_POST['data_fim'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Gerar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($vendas !== null): ?>
            <h3 class="mt-4">Resultados para o Período</h3>
            <div class="alert alert-info">
                <strong>Total de Vendas no Período:</strong> <?= count($vendas) ?> encomenda(s) <br>
                <strong>Valor Total Faturado:</strong> R$ <?= number_format($total_faturado, 2, ',', '.') ?>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Encomenda</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($vendas) > 0): ?>
                            <?php foreach ($vendas as $venda): ?>
                                <tr>
                                    <td><?= htmlspecialchars($venda['id']) ?></td>
                                    <td><?= htmlspecialchars($venda['cliente_nome']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($venda['data_encomenda'])) ?></td>
                                    <td>
                                        <?php
                                            $status_class = 'bg-secondary';
                                            switch ($venda['status']) {
                                                case 'pago':
                                                case 'concluido':
                                                    $status_class = 'bg-success'; 
                                                    break;
                                                case 'em_aberto':
                                                    $status_class = 'bg-info';
                                                    break;
                                                case 'cancelado':
                                                    $status_class = 'bg-danger';
                                                    break;
                                            }
                                        ?>
                                        <span class="badge <?= $status_class; ?>">
                                            <?= htmlspecialchars(ucfirst($venda['status'])) ?>
                                        </span>
                                    </td>
                                    <td>R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhuma venda encontrada para o período selecionado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>