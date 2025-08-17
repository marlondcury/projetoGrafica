<?php require_once '../includes/cabecalho.php'; ?>
<?php
// Lógica de segurança
if (!isset($_SESSION['usuario_id'])) { header('Location: /grafica_web/login.php'); exit(); }

// Dados da encomenda viriam do banco de dados usando o ID da URL
$encomenda_id = $_GET['id'] ?? 'N/A';
// Simulação de dados
$cliente_nome = $_SESSION['usuario_nome'];
$valor_total = 165.50; // Este valor viria do banco
$data_vencimento = date('d/m/Y', strtotime('+3 days'));
$linha_digitavel = '00190.00009 01027.234563 00000.000114 1 98760000016550';
?>

<div class="container-xl mx-auto mt-4">
    <div class="text-center">
        <h3>Encomenda #<?= htmlspecialchars($encomenda_id) ?> realizada com sucesso!</h3>
        <p class="lead">Pague o boleto abaixo para confirmar seu pedido.</p>
    </div>
    
    <div class="boleto-container mx-auto mt-4">
        <div class="boleto-header">
            <div><img src="https://via.placeholder.com/150x40.png?text=LOGO+BANCO" alt="Logo Banco"></div>
            <div class="banco-codigo">001-9</div>
            <div class="linha-digitavel"><?= $linha_digitavel ?></div>
        </div>
        <div class="boleto-row">
            <div class="boleto-campo">
                <div class="boleto-label">Beneficiário</div>
                <div class="boleto-valor">Gráfica Rápida LTDA</div>
            </div>
            <div class="boleto-campo">
                <div class="boleto-label">Agência / Código do Beneficiário</div>
                <div class="boleto-valor">1234-5 / 123456-7</div>
            </div>
        </div>
        <div class="boleto-row">
            <div class="boleto-campo">
                <div class="boleto-label">Pagador</div>
                <div class="boleto-valor"><?= htmlspecialchars($cliente_nome) ?></div>
            </div>
            <div class="boleto-campo data-vencimento">
                <div class="boleto-label">Data de Vencimento</div>
                <div class="boleto-valor fw-bold"><?= $data_vencimento ?></div>
            </div>
        </div>
        <div class="boleto-row">
            <div class="boleto-campo">
                <div class="boleto-label">Nosso Número</div>
                <div class="boleto-valor">123456789-0</div>
            </div>
            <div class="boleto-campo valor-documento">
                <div class="boleto-label">(=) Valor do Documento</div>
                <div class="boleto-valor fw-bold">R$ <?= number_format($valor_total, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="boleto-instrucoes">
            <div class="boleto-label">Instruções de responsabilidade do beneficiário.</div>
            <div class="boleto-valor">- Não receber após o vencimento. <br>- Multa de 2% após vencimento.</div>
        </div>
        <div class="boleto-footer">
            <img src="https://barcode.tec-it.com/barcode.ashx?data=<?= str_replace(['.', ' '], '', $linha_digitavel) ?>&code=Code25IL&dpi=96" alt="Código de Barras" class="barcode-img">
        </div>
    </div>
    <div class="text-center mt-4">
        <button class="btn btn-primary" onclick="window.print()">Imprimir Boleto</button>
        <a href="minhas_encomendas.php" class="btn btn-secondary">Voltar para Minhas Encomendas</a>
    </div>
</div>

<?php require_once '../includes/rodape.php'; ?>