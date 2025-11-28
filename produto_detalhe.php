<?php
/*
====================================================
  produto_detalhe.php - Página de detalhes do produto
  Exibe informações detalhadas de um produto selecionado
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once 'php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// OBTÉM O ID DO PRODUTO VIA GET
// ===============================

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ===============================
// VALIDAÇÃO DO ID
// ===============================
// Se o ID não for válido, redireciona com erro
if ($id <= 0) {
    set_flash('error', 'Produto não encontrado.');
    redirect('produtos.php');
}

// ===============================
// BUSCA O PRODUTO NO BANCO
// ===============================
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ? AND ativo = 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

// Se não encontrar produto, redireciona
if ($result->num_rows === 0) {
    set_flash('error', 'Produto não encontrado.');
    redirect('produtos.php');
}

$produto = $result->fetch_assoc(); // Dados do produto
$stmt->close();

// ===============================
// BUSCA PRODUTOS RELACIONADOS
// ===============================
// Busca até 3 produtos da mesma categoria
// Buscar produtos relacionados (mesma categoria)
$stmt_related = $conn->prepare("SELECT * FROM produtos WHERE categoria = ? AND id != ? AND ativo = 1 LIMIT 3");
$stmt_related->bind_param('si', $produto['categoria'], $id);
$stmt_related->execute();
$produtos_relacionados = $stmt_related->get_result();

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================
$page_title = sanitize($produto['nome']) . ' - CarbonBurguer'; // Título dinâmico
$css_path = 'assets/css/style.css';
$js_path = 'assets/js/app.js';
$base_path = '';

// ===============================
// INCLUI O HEADER PADRÃO
// ===============================
include 'partials/header.php';
?>

<div class="container">
    <!--
        ===============================
        BREADCRUMB
        ===============================
        Exibe o caminho de navegação até o produto
    -->
    <div class="breadcrumb">
        <a href="index.php">Home</a> » 
        <a href="produtos.php">Cardápio</a> » 
        <span><?php echo sanitize($produto['nome']); ?></span>
    </div>
    
    <!--
        ===============================
        VISUALIZAÇÃO DO PRODUTO
        ===============================
        Exibe imagem, nome, descrição, preço e botão de adicionar ao carrinho
    -->
    <div class="produto-view">
        <!-- Imagem do produto -->
        <div class="produto-image">
            <img 
                src="<?php echo sanitize($produto['imagem']); ?>" 
                alt="<?php echo sanitize($produto['nome']); ?>"
                onerror="this.src='assets/img/placeholder.jpg'"
            >
        </div>

        <div class="details">
            <!-- Badge de categoria -->
            <span class="badge badge-<?php echo sanitize($produto['categoria']); ?>">
                <?php echo ucfirst(sanitize($produto['categoria'])); ?>
            </span>
            <!-- Nome do produto -->
            <h1><?php echo sanitize($produto['nome']); ?></h1>
            <!-- Descrição do produto -->
            <p class="descricao"><?php echo nl2br(sanitize($produto['descricao'])); ?></p>
            <!-- Preço do produto -->
            <div class="price-box">
                <span class="price-label">Preço:</span>
                <span class="price"><?php echo format_price($produto['preco']); ?></span>
            </div>
            <!-- Formulário para adicionar ao carrinho -->
            <form method="post" action="backend/adicionar_carrinho.php" class="add-to-cart-form">
                <input type="hidden" name="id" value="<?php echo intval($produto['id']); ?>">
                <button class="btn btn-primary btn-large btn-add-cart" type="submit">
                    🛒 Adicionar ao Carrinho
                </button>
            </form>
            <!-- Botão para voltar ao cardápio -->
            <a href="produtos.php" class="btn btn-secondary">← Voltar ao Cardápio</a>
        </div>
    </div>
    
    <?php if ($produtos_relacionados->num_rows > 0): ?>
        <section class="related-products">
            <h2>Produtos Relacionados</h2>
            <div class="produtos-grid">
                <?php while($rel = $produtos_relacionados->fetch_assoc()): ?>
                    <article class="card">
                        <div class="card-image">
                            <img src="<?php echo sanitize($rel['imagem']); ?>" 
                                 alt="<?php echo sanitize($rel['nome']); ?>"
                                 onerror="this.src='assets/img/placeholder.jpg'">
                        </div>
                        <div class="card-content">
                            <h3><?php echo sanitize($rel['nome']); ?></h3>
                            <div class="price"><?php echo format_price($rel['preco']); ?></div>
                            <a href="produto_detalhe.php?id=<?php echo intval($rel['id']); ?>" 
                               class="btn btn-primary">
                                Ver Detalhes
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php 
$stmt_related->close();
$conn->close();
include 'partials/footer.php'; 
?>