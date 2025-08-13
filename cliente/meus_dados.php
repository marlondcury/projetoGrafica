<?php
require_once '../includes/cabecalho.php';

// Lógica de segurança para verificar se o usuário é cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'cliente') {
    header('Location: /grafica_web/login.php');
    exit();
}

/*
 * Em um cenário real, estes dados viriam do banco de dados usando o ID do usuário da sessão.
 * Ex: $clienteDao = new ClienteDao(); $cliente = $clienteDao->buscarPorUsuarioId($_SESSION['usuario_id']);
 * Para este exemplo, usaremos dados estáticos.
 */
$cliente_dados = [
    'nome' => $_SESSION['usuario_nome'],
    'email' => 'cliente@email.com', // Supondo que o email foi buscado no banco
    'telefone' => '(27) 98877-6655',
    'endereco' => 'Rua das Flores, 123, Bairro Jardim, Vitória - ES'
];

?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Meus Dados Cadastrais</h2>
        <p>Mantenha suas informações sempre atualizadas.</p>
        <hr>

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

                <hr class="my-4">

                <h5 class="card-title">Alterar Senha</h5>
                <form action="../controllers/usuarioController.php" method="POST">
                     <input type="hidden" name="opcao" value="alterar_senha">
                    <div class="mb-3">
                        <label for="senha_atual" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                    </div>
                    <div class="mb-3">
                        <label for="nova_senha" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                    </div>
                     <div class="mb-3">
                        <label for="confirma_nova_senha" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="confirma_nova_senha" name="confirma_nova_senha" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Alterar Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>