<?php
require_once '../includes/cabecalho.php';
require_once '../dao/EncomendaDao.php';
require_once '../dao/ServicoDao.php';
require_once '../dao/UsuarioDao.php';

$encomendaDao = new EncomendaDao();
$servicoDao = new ServicoDao();
$encomendaDetalhes = null;
$encomenda = null;
$clienteIdDaSessao = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $id_encomenda = $_GET['id'];
    $encomendaDetalhes = $encomendaDao->buscarDetalhesPorId($id_encomenda);
    
    if ($encomendaDetalhes) {
        $encomenda = $encomendaDetalhes['encomenda'];
        $itens = $encomendaDetalhes['itens'];
        
        if ($encomenda['cliente_id'] != $clienteIdDaSessao) {
            header('Location: minhas_encomendas.php?erro=acesso_negado');
            exit();
        }
    }
}
?>

<div class="row container-xl mx-auto mt-4">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Detalhes da Encomenda #<?= htmlspecialchars($encomenda['id'] ?? 'N/A') ?></h2>
        <p><a href="minhas_encomendas.php">Voltar para Minhas Encomendas</a></p>
        <hr>

        <?php if ($encomenda): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Informações do Pedido</h4>
                </div>
                <div class="card-body">
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i:s', strtotime($encomenda['data_encomenda'])) ?></p>
                    <p><strong>Valor Total:</strong> R$ <?= number_format($encomenda['valor_total'], 2, ',', '.') ?></p>
                    <p><strong>Status:</strong> 
                        <?php
                            $status = $encomenda['status'];
                            $badge_class = 'bg-secondary';
                            if ($status == 'concluido') $badge_class = 'bg-success';
                            if ($status == 'pago') $badge_class = 'bg-primary';
                            if ($status == 'em_aberto') $badge_class = 'bg-warning text-dark';
                            if ($status == 'cancelado') $badge_class = 'bg-danger';
                        ?>
                        <span class="badge <?= $badge_class ?>"><?= str_replace('_', ' ', ucfirst($status)) ?></span>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>Itens do Pedido</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Serviço</th>
                                <th>Qtd.</th>
                                <th>Valor Unitário</th>
                                <th>Atributos</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($itens as $item): ?>
                                <?php $servico = $servicoDao->buscarObjetoPorId($item['servico_id']); ?>
                                <tr>
                                    <td><?= htmlspecialchars($servico->getNome() ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($item['quantidade']) ?></td>
                                    <td>R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                                    <td>
                                        <?php 
                                            $atributos = json_decode($item['atributos'], true);
                                            if (is_array($atributos) && !empty($atributos)) {
                                                foreach ($atributos as $chave => $valor) {
                                                    echo "<strong>" . htmlspecialchars($chave) . ":</strong> " . htmlspecialchars($valor) . "<br>";
                                                }
                                            } else {
                                                echo "N/A";
                                            }
                                        ?>
                                    </td>
                                    <td>R$ <?= number_format($item['valor_unitario'] * $item['quantidade'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Pedido não encontrado ou você não tem permissão para acessá-lo.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>