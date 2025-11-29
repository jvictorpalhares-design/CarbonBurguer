<?php
/*
====================================================
  config.php - Arquivo de configuração principal
  Centraliza as configurações do sistema e funções utilitárias
====================================================
*/

// ===============================
// CONFIGURAÇÕES DO BANCO DE DADOS
// ===============================
// Define constantes para conexão com o banco
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'carbonburguer');

// ===============================
// CONFIGURAÇÕES DO SITE
// ===============================
// Define constantes globais do site
define('SITE_NAME', 'CarbonBurguer');
define('SITE_URL', 'http://localhost/carbonburguer');

// ===============================
// CONEXÃO COM O BANCO DE DADOS
// ===============================
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}

// Define charset para suportar acentuação
$conn->set_charset("utf8mb4");

// ===============================
// FUNÇÕES AUXILIARES
// ===============================

/**
 * Sanitiza string para exibição
 * Evita XSS e problemas de encoding
 */
function sanitize($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Valida email
 * Retorna true se o email for válido
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Formata preço
 */
function format_price($price) {
    return 'R$ ' . number_format(floatval($price), 2, ',', '.');
}

/**
 * Verifica se usuário está logado
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica se usuário é admin
 */
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

/**
 * Define mensagem flash
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Obtém e remove mensagem flash
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redireciona para URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Exibe mensagem flash se existir
 */
function display_flash() {
    $flash = get_flash();
    if ($flash) {
        $type = sanitize($flash['type']);
        $message = sanitize($flash['message']);
        echo "<div class='alert alert-{$type}'>{$message}</div>";
    }
}