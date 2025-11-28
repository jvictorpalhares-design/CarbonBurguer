<?php
// ===============================
// LISTAGEM DE PEDIDOS (ADMIN)
// Página que exibe todos os pedidos cadastrados no sistema para o administrador.
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
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; }

// ===============================
// CONSULTA DE TODOS OS PEDIDOS NO BANCO
// ===============================
// listar pedidos
$orders = $conn->query("SELECT p.*, u.nome as cliente FROM pedidos p LEFT JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.id DESC");
?>
<!-- =============================== -->
<!-- ESTRUTURA HTML DA LISTA DE PEDIDOS -->
<!-- Exibe todos os pedidos cadastrados, com link para detalhes -->
<!-- =============================== -->
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Pedidos — Admin</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include '../partials/header.php'; ?>
<main class="container">
  <!-- =============================== -->
  <!-- TÍTULO DA PÁGINA DE PEDIDOS -->
  <!-- =============================== -->
  <h2>Pedidos</h2>
  <!-- =============================== -->
  <!-- LISTAGEM DOS PEDIDOS -->
  <!-- Cada pedido é exibido em um card com informações resumidas e link para detalhes -->
  <!-- =============================== -->
  <?php while ($o = $orders->fetch_assoc()): ?>
    <div class="order-card" style="background:#111;padding:12px;margin-bottom:10px;border-radius:8px">
      <strong>Pedido #<?php echo intval($o['id']); ?></strong> — <?php echo htmlspecialchars($o['cliente']); ?> — Total: R$ <?php echo number_format($o['total'],2,',','.'); ?> — <?php echo $o['data_pedido']; ?>
      <div style="margin-top:8px">
        <a href="order_view.php?id=<?php echo intval($o['id']); ?>" class="btn">Ver detalhes</a>
      </div>
    </div>
  <?php endwhile; ?>
</main>
</body>
</html>
