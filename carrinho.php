<?php
/**
 /*
====================================================
    carrinho.php - Página do carrinho de compras
    Exibe os produtos adicionados ao carrinho pelo usuário
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
// Inclui o arquivo de configuração do banco e funções auxiliares
require_once 'php/config.php';
// Inicia a sessão para acessar o carrinho
session_start();

// ===============================
// RECUPERAÇÃO DO CARRINHO
// ===============================
// Verifica se existe um carrinho na sessão e inicializa a variável
$cart = isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];
$total = 0; // Variável para somar o valor total do carrinho

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================
$page_title = 'Carrinho - CarbonBurguer'; // Título da página
$css_path = 'assets/css/style.css'; // Caminho do CSS
$js_path = 'assets/js/app.js'; // Caminho do JS
$base_path = '';

// ===============================
// INCLUI O HEADER PADRÃO
// ===============================
include 'partials/header.php';
?>

<main class="container">
    <!--
        ===============================
        TÍTULO DA PÁGINA
        ===============================
        Exibe o título principal do carrinho
    -->
    <h1 class="page-title">Seu Carrinho</h1>

    <?php if (empty($cart)): ?>
        <!--
            ===============================
            ESTADO VAZIO
            ===============================
            Exibe mensagem caso o carrinho esteja vazio
        -->
        <div class="empty-state">
            <div class="empty-icon">🛒</div>
            <h2>Seu carrinho está vazio</h2>
            <p>Que tal experimentar nossos deliciosos hambúrgueres artesanais?</p>
            <a class="btn btn-primary btn-large" href="produtos.php">Ver Cardápio Completo</a>
        </div>
    <?php else: ?>
        <!--
            ===============================
            CONTAINER DO CARRINHO
            ===============================
            Exibe os itens adicionados ao carrinho
        -->
        <div class="cart-container">
            <div class="cart-items">
                <?php foreach($cart as $item): 
                    // Calcula o subtotal de cada item
                    $subtotal = $item['preco'] * $item['qtd'];
                    $total += $subtotal;
                ?>
                    <div class="cart-item">
                        <!-- Imagem do produto no carrinho -->
                        <div class="item-image">
                            <img 
                                src="<?php echo sanitize($item['imagem']); ?>" 
                                alt="<?php echo sanitize($item['nome']); ?>"
                                onerror="this.src='assets/img/placeholder.jpg'"
                            >
                        </div>
                        <!-- Informações do produto -->
                        <div class="item-info">
                            <h3><?php echo sanitize($item['nome']); ?></h3>
                            <p class="item-price">Preço unitário: <?php echo format_price($item['preco']); ?></p>
                            <p class="item-qty">Quantidade: <strong><?php echo intval($item['qtd']); ?></strong></p>
                            <p class="item-subtotal">Subtotal: <strong><?php echo format_price($subtotal); ?></strong></p>
                        </div>
                        <!-- Formulário para remover item do carrinho -->
                        <form method="post" action="backend/remover_carrinho.php" class="remove-form">
                            <input type="hidden" name="id" value="<?php echo intval($item['id']); ?>">
                            <button class="btn btn-danger btn-remove" type="submit" title="Remover item">
                                🗑️ Remover
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h2>Resumo do Pedido</h2>
                
                <div class="summary-details">
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

                <?php if (is_logged_in()): ?>
                    <a class="btn btn-primary btn-large btn-checkout" href="checkout.php">
                        Finalizar Pedido →
                    </a>
                <?php else: ?>
                    <a class="btn btn-primary btn-large" href="login.php">
                        Entrar para Finalizar →
                    </a>
                    <p class="info-text">Você precisa estar logado para finalizar o pedido</p>
                <?php endif; ?>
                
                <a class="btn btn-secondary" href="produtos.php">← Continuar Comprando</a>
                
                <div class="payment-info">
                    <p>💳 Pagamento na entrega</p>
                    <p>🚚 Entrega em até 40 minutos</p>
                    <p>📦 Embalagem especial</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'partials/footer.php'; ?>