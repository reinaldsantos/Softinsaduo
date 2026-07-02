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
    <link rel="stylesheet" href="css/definicoes.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<div class="app">

    <?php include "includes/sidebar.php"; ?>

    <main class="main">

        <?php include "includes/header.php"; ?>

        <section class="form-page settings-page">

            <div class="form-card card settings-card">

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

                    <div class="settings-section">
                        <div class="settings-section-title">
                            <i class="fa-solid fa-palette"></i>
                            <div>
                                <h3>Aparência</h3>
                                <p>Escolhe o tema visual da aplicação.</p>
                            </div>
                        </div>

                        <div class="settings-row">
                            <div>
                                <strong>Tema</strong>
                                <p>Aplica o tema claro ou escuro.</p>
                            </div>

                            <select class="select settings-select" name="tema">
                                <option value="claro">Claro</option>
                                <option value="escuro">Escuro</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-title">
                            <i class="fa-solid fa-bell"></i>
                            <div>
                                <h3>Notificações</h3>
                                <p>Controla os avisos da aplicação.</p>
                            </div>
                        </div>

                        <label class="settings-row">
                            <div>
                                <strong>Receber notificações</strong>
                                <p>Permite receber avisos gerais.</p>
                            </div>
                            <input type="checkbox" name="notificacoes" checked>
                        </label>

                        <label class="settings-row">
                            <div>
                                <strong>Avisar tarefas em atraso</strong>
                                <p>Mostra avisos quando uma tarefa ultrapassa a data limite.</p>
                            </div>
                            <input type="checkbox" name="tarefas_atrasadas" checked>
                        </label>

                        <label class="settings-row">
                            <div>
                                <strong>Avisar tarefas para hoje</strong>
                                <p>Mostra avisos das tarefas com prazo no dia atual.</p>
                            </div>
                            <input type="checkbox" name="tarefas_hoje">
                        </label>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-title">
                            <i class="fa-solid fa-list-check"></i>
                            <div>
                                <h3>Tarefas</h3>
                                <p>Define como as tarefas aparecem na aplicação.</p>
                            </div>
                        </div>

                        <label class="settings-row">
                            <div>
                                <strong>Mostrar tarefas concluídas</strong>
                                <p>Inclui tarefas concluídas no Dashboard.</p>
                            </div>
                            <input type="checkbox" name="mostrar_concluidas" checked>
                        </label>

                        <label class="settings-row">
                            <div>
                                <strong>Confirmar antes de apagar</strong>
                                <p>Pede confirmação antes de eliminar uma tarefa.</p>
                            </div>
                            <input type="checkbox" name="confirmar_apagar" checked>
                        </label>

                        <div class="settings-row">
                            <div>
                                <strong>Tarefas por página</strong>
                                <p>Quantidade de tarefas mostradas por página.</p>
                            </div>

                            <select class="select settings-select" name="tarefas_por_pagina">
                                <option value="10">10 tarefas</option>
                                <option value="20">20 tarefas</option>
                                <option value="50">50 tarefas</option>
                            </select>
                        </div>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-title">
                            <i class="fa-solid fa-gear"></i>
                            <div>
                                <h3>Aplicação</h3>
                                <p>Define o comportamento geral da aplicação.</p>
                            </div>
                        </div>

                        <div class="settings-row">
                            <div>
                                <strong>Página inicial após login</strong>
                                <p>Escolhe a primeira página depois de entrar.</p>
                            </div>

                            <select class="select settings-select" name="pagina_inicial">
                                <option value="dashboard">Dashboard</option>
                                <option value="tarefas">Tarefas</option>
                            </select>
                        </div>

                        <label class="settings-row">
                            <div>
                                <strong>Mostrar estatísticas</strong>
                                <p>Mostra os cartões de estatísticas no Dashboard.</p>
                            </div>
                            <input type="checkbox" name="mostrar_stats" checked>
                        </label>
                    </div>

                    <div class="settings-section">
                        <div class="settings-section-title">
                            <i class="fa-solid fa-lock"></i>
                            <div>
                                <h3>Segurança</h3>
                                <p>Opções relacionadas com a conta.</p>
                            </div>
                        </div>

                        <div class="settings-actions-left">
                            <a href="perfil.php" class="btn btn-secondary">
                                <i class="fa-solid fa-key"></i>
                                Alterar Palavra-passe
                            </a>

                            <a href="logout.php" class="btn btn-secondary">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                Terminar Sessão
                            </a>
                        </div>
                    </div>

                    <!-- ZONA DE PERIGO -->
                    <div class="settings-section danger-zone">
                        <div class="settings-section-title danger-title">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <div>
                                <h3 style="color:#DC2626;">Zona de Perigo</h3>
                                <p>Esta ação é permanente. A conta e as tarefas associadas serão removidas.</p>
                            </div>
                        </div>

                        <a href="eliminar_conta.php" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                            Eliminar Conta
                        </a>
                    </div>

                    <div class="form-actions settings-final-actions">
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

<script src="js/menu.js"></script>
<script src="js/tema.js"></script>
</body>
</html>