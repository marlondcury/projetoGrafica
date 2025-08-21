<?php

require_once '../includes/cabecalho.php';

?>

<div class="row">
    <div class="col-md-3">
        <?php 
            require_once '../includes/menu_admin.php'; 
        ?>
    </div>
    <div class="col-md-9">
        <h2>Painel Administrativo</h2>
        <p>Bem-vindo(a), <strong><?= htmlspecialchars($_SESSION['usuario_nome']); ?></strong>! Utilize o menu ao lado para gerenciar o conteúdo do site.</p>
        <hr>
        <h4>Acesso Rápido</h4>
        <div class="list-group">
            <a href="gerenciar_servicos.php" class="list-group-item list-group-item-action">Gerenciar Serviços</a>
            <a href="gerenciar_clientes.php" class="list-group-item list-group-item-action">Visualizar Clientes</a>
            <a href="relatorio_vendas.php" class="list-group-item list-group-item-action">Relatório de Vendas</a>
        </div>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>