<?php
/*
====================================================
  register.php - Página de cadastro de usuário
  Permite que novos usuários criem uma conta
====================================================
*/

// ===============================
// INICIA SESSÃO
// ===============================
session_start();

// ===============================
// REDIRECIONA SE JÁ ESTIVER LOGADO
// ===============================
if (isset($_SESSION['user_id']))
  { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8">
<title>Cadastrar — CarbonBurguer</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include "partials/header.php"; ?>

<main class="container form-page">
  <h2>Criar Conta</h2>

  <form method="post" action="backend/register_process.php">
    <input type="text" name="nome" placeholder="Nome completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha (mín. 6)" minlength="6" required>
    <button class="btn">Registrar</button>
  </form>
</main>

</body>
</html>
