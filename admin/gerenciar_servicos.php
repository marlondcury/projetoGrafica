<?php

require_once '../includes/cabecalho.php'; 
require_once '../dao/ServicoDao.php';
require_once '../classes/Servico.php';

$servicoDao = new ServicoDao();
$filtros = [];
if (!empty($_GET['q'])) $filtros['nome'] = trim($_GET['q']);
if (!empty($_GET['tipo'])) $filtros['tipo'] = trim($_GET['tipo']);
if (!empty($_GET['id'])) $filtros['id'] = (int) $_GET['id'];

$listaServicos = $servicoDao->buscar($filtros);
$tiposDisponiveis = $servicoDao->listarTipos();
?>

<div class="row container-xl mx-auto">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>

    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gerenciar Serviços</h1>
            <a href="servicoForm.php" class="btn btn-primary">Adicionar Novo Serviço</a>
        </div>

        <form class="row g-2 mb-3" method="GET" action="gerenciar_servicos.php">
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
                <button type="submit" class="btn btn-secondary me-2">Filtrar</button>
                <a href="gerenciar_servicos.php" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success">
                Serviço <?= $_GET['acao']; ?> com sucesso!
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert alert-danger">
                Erro ao tentar <?= $_GET['acao']; ?> o serviço.
            </div>
        <?php endif; ?>

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
                <?php foreach ($listaServicos as $servico): ?>
                <tr>
                    <td><?= htmlspecialchars($servico['id']); ?></td>
                    <td><?= htmlspecialchars($servico['nome']); ?></td>
                    <td><?= ucfirst(htmlspecialchars($servico['tipo_servico'])); ?></td>
                    <td>R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></td>
                    <td>
                        <a href="servicoForm.php?id=<?= htmlspecialchars($servico['id']); ?>" class="btn btn-warning btn-sm">Alterar</a>
                        <a href="../controllers/controlerServico.php?opcao=excluir&id=<?= htmlspecialchars($servico['id']); ?>" 
                           onclick="return confirm('Tem certeza que deseja excluir este serviço?');" 
                           class="btn btn-danger btn-sm">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>