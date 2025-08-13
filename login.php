<?php require_once 'includes/cabecalho.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h3 class="text-center">Login no Sistema</h3>
            </div>
            <div class="card-body">
                 <?php
                    // Feedback para o usuário
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        if ($status == 'erro_campos') {
                            echo '<div class="alert alert-danger">Por favor, preencha e-mail e senha.</div>';
                        } elseif ($status == 'erro_login') {
                             echo '<div class="alert alert-danger">E-mail ou senha inválidos.</div>';
                        } elseif ($status == 'sucesso_cadastro') {
                            echo '<div class="alert alert-success">Cadastro realizado com sucesso! Faça o login.</div>';
                        }
                    }
                ?>
                <form action="controllers/usuarioController.php" method="POST">
                    <input type="hidden" name="opcao" value="login">
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">Entrar</button>
                    </div>
                </form>
            </div>
             <div class="card-footer text-center">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se gratuitamente</a>.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/rodape.php'; ?>