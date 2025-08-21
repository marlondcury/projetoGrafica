<?php

require_once '../includes/cabecalho.php'; 

require_once __DIR__.'/../dao/UsuarioDao.php';
require_once __DIR__.'/../classes/Usuario.php';

$usuarioDao = new UsuarioDao();

$listaUsuarios = $usuarioDao->listarTodosComDetalhes();

?>

<div class="row">
    <div class="col-md-3">
    <?php require_once '../includes/menuAdmin.php'; ?>
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
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaUsuarios as $usuario): ?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id']) ?></td>
                        <td><?= htmlspecialchars($usuario['nome']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <td><?= htmlspecialchars($usuario['cpf'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($usuario['telefone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($usuario['endereco'] ?? 'N/A') ?></td>
                        <td>
                            <a href="clienteForm.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Alterar</a>
                            <a href="../controllers/usuarioController.php?opcao=excluir&id=<?= $usuario['id'] ?>" 
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