<?php 
require_once '../includes/cabecalho.php'; 
require_once '../dao/ServicoDao.php';

// Cria a instância do DAO
$servicoDao = new ServicoDao();

// Recebe parâmetros de busca (nome, tipo, id)
$filtros = [];
if (!empty($_GET['q'])) {
    $filtros['nome'] = trim($_GET['q']);
}
if (!empty($_GET['tipo'])) {
    $filtros['tipo'] = trim($_GET['tipo']);
}
if (!empty($_GET['id'])) {
    $filtros['id'] = (int) $_GET['id'];
}

$servicos = $servicoDao->buscar($filtros);
$tiposDisponiveis = $servicoDao->listarTipos();
?>

<div class="container-xl mt-5">
    <h1 class="mb-4">Nossos Serviços</h1>

    <form class="row g-2 mb-4" method="GET" action="/grafica_web/public/servicos.php">
        <div class="col-md-4">
            <input type="text" name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" class="form-control" placeholder="Buscar por nome">
        </div>
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="">Todos os tipos</option>
                <?php foreach ($tiposDisponiveis as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo) ?>" <?= (isset($_GET['tipo']) && $_GET['tipo'] === $tipo) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($tipo)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="id" value="<?= isset($_GET['id']) ? (int)$_GET['id'] : '' ?>" class="form-control" placeholder="Código">
        </div>
        <div class="col-md-3 d-flex">
            <button type="submit" class="btn btn-primary me-2">Buscar</button>
            <a href="/grafica_web/public/servicos.php" class="btn btn-outline-secondary">Limpar</a>
        </div>
    </form>

    <div class="row">
        <?php if ($servicos): ?>
            <?php foreach ($servicos as $servico): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?= htmlspecialchars($servico['imagem_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($servico['nome']) ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($servico['nome']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($servico['descricao']) ?></p>
                            <p class="card-text fw-bold mt-auto">A partir de R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></p>
                            <a href="/grafica_web/cliente/encomendar.php?id=<?= $servico['id'] ?>" class="btn btn-primary mt-2">Personalizar e Comprar</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Nenhum serviço disponível no momento.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>