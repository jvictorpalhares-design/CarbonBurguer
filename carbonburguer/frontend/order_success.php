<?php
/*
====================================================
    order_success.php - Página de confirmação de pedido
    Exibe mensagem de sucesso após o cliente finalizar o pedido
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once 'php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// OBTÉM O ID DO PEDIDO
// ===============================

$pedido_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // ID do pedido finalizado

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================

$page_title = 'Pedido Confirmado - CarbonBurguer'; // Título da página
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
        PÁGINA DE SUCESSO DO PEDIDO
        ===============================
        Exibe mensagem de confirmação e informações do pedido
    -->
    <div class="success-page">
        <!-- Animação de sucesso -->
        <div class="success-animation">
            <div class="success-icon">✓</div>
        </div>
        <!-- Título de sucesso -->
        <h1>Pedido realizado com sucesso!</h1>
        <!-- Número do pedido, se disponível -->
        <?php if ($pedido_id > 0): ?>
            <p class="order-number">Pedido <strong>#<?php echo $pedido_id; ?></strong></p>
        <?php endif; ?>
        <!-- Mensagem de agradecimento -->
        <p class="success-message">
            Obrigado por escolher a CarbonBurguer! 🔥<br>
            Seu pedido está sendo preparado com todo carinho.
        </p>
        <!-- Informações do pedido -->
        <div class="success-info">
            <div class="info-card">
                <div class="info-icon">🍔</div>
                <h3>Preparando</h3>
                <p>Seu pedido está sendo preparado por nossos chefs</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon">🚚</div>
                <h3>Entrega</h3>
                <p>Tempo estimado: 30-40 minutos</p>
            </div>
            
            <div class="info-card">
                <div class="info-icon">💳</div>
                <h3>Pagamento</h3>
                <p>Pagamento na entrega</p>
            </div>
        </div>
        <!-- Informações de acompanhamento -->
        <div class="tracking-info">
            <h3>📱 Acompanhe seu pedido</h3>
            <p>Em breve você receberá atualizações sobre o status do seu pedido</p>
        </div>
        <!-- Ações após o pedido -->
        <div class="success-actions">
            <a class="btn btn-primary btn-large" href="produtos.php">
                Fazer Novo Pedido
            </a>
            <a class="btn btn-secondary" href="index.php">
                Voltar ao Início
            </a>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>