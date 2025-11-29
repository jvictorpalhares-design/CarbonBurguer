<?php
/*
====================================================
    index.php - Página inicial
    Apresenta a home do site com informações e chamadas para ação
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

$page_title = 'CarbonBurguer - Hambúrgueres Artesanais Premium'; // Título da página
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
        HERO SECTION
        ===============================
        Apresenta o nome e slogan do site
    -->
    <section class="hero">
        <h1>🔥 CarbonBurguer</h1>
        <p class="hero-subtitle">Hambúrgueres artesanais premium feitos no carvão</p>
        <p class="hero-description">Sabor defumado único com ingredientes selecionados</p>
        <a class="btn btn-primary btn-large" href="produtos.php">Ver Cardápio Completo</a>
    </section>
    <!--
        ===============================
        FEATURES SECTION
        ===============================
        Mostra os diferenciais da CarbonBurguer
    -->

    <section class="features">
        <h2 class="page-title">Por que escolher a CarbonBurguer?</h2>
        
        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon">🥩</div>
                <h3>Carnes Premium</h3>
                <p>Utilizamos apenas carnes nobres e selecionadas, garantindo maciez e sabor excepcional em cada mordida.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">🔥</div>
                <h3>Sabor Defumado</h3>
                <p>Nossos hambúrgueres são grelhados no carvão, proporcionando aquele sabor defumado inconfundível.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">🚚</div>
                <h3>Entrega Rápida</h3>
                <p>Entregamos seu pedido em até 40 minutos, sempre quentinho e com embalagem especial.</p>
            </div>
            
            <div class="feature-box">
                <div class="feature-icon">🌿</div>
                <h3>Ingredientes Frescos</h3>
                <p>Trabalhamos apenas com ingredientes frescos e de alta qualidade, preparados diariamente.</p>
            </div>
        </div>
    </section>
    <!--
        ===============================
        CALL TO ACTION (CTA)
        ===============================
        Incentiva o usuário a fazer um pedido
    -->

    <section class="cta">
        <h2>Pronto para experimentar?</h2>
        <p>Faça seu pedido agora e receba em casa quentinho!</p>
        <a class="btn btn-large" href="produtos.php" style="background: var(--white); color: var(--primary);">
            Ver Cardápio Completo
        </a>
    </section>
</div>

<?php include 'partials/footer.php'; ?>