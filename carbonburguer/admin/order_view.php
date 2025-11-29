<?php
// ===============================
// VISUALIZAÇÃO DE DETALHES DO PEDIDO (ADMIN)
// Esta página exibe todas as informações de um pedido específico, incluindo itens, cliente e endereço.
// ===============================

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E INICIALIZAÇÃO DE SESSÃO
// ===============================
require_once '../php/config.php';
session_start();

// ===============================
// VERIFICAÇÃO DE PERMISSÃO DE ADMINISTRADOR
// Redireciona para login se não for admin
// ===============================
if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

// ===============================
// OBTENÇÃO DO ID DO PEDIDO VIA GET
// ===============================

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ===============================
// VALIDAÇÃO DO ID E REDIRECIONAMENTO SE INVÁLIDO
// ===============================

if ($id <= 0) {
    set_flash('error', 'Pedido inválido.');
    redirect('orders.php');
}

// ===============================
// BUSCA DOS DADOS DO PEDIDO NO BANCO
// Inclui informações do cliente via JOIN
// ===============================
$stmt = $conn->prepare("
    SELECT p.*, u.nome as cliente_nome, u.email as cliente_email 
    FROM pedidos p 
    LEFT JOIN usuarios u ON u.id = p.usuario_id 
    WHERE p.id = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// ===============================
// VALIDAÇÃO SE PEDIDO EXISTE
// ===============================

if (!$order) {
    set_flash('error', 'Pedido não encontrado.');
    redirect('orders.php');
}

$stmt->close();

// ===============================
// BUSCA DOS ITENS DO PEDIDO
// ===============================
$stmt = $conn->prepare("
    SELECT i.*, pr.nome as produto_nome 
    FROM itens_pedido i 
    LEFT JOIN produtos pr ON pr.id = i.produto_id 
    WHERE i.pedido_id = ?
");
$stmt->bind_param('i', $id);
$stmt->execute();
$items = $stmt->get_result();

// ===============================
// DEFINIÇÃO DE VARIÁVEIS DE TÍTULO E CAMINHOS DE ASSETS
// ===============================

$page_title = 'Pedido #' . $id . ' - Admin';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/app.js';
$base_path = '../';

// ===============================
// INCLUSÃO DO HEADER PADRÃO
// ===============================
include '../partials/header.php';
?>
<!-- =============================== -->
<!-- ESTRUTURA HTML DA VISUALIZAÇÃO DE PEDIDO -->
<!-- Exibe detalhes do pedido, cliente, endereço, itens e total -->
<!-- =============================== -->
<div class="container">
    <!-- =============================== -->
    <!-- TÍTULO DA PÁGINA -->
    <!-- =============================== -->
    <h1 class="page-title">Detalhes do Pedido #<?php echo $id; ?></h1>
    
    <div class="checkout-container">
        <div class="checkout-form-section">
            <div class="form-section">
                <!-- =============================== -->
            <!-- INFORMAÇÕES DO PEDIDO -->
            <!-- =============================== -->
                <h3>📋 Informações do Pedido</h3>
                <div style="color: var(--light); line-height: 1.8;">
                    <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($order['data_pedido'])); ?></p>
                    <p><strong>Status:</strong> 
                        <span style="padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; background: var(--warning); color: var(--white);">
                            <?php echo ucfirst(sanitize($order['status'])); ?>
                        </span>
                    </p>
                </div>
            </div>
            <!-- =============================== -->
            <!-- DADOS DO CLIENTE -->
            <!-- =============================== -->
            <div class="form-section">
                <h3>👤 Dados do Cliente</h3>
                <div style="color: var(--light); line-height: 1.8;">
                    <p><strong>Nome:</strong> <?php echo sanitize($order['cliente_nome'] ?? $order['nome']); ?></p>
                    <?php if ($order['cliente_email']): ?>
                        <p><strong>E-mail:</strong> <?php echo sanitize($order['cliente_email']); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- =============================== -->
            <!-- ENDEREÇO DE ENTREGA -->
            <!-- =============================== -->
            
            <div class="form-section">
                <h3>📍 Endereço de Entrega</h3>
                <p style="color: var(--light); line-height: 1.8;">
                    <?php echo sanitize($order['endereco']); ?>
                </p>
            </div>
            
            <!-- =============================== -->
            <!-- OBSERVAÇÕES DO PEDIDO (SE HOUVER) -->
            <!-- =============================== -->
            <?php if (!empty($order['obs'])): ?>
                <div class="form-section">
                    <h3>📝 Observações</h3>
                    <p style="color: var(--light); line-height: 1.8;">
                        <?php echo nl2br(sanitize($order['obs'])); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="checkout-summary">
            <!-- =============================== -->
            <!-- ITENS DO PEDIDO -->
            <!-- =============================== -->
            <h3>🛒 Itens do Pedido</h3>
            
            <div class="summary-items">
                <?php if ($items->num_rows > 0): ?>
                    <?php while($item = $items->fetch_assoc()): ?>
                        <div class="summary-item">
                            <div class="summary-item-info">
                                <span class="item-qty"><?php echo intval($item['quantidade']); ?>x</span>
                                <span class="item-name"><?php echo sanitize($item['produto_nome'] ?? 'Produto'); ?></span>
                            </div>
                            <span class="item-price"><?php echo format_price($item['subtotal']); ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: var(--light); text-align: center;">Nenhum item encontrado.</p>
                <?php endif; ?>
            </div>

            <!-- =============================== -->
            <!-- TOTAL DO PEDIDO -->
            <!-- =============================== -->
            
            <div class="summary-totals">
                <div class="summary-line summary-total">
                    <strong>Total:</strong>
                    <strong><?php echo format_price($order['total']); ?></strong>
                </div>
            </div>

            <!-- =============================== -->
            <!-- INFORMAÇÕES DE PAGAMENTO -->
            <!-- =============================== -->
            
            <div class="delivery-info">
                <h4>💳 Informações de Pagamento</h4>
                <p>Pagamento na entrega</p>
            </div>
        </div>
    </div>

    <!-- =============================== -->
    <!-- BOTÃO DE VOLTAR -->
    <!-- =============================== -->
    
    <div style="text-align: center; margin: 40px 0;">
        <a href="orders.php" class="btn btn-secondary btn-large">
            ← Voltar para Pedidos
        </a>
    </div>
</div>

<?php 
// ===============================
// FECHAMENTO DA CONEXÃO E INCLUSÃO DO FOOTER
// ===============================
$stmt->close();
$conn->close();
include '../partials/footer.php'; 
?>