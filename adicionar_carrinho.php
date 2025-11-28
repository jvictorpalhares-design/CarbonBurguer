<?php

/*
====================================================
  adicionar_carrinho.php - Backend
  Adiciona um produto ao carrinho de compras na sessão
====================================================
*/

// ===============================
// INICIA SESSÃO
// ===============================
// Necessário para manipular o carrinho do usuário

session_start();

// ===============================
// INCLUI CONEXÃO COM BANCO
// ===============================

include "db.php";

$id = intval($_POST['id'] ?? 0); // Garante que o ID é inteiro

// ===============================
// VALIDAÇÃO DO ID
// ===============================

if ($id <= 0) {
    header("Location: ../produtos.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, preco, imagem FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("Produto inexistente.");
}

$produto = $res->fetch_assoc(); // Dados do produto

// ===============================
// INICIALIZA O CARRINHO NA SESSÃO
// ===============================

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ===============================
// ADICIONA OU ATUALIZA PRODUTO NO CARRINHO
// ===============================
if (!isset($_SESSION['carrinho'][$id])) {
    // Se o produto não está no carrinho, adiciona
    $_SESSION['carrinho'][$id] = [
        "id" => $id,
        "nome" => $produto['nome'],
        "preco" => $produto['preco'],
        "imagem" => $produto['imagem'],
        "qtd" => 1
    ];
} else {
    // Se já existe, apenas incrementa a quantidade
    $_SESSION['carrinho'][$id]["qtd"]++;
}

// ===============================
// REDIRECIONA PARA O CARRINHO
// ===============================
header("Location: ../carrinho.php");
exit;
?>
