<?php
// ===============================
// TELA DE ADIÇÃO DE PRODUTO (ADMIN)
// Esta página permite ao administrador adicionar um novo produto ao sistema.
// Inclui validação, upload de imagem e inserção no banco de dados.
// ===============================

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E INICIALIZAÇÃO DE SESSÃO
// Carrega configurações globais e inicia a sessão para controle de acesso.
// ===============================
require_once '../php/config.php';
session_start();

// ===============================
// VERIFICAÇÃO DE PERMISSÃO DE ADMINISTRADOR
// Garante que apenas usuários administradores possam acessar esta página.
// Redireciona para login se não for admin.
// ===============================

if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}


// ===============================
// INICIALIZAÇÃO DE VARIÁVEIS DE ERRO E SUCESSO
// ===============================

$error = '';
$success = '';

// ===============================
// PROCESSAMENTO DO FORMULÁRIO DE ADIÇÃO DE PRODUTO
// Executa quando o formulário é enviado via POST
// ===============================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ===============================
    // OBTENÇÃO E TRATAMENTO DOS DADOS DO FORMULÁRIO
    // ===============================
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : 'hamburguer';

    // ===============================
    // VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
    // ===============================


    if (empty($nome)) {
        $error = 'O nome do produto é obrigatório.';
    } elseif ($preco <= 0) {
        $error = 'O preço deve ser maior que zero.';
    } else {
        // ===============================
        // PROCESSAMENTO DO UPLOAD DA IMAGEM
        // Define imagem padrão e processa upload se fornecido
        // ===============================
        $imagem_path = 'assets/img/placeholder.jpg'; // Padrão
        
        if (!empty($_FILES['imagem']['name'])) {
            $img = $_FILES['imagem'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            
            if ($img['error'] === UPLOAD_ERR_OK) {
                if (in_array($img['type'], $allowed) && $img['size'] <= 2 * 1024 * 1024) {
                    $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
                    $filename = 'prod_' . uniqid() . '.' . $ext;
                    $upload_dir = __DIR__ . '/../assets/img/uploads/';
                    
                    // Criar diretório se não existir
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $dest = $upload_dir . $filename;
                    
                    if (move_uploaded_file($img['tmp_name'], $dest)) {
                        $imagem_path = 'assets/img/uploads/' . $filename;
                    } else {
                        $error = 'Erro ao salvar a imagem.';
                    }
                } else {
                    $error = 'Formato de imagem inválido ou arquivo muito grande (máx 2MB).';
                }
            }
        }
        
        // ===============================
        // INSERÇÃO DO NOVO PRODUTO NO BANCO DE DADOS
        // Executa a query apenas se não houver erro anterior
        // ===============================

        if (empty($error)) {
            $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, categoria, imagem, ativo) VALUES (?, ?, ?, ?, ?, 1)");
            $stmt->bind_param('ssdss', $nome, $descricao, $preco, $categoria, $imagem_path);
            
            if ($stmt->execute()) {
                set_flash('success', 'Produto adicionado com sucesso!');
                redirect('products.php');
            } else {
                $error = 'Erro ao adicionar produto.';
            }
            
            $stmt->close();
        }
    }
}
// ===============================
// DEFINIÇÃO DE VARIÁVEIS DE TÍTULO E CAMINHOS DE ASSETS
// ===============================
$page_title = 'Adicionar Produto - Admin';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/app.js';
$base_path = '../';

// ===============================
// INCLUSÃO DO HEADER PADRÃO
// ===============================


include '../partials/header.php';
?>

<!-- =============================== -->
<!-- ESTRUTURA HTML DA PÁGINA DE ADIÇÃO DE PRODUTO -->
<!-- Inclui formulário, exibição de erros e layout responsivo -->
<!-- =============================== -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($page_title); ?></title>
    <link rel="stylesheet" href="<?php echo sanitize($css_path); ?>">
    <script src="<?php echo sanitize($js_path); ?>" defer></script>
</head>
<body>

<div class="container">
    <!-- =============================== -->
    <!-- TÍTULO DA PÁGINA -->
    <!-- =============================== -->
    <h1 class="page-title">Adicionar Novo Produto</h1>

    <!-- =============================== -->
    <!-- EXIBIÇÃO DE MENSAGENS DE ERRO -->
    <!-- Mostra mensagens de validação ou erro de upload -->
    <!-- =============================== -->
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo sanitize($error); ?></div>
    <?php endif; ?>
    
    <div class="checkout-container" style="grid-template-columns: 1fr;">
        <div class="checkout-form-section">
            <!-- =============================== -->
            <!-- FORMULÁRIO DE ADIÇÃO DE PRODUTO -->
            <!-- =============================== -->
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome do Produto: *</label>
                    <input 
                        type="text" 
                        id="nome"
                        name="nome" 
                        placeholder="Ex: Carbon Burger"
                        required
                        value="<?php echo isset($_POST['nome']) ? sanitize($_POST['nome']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea 
                        id="descricao"
                        name="descricao" 
                        rows="4"
                        placeholder="Descreva o produto..."
                    ><?php echo isset($_POST['descricao']) ? sanitize($_POST['descricao']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$): *</label>
                    <input 
                        type="number" 
                        id="preco"
                        name="preco" 
                        placeholder="0.00"
                        step="0.01"
                        min="0"
                        required
                        value="<?php echo isset($_POST['preco']) ? sanitize($_POST['preco']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria: *</label>
                    <select id="categoria" name="categoria" style="width: 100%; padding: 12px 15px; background: #1a1a1a; border: 2px solid #333; border-radius: 5px; color: var(--white); font-size: 16px;">
                        <option value="hamburguer">Hambúrguer</option>
                        <option value="combo">Combo</option>
                        <option value="acompanhamento">Acompanhamento</option>
                        <option value="bebida">Bebida</option>
                        <option value="sobremesa">Sobremesa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="imagem">Imagem do Produto:</label>
                    <input 
                        type="file" 
                        id="imagem"
                        name="imagem" 
                        accept="image/*"
                        style="padding: 10px 15px;"
                    >
                    <small>Formatos aceitos: JPG, PNG, WEBP (máx 2MB)</small>
                </div>

                <button class="btn btn-primary btn-large" type="submit">
                    Adicionar Produto
                </button>
                
                <a href="products.php" class="btn btn-secondary" style="margin-top: 10px; display: inline-block; text-align: center;">
                    ← Voltar
                </a>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php 
// ===============================
// FECHAMENTO DA CONEXÃO E INCLUSÃO DO FOOTER
// ===============================
$conn->close();
include '../partials/footer.php'; 
?>