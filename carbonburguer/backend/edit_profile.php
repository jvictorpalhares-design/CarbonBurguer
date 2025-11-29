<?php
// backend/edit_profile.php
require_once 'db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada. Faça login novamente.']);
    exit;
}
$user_id = $_SESSION['user_id'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirma = $_POST['confirma_senha'] ?? '';
if (strlen($nome) < 3) {
    echo json_encode(['success' => false, 'message' => 'Nome deve ter pelo menos 3 caracteres.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'E-mail inválido.']);
    exit;
}
if ($senha !== '' && strlen($senha) < 6) {
    echo json_encode(['success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres.']);
    exit;
}
if ($senha !== $confirma) {
    echo json_encode(['success' => false, 'message' => 'As senhas não coincidem.']);
    exit;
}
// Verifica se o email já está em uso por outro usuário
$stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
$stmt->bind_param('si', $email, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'E-mail já cadastrado por outro usuário.']);
    exit;
}
$stmt->close();
if ($senha !== '') {
    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?');
    $stmt->bind_param('sssi', $nome, $email, $hash, $user_id);
} else {
    $stmt = $conn->prepare('UPDATE usuarios SET nome = ?, email = ? WHERE id = ?');
    $stmt->bind_param('ssi', $nome, $email, $user_id);
}
if ($stmt->execute()) {
    $_SESSION['user_nome'] = $nome;
    $_SESSION['user_email'] = $email;
    echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar dados.']);
}
$stmt->close();
$conn->close();
