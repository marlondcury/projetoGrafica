<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica Rápida Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
    session_start();

    $tipo = "visitante"; 
    if (isset($_SESSION['usuario_id'])) {
        require_once __DIR__ . '/../dao/UsuarioDao.php';
        $usuarioDao = new UsuarioDao();
        $usuario = $usuarioDao->buscarPorId($_SESSION['usuario_id']);
        if ($usuario) {
            $tipo = strtolower($usuario->getTipo());
            $_SESSION['usuario_tipo'] = $tipo;
        } else {
            $tipo = 'visitante';
            unset($_SESSION['usuario_tipo']);
        }
    } else {
        $tipo = 'visitante';
    }
    require_once "cabecalho$tipo.inc.php";
?>