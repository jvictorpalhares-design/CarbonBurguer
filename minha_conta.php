<?php
// minha_conta.php - Página de perfil do usuário logado
require_once 'php/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Busca dados do usuário logado
require_once 'backend/db.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT nome, email FROM usuarios WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($nome, $email);
$stmt->fetch();
$stmt->close();
$conn->close();

$page_title = 'Minha Conta - CarbonBurguer';
$css_path = 'assets/css/style.css';
$js_path = 'assets/js/app.js';
$base_path = '';
include 'partials/header.php';
?>
<main class="container">
    <div class="auth-page">
        <div class="auth-container" style="max-width:420px;">
            <div class="auth-header">
                <h1>Minha Conta</h1>
            </div>
            <div class="form-group">
                <label>Nome:</label>
                <div style="font-weight:500; color:#ffffff; margin-bottom:8px;"> <?= htmlspecialchars($nome ?? '') ?> </div>
                <label>E-mail:</label>
                <div style="font-weight:500; color:#ffffff;"> <?= htmlspecialchars($email ?? '') ?> </div>
            </div>
            <div style="display:flex; gap:10px; margin:24px 0 0 0; justify-content:space-between;">
                <button class="btn btn-secondary" id="logoutBtn" style="flex:1;">Sair</button>
                <button class="btn btn-primary" id="editProfileBtn" style="flex:1;">Editar Perfil</button>
                <button class="btn btn-danger" id="deleteAccountBtn" style="flex:1;">Excluir Conta</button>
            </div>
            <div id="accountMsg" style="margin-top:16px; font-size:0.98em;"></div>
        </div>
    </div>
    <!-- Modal Editar Perfil -->
    <div id="editProfileModal" class="modal" style="display:none; position:fixed; z-index:10001; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); justify-content:center; align-items:center;">
        <div style="background:#222; padding:32px 24px 24px 24px; border-radius:8px; max-width:350px; width:90%; box-shadow:0 4px 24px rgba(0,0,0,0.18); position:relative;">
            <button id="closeEditModal" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:1.3em; cursor:pointer;">&times;</button>
            <h2 style="margin-bottom:10px; font-size:1.2em;">Editar Perfil</h2>
            <form id="editProfileForm" autocomplete="off">
                <div class="form-group">
                    <label for="edit_nome">Nome:</label>
                    <input type="text" id="edit_nome" name="nome" required minlength="3" value="<?= htmlspecialchars($nome ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="edit_email">E-mail:</label>
                    <input type="email" id="edit_email" name="email" required value="<?= htmlspecialchars($email ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="edit_senha">Nova senha:</label>
                    <input type="password" id="edit_senha" name="senha" minlength="6" placeholder="Deixe em branco para não alterar">
                </div>
                <div class="form-group">
                    <label for="edit_confirma_senha">Confirmar nova senha:</label>
                    <input type="password" id="edit_confirma_senha" name="confirma_senha" minlength="6" placeholder="Confirme a nova senha">
                </div>
                <div style="display:flex; gap:10px; margin-top:10px;">
                    <button type="button" class="btn btn-secondary" id="backEditBtn" style="flex:1;">Voltar</button>
                    <button type="submit" class="btn btn-primary" style="flex:1;">Salvar</button>
                </div>
            </form>
            <div id="editProfileMsg" style="margin-top:10px; font-size:0.98em;"></div>
        </div>
    </div>
</main>
<?php include 'partials/footer.php'; ?>
