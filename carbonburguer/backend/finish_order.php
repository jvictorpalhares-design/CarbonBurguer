<?php
/*
====================================================
  backend/finish_order.php - Finaliza pedido
  Processa o fechamento do pedido, grava no banco e limpa o carrinho
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once '../php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// VERIFICA SE O USUÁRIO ESTÁ LOGADO
// ===============================

// Verificar login
if (!is_logged_in()) {
    set_flash('error', 'Você precisa estar logado para finalizar o pedido.');
    redirect('../login.php');
}

// ===============================
// VERIFICA MÉTODO POST
// ===============================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../carrinho.php');
}

// ===============================
// VERIFICA SE O CARRINHO ESTÁ VAZIO
// ===============================
$cart = isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

if (empty($cart)) {
    set_flash('error', 'Seu carrinho está vazio.');
    redirect('../carrinho.php');
}

// ===============================
// RECEBE E SANITIZA DADOS DO FORMULÁRIO
// ===============================
$usuario_id = intval($_SESSION['user_id']);
$nome = isset($_POST['nome']) ? sanitize($_POST['nome']) : '';
$endereco = isset($_POST['endereco']) ? sanitize($_POST['endereco']) : '';
$obs = isset($_POST['obs']) ? sanitize($_POST['obs']) : '';

// ===============================
// VALIDAÇÃO DOS DADOS
// ===============================
$errors = [];

if (empty($nome) || strlen($nome) < 3) {
    $errors[] = 'Nome deve ter pelo menos 3 caracteres.';
}

if (empty($endereco) || strlen($endereco) < 10) {
    $errors[] = 'Endereço deve ser completo (mínimo 10 caracteres).';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('../checkout.php');
}

// ===============================
// CALCULA O TOTAL DO PEDIDO
// ===============================
$total = 0;
foreach ($cart as $item) {
    $total += floatval($item['preco']) * intval($item['qtd']);
}

// ===============================
// VALIDA O TOTAL
// ===============================
if ($total <= 0) {
    set_flash('error', 'Erro ao calcular total do pedido.');
    redirect('../carrinho.php');
}

// ===============================
// INICIA TRANSAÇÃO NO BANCO
// ===============================

$conn->begin_transaction();

try {
    // Inserir pedido
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, nome, endereco, obs, total, status) VALUES (?, ?, ?, ?, ?, 'pendente')");
    $stmt->bind_param('isssd', $usuario_id, $nome, $endereco, $obs, $total);
    
    if (!$stmt->execute()) {
        throw new Exception('Erro ao criar pedido.');
    }
    
    $pedido_id = $stmt->insert_id;
    $stmt->close();
    
    // Inserir itens do pedido
    $stmt = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco, subtotal) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($cart as $item) {
        $produto_id = intval($item['id']);
        $quantidade = intval($item['qtd']);
        $preco = floatval($item['preco']);
        $subtotal = $preco * $quantidade;
        
        $stmt->bind_param('iiidd', $pedido_id, $produto_id, $quantidade, $preco, $subtotal);
        
        if (!$stmt->execute()) {
            throw new Exception('Erro ao adicionar itens do pedido.');
        }
    }
    
    $stmt->close();
    
    // Confirmar transação
    $conn->commit();
    
    // Limpar carrinho
    unset($_SESSION['carrinho']);
    
    // Redirecionar para página de sucesso
    set_flash('success', 'Pedido realizado com sucesso!');
    redirect('../order_success.php?id=' . $pedido_id);
    
} catch (Exception $e) {
    // Reverter transação em caso de erro
    $conn->rollback();
    
    error_log("Erro ao processar pedido: " . $e->getMessage());
    
    set_flash('error', 'Erro ao processar pedido. Tente novamente.');
    redirect('../checkout.php');
}

$conn->close();
?>