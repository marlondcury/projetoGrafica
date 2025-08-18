<?php 
require_once '../includes/cabecalho.php'; 

// Lógica de segurança para verificar se o usuário é cliente
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'cliente') {
    header('Location: /grafica_web/login.php');
    exit();
}

// Pega o carrinho da sessão. Se não existir, cria um array vazio.
$carrinho = $_SESSION['carrinho'] ?? [];
$total_carrinho = 0;
?>

<div class="container-xl mx-auto mt-5">
    <h2>Meu Carrinho de Compras</h2>
    <hr>
    <?php 
    // Verifica se o carrinho NÃO está vazio antes de tentar mostrar a tabela
    if (count($carrinho) > 0): 
    ?>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Serviço</th>
                    <th>Detalhes</th>
                    <th>Qtd.</th>
                    <th>Subtotal</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carrinho as $index => $item): ?>
                    <?php 
                        $subtotal = $item['preco_unitario'] * $item['quantidade'];
                        $total_carrinho += $subtotal;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td>
                            <?php if (!empty($item['atributos'])): ?>
                                <?php foreach($item['atributos'] as $chave => $valor): ?>
                                    <small><strong><?= ucfirst(htmlspecialchars($chave)) ?>:</strong> <?= htmlspecialchars($valor) ?></small><br>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($item['quantidade']) ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        <td>
                            <a href="../controllers/encomendaController.php?opcao=remover_item&index=<?= $index ?>" class="btn btn-danger btn-sm">Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <h4>Total: <span class="float-end">R$ <?= number_format($total_carrinho, 2, ',', '.') ?></span></h4>
                <div class="d-grid gap-2 mt-3">
                    <a href="../controllers/encomendaController.php?opcao=finalizar" class="btn btn-success btn-lg">Finalizar Compra</a>
                    <a href="../public/servicos.php" class="btn btn-outline-secondary">Continuar Comprando</a>
                </div>
            </div>
        </div>
    <?php else: // Se o carrinho estiver vazio, mostra esta mensagem ?>
        <div class="alert alert-info">
            <h4 class="alert-heading">Seu carrinho está vazio!</h4>
            <p>Adicione serviços para poder finalizar sua encomenda.</p>
            <hr>
            <a href="../public/servicos.php" class="btn btn-primary">Ver Serviços</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../includes/rodape.php'; ?>