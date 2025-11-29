<?php
/*
====================================================
    backend/remover_carrinho.php - Remove item do carrinho
    Remove um produto do carrinho de compras na sessão
====================================================
*/

// ===============================
// INICIA SESSÃO
// ===============================
session_start();

// ===============================
// OBTÉM O ID DO PRODUTO VIA POST
// ===============================
$id = intval($_POST['id'] ?? 0);

// ===============================
// REMOVE O ITEM DO CARRINHO
// ===============================
if (isset($_SESSION['carrinho'][$id])) {
    unset($_SESSION['carrinho'][$id]);
}

// ===============================
// REDIRECIONA PARA O CARRINHO
// ===============================
header("Location: ../carrinho.php");
exit;
?>
