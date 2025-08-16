<?php require_once 'includes/cabecalho.php'; ?>
<div class="mx-auto mt-5" style="max-width: 1200px;">
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Bem-vindo à Gráfica Rápida Online</h1>
            <p class="col-md-8 fs-4">A solução completa para suas necessidades de impressão. De cartões de visita a banners em lona, oferecemos qualidade e agilidade.</p>
            <a href="public/servicos.php" class="btn btn-primary btn-lg" type="button">Conheça Nossos Serviços</a>
        </div>
    </div>

<div class="row align-items-md-stretch">
    
    <?php // Inicia a verificação: O usuário está logado?
    if (isset($_SESSION['usuario_id'])): 
        // Define o link do painel com base no tipo de usuário
        $link_painel = ($_SESSION['usuario_tipo'] == 'admin') ? 'admin/' : 'cliente/';
    ?>

        <div class="col-md-6">
            <div class="h-100 p-5 text-white bg-success rounded-3">
                <h2>Acessar Meu Painel</h2>
                <p>Bem-vindo(a) de volta, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>! Acesse seu painel para ver suas encomendas, gerenciar seus dados e muito mais.</p>
                <a href="<?= $link_painel ?>" class="btn btn-outline-light" type="button">Ir para Meu Painel</a>
            </div>
        </div>

    <?php else: ?>

        <div class="col-md-6">
            <div class="h-100 p-5 text-white bg-dark rounded-3">
                <h2>Cadastre-se</h2>
                <p>Crie sua conta gratuitamente e tenha acesso a um painel exclusivo para gerenciar suas encomendas, acompanhar o status e muito mais.</p>
                <a href="cadastro.php" class="btn btn-outline-light" type="button">Criar Minha Conta</a>
            </div>
        </div>

    <?php endif; // Fim da verificação ?>

    <div class="col-md-6">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h2>Fale Conosco</h2>
            <p>Tem alguma dúvida ou precisa de um orçamento personalizado? Nossa equipe está pronta para te atender. Entre em contato conosco.</p>
            <a href="public/fale_conosco.php" class="btn btn-outline-secondary" type="button">Entrar em Contato</a>
        </div>
    </div>
</div>

<?php require_once 'includes/rodape.php'; ?>