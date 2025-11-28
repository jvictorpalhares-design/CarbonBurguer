<?php
/*
====================================================
    backend/register_process.php - Processa cadastro
    Valida dados, insere usuário no banco e redireciona
====================================================
*/

// ===============================
// INCLUI CONEXÃO COM BANCO
// ===============================
include "db.php";

// ===============================
// RECEBE E ESCAPA DADOS DO FORMULÁRIO
// ===============================
include "db.php";

$nome = $conn->real_escape_string($_POST['nome'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$senha_raw = $_POST['senha'] ?? '';

// ===============================
// VALIDAÇÃO DOS DADOS
// ===============================

if (!$nome || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha_raw) < 6) {
    die("Dados inválidos.");
}

// ===============================
// GERA HASH DA SENHA
// ===============================
$hash = password_hash($senha_raw, PASSWORD_BCRYPT);

// ===============================
// INSERE USUÁRIO NO BANCO
// ===============================

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $hash);
$stmt->execute();

// ===============================
// REDIRECIONA PARA LOGIN
// ===============================
header("Location: ../login.php?registered=1");
exit;
?>
