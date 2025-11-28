<?php
/*
====================================================
  checkout.php - Página de finalização do pedido
  Permite ao usuário inserir dados de entrega e finalizar a compra
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once 'php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// VERIFICA SE O USUÁRIO ESTÁ LOGADO
// ===============================
if (!is_logged_in()) {
    set_flash('error', 'Você precisa estar logado para finalizar o pedido.');
    redirect('login.php');
}

// ===============================
// VERIFICA SE O CARRINHO ESTÁ VAZIO
// ===============================
$cart = isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

if (empty($cart)) {
    set_flash('error', 'Seu carrinho está vazio.');
    redirect('carrinho.php');
}

// ===============================
// CALCULA O TOTAL DO PEDIDO
// ===============================
$total = 0;
foreach ($cart as $item) {
    $total += $item['preco'] * $item['qtd'];
}

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================
$page_title = 'Finalizar Pedido - CarbonBurguer'; // Título da página
$css_path = 'assets/css/style.css';
$js_path = 'assets/js/app.js';
$base_path = '';

// ===============================
// INCLUI O HEADER PADRÃO
// ===============================
include 'partials/header.php';
?>

<main class="container">
<!--
        ===============================
        PÁGINA DE FINALIZAÇÃO DE PEDIDO
        ===============================
        Permite ao usuário inserir dados de entrega e finalizar a compra
    -->
    <h1 class="page-title">Finalizar Pedido</h1>

    <div class="checkout-container">
        <div class="checkout-form-section">
            <!-- Formulário de finalização do pedido -->
            <form method="post" action="backend/finish_order.php" id="checkoutForm">
                <div class="form-section">
                    <h3>🏠 Dados de Entrega</h3>
                    <!-- Campo para nome completo -->
                    
                    <div class="form-group">
                        <label for="nome">Nome completo: *</label>
                        <input 
                            type="text" 
                            id="nome"
                            name="nome" 
                            value="<?php echo sanitize($_SESSION['user']); ?>" 
                            required
                            minlength="3"
                            placeholder="Seu nome completo"
                        >
                    </div>
                    <!-- Campo para endereço completo -->
                    <div class="form-group">
                        <label for="endereco">Endereço completo: *</label>
                        <input 
                            type="text" 
                            id="endereco"
                            name="endereco" 
                            placeholder="Rua, número, bairro, complemento"
                            required
                            minlength="10"
                        >
                        <small>Ex: Rua das Flores, 123, Centro, Apt 45</small>
                    </div>

                    <div class="form-group">
                        <label for="obs">Observações (opcional):</label>
                        <textarea 
                            id="obs"
                            name="obs" 
                            rows="4"
                            placeholder="Ex: sem cebola, ponto da carne, retirar ingrediente, etc."
                        ></textarea>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>💳 Forma de Pagamento</h3>
                    <div class="payment-options">
                        <div class="payment-option">
                            <input type="radio" id="dinheiro" name="pagamento" value="dinheiro" checked>
                            <label for="dinheiro">💵 Dinheiro</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="cartao" name="pagamento" value="cartao">
                            <label for="cartao">💳 Cartão (débito/crédito)</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="pix" name="pagamento" value="pix">
                            <label for="pix">📱 PIX</label>
                        </div>
                    </div>
                    <p class="info-text">O pagamento será realizado na entrega</p>
                </div>

                <button class="btn btn-primary btn-large btn-submit" type="submit">
                    ✓ Confirmar Pedido
                </button>
            </form>
        </div>

        <div class="checkout-summary">
            <h3>📋 Resumo do Pedido</h3>
            
            <div class="summary-items">
                <?php foreach($cart as $item): ?>
                    <div class="summary-item">
                        <div class="summary-item-info">
                            <span class="item-qty"><?php echo intval($item['qtd']); ?>x</span>
                            <span class="item-name"><?php echo sanitize($item['nome']); ?></span>
                        </div>
                        <span class="item-price"><?php echo format_price($item['preco'] * $item['qtd']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-totals">
                <div class="summary-line">
                    <span>Subtotal:</span>
                    <span><?php echo format_price($total); ?></span>
                </div>
                <div class="summary-line">
                    <span>Taxa de entrega:</span>
                    <span class="delivery-free">Grátis</span>
                </div>
                <div class="summary-line summary-total">
                    <strong>Total:</strong>
                    <strong><?php echo format_price($total); ?></strong>
                </div>
            </div>
            
            <div class="delivery-info">
                <h4>🚚 Informações de Entrega</h4>
                <p>⏱️ Tempo estimado: 30-40 minutos</p>
                <p>📦 Embalagem térmica especial</p>
                <p>✅ Rastreamento em tempo real</p>
            </div>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>