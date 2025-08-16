<?php 
require_once '../includes/cabecalho.php'; 
// Inclui o DAO para buscar o serviço específico
require_once '../dao/ServicoDao.php';

// Lógica de segurança para usuário logado
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: /grafica_web/login.php");
    exit();
}

// Pega o ID da URL de forma segura
$servico_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$servico = null; // Inicia a variável como nula

if ($servico_id) {
    // Busca o serviço específico no banco de dados
    $servicoDao = new ServicoDao();
    $servico = $servicoDao->buscarPorId($servico_id);
}

// Se o serviço não for encontrado, exibe uma mensagem de erro
if (!$servico) {
    echo "<div class='alert alert-danger'>Serviço não encontrado ou inválido.</div>";
    require_once '../includes/rodape.php';
    exit(); // Interrompe a execução
}
?>

<div class="container-xl mt-5">
    <h1 class="mb-4">Personalizar Serviço: <?= htmlspecialchars($servico['nome']) ?></h1>

    <form action="/grafica_web/controllers/encomendaController.php" method="POST">
        <input type="hidden" name="servico_id" value="<?= $servico['id'] ?>">
        <input type="hidden" name="opcao" value="adicionar">
        
        <?php if ($servico['tipo_servico'] == 'reproducao'): ?>
            <div class="mb-3">
                <label for="tipo_papel" class="form-label">Tipo de Papel</label>
                <select name="atributos[tipo_papel]" id="tipo_papel" class="form-select">
                    <option value="sulfite_75g">Sulfite 75g</option>
                    <option value="couche_120g">Couchê 120g</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo_impressao" class="form-label">Tipo de Impressão</label>
                <select name="atributos[tipo_impressao]" id="tipo_impressao" class="form-select">
                    <option value="pb">Preto e Branco</option>
                    <option value="colorido">Colorido</option>
                </select>
            </div>
        <?php elseif ($servico['tipo_servico'] == 'banner'): ?>
             <div class="mb-3">
                <label for="material" class="form-label">Material</label>
                <select name="atributos[material]" id="material" class="form-select">
                    <option value="lona_fosca">Lona Fosca</option>
                    <option value="lona_brilho">Lona com Brilho</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1">
        </div>

        <div class="mb-3">
            <h4>Valor Total Estimado: <span id="valor_total">R$ <?= number_format($servico['preco_base'], 2, ',', '.') ?></span></h4>
        </div>

        <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
    </form>

    <script>
        const inputQuantidade = document.getElementById('quantidade');
        const spanValorTotal = document.getElementById('valor_total');
        const precoBase = <?= $servico['preco_base']; ?>;

        function atualizarTotal() {
            const quantidade = parseInt(inputQuantidade.value) || 0;
            const novoTotal = precoBase * quantidade;
            const valorFormatado = novoTotal.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            spanValorTotal.innerText = valorFormatado;
        }
        inputQuantidade.addEventListener('input', atualizarTotal);
    </script>
</div>

<?php require_once '../includes/rodape.php'; ?>