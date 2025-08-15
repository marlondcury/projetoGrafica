<?php
require_once '../includes/cabecalho.php';
?>
<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Painel do Cliente</h2>
        <p>Olá, <?php echo $_SESSION['usuario_nome']; ?>! Bem-vindo(a) à sua área pessoal.</p>
        <hr>
    </div>
</div>
<?php require_once '../includes/rodape.php'; ?>