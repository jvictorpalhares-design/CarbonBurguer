<?php
/*
====================================================
    backend/db.php - Conexão com o banco de dados
    Fornece conexão para scripts do backend
====================================================
*/

// ===============================
// DADOS DE CONEXÃO
// ===============================
$host = "localhost";
$user = "root";
$pass = "";
$db = "carbonburguer";

// ===============================
// CRIA CONEXÃO COM O BANCO
// ===============================
$conn = new mysqli($host, $user, $pass, $db);

// ===============================
// VERIFICA ERRO DE CONEXÃO
// ===============================
if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}
?>
