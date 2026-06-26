<?php
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Se já estiver logado, vai para dashboard
if (estaAutenticado()) {
    header('Location: dashboard.php');
    exit();
}

// Senão, vai para login
header('Location: login.php');
exit();
?>