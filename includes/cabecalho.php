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
<?php
    session_start();

    $tipo = "visitante"; 

    if(isset($_SESSION['usuario_tipo'])) {
        $tipo = $_SESSION['usuario_tipo'];
    }
    require_once "cabecalho$tipo.inc.php";
?>