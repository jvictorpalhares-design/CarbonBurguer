<?php
/*
====================================================
	backend/logout.php - Logout do usuário
	Encerra a sessão e redireciona para a home
====================================================
*/

// ===============================
// INICIA SESSÃO
// ===============================
session_start();

// ===============================
// LIMPA E DESTROI A SESSÃO
// ===============================
session_unset(); // Remove todas as variáveis de sessão
session_destroy(); // Destroi a sessão

// ===============================
// REDIRECIONA PARA HOME
// ===============================
header("Location: ../index.php");
exit;
?>
