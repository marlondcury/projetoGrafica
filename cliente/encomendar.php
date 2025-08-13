<?php require_once '../includes/cabecalho.php'; ?>
<?php
    // Lógica para verificar se o usuário está logado. Se não, redirecionar para login.php
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: /grafica_web/login.php?redirect=encomendar.php?id=" . $_GET['id']);
        exit();
    }

    // Em um cenário real, buscaria o serviço pelo ID usando o DAO
    $servico_id = $_GET['id'] ?? 1;
    $servico = ['id' => $servico_id, 'nome' => 'Impressão A4', 'preco_base' => 0.50, 'tipo_servico' => 'reproducao'];
?>

<h1 class="mb-4">Encomendar: <?= $servico['nome'] ?></h1>

<form action="/grafica_web/controllers/encomendaController.php" method="POST">
    <input type="hidden" name="servico_id" value="<?= $servico['id'] ?>">
    <input type="hidden" name="opcao" value="adicionar">
    
    <?php if ($servico['tipo_servico'] == 'reproducao'): ?>
        <div class="mb-3">
            [cite_start]<label for="tipo_papel" class="form-label">Tipo de Papel</label> [cite: 56]
            <select name="atributos[tipo_papel]" id="tipo_papel" class="form-select">
                <option value="sulfite_75g">Sulfite 75g</option>
                <option value="couche_120g">Couchê 120g</option>
            </select>
        </div>
        <div class="mb-3">
            [cite_start]<label for="tipo_impressao" class="form-label">Tipo de Impressão</label> [cite: 56]
            <select name="atributos[tipo_impressao]" id="tipo_impressao" class="form-select">
                <option value="pb">Preto e Branco</option>
                <option value="colorido">Colorido</option>
            </select>
        </div>
    <?php elseif ($servico['tipo_servico'] == 'banner'): ?>
         <div class="mb-3">
            [cite_start]<label for="tamanho" class="form-label">Tamanho</label> [cite: 57]
            <select name="atributos[tamanho]" id="tamanho" class="form-select">
                <option value="80x120">80cm x 120cm</option>
                <option value="100x150">100cm x 150cm</option>
            </select>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1">
    </div>

    <div class="mb-3">
        <h4>Valor Total Estimado: <span id="valor_total">R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></span></h4>
    </div>

    <button type="submit" class="btn btn-success">Adicionar à Encomenda</button>
</form>

<?php require_once '../includes/rodape.php'; ?>