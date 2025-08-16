<?php
// Pega o nome do arquivo da página atual para saber qual link destacar
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>

<div class="list-group shadow-sm">
  
  <a href="/grafica_web/cliente/index.php" class="list-group-item list-group-item-action <?= ($pagina_atual == 'index.php') ? 'active' : '' ?>">
    Meu Painel
  </a>

  <a href="/grafica_web/cliente/minhas_encomendas.php" class="list-group-item list-group-item-action <?= ($pagina_atual == 'minhas_encomendas.php') ? 'active' : '' ?>">
    Minhas Encomendas
  </a>

  <a href="/grafica_web/cliente/meus_dados.php" class="list-group-item list-group-item-action <?= ($pagina_atual == 'meus_dados.php') ? 'active' : '' ?>">
    Meus Dados
  </a>

  <a href="/grafica_web/public/servicos.php" class="list-group-item list-group-item-action">
    Fazer Nova Encomenda
  </a>
  
  <a href="/grafica_web/controllers/usuarioController.php?opcao=logout" class="list-group-item list-group-item-action text-danger">
    Sair
  </a>
</div>