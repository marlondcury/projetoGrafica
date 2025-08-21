<?php
require_once '../includes/cabecalho.php';
require_once '../dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$cliente_dados = $usuarioDao->buscarClientePorUsuarioId($_SESSION['usuario_id']);
?>

<div class="row container-xl mx-auto mt-4">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Meus Dados Cadastrais</h2>
        <p>Mantenha suas informações sempre atualizadas.</p>
        <hr>

        <?php
        // Feedback para o usuário após a atualização
        if (isset($_GET['status']) && $_GET['status'] == 'sucesso_update'): ?>
            <div class="alert alert-success">Dados atualizados com sucesso!</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informações Pessoais</h5>
                <form action="../controllers/usuarioController.php" method="POST">
                    <input type="hidden" name="opcao" value="atualizar_dados">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($cliente_dados['nome']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail (não pode ser alterado)</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente_dados['email']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente_dados['telefone']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <textarea class="form-control" id="endereco" name="endereco" rows="3"><?= htmlspecialchars($cliente_dados['endereco']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
                </div>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>