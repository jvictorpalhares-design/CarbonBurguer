<?php
// ===============================
// TELA DE EDIÇÃO DE PRODUTO (ADMIN)
// Esta página permite ao administrador editar os dados de um produto existente.
// Inclui validação, upload de nova imagem e atualização no banco de dados.
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
// OBTENÇÃO DO ID DO PRODUTO VIA GET
// ===============================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// ===============================
// VALIDAÇÃO DO ID E REDIRECIONAMENTO SE INVÁLIDO
// ===============================
if ($id <= 0) {
    set_flash('error', 'Produto inválido.');
    redirect('products.php');
}

// ===============================
// BUSCA DOS DADOS DO PRODUTO NO BANCO
// ===============================
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$p = $result->fetch_assoc();
// ===============================
// VALIDAÇÃO SE PRODUTO EXISTE
// ===============================
if (!$p) {
    set_flash('error', 'Produto não encontrado.');
    redirect('products.php');
}

$stmt->close();
// ===============================
// INICIALIZAÇÃO DE VARIÁVEL DE ERRO
// ===============================
$error = '';
// ===============================
// PROCESSAMENTO DO FORMULÁRIO DE EDIÇÃO DE PRODUTO
// Executa quando o formulário é enviado via POST
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // ===============================
    // OBTENÇÃO E TRATAMENTO DOS DADOS DO FORMULÁRIO
    // ===============================
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $ativo = isset($_POST['ativo']) ? intval($_POST['ativo']) : 1;
    // ===============================
    // VALIDAÇÃO DOS CAMPOS OBRIGATÓRIOS
    // ===============================
    if (empty($nome)) {
        $error = 'O nome do produto é obrigatório.';
    } elseif ($preco <= 0) {
        $error = 'O preço deve ser maior que zero.';
    } else {
        // ===============================
        // PROCESSAMENTO DO UPLOAD DE NOVA IMAGEM (SE FORNECIDA)
        // Mantém a imagem atual caso não seja enviado novo arquivo
        // ===============================
        $imagem_path = $p['imagem']; // Manter imagem atual
        
        // Upload de nova imagem se fornecida
        if (!empty($_FILES['imagem']['name'])) {
            $img = $_FILES['imagem'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            
            if ($img['error'] === UPLOAD_ERR_OK) {
                if (in_array($img['type'], $allowed) && $img['size'] <= 2 * 1024 * 1024) {
                    $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
                    $filename = 'prod_' . uniqid() . '.' . $ext;
                    $upload_dir = __DIR__ . '/../assets/img/uploads/';
                    
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $dest = $upload_dir . $filename;
                    
                    if (move_uploaded_file($img['tmp_name'], $dest)) {
                        // Remover imagem antiga se for de upload
                        if (strpos($p['imagem'], 'uploads/') !== false) {
                            $old_file = __DIR__ . '/../' . $p['imagem'];
                            if (file_exists($old_file)) {
                                @unlink($old_file);
                            }
                        }
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
        // ATUALIZAÇÃO DOS DADOS DO PRODUTO NO BANCO
        // Executa a query apenas se não houver erro anterior
        // ===============================
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, categoria=?, imagem=?, ativo=? WHERE id=?");
            $stmt->bind_param('ssdssii', $nome, $descricao, $preco, $categoria, $imagem_path, $ativo, $id);
            
            if ($stmt->execute()) {
                set_flash('success', 'Produto atualizado com sucesso!');
                redirect('products.php');
            } else {
                $error = 'Erro ao atualizar produto.';
            }
            
            $stmt->close();
        }
    }
}

// ===============================
// DEFINIÇÃO DE VARIÁVEIS DE TÍTULO E CAMINHOS DE ASSETS
// ===============================

$page_title = 'Editar Produto - Admin';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/app.js';
$base_path = '../';

// ===============================
// INCLUSÃO DO HEADER PADRÃO
// ===============================

include '../partials/header.php';
?>

<!-- =============================== -->
<!-- ESTRUTURA HTML DA PÁGINA DE EDIÇÃO DE PRODUTO -->
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
    <h1 class="page-title">Editar Produto</h1>
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
                <!-- FORMULÁRIO DE EDIÇÃO DE PRODUTO -->
                <!-- =============================== -->
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome do Produto: *</label>
                    <input 
                        type="text" 
                        id="nome"
                        name="nome" 
                        required
                        value="<?php echo sanitize($p['nome']); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea 
                        id="descricao"
                        name="descricao" 
                        rows="4"
                    ><?php echo sanitize($p['descricao']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="preco">Preço (R$): *</label>
                    <input 
                        type="number" 
                        id="preco"
                        name="preco" 
                        step="0.01"
                        min="0"
                        required
                        value="<?php echo $p['preco']; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="categoria">Categoria: *</label>
                    <select id="categoria" name="categoria" style="width: 100%; padding: 12px 15px; background: #1a1a1a; border: 2px solid #333; border-radius: 5px; color: var(--white); font-size: 16px;">
                        <option value="hamburguer" <?php echo $p['categoria'] === 'hamburguer' ? 'selected' : ''; ?>>Hambúrguer</option>
                        <option value="combo" <?php echo $p['categoria'] === 'combo' ? 'selected' : ''; ?>>Combo</option>
                        <option value="acompanhamento" <?php echo $p['categoria'] === 'acompanhamento' ? 'selected' : ''; ?>>Acompanhamento</option>
                        <option value="bebida" <?php echo $p['categoria'] === 'bebida' ? 'selected' : ''; ?>>Bebida</option>
                        <option value="sobremesa" <?php echo $p['categoria'] === 'sobremesa' ? 'selected' : ''; ?>>Sobremesa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ativo">Status:</label>
                    <select id="ativo" name="ativo" style="width: 100%; padding: 12px 15px; background: #1a1a1a; border: 2px solid #333; border-radius: 5px; color: var(--white); font-size: 16px;">
                        <option value="1" <?php echo $p['ativo'] == 1 ? 'selected' : ''; ?>>Ativo</option>
                        <option value="0" <?php echo $p['ativo'] == 0 ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Imagem Atual:</label>
                    <img 
                        src="../<?php echo sanitize($p['imagem']); ?>" 
                        alt="<?php echo sanitize($p['nome']); ?>"
                        style="width: 200px; height: 200px; object-fit: cover; border-radius: 10px; border: 3px solid var(--primary);"
                        onerror="this.src='../assets/img/placeholder.jpg'"
                    >
                </div>

                <div class="form-group">
                    <label for="imagem">Nova Imagem (opcional):</label>
                    <input 
                        type="file" 
                        id="imagem"
                        name="imagem" 
                        accept="image/*"
                        style="padding: 10px 15px;"
                    >
                    <small>Deixe em branco para manter a imagem atual</small>
                </div>

                <button class="btn btn-primary btn-large" type="submit">
                    Atualizar Produto
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