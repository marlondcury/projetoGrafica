<?php
require_once '../includes/cabecalho.php';

require_once __DIR__.'/../dao/ServicoDao.php';
require_once __DIR__.'/../classes/Servico.php';

$servicoDao = new ServicoDao();
$servico = null;
$titulo_pagina = 'Adicionar Novo Serviço';
$acao_form = 'cadastrar';


if (isset($_GET['id'])) {
    $servico = $servicoDao->buscarPorId($_GET['id']);
    if ($servico) {
        $titulo_pagina = 'Alterar Serviço';
        $acao_form = 'alterar';
    }
}
?>


<div class="container container-xl mx-auto mt-5">
    <h1><?= $titulo_pagina; ?></h1>
    <p><a href="gerenciar_servicos.php">Voltar para a lista de serviços</a></p>
    
    <form action="../controllers/controlerServico.php" method="POST">
        <input type="hidden" name="opcao" value="<?= $acao_form; ?>">
        
        <?php if ($servico): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($servico['id']); ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= $servico ? htmlspecialchars($servico['nome']) : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?= $servico ? htmlspecialchars($servico['descricao']) : ''; ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="preco_base" class="form-label">Preço Base</label>
            <input type="number" step="0.01" class="form-control" id="preco_base" name="preco_base" value="<?= $servico ? htmlspecialchars($servico['preco_base']) : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="imagem_url" class="form-label">URL da Imagem</label>
            <input type="text" class="form-control" id="imagem_url" name="imagem_url" value="<?= $servico ? htmlspecialchars($servico['imagem_url']) : ''; ?>">
        </div>
        
        <div class="mb-3">
            <label for="tipo_servico" class="form-label">Tipo de Serviço</label>
            <input type="text" class="form-control" id="tipo_servico" name="tipo_servico" value="<?= $servico ? htmlspecialchars($servico['tipo_servico']) : ''; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-success">Salvar Serviço</button>
    </form>
</div>

<?php require_once '../includes/rodape.php';?>