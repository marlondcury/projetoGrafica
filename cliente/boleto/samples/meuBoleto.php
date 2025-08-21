<?php

session_start();

require '../autoloader.php';
require_once '../../../dao/EncomendaDao.php';
require_once '../../../dao/UsuarioDao.php';
require_once '../../../dao/ServicoDao.php';

use OpenBoleto\Banco\BancoDoBrasil;
use OpenBoleto\Agente;

$encomendaId = $_GET['id'] ?? null;
if (!$encomendaId) {
    die("ID da encomenda não fornecido.");
}

$encomendaDao = new EncomendaDao();
$usuarioDao = new UsuarioDao();
$servicoDao = new ServicoDao();

$dadosDaEncomenda = $encomendaDao->buscarDetalhesPorId($encomendaId);

if (!$dadosDaEncomenda) {
    die("Encomenda não encontrada.");
}

$encomenda = $dadosDaEncomenda['encomenda'];
$itens = $dadosDaEncomenda['itens'];

if ($encomenda['cliente_id'] != $_SESSION['usuario_id']) {
    die("Acesso negado.");
}

$sacado = $usuarioDao->buscarDetalhesDoUsuario($encomenda['cliente_id']);
$cedente = new Agente('Empresa de cosméticos LTDA', '02.123.123/0001-11', 'CLS 403 Lj 23', '71000-000', 'Brasília', 'DF');

$descricaoDemonstrativo = [];
foreach ($itens as $item_data) {
    $servico = $servicoDao->buscarPorId($item_data['servico_id']);
    $descricaoDemonstrativo[] = "{$item_data['quantidade']}x " . ($servico['nome'] ?? 'N/A');
}

$instrucoes = array('Após o vencimento, cobrar R$ 0,50 de mora ao dia.');

$dataVencimento = !empty($encomenda['data_vencimento_boleto'])
    ? new DateTime($encomenda['data_vencimento_boleto'])
    : (new DateTime())->modify('+5 days');

$boleto = new BancoDoBrasil(array(
    'dataVencimento' => $dataVencimento, 
    'valor' => $encomenda['valor_total'],
    'sequencial' => $encomenda['id'],
    'sacado' => new Agente(
        $sacado['nome'] ?? null,
        $sacado['cpf'] ?? null,
        $sacado['endereco'] ?? null,
        $sacado['cep'] ?? null,
        $sacado['cidade'] ?? null,
        $sacado['estado'] ?? null
    ),
    'cedente' => $cedente,
    'agencia' => 1724, 
    'carteira' => 18,
    'conta' => 10403005,
    'convenio' => 1234,
    'descricaoDemonstrativo' => $descricaoDemonstrativo,
    'instrucoes' => $instrucoes,
));

echo $boleto->getOutput();
?>