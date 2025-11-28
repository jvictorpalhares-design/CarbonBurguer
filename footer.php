</main>

<!--
    ===============================
    FOOTER
    ===============================
    Exibe o rodapé do site com informações e links
-->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>🔥 CarbonBurguer</h3>
                <p>Os melhores hambúrgueres artesanais da cidade, feitos no carvão com ingredientes premium selecionados.</p>
            </div>
            <!-- Seção de links rápidos -->
            <div class="footer-section">
                <h4>Links Rápidos</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produtos.php">Cardápio</a></li>
                    <li><a href="carrinho.php">Carrinho</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Contato</h4>
                <p>📞 (91) 99999-9999</p>
                <p>📧 contato@carbonburguer.com</p>
                <p>📍 Ananindeua, Pará - BR</p>
            </div>
        </div>
        <!-- Rodapé inferior -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> CarbonBurguer - Todos os direitos reservados.</p>
        </div>
    </div>
</footer>

<!--
    ===============================
    SCRIPTS FINAIS
    ===============================
    Inclui o JS principal do site
-->
<script src="<?php echo isset($js_path) ? $js_path : 'assets/js/app.js'; ?>" defer></script>
</body>
</html>