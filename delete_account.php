<?php
// backend/delete_account.php
require_once 'db.php';
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sessão expirada. Faça login novamente.']);
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('DELETE FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $user_id);
if ($stmt->execute()) {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Conta excluída com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao excluir conta.']);
}
$stmt->close();
$conn->close();
