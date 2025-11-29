<?php
// ===============================
// TELA DE EXCLUSÃO DE PRODUTO (ADMIN)
// Este script remove um produto do banco de dados e, se necessário, apaga a imagem associada.
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
// OBTENÇÃO DO ID DO PRODUTO VIA GET
// ===============================

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: products.php'); exit; }

// ===============================
// BUSCA DA IMAGEM DO PRODUTO PARA REMOÇÃO FÍSICA
// ===============================
$stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if ($row && $row['imagem']) {
    // ===============================
    // REMOÇÃO DO ARQUIVO DE IMAGEM (CASO SEJA DE UPLOAD)
    // ===============================
    if (str_starts_with($row['imagem'], 'admin/uploads/')) {
        $f = __DIR__ . '/' . basename($row['imagem']);
        if (file_exists($f)) @unlink($f);
    }
}

// ===============================
// EXCLUSÃO DO PRODUTO NO BANCO DE DADOS
// ===============================
$stmt2 = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$stmt2->bind_param('i', $id);
$stmt2->execute();
// ===============================
// REDIRECIONAMENTO PARA LISTA DE PRODUTOS
// ===============================
header('Location: products.php');
exit;
?>
