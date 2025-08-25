<?php
require_once '../includes/cabecalho.php';
require_once __DIR__ . '/../dao/EncomendaDao.php';

$encomendaId = $_GET['id'] ?? null;


$encomendaDao = new EncomendaDao();
$encomenda = $encomendaDao->buscarDetalhesPorId($encomendaId);

if (!$encomenda) {
    die("Encomenda não encontrada.");
}


$valorTotal = $encomenda['encomenda']['valor_total'];
?>

<div class="row justify-content-center container-xl mx-auto mt-4">
    <div class="col-md-8"> <h2>Escolha o Método de Pagamento</h2>
        <hr>
        <div class="card mb-4">
            <div class="card-body">
                <h4>Sua Encomenda #<?= $encomendaId; ?></h4>
                <p class="fs-4">Valor Total: <span class="fw-bold">R$ <?= number_format($valorTotal, 2, ',', '.'); ?></span></p>
                
                <h5 class="mt-4">Opções de Pagamento</h5>
                <div class="d-grid gap-2 col-md-6">
                    <a href="boleto/samples/meuBoleto.php?id=<?= $encomendaId; ?>" class="btn btn-primary btn-lg">Pagar com Boleto</a>
                    <a href="#" class="btn btn-secondary btn-lg" disabled>Pagar com Cartão (Não implementado)</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>