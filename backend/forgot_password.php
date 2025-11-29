<?php
// backend/forgot_password.php
// Recebe email, nova senha e confirmação, faz validação e atualiza a senha do usuário
require_once 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}
if (strlen($new_password) < 6) {
    echo json_encode(['success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres.']);
    exit;
}
if ($new_password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'As senhas não coincidem.']);
    exit;
}

// Verifica se o usuário existe
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'E-mail não encontrado.']);
    exit;
}
$stmt->close();

// Atualiza a senha
$hash = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE usuarios SET senha = ? WHERE email = ?');
$stmt->bind_param('ss', $hash, $email);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Senha redefinida com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar senha.']);
}
$stmt->close();
$conn->close();
