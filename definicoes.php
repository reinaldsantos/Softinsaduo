<?php
session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Definições";

$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sucesso = "Definições guardadas com sucesso.";
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Definições</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<div class="app">

    <?php include "includes/sidebar.php"; ?>

    <main class="main">

        <?php include "includes/header.php"; ?>

        <section class="form-page">

            <div class="form-card card">

                <div class="form-title">
                    <h1>Definições</h1>
                    <p>Personaliza as opções da aplicação.</p>
                </div>

                <?php if ($sucesso): ?>
                    <div class="alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <?php echo htmlspecialchars($sucesso); ?>
                    </div>
                <?php endif; ?>

                <?php if ($erro): ?>
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <h3><i class="fa-solid fa-palette"></i> Aparência</h3>

                    <div class="form-group">
                        <label>Tema</label>
                        <select class="select" name="tema">
                            <option value="claro">Claro</option>
                            <option value="escuro">Escuro</option>
                        </select>
                    </div>

                    <hr>

                    <h3><i class="fa-solid fa-bell"></i> Notificações</h3>

                    <div class="form-group">
                        <label><input type="checkbox" name="notificacoes" checked> Receber notificações</label>
                    </div>

                    <div class="form-group">
                        <label><input type="checkbox" name="tarefas_atrasadas" checked> Avisar tarefas em atraso</label>
                    </div>

                    <div class="form-group">
                        <label><input type="checkbox" name="tarefas_hoje"> Avisar tarefas para hoje</label>
                    </div>

                    <hr>

                    <h3><i class="fa-solid fa-list-check"></i> Tarefas</h3>

                    <div class="form-group">
                        <label><input type="checkbox" name="mostrar_concluidas" checked> Mostrar tarefas concluídas no Dashboard</label>
                    </div>

                    <div class="form-group">
                        <label><input type="checkbox" name="confirmar_apagar" checked> Confirmar antes de apagar tarefas</label>
                    </div>

                    <div class="form-group">
                        <label>Tarefas por página</label>
                        <select class="select" name="tarefas_por_pagina">
                            <option value="10">10 tarefas</option>
                            <option value="20">20 tarefas</option>
                            <option value="50">50 tarefas</option>
                        </select>
                    </div>

                    <hr>

                    <h3><i class="fa-solid fa-gear"></i> Aplicação</h3>

                    <div class="form-group">
                        <label>Página inicial após login</label>
                        <select class="select" name="pagina_inicial">
                            <option value="dashboard">Dashboard</option>
                            <option value="tarefas">Tarefas</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label><input type="checkbox" name="mostrar_stats" checked> Mostrar estatísticas no Dashboard</label>
                    </div>

                    <hr>

                    <h3><i class="fa-solid fa-lock"></i> Segurança</h3>

                    <div class="form-actions" style="justify-content:flex-start;">
                        <a href="perfil.php" class="btn btn-secondary">
                            <i class="fa-solid fa-key"></i>
                            Alterar Palavra-passe
                        </a>

                        <a href="logout.php" class="btn btn-secondary">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Terminar Sessão
                        </a>
                    </div>

                    <hr>

                    <h3 style="color:#991B1B;"><i class="fa-solid fa-triangle-exclamation"></i> Zona de Perigo</h3>

                    <div class="form-group">
                        <p style="color:var(--gray-500); font-size:14px;">
                            Esta ação é permanente. A conta e as tarefas associadas serão removidas.
                        </p>
                    </div>

                    <button type="button" class="btn btn-danger" onclick="return confirm('Tens a certeza que queres eliminar a conta?')">
                        <i class="fa-solid fa-trash"></i>
                        Eliminar Conta
                    </button>

                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>

                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Guardar Alterações
                        </button>
                    </div>

                </form>

            </div>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/tema.js"></script>
<script src="js/menu.js"></script>
</body>
</html>

