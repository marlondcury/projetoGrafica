<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica Rápida Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/grafica_web/assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/grafica_web/index.php">
        <img src="/grafica_web/assets/img/logo.png" alt="Logo Gráfica" width="30" height="24" class="d-inline-block align-text-top">
        Gráfica Rápida
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/grafica_web/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/grafica_web/public/servicos.php">Serviços</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/grafica_web/public/sobre.php">Sobre Nós</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/grafica_web/public/fale_conosco.php">Fale Conosco</a>
        </li>
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?>
                 <li class="nav-item"><a class="nav-link active" href="/grafica_web/admin/">Área Admin</a></li>
            <?php else: ?>
                 <li class="nav-item"><a class="nav-link active" href="/grafica_web/cliente/">Minha Conta</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="/grafica_web/controllers/usuarioController.php?opcao=logout">Sair</a></li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/grafica_web/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary" href="/grafica_web/cadastro.php">Cadastre-se</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">