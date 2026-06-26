<?php
// includes/auth.php - Funções de Autenticação

/**
 * Verifica se o utilizador está autenticado
 */
function estaAutenticado() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Protege uma página - redireciona para login se não estiver autenticado
 */
function protegerPagina() {
    if (!estaAutenticado()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Redireciona para dashboard se já estiver autenticado
 */
function redirecionarSeLogado() {
    if (estaAutenticado()) {
        header('Location: dashboard.php');
        exit();
    }
}

/**
 * Obtém os dados do utilizador atual
 */
function getUsuarioAtual($conn) {
    if (!estaAutenticado()) {
        return null;
    }
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT id, nome, email, criado_em FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}
?>