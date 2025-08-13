<?php
require_once '../includes/cabecalho.php';

// Lógica de segurança para verificar se o usuário é admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header('Location: /grafica_web/login.php');
    exit();
}

/*
 * Em um cenário real, estes dados viriam do banco de dados através de um ClienteDao.
 * Ex: $clienteDao = new ClienteDao(); $clientes = $clienteDao->listarTodos();
 * Para este exemplo, usaremos um array estático.
 */
$clientes = [
    ['id' => 1, 'nome' => 'Bia Martins', 'email' => 'bia@email.com', 'telefone' => '(27) 99999-0001', 'data_cadastro' => '2025-07-10'],
    ['id' => 2, 'nome' => 'Illan Souza', 'email' => 'illan@email.com', 'telefone' => '(27) 99999-0002', 'data_cadastro' => '2025-07-11'],
    ['id' => 3, 'nome' => 'Marlon Aguiar', 'email' => 'marlon@email.com', 'telefone' => '(27) 99999-0003', 'data_cadastro' => '2025-07-12'],
    ['id' => 4, 'nome' => 'Carlos Furtado', 'email' => 'carlos@email.com', 'telefone' => '(27) 99999-0004', 'data_cadastro' => '2025-07-13']
];
?>

<div class="row">
    <div class="col-md-3">
        <?php require_once '../includes/menu_admin.php'; ?>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciar Clientes</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">