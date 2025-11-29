<?php
// ===============================
// DASHBOARD ADMINISTRATIVO
// Página inicial do painel admin, com links para gerenciar produtos e pedidos.
// ===============================

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E INICIALIZAÇÃO DE SESSÃO
// ===============================
require_once '../php/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
// ===============================
// VERIFICAÇÃO DE PERMISSÃO DE ADMINISTRADOR
// Redireciona para login se não for admin
// ===============================
if (empty($_SESSION['is_admin'])) {
    header('Location: login.php'); exit;
}
?>
<!-- =============================== -->
<!-- ESTRUTURA HTML DO DASHBOARD ADMIN -->
<!-- Exibe links para gerenciamento de produtos, pedidos e acesso ao site -->
<!-- =============================== -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Admin — CarbonBurguer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<?php include '../partials/header.php'; ?>
<main class="container">
  <!-- =============================== -->
    <!-- TÍTULO DO PAINEL ADMINISTRATIVO -->
    <!-- =============================== -->
  <h2>Admin Dashboard</h2>
  <div class="admin-grid">
    <!-- =============================== -->
      <!-- LINKS DE NAVEGAÇÃO ADMIN -->
      <!-- =============================== -->
    <a class="btn" href="products.php">Gerenciar Produtos</a>
    <a class="btn" href="orders.php">Gerenciar Pedidos</a>
    <a class="btn" href="../index.php">Ver site</a>
  </div>
</main>
</body>
</html>
