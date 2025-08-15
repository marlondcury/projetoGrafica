<?php 
require_once '../includes/cabecalho.php'; 
// Inclui o DAO para buscar os serviços no banco
require_once '../dao/ServicoDao.php';

// Cria a instância do DAO e busca todos os serviços
$servicoDao = new ServicoDao();
$servicos = $servicoDao->listarTodos();
?>

<div class="mx-auto mt-5" style="max-width: 1200px;">
    <h1 class="mb-4">Nossos Serviços</h1>
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