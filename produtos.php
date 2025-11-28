<?php
/*
====================================================
  produtos.php - Página de produtos (cardápio)
  Apresenta todos os produtos disponíveis para compra
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
// Inclui o arquivo de configuração do banco e funções auxiliares
require_once 'php/config.php';
// Inicia a sessão para controle de login e carrinho
session_start();


// ===============================
// FILTRO DE CATEGORIA
// ===============================
// Verifica se foi passado um filtro de categoria via GET
$categoria_filter = isset($_GET['categoria']) ? sanitize($_GET['categoria']) : '';

// ===============================
// BUSCA DE PRODUTOS NO BANCO
// ===============================
// Se houver filtro, busca apenas produtos daquela categoria
if ($categoria_filter) {
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE categoria = ? AND ativo = 1 ORDER BY id DESC");
    $stmt->bind_param('s', $categoria_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Se não houver filtro, busca todos os produtos ativos
    $result = $conn->query("SELECT * FROM produtos WHERE ativo = 1 ORDER BY id DESC");
}

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================
$page_title = 'Cardápio - CarbonBurguer'; // Título da página
$css_path = 'assets/css/style.css'; // Caminho do CSS
$js_path = 'assets/js/app.js'; // Caminho do JS
$base_path = '';

// ===============================
// INCLUI O HEADER PADRÃO
// ===============================
include 'partials/header.php';
?>

<div class="container">
    <!--
        ===============================
        TÍTULO DA PÁGINA
        ===============================
        Exibe o título principal do cardápio
    -->
    <h1 class="page-title">Nosso Cardápio</h1>
    
    <!--
        ===============================
        FILTRO DE CATEGORIAS
        ===============================
        Permite ao usuário filtrar produtos por categoria
    -->
    <div class="categoria-filter">
        <a href="produtos.php" class="filter-btn <?php echo $categoria_filter === '' ? 'active' : ''; ?>">
            Todos
        </a>
        <a href="produtos.php?categoria=hamburguer" class="filter-btn <?php echo $categoria_filter === 'hamburguer' ? 'active' : ''; ?>">
            Hambúrgueres
        </a>
        <a href="produtos.php?categoria=combo" class="filter-btn <?php echo $categoria_filter === 'combo' ? 'active' : ''; ?>">
            Combos
        </a>
        <a href="produtos.php?categoria=acompanhamento" class="filter-btn <?php echo $categoria_filter === 'acompanhamento' ? 'active' : ''; ?>">
            Acompanhamentos
        </a>
        <a href="produtos.php?categoria=bebida" class="filter-btn <?php echo $categoria_filter === 'bebida' ? 'active' : ''; ?>">
            Bebidas
        </a>
        <a href="produtos.php?categoria=sobremesa" class="filter-btn <?php echo $categoria_filter === 'sobremesa' ? 'active' : ''; ?>">
            Sobremesas
        </a>
    </div>

    <!--
        ===============================
        GRID DE PRODUTOS
        ===============================
        Exibe os produtos em formato de cards
    -->
    <div class="produtos-grid">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($p = $result->fetch_assoc()): ?>
                <!--
                    ===============================
                    CARD DE PRODUTO
                    ===============================
                    Cada produto é exibido em um card com imagem, nome, descrição, preço e ações
                -->
                <article class="card">
                    <div class="card-image">
                        <!-- Imagem do produto -->
                        <img 
                            src="<?php echo sanitize($p['imagem']); ?>" 
                            alt="<?php echo sanitize($p['nome']); ?>"
                            onerror="this.src='assets/img/placeholder.jpg'"
                        >
                        <span class="badge badge-<?php echo sanitize($p['categoria']); ?>">
                            <?php echo ucfirst(sanitize($p['categoria'])); ?>
                        </span>
                    </div>
                    
                    <div class="card-content">
                        <!-- Nome do produto -->
                        <h3><?php echo sanitize($p['nome']); ?></h3>
                        <!-- Descrição do produto -->
                        <p class="desc"><?php echo sanitize($p['descricao']); ?></p>
                        <!-- Preço formatado -->
                        <div class="price"><?php echo format_price($p['preco']); ?></div>
                        
                        <div class="card-actions">
                            <!-- Botão para ver detalhes do produto -->
                            <a href="produto_detalhe.php?id=<?php echo intval($p['id']); ?>" class="btn btn-secondary">
                                Ver Detalhes
                            </a>
                             <!-- Formulário para adicionar ao carrinho -->
                            <form method="post" action="backend/adicionar_carrinho.php" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo intval($p['id']); ?>">
                                <button class="btn btn-primary" type="submit">Adicionar</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <!--
                ===============================
                ESTADO VAZIO
                ===============================
                Exibe mensagem caso não haja produtos
            -->
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h2>Nenhum produto encontrado</h2>
                <p>Não há produtos disponíveis nesta categoria no momento.</p>
                <a class="btn btn-primary btn-large" href="produtos.php">Ver Todos os Produtos</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// ===============================
// FECHAMENTO DE CONEXÕES E FOOTER
// ===============================
if (isset($stmt)) $stmt->close(); // Fecha statement se usado
$conn->close(); // Fecha conexão com banco
include 'partials/footer.php';  // Inclui o rodapé
?>