<?php
// Ativar erros para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuração da Base de Dados
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gestor_tarefas';

// Criar ligação usando mysqli
$conn = new mysqli($host, $user, $password, $database);

// Verificar ligação
if ($conn->connect_error) {
    die("Erro de ligação: " . $conn->connect_error);
}

// Definir charset para UTF-8
$conn->set_charset("utf8mb4");

// Descomentar para testar (depois podes remover)
// echo "✅ Ligação à base de dados OK!";
?>