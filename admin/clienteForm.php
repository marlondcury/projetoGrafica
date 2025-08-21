<?php

require_once '../includes/cabecalho.php'; 


require_once __DIR__.'/../dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$usuario = null;
$titulo_pagina = 'Visualizar Usuário';

if (isset($_GET['id'])) {
    $usuario = $usuarioDao->buscarDetalhesDoUsuario($_GET['id']);
    if ($usuario) {
        $titulo_pagina = 'Alterar Usuário';
    }
}
?>

<div class="container mt-5">
    <h1><?= $titulo_pagina; ?></h1>
    <p><a href="gerenciar_clientes.php">Voltar para a lista de clientes</a></p>
    
    <?php if ($usuario): ?>
        <form action="../controllers/usuarioController.php" method="POST">
            <input type="hidden" name="opcao" value="atualizar">
            <input type="hidden" name="id" value="<?= $usuario['id']; ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="cliente" <?= ($usuario['tipo'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="admin" <?= ($usuario['tipo'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($usuario['cpf'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['telefone'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($usuario['endereco'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="cep" class="form-label">CEP</label>
                <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($usuario['cep'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($usuario['cidade'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="uf" class="form-label">UF</label>
                <input type="text" class="form-control" id="uf" name="uf" value="<?= htmlspecialchars($usuario['uf'] ?? ''); ?>">
            </div>

            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Usuário não encontrado.</div>
    <?php endif; ?>
</div>

<?php require_once '../includes/rodape.php'; ?>