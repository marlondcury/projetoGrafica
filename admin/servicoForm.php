<?php

require_once '../includes/cabecalho.php';
require_once '../dao/ServicoDao.php';
require_once '../classes/Servico.php';

$servicoDao = new ServicoDao();
$servico = null;

$tituloPagina = 'Adicionar Novo Serviço';
$acaoForm = 'cadastrar';


if (isset($_GET['id'])) {
    $servico = $servicoDao->buscarPorId($_GET['id']);
    if ($servico) {
        $tituloPagina = 'Alterar Serviço';
        $acaoForm = 'alterar';
    }
}
?>


<div class="container container-xl mx-auto mt-5">
    <h1><?= $tituloPagina; ?></h1>
    <a href="gerenciarServicos.php" class="btn btn-secondary mb-3">
        &larr; Voltar para a lista de serviços
    </a>    
    <form action="../controllers/controlerServico.php" method="POST">
    <input type="hidden" name="opcao" value="<?= $acaoForm; ?>">
        
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
            <label for="precoBase" class="form-label">Preço Base</label>
            <input type="number" step="0.01" class="form-control" id="precoBase" name="precoBase" value="<?= $servico ? htmlspecialchars($servico['preco_base']) : ''; ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="imagemUrl" class="form-label">URL da Imagem</label>
            <input type="text" class="form-control" id="imagemUrl" name="imagemUrl" value="<?= $servico ? htmlspecialchars($servico['imagem_url']) : ''; ?>">
        </div>
        
        <div class="mb-3">
            <label for="tipoServico" class="form-label">Tipo de Serviço</label>
            <input type="text" class="form-control" id="tipoServico" name="tipoServico" value="<?= $servico ? htmlspecialchars($servico['tipo_servico']) : ''; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-success">Salvar Serviço</button>
    </form>
</div>

<?php require_once '../includes/rodape.php';?>