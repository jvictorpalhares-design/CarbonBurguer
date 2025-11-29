<?php
/*
====================================================
  partials/header.php - Cabeçalho e navegação
  Inclui o topo do site, menu e início do <body>
====================================================
*/

// ===============================
// INICIA SESSÃO SE NECESSÁRIO
// ===============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===============================
// INCLUI CONFIGURAÇÃO GLOBAL
// ===============================
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../php/config.php';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--
        ===============================
        TÍTULO E CSS
        ===============================
        Define o título da página e inclui o CSS
    -->
    <title><?php echo isset($page_title) ? sanitize($page_title) : 'CarbonBurguer'; ?></title>
    <link rel="stylesheet" href="<?php echo isset($css_path) ? $css_path : 'assets/css/style.css'; ?>">
</head>
<body>

<!--
    ===============================
    HEADER E NAVEGAÇÃO
    ===============================
    Exibe o topo do site, logo e menu
-->
<header class="header">
    <div class="container nav">
        <!-- Logo -->
        <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="logo">
            Carbon<span>Burguer</span>
        </a>
        <!-- Menu responsivo -->
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="menu-icon">&#9776;</label>

        <nav class="menu">
            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php">Home</a>
            <a href="<?php echo isset($base_path) ? $base_path : ''; ?>produtos.php">Cardápio</a>

            <?php if (is_logged_in()): ?>
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>carrinho.php">Carrinho</a>
                
                <?php if (is_admin()): ?>
                    <a href="<?php echo isset($base_path) ? $base_path : ''; ?>admin/index.php">Admin</a>
                <?php endif; ?>
                
                <span class="user-name">Olá, <?php echo sanitize($_SESSION['user']); ?></span>
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>backend/logout.php">Sair</a>
            <?php else: ?>
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>login.php">Entrar</a>
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>register.php">Cadastrar</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                 <li><a href="minha_conta.php">Minha Conta</a></li>
                 <?php endif; ?>

        </nav>
    </div>
</header>

<!--
    ===============================
    INÍCIO DO MAIN
    ===============================
    Exibe mensagens flash e conteúdo principal
-->
<main>
    <?php display_flash(); ?>