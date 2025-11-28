<?php
/*
====================================================
  admin/products.php - Gerenciamento de produtos
  Permite ao admin visualizar, editar e excluir produtos
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once '../php/config.php';// Inclui configurações e funções
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['is_admin'])) { header('Location: login.php'); exit; } // Restringe acesso a admins

// ===============================
// BUSCA PRODUTOS NO BANCO
// ===============================
$res = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Produtos — Admin</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<?php include '../partials/header.php'; ?>
<main class="container">
  <!--
    ===============================
    TÍTULO E BOTÃO DE ADIÇÃO
    ===============================
    Exibe título e botão para adicionar novo produto
  -->
  <h2>Produtos</h2>
  <a class="btn" href="add.php">+ Adicionar produto</a>
  <!--
    ===============================
    TABELA DE PRODUTOS
    ===============================
    Lista todos os produtos cadastrados
  -->
  <table class="admin-table" style="width:100%;margin-top:12px">
    <thead><tr><th>Imagem</th><th>Nome</th><th>Preço</th><th>Categoria</th><th>Ações</th></tr></thead>
    <tbody>
      <?php while($p = $res->fetch_assoc()): ?>
        <tr>
          <!-- Imagem do produto -->
          <td style="width:120px"><img src="<?php echo htmlspecialchars($p['imagem']); ?>" alt="" style="width:100px;height:60px;object-fit:cover;border-radius:6px"></td>
          <!-- Nome do produto -->
          <td><?php echo htmlspecialchars($p['nome']); ?></td>
          <!-- Preço formatado -->
          <td>R$ <?php echo number_format($p['preco'],2,',','.'); ?></td>
          <!-- Categoria -->
          <td><?php echo htmlspecialchars($p['categoria']); ?></td>
          <!-- Ações de editar/excluir -->
          <td>
            <a href="edit.php?id=<?php echo intval($p['id']); ?>">Editar</a> |
            <a href="delete.php?id=<?php echo intval($p['id']); ?>" onclick="return confirm('Excluir produto?')">Excluir</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>
</body>
</html>
