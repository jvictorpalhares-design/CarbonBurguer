<?php
/*
====================================================
  backend/login_process.php - Processa login do usuário
  Valida credenciais, inicia sessão e redireciona
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once '../php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// VERIFICA MÉTODO POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../login.php');
}

// ===============================
// RECEBE E SANITIZA DADOS
// ===============================
$email = isset($_POST['email']) ? sanitize($_POST['email']) : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

// ===============================
// VALIDAÇÕES BÁSICAS
// ===============================
if (empty($email) || empty($senha)) {
    set_flash('error', 'Preencha todos os campos.');
    redirect('../login.php');
}

if (!validate_email($email)) {
    set_flash('error', 'E-mail inválido.');
    redirect('../login.php');
}

// ===============================
// BUSCA USUÁRIO NO BANCO
// ===============================
$stmt = $conn->prepare("SELECT id, nome, senha, is_admin FROM usuarios WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

// ===============================
// VERIFICA SE USUÁRIO EXISTE
// ===============================
if ($result->num_rows === 0) {
    set_flash('error', 'E-mail ou senha incorretos.');
    $stmt->close();
    $conn->close();
    redirect('../login.php');
}

$user = $result->fetch_assoc();
$stmt->close();

// ===============================
// VERIFICA SENHA
// ===============================
if (!password_verify($senha, $user['senha'])) {
    set_flash('error', 'E-mail ou senha incorretos.');
    $conn->close();
    redirect('../login.php');
}

// ===============================
// LOGIN BEM-SUCEDIDO
// ===============================
// Regenera o ID da sessão por segurança
session_regenerate_id(true);

// Definir variáveis de sessão
$_SESSION['user_id'] = $user['id'];
$_SESSION['user'] = $user['nome'];
$_SESSION['is_admin'] = $user['is_admin'];

// Redirecionar baseado no tipo de usuário
if ($user['is_admin'] == 1) {
    set_flash('success', 'Login realizado com sucesso! Bem-vindo, ' . $user['nome']);
    redirect('../admin/index.php');
} else {
    set_flash('success', 'Login realizado com sucesso! Bem-vindo, ' . $user['nome']);
    redirect('../index.php');
}

$conn->close();
?>