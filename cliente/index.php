<?php
require_once '../includes/cabecalho.php';
// Inclui o DAO para poder buscar os dados das encomendas
require_once '../dao/EncomendaDao.php';

// Lógica de segurança para verificar se o usuário é cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'cliente') {
    header('Location: /grafica_web/login.php');
    exit();
}

// --- LÓGICA PARA BUSCAR OS DADOS DINÂMICOS ---
$encomendaDao = new EncomendaDao();
$encomendas = $encomendaDao->listarPorClienteId($_SESSION['usuario_id']);

// Pega apenas as 3 últimas encomendas para o resumo
$ultimas_encomendas = array_slice($encomendas, 0, 3);

// Calcula os totais para os cards de resumo
$total_encomendas = count($encomendas);
$encomendas_abertas = count(array_filter($encomendas, function($e) {
    return $e['status'] == 'em_aberto';
}));
?>

<div class="container-xl mt-4">
    <div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Painel do Cliente</h2>
        <p>Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']); ?></strong>! Bem-vindo(a) à sua área pessoal.</p>
        <hr>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total de Encomendas</h5>
                        <p class="card-text display-4"><?= $total_encomendas ?></p>
                        <a href="minhas_encomendas.php" class="btn btn-outline-primary">Ver Todas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h5 class="card-title">Encomendas em Aberto</h5>
                        <p class="card-text display-4"><?= $encomendas_abertas ?></p>
                        <p class="card-text"><small>Aguardando pagamento ou produção.</small></p>
                    </div>
                </div>
            </div>
        </div>

        <h4>Suas Últimas Encomendas</h4>
        <?php if (count($ultimas_encomendas) > 0): ?>
            <ul class="list-group">
                <?php foreach ($ultimas_encomendas as $encomenda): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Pedido #<?= htmlspecialchars($encomenda['id']) ?></strong> - 
                            <small>Data: <?= date('d/m/Y', strtotime($encomenda['data_encomenda'])) ?></small>
                        </div>
                        <div>
                            <span class="fw-bold me-3">R$ <?= number_format($encomenda['valor_total'], 2, ',', '.') ?></span>
                            <?php
                                $status = $encomenda['status'];
                                $badge_class = 'bg-secondary';
                                if ($status == 'concluido') $badge_class = 'bg-success';
                                if ($status == 'pago') $badge_class = 'bg-primary';
                                if ($status == 'em_aberto') $badge_class = 'bg-warning text-dark';
                                if ($status == 'cancelado') $badge_class = 'bg-danger';
                            ?>
                            <span class="badge <?= $badge_class ?>"><?= str_replace('_', ' ', ucfirst($status)) ?></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">
                Você ainda não realizou nenhuma encomenda. Que tal começar agora?
                <a href="/grafica_web/servicos.php" class="alert-link">Ver nossos serviços</a>.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>