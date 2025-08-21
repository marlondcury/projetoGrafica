<?php

require_once '../includes/cabecalho.php';
require_once '../dao/UsuarioDao.php';
require_once '../classes/Usuario.php';

$usuarioDao = new UsuarioDao();
$usuario = null;
$titulo_pagina = 'Visualizar Usuário';

if (isset($_GET['id'])) {
    $usuario = $usuarioDao->buscarPorId($_GET['id']);
    if ($usuario) {
        $titulo_pagina = 'Alterar Usuário';
    }
}
?>

<div class="container container-xl mx-auto mt-5">
    <h1 class="mb-4"><?= $titulo_pagina; ?></h1>
    <p><a href="gerenciar_clientes.php">Voltar para a lista de clientes</a></p>
    
    <?php if ($usuario): ?>
        <form action="../controllers/usuarioController.php" method="POST">
            <input type="hidden" name="opcao" value="alterar">
            <input type="hidden" name="id" value="<?= $usuario->getId(); ?>">

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= $usuario->getNome(); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $usuario->getEmail(); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Usuário</label>
                <select class="form-control" id="tipo" name="tipo">
                    <option value="cliente" <?= ($usuario->getTipo() == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="admin" <?= ($usuario->getTipo() == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Usuário não encontrado.</div>
    <?php endif; ?>
</div>

<?php require_once '../includes/rodape.php'; ?>