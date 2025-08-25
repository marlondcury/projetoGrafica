<?php

session_start();

require_once '../dao/ServicoDao.php';
require_once '../dao/EncomendaDao.php';
require_once '../classes/Encomenda.php';
require_once '../classes/ItemEncomenda.php';

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
                    header('Location: ../cliente/escolherPagamento.php?id=' . $novaEncomendaId);
            } else {
                    header('Location: ../cliente/carrinho.php?status=erro_finalizar');
            }
            break;
        
            case 'atualizar_status':
                case 'atualizar_status':
                
                    $id = $_POST['id'] ?? null;
                    $novoStatus = $_POST['novo_status'] ?? null;
                
                    if (!$id || empty($novoStatus)) {
                        header('Location: ../admin/gerenciarEncomendas.php?id=' . $id . '&status=erro&acao=dados_invalidos');
                        exit();
                    }
                    
                    $encomendaDao = new EncomendaDao();
                    
                    if ($encomendaDao->atualizarStatus($id, $novoStatus)) {
                        header('Location: ../admin/gerenciarEncomendas.php?id=' . $id . '&status=sucesso&acao=status_atualizado');
                    } else {
                        header('Location: ../admin/gerenciarEncomendas.php?id=' . $id . '&status=erro&acao=status_nao_atualizado');
                    }
                    break;
            default:
                header('Location: ../cliente/carrinho.php');
                break;
}
?>