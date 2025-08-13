<?php require_once '../includes/cabecalho.php'; ?>
<?php
    // Em um cenário real, os dados viriam do ServicoDao
    $servicos = [
        ['id' => 1, 'nome' => 'Impressão A4', 'descricao' => 'Impressões de alta qualidade em papel A4.', 'preco_base' => 0.50, 'imagem_url' => 'https://via.placeholder.com/300'],
        ['id' => 2, 'nome' => 'Banner em Lona', 'descricao' => 'Banners resistentes para eventos e divulgação.', 'preco_base' => 80.00, 'imagem_url' => 'https://via.placeholder.com/300'],
        ['id' => 3, 'nome' => 'Caneca Personalizada', 'descricao' => 'Canecas de cerâmica com sua arte ou foto.', 'preco_base' => 35.00, 'imagem_url' => 'https://via.placeholder.com/300']
    ];
?>

<h1 class="mb-4">Nossos Serviços</h1>
<div class="row">
    <?php foreach ($servicos as $servico): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?= $servico['imagem_url'] ?>" class="card-img-top" alt="<?= $servico['nome'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $servico['nome'] ?></h5>
                    <p class="card-text"><?= $servico['descricao'] ?></p>
                    <p class="card-text fw-bold">A partir de R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></p>
                    <a href="/grafica_web/cliente/encomendar.php?id=<?= $servico['id'] ?>" class="btn btn-primary">Encomendar Agora</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once '../includes/rodape.php'; ?>