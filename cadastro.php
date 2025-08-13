<?php require_once 'includes/cabecalho.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Cadastro de Novo Cliente</h3>
            </div>
            <div class="card-body">
                <?php
                    // Feedback para o usuário
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        if ($status == 'erro_campos') {
                            echo '<div class="alert alert-danger">Por favor, preencha todos os campos.</div>';
                        } elseif ($status == 'erro_email_existente') {
                             echo '<div class="alert alert-danger">O e-mail informado já está cadastrado.</div>';
                        } elseif ($status == 'erro_cadastro') {
                            echo '<div class="alert alert-danger">Ocorreu um erro ao realizar o cadastro. Tente novamente.</div>';
                        }
                    }
                ?>
                <form action="controllers/usuarioController.php" method="POST">
                    <input type="hidden" name="opcao" value="cadastrar">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p>Já tem uma conta? <a href="login.php">Faça o login aqui</a>.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/rodape.php'; ?>