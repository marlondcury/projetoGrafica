<?php
require_once '../includes/cabecalho.php';

// Lógica de segurança para verificar se o usuário é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: /grafica_web/login.php');
    exit();
}

$vendas = null;
$total_faturado = 0;

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    /*
     * Em um cenário real, aqui seria feita a chamada ao DAO:
     * $encomendaDao = new EncomendaDao();
     * $vendas = $encomendaDao->buscarPorIntervaloDeDatas($data_inicio, $data_fim);
     * Para este exemplo, vamos simular o resultado.
     */
    $vendas = [
        ['id' => 101, 'cliente_nome' => 'Bia Martins', 'data_encomenda' => '2025-08-10 10:30:00', 'valor_total' => 75.50, 'status' => 'Concluído'],
        ['id' => 102, 'cliente_nome' => 'Carlos Furtado', 'data_encomenda' => '2025-08-11 14:00:00', 'valor_total' => 120.00, 'status' => 'Concluído'],
        ['id' => 103, 'cliente_nome' => 'Marlon Aguiar', 'data_encomenda' => '2025-08-12 09:15:00', 'valor_total' => 45.00, 'status' => 'Pago']
    ];
    
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
                                        <span class="badge bg-success"><?= htmlspecialchars($venda['status']) ?></span>
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