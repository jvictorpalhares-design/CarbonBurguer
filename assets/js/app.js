/**
 * =============================================
 * app.js - JavaScript principal do CarbonBurguer
 * Arquivo responsável por interatividade, validações e UX.
 * Cada bloco está comentado com título e explicação didática.
 * =============================================
 */

// ===============================
// AGUARDAR CARREGAMENTO DO DOM
// Garante que o JS só execute após o HTML estar pronto.
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    
    // ===============================
    // AUTO-FECHAR ALERTAS
    // Esconde mensagens de alerta automaticamente após 5 segundos.
    // ===============================
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // === CONFIRMAÇÃO AO REMOVER DO CARRINHO ===
    const removeForms = document.querySelectorAll('.remove-form');
    removeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Deseja realmente remover este item do carrinho?')) {
                e.preventDefault();
            }
        });
    });
    
    // === VALIDAÇÃO DO FORMULÁRIO DE CHECKOUT ===
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const endereco = document.getElementById('endereco');
            const nome = document.getElementById('nome');
            
            if (nome && nome.value.trim().length < 3) {
                e.preventDefault();
                alert('Nome deve ter pelo menos 3 caracteres.');
                nome.focus();
                return;
            }
            
            if (endereco && endereco.value.trim().length < 10) {
                e.preventDefault();
                alert('Por favor, insira um endereço completo (mínimo 10 caracteres).');
                endereco.focus();
                return;
            }
        });
    }
    
    // === VALIDAÇÃO DO FORMULÁRIO DE REGISTRO ===
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const senha = document.getElementById('senha');
            const confirmaSenha = document.getElementById('confirma_senha');
            
            if (senha && confirmaSenha && senha.value !== confirmaSenha.value) {
                e.preventDefault();
                alert('As senhas não coincidem!');
                confirmaSenha.focus();
                return;
            }
        });
    }
    
    // === ANIMAÇÃO DE ADICIONAR AO CARRINHO ===
    const addToCartForms = document.querySelectorAll('form[action*="adicionar_carrinho"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                const originalText = button.innerHTML;
                button.innerHTML = '✓ Adicionado!';
                button.style.backgroundColor = '#27ae60';
                button.disabled = true;
                
                setTimeout(() => {
                    // Permite envio do formulário
                    button.disabled = false;
                }, 500);
            }
        });
    });
    
    // === FECHAR MENU MOBILE AO CLICAR EM LINK ===
    const menuToggle = document.getElementById('menu-toggle');
    const menuLinks = document.querySelectorAll('.menu a');
    
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (menuToggle) {
                menuToggle.checked = false;
            }
        });
    });
    
    // === SCROLL SUAVE PARA ÂNCORAS ===
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
  // INDICADOR DE LOADING EM FORMULÁRIOS
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton && !submitButton.classList.contains('no-loading')) {
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '⏳ Processando...';
                
                // Reset após 10 segundos (segurança)
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }, 10000);
            }
        });
    });
    
    // === PREVENÇÃO DE DUPLO CLIQUE ===
    let clickTimeout;
    const buttons = document.querySelectorAll('button, .btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            if (clickTimeout) {
                return false;
            }
            clickTimeout = setTimeout(() => {
                clickTimeout = null;
            }, 1000);
        });
    });
    
    // === LAZY LOADING DE IMAGENS ===
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // Log de inicialização
    console.log('🔥 CarbonBurguer App carregado com sucesso!');

    // === MINHA CONTA: BOTÕES E MODAL ===
    const logoutBtn = document.getElementById('logoutBtn');
    const editBtn = document.getElementById('editProfileBtn');
    const deleteBtn = document.getElementById('deleteAccountBtn');
    const accountMsg = document.getElementById('accountMsg');
    const editModal = document.getElementById('editProfileModal');
    const closeEdit = document.getElementById('closeEditModal');
    const backEdit = document.getElementById('backEditBtn');
    const editForm = document.getElementById('editProfileForm');
    const editMsg = document.getElementById('editProfileMsg');

    // Sair
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            fetch('backend/logout.php', { method: 'POST' })
            .then(() => { window.location = 'login.php'; });
        });
    }
    // Editar Perfil
    if (editBtn && editModal) {
        editBtn.addEventListener('click', function() {
            editModal.style.display = 'flex';
            editMsg.innerHTML = '';
        });
    }
    if (closeEdit && editModal) {
        closeEdit.addEventListener('click', function() {
            editModal.style.display = 'none';
        });
    }
    if (backEdit && editModal) {
        backEdit.addEventListener('click', function() {
            editModal.style.display = 'none';
        });
    }
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                editModal.style.display = 'none';
            }
        });
    }
    // Salvar edição
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            editMsg.innerHTML = '';
            const nome = document.getElementById('edit_nome').value.trim();
            const email = document.getElementById('edit_email').value.trim();
            const senha = document.getElementById('edit_senha').value;
            const confirma = document.getElementById('edit_confirma_senha').value;
            if (!nome || !email) {
                editMsg.innerHTML = '<span style="color:#c0392b">Preencha todos os campos obrigatórios.</span>';
                return;
            }
            if (senha && senha.length < 6) {
                editMsg.innerHTML = '<span style="color:#c0392b">A senha deve ter pelo menos 6 caracteres.</span>';
                return;
            }
            if (senha !== confirma) {
                editMsg.innerHTML = '<span style="color:#c0392b">As senhas não coincidem.</span>';
                return;
            }
            editMsg.innerHTML = '⏳ Salvando...';
            const formData = new URLSearchParams();
            formData.append('nome', nome);
            formData.append('email', email);
            formData.append('senha', senha);
            formData.append('confirma_senha', confirma);
            fetch('backend/edit_profile.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    editMsg.innerHTML = '<span style="color:#27ae60">' + data.message + '</span>';
                    setTimeout(() => { window.location.reload(); }, 1200);
                } else {
                    editMsg.innerHTML = '<span style="color:#c0392b">' + data.message + '</span>';
                }
            })
            .catch(() => {
                editMsg.innerHTML = '<span style="color:#c0392b">Erro ao processar. Tente novamente.</span>';
            });
        });
    }
    // Excluir conta
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (!confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível!')) return;
            accountMsg.innerHTML = '⏳ Excluindo conta...';
            fetch('backend/delete_account.php', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    accountMsg.innerHTML = '<span style="color:#27ae60">' + data.message + '</span>';
                    setTimeout(() => { window.location = 'register.php'; }, 1500);
                } else {
                    accountMsg.innerHTML = '<span style="color:#c0392b">' + data.message + '</span>';
                }
            })
            .catch(() => {
                accountMsg.innerHTML = '<span style="color:#c0392b">Erro ao processar. Tente novamente.</span>';
            });
        });
    }
    // === ESQUECEU A SENHA: MODAL E SUBMISSÃO ===
    const forgotLink = document.getElementById('forgotPasswordLink');
    const forgotModal = document.getElementById('forgotPasswordModal');
    const closeForgot = document.getElementById('closeForgotModal');
    const forgotForm = document.getElementById('forgotPasswordForm');
    const forgotMsg = document.getElementById('forgotPasswordMsg');

    if (forgotLink && forgotModal) {
        forgotLink.addEventListener('click', function(e) {
            e.preventDefault();
            forgotModal.style.display = 'flex';
            forgotMsg.innerHTML = '';
            forgotForm.reset();
        });
    }
    if (closeForgot && forgotModal) {
        closeForgot.addEventListener('click', function() {
            forgotModal.style.display = 'none';
        });
    }
    if (forgotModal) {
        forgotModal.addEventListener('click', function(e) {
            if (e.target === forgotModal) {
                forgotModal.style.display = 'none';
            }
        });
    }
    if (forgotForm) {
        forgotForm.addEventListener('submit', function(e) {
            e.preventDefault();
            forgotMsg.innerHTML = '';
            const email = document.getElementById('forgot_email').value.trim();
            const newPass = document.getElementById('forgot_new_password').value;
            const confirmPass = document.getElementById('forgot_confirm_password').value;
            if (!email || !newPass || !confirmPass) {
                forgotMsg.innerHTML = '<span style="color:#c0392b">Preencha todos os campos.</span>';
                return;
            }
            if (newPass.length < 6) {
                forgotMsg.innerHTML = '<span style="color:#c0392b">A senha deve ter pelo menos 6 caracteres.</span>';
                return;
            }
            if (newPass !== confirmPass) {
                forgotMsg.innerHTML = '<span style="color:#c0392b">As senhas não coincidem.</span>';
                return;
            }
            forgotMsg.innerHTML = '⏳ Processando...';
            fetch('backend/forgot_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `email=${encodeURIComponent(email)}&new_password=${encodeURIComponent(newPass)}&confirm_password=${encodeURIComponent(confirmPass)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    forgotMsg.innerHTML = '<span style="color:#27ae60">' + data.message + '</span>';
                    setTimeout(() => { forgotModal.style.display = 'none'; }, 1800);
                } else {
                    forgotMsg.innerHTML = '<span style="color:#c0392b">' + data.message + '</span>';
                }
            })
            .catch(() => {
                forgotMsg.innerHTML = '<span style="color:#c0392b">Erro ao processar. Tente novamente.</span>';
            });
        });
    }
});

// ===============================
// FUNÇÃO GLOBAL PARA FORMATAR PREÇO
// Retorna valor formatado em reais (R$).
// ===============================
window.formatPrice = function(price) {
    return 'R$ ' + parseFloat(price).toFixed(2).replace('.', ',');
};

// ===============================
// FUNÇÃO PARA MOSTRAR NOTIFICAÇÃO
// Exibe mensagem temporária no canto da tela.
// ===============================
window.showNotification = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '10000';
    notification.style.maxWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 3000);
};