<?php
// ===============================
// LOGIN DO ADMINISTRADOR
// Página de autenticação para acesso ao painel administrativo.
// ===============================

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E INICIALIZAÇÃO DE SESSÃO
// ===============================
require_once '../php/config.php';
session_start();
// ===============================
// REDIRECIONA SE JÁ ESTIVER LOGADO COMO ADMIN
// ===============================
if (!empty($_SESSION['is_admin'])) header('Location: index.php');

// ===============================
// PROCESSAMENTO DO FORMULÁRIO DE LOGIN
// ===============================

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email && $senha) {
         // ===============================
    // CONSULTA USUÁRIO NO BANCO DE DADOS
    // ===============================
        $stmt = $conn->prepare("SELECT id, nome, senha, is_admin FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            // ===============================
      // VERIFICAÇÃO DE SENHA E PERMISSÃO DE ADMIN
      // ===============================
            if (password_verify($senha, $row['senha']) && !empty($row['is_admin'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user'] = $row['nome'];
                $_SESSION['is_admin'] = 1;
                header('Location: index.php');
                exit;
            }
        }
    }
    // ===============================
  // MENSAGEM DE ERRO PARA CREDENCIAIS INVÁLIDAS
  // ===============================
    $error = 'Credenciais inválidas ou usuário não é admin.';
}
?>
<!-- =============================== -->
<!-- ESTRUTURA HTML DA TELA DE LOGIN ADMIN -->
<!-- =============================== -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main class="container form-page">
    <!-- =============================== -->
    <!-- TÍTULO DA PÁGINA DE LOGIN -->
    <!-- =============================== -->
  <h2>Login Admin</h2>
  <!-- =============================== -->
    <!-- EXIBIÇÃO DE MENSAGEM DE ERRO -->
    <!-- =============================== -->
  <?php if ($error) echo "<p class='erro'>".htmlspecialchars($error)."</p>"; ?>
  <!-- =============================== -->
    <!-- FORMULÁRIO DE LOGIN -->
    <!-- =============================== -->
  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button class="btn" type="submit">Entrar</button>
  
  </form>
</main>
</body>
</html>
