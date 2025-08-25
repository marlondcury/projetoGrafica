<?php

require_once '../includes/cabecalho.php';

require_once __DIR__ . '/../dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$cliente_dados = $usuarioDao->buscarDetalhesDoUsuario($_SESSION['usuario_id']);
?>

<div class="row container-xl mx-auto mt-4">
    <div class="col-md-3">
    <?php require_once '../includes/menuCliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Meus Dados Cadastrais</h2>
        <p>Mantenha suas informações sempre atualizadas.</p>
        <hr>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'sucesso'): ?>
        <div class="alert alert-success">
            Dados atualizados com sucesso!
        </div>
    <?php elseif (isset($_GET['status']) && $_GET['status'] == 'erro'): ?>
        <div class="alert alert-danger">
            Ocorreu um erro ao atualizar os dados. Por favor, tente novamente.
        </div>
    <?php endif; ?>

<div class="card">
    </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informações Pessoais</h5>
                <form action="../controllers/usuarioController.php" method="POST">
                    <input type="hidden" name="opcao" value="atualizar_meus_dados">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_SESSION['usuario_id']) ?>">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($cliente_dados['nome'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail (não pode ser alterado)</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($cliente_dados['email'] ?? '') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" value="<?= htmlspecialchars($cliente_dados['cpf'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($cliente_dados['telefone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco" value="<?= htmlspecialchars($cliente_dados['endereco'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep" value="<?= htmlspecialchars($cliente_dados['cep'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade" value="<?= htmlspecialchars($cliente_dados['cidade'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="uf" class="form-label">UF</label>
                        <input type="text" class="form-control" id="uf" name="uf" value="<?= htmlspecialchars($cliente_dados['uf'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>