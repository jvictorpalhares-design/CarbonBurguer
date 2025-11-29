<?php
/*
====================================================
    login.php - Página de login
    Permite que o usuário acesse sua conta
====================================================
*/

// ===============================
// INCLUSÃO DE CONFIGURAÇÕES E SESSÃO
// ===============================
require_once 'php/config.php'; // Inclui configurações e funções
session_start(); // Inicia sessão

// ===============================
// VARIÁVEIS DE TÍTULO E ASSETS
// ===============================
$page_title = 'Login - CarbonBurguer'; // Título da página
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
        PÁGINA DE LOGIN
        ===============================
        Permite ao usuário acessar sua conta
    -->
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-header">
                <h1>Bem-vindo de volta! 👋</h1>
                <p>Entre na sua conta para continuar</p>
            </div>
            <!-- Formulário de login -->
            <form method="post" action="backend/login_process.php" class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="seu@email.com"
                        required
                        autocomplete="email"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input 
                        type="password" 
                        id="senha"
                        name="senha" 
                        placeholder="Digite sua senha"
                        required
                        minlength="6"
                        autocomplete="current-password"
                    >
                </div>

                <button class="btn btn-primary btn-large" type="submit">
                    Entrar →
                </button>
            </form>
            <!-- Esqueceu a senha -->
            <div style="text-align:center; margin-top:10px;">
                <a href="#" id="forgotPasswordLink" style="font-size:0.98em;">Esqueceu a senha?</a>
            </div>
            <!-- Rodapé com link para cadastro -->
            <div class="auth-footer">
                <p>Não tem uma conta? <a href="register.php">Criar conta grátis</a></p>
            </div>
            <div class="auth-info">
                <p>🔒 Seus dados estão seguros conosco</p>
            </div>
            <!-- Modal Esqueceu a Senha -->
            <div id="forgotPasswordModal" class="modal" style="display:none; position:fixed; z-index:10001; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.45); justify-content:center; align-items:center;">
                <div style="background:#222; padding:32px 24px 24px 24px; border-radius:8px; max-width:350px; width:90%; box-shadow:0 4px 24px rgba(0,0,0,0.18); position:relative;">
                    <button id="closeForgotModal" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:1.3em; cursor:pointer;">&times;</button>
                    <h2 style="margin-bottom:10px; font-size:1.2em;">Redefinir senha</h2>
                    <form id="forgotPasswordForm" autocomplete="off">
                        <div class="form-group">
                            <label for="forgot_email">E-mail:</label>
                            <input type="email" id="forgot_email" name="email" required placeholder="Digite seu e-mail">
                        </div>
                        <div class="form-group">
                            <label for="forgot_new_password">Nova senha:</label>
                            <input type="password" id="forgot_new_password" name="new_password" required minlength="6" placeholder="Nova senha">
                        </div>
                        <div class="form-group">
                            <label for="forgot_confirm_password">Confirmar nova senha:</label>
                            <input type="password" id="forgot_confirm_password" name="confirm_password" required minlength="6" placeholder="Confirme a nova senha">
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;">Redefinir senha</button>
                    </form>
                    <div id="forgotPasswordMsg" style="margin-top:10px; font-size:0.98em;"></div>
                </div>
            </div>
        </div>
        <div class="auth-banner">
            <h2>🔥 CarbonBurguer</h2>
            <p>Os melhores hambúrgueres artesanais da cidade!</p>
            <ul>
                <li>✓ Carnes premium selecionadas</li>
                <li>✓ Sabor defumado único</li>
                <li>✓ Entrega rápida</li>
                <li>✓ Ingredientes frescos</li>
            </ul>
        </div>
    </div>
</main>

<?php include 'partials/footer.php'; ?>