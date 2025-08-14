<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Inclusão de todas as dependências necessárias
require_once __DIR__.'/../dao/ServicoDao.php';
require_once __DIR__.'/../dao/EncomendaDao.php';
require_once __DIR__.'/../classes/Encomenda.php';
require_once __DIR__.'/../classes/ItemEncomenda.php';

// ... coloque este código logo após o último require_once

//--- INÍCIO DO TESTE DE FOGO ---
if (class_exists('ItemEncomenda')) {
    // Se entrar aqui, a classe foi carregada com sucesso.
    // O erro pode estar em outro lugar (muito improvável).
} else {
    // Se entrar aqui, o require_once falhou.
    die("FALHA DEFINITIVA: A classe 'ItemEncomenda' NÃO foi carregada. Verifique o nome do arquivo 'ItemEncomenda.php' e o caminho no require_once. O caminho usado foi: " . __DIR__.'/../classes/ItemEncomenda.php');
}
//--- FIM DO TESTE DE FOGO ---

// O resto do seu código continua aqui...
// Garante que o usuário está logado para qualquer ação de encomenda
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php?erro=login_necessario');
    exit();
}

$opcao = $_REQUEST['opcao'] ?? 'ver';

switch($opcao) {
    case 'adicionar':
        $servico_id = filter_input(INPUT_POST, 'servico_id', FILTER_VALIDATE_INT);
        $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
        $atributos = $_POST['atributos'] ?? [];

        if (!$servico_id || !$quantidade || $quantidade <= 0) {
            header('Location: ../public/servicos.php?status=erro_dados');
            exit();
        }
        
        $servicoDao = new ServicoDao();
        $servico = $servicoDao->buscarPorId($servico_id);

        if (!$servico) {
            header('Location: ../public/servicos.php?status=servico_invalido');
            exit();
        }
        
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        $item = [
            'servico_id' => $servico['id'],
            'nome' => $servico['nome'],
            'quantidade' => $quantidade,
            'atributos' => $atributos,
            'preco_unitario' => $servico['preco_base'] 
        ];

        $_SESSION['carrinho'][] = $item;
        
        header('Location: ../cliente/carrinho.php?status=item_adicionado');
        break;

    case 'remover_item':
        $index = $_GET['index'] ?? -1;
        if (isset($_SESSION['carrinho'][$index])) {
            unset($_SESSION['carrinho'][$index]);
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
        }
        header('Location: ../cliente/carrinho.php');
        break;
        
        case 'finalizar':
            if (empty($_SESSION['carrinho'])) {
                header('Location: ../cliente/carrinho.php');
                exit();
            }
            
            $encomenda = new Encomenda();
            $encomenda->setClienteId($_SESSION['usuario_id']);
            $encomenda->setDataEncomenda(date('Y-m-d H:i:s'));
            // --- CORREÇÃO APLICADA AQUI ---
            $encomenda->setStatus('em_aberto');
    
            $valor_total_calculado = 0;
            foreach ($_SESSION['carrinho'] as $item_data) {
                $item = new ItemEncomenda(); 
                $item->setServicoId($item_data['servico_id']);
                $item->setQuantidade($item_data['quantidade']);
                $item->setAtributos(json_encode($item_data['atributos']));
                $item->setValorUnitario($item_data['preco_unitario']);
                
                $encomenda->adicionarItem($item);
                $valor_total_calculado += $item_data['preco_unitario'] * $item_data['quantidade'];
            }
            $encomenda->setValorTotal($valor_total_calculado);
    
            $encomendaDao = new EncomendaDao();
            $novaEncomendaId = $encomendaDao->criar($encomenda);
    
            if ($novaEncomendaId) {
                $_SESSION['ultima_encomenda'] = [
                    'id' => $novaEncomendaId,
                    'valor_total' => $encomenda->getValorTotal()
                ];
                unset($_SESSION['carrinho']);
                header('Location: ../cliente/boleto.php?id=' . $novaEncomendaId);
            } else {
                // Se o DAO retornar false, redireciona com erro
                header('Location: ../cliente/carrinho.php?status=erro_finalizar');
            }
            break;
        
    default:
         header('Location: ../cliente/carrinho.php');
        break;
}
?>