<?php

require_once '../includes/cabecalho.php';
require_once __DIR__.'/../dao/EncomendaDao.php';
require_once __DIR__.'/../dao/ServicoDao.php';
require_once __DIR__.'/../dao/UsuarioDao.php';

$encomendaDao = new EncomendaDao();
$servicoDao = new ServicoDao();
$usuarioDao = new UsuarioDao();

$encomendaDetalhes = null;
$encomenda = null;

if (isset($_GET['id'])) {
    $encomendaDetalhes = $encomendaDao->buscarDetalhesPorId($_GET['id']);
    if ($encomendaDetalhes) {
        $encomenda = $encomendaDetalhes['encomenda'];
        $itens = $encomendaDetalhes['itens'];
        $cliente = $usuarioDao->buscarPorId($encomenda['cliente_id']);
    }
}
?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2 class="mb-4">Detalhes da Encomenda</h2>
        <p><a href="gerenciarEncomendas.php">Voltar para a lista de encomendas</a></p>

        <?php if ($encomenda): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informações da Encomenda #<?= $encomenda['id']; ?></h4>
                </div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> <?= $cliente->getNome() ?? 'N/A'; ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i:s', strtotime($encomenda['data_encomenda'])); ?></p>
                    <p><strong>Valor Total:</strong> R$ <?= number_format($encomenda['valor_total'], 2, ',', '.'); ?></p>
                    <p><strong>Status Atual:</strong> <?= ucfirst($encomenda['status']); ?></p>

                    <h5 class="mt-4">Alterar Status</h5>
                    <form action="../controllers/encomendaController.php" method="POST">
                        <input type="hidden" name="opcao" value="atualizar_status">
                        <input type="hidden" name="id" value="<?= $encomenda['id']; ?>">
                        <div class="mb-3">
                            <select name="novo_status" class="form-select" required>
                                <option value="em_aberto" <?= ($encomenda['status'] == 'em_aberto') ? 'selected' : ''; ?>>Em Aberto</option>
                                <option value="pago" <?= ($encomenda['status'] == 'pago') ? 'selected' : ''; ?>>Pago</option>
                                <option value="concluido" <?= ($encomenda['status'] == 'concluido') ? 'selected' : ''; ?>>Concluído</option>
                                <option value="cancelado" <?= ($encomenda['status'] == 'cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Atualizar Status</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Itens da Encomenda</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Serviço</th>
                                <th>Quantidade</th>
                                <th>Valor Unitário</th>
                                <th>Atributos</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <?php $servico = $servicoDao->buscarPorId($item['servico_id']); ?>
                                <tr>
                                    <td><?= $servico->getNome() ?? 'N/A'; ?></td>
                                    <td><?= $item['quantidade']; ?></td>
                                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.'); ?></td>
                                    <td>
                                        <?php 
                                            $atributos = json_decode($item['atributos'], true);
                                            if (is_array($atributos) && !empty($atributos)) {
                                                foreach ($atributos as $key => $value) {
                                                    echo "<strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "<br>";
                                                }
                                            } else {
                                                echo "N/A";
                                            }
                                        ?>
                                    </td>
                                    <td>R$ <?= number_format($item['valor_unitario'] * $item['quantidade'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Encomenda não encontrada.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>