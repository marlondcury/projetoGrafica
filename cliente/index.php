<?php
require_once '../includes/cabecalho.php';
// Lógica de segurança para verificar se o usuário é cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'cliente') {
    header('Location: /grafica_web/login.php');
    exit();
}
?>
<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_cliente.php'; ?>
    </div>
    <div class="col-md-9">
        <h2>Painel do Cliente</h2>
        <p>Olá, <?php echo $_SESSION['usuario_nome']; ?>! Bem-vindo(a) à sua área pessoal.</p>
        <hr>
        <h4>Suas Últimas Encomendas</h4>
        <p>Aqui você pode ver um resumo das suas encomendas. Para mais detalhes, acesse o menu ao lado.</p>
        </div>
</div>
<?php require_once '../includes/rodape.php'; ?>