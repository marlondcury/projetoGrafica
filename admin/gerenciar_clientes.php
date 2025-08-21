<?php

require_once '../includes/cabecalho.php'; 
require_once '../dao/UsuarioDao.php';
require_once '../classes/Usuario.php';

$usuarioDao = new UsuarioDao();
$clientes = $usuarioDao->listarTodos();

?>

<div class="row container-xl mx-auto">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciar Clientes</h2>
            </div>

        <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
            <div class="alert alert-success">
                Usuário <?= $_GET['acao']; ?> com sucesso!
            </div>
        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
            <div class="alert alert-danger">
                Erro ao tentar <?= $_GET['acao']; ?> o usuário.
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente->getId() ?></td>
                        <td><?= $cliente->getNome() ?></td>
                        <td><?= $cliente->getEmail() ?></td>
                        <td><?= ucfirst($cliente->getTipo()) ?></td>
                        <td>
                            <a href="clienteForm.php?id=<?= $cliente->getId() ?>" class="btn btn-warning btn-sm">Alterar</a>
                            <a href="../controllers/usuarioController.php?opcao=excluir&id=<?= $cliente->getId() ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>