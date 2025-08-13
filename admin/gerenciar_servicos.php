<?php require_once '../includes/cabecalho.php'; ?>
<?php
    // Lógica para verificar se é admin
    if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
        header("Location: /grafica_web/login.php");
        exit();
    }
    // Dados de exemplo
    $servicos = [
        ['id' => 1, 'nome' => 'Impressão A4', 'tipo_servico' => 'reproducao', 'preco_base' => 0.50],
        ['id' => 2, 'nome' => 'Banner em Lona', 'tipo_servico' => 'banner', 'preco_base' => 80.00]
    ];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Gerenciar Serviços</h1>
    <a href="#" class="btn btn-primary">Adicionar Novo Serviço</a>
</div>

<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Preço Base</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($servicos as $servico): ?>
        <tr>
            <td><?= $servico['id'] ?></td>
            <td><?= $servico['nome'] ?></td>
            <td><?= ucfirst($servico['tipo_servico']) ?></td>
            <td>R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></td>
            <td>
                <a href="#" class="btn btn-warning btn-sm">Alterar</a>
                <a href="#" class="btn btn-danger btn-sm">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/rodape.php'; ?>