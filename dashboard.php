<?php
$tituloPagina = "Dashboard";
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <script src="js/app.js"></script>
    <script src="js/filtros.js"></script>
    <script src="js/animacoes.js"></script>
    
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/tarefas.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<div class="app">

    <?php include "includes/sidebar.php"; ?>

    <main class="main page">

        <?php include "includes/header.php"; ?>

        <section class="dashboard">

            <div class="page-header">
                <div>
                    <h1>Dashboard</h1>
                    <p>Bem-vindo ao Gestor de Tarefas.</p>
                </div>

                <a href="nova_tarefa.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Nova Tarefa
                </a>
            </div>

            <div class="stats-grid">

                <div class="card stat-card">
                    <div class="stat-label">Total de Tarefas</div>
                    <div class="stat-value purple">15</div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Por Fazer</div>
                    <div class="stat-value yellow">5</div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Em Curso</div>
                    <div class="stat-value">4</div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Concluídas</div>
                    <div class="stat-value green">6</div>
                </div>

            </div>

            <section class="tasks-section">

                <div class="section-top">
                    <h2>Últimas tarefas</h2>
                    <a href="tarefas.php">Ver todas →</a>
                </div>

                <div class="card task-item">
                    <div class="task-left">
                        <div class="task-check checked"></div>

                        <div class="task-info">
                            <h3>Criar relatório do projeto</h3>
                            <p>Entrega final do projeto.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge done">Concluída</span>
                        <span class="task-date">
                            <i class="fa-regular fa-calendar"></i>
                            26/06/2026
                        </span>
                    </div>
                </div>

                <div class="card task-item">
                    <div class="task-left">
                        <div class="task-check"></div>

                        <div class="task-info">
                            <h3>Atualizar Website</h3>
                            <p>Melhorar interface e corrigir erros visuais.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge progress">Em Curso</span>
                        <span class="task-date">
                            <i class="fa-regular fa-calendar"></i>
                            28/06/2026
                        </span>
                    </div>
                </div>

                <div class="card task-item">
                    <div class="task-left">
                        <div class="task-check"></div>

                        <div class="task-info">
                            <h3>Backup dos ficheiros</h3>
                            <p>Guardar cópias dos ficheiros importantes.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge late">Atrasada</span>
                        <span class="task-date overdue">
                            <i class="fa-regular fa-calendar"></i>
                            20/06/2026
                        </span>
                    </div>
                </div>

            </section>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/menu.js"></script>
</body>
</html>