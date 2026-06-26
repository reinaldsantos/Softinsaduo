<?php
$tituloPagina = "Tarefas";
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Tarefas</title>

    <script src="js/app.js"></script>
    <script src="js/filtros.js"></script>
    <script src="js/animacoes.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/tarefas.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<div class="app">

    <?php include "includes/sidebar.php"; ?>

    <main class="main">

        <?php include "includes/header.php"; ?>

        <section class="tasks-page">

            <div class="tasks-toolbar">

                <div>
                    <h1>Tarefas</h1>
                    <p>Consulta, filtra e gere as tarefas da equipa.</p>
                </div>

                <a href="nova_tarefa.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Nova Tarefa
                </a>

            </div>

            <div class="filters">

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Pesquisar tarefa...">
                </div>

                <div class="filter-group">
                    <button class="filter-btn active">Todas</button>
                    <button class="filter-btn">Por Fazer</button>
                    <button class="filter-btn">Em Curso</button>
                    <button class="filter-btn">Concluídas</button>
                </div>

            </div>

            <div class="tasks-list">

                <article class="card task-item">

                    <div class="task-left">
                        <div class="task-check"></div>

                        <div class="task-info">
                            <h3>Atualizar Website</h3>
                            <p>Melhorar a página inicial e corrigir erros visuais.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge progress">Em Curso</span>
                        <span class="task-date">
                            <i class="fa-regular fa-calendar"></i>
                            28/06/2026
                        </span>
                        <a href="editar_tarefa.php" class="btn btn-secondary">
                            Editar
                        </a>
                        <button class="btn btn-danger">
                            Apagar
                        </button>
                    </div>

                </article>

                <article class="card task-item">

                    <div class="task-left">
                        <div class="task-check"></div>

                        <div class="task-info">
                            <h3>Fazer backup dos ficheiros</h3>
                            <p>Guardar uma cópia dos ficheiros importantes.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge late">Atrasada</span>
                        <span class="task-date">
                            <i class="fa-regular fa-calendar"></i>
                            20/06/2026
                        </span>
                        <a href="editar_tarefa.php" class="btn btn-secondary">
                            Editar
                        </a>
                        <button class="btn btn-danger">
                            Apagar
                        </button>
                    </div>

                </article>

                <article class="card task-item">

                    <div class="task-left">
                        <div class="task-check checked"></div>

                        <div class="task-info">
                            <h3>Entregar relatório</h3>
                            <p>Relatório final do projeto de estágio.</p>
                        </div>
                    </div>

                    <div class="task-right">
                        <span class="badge done">Concluída</span>
                        <span class="task-date">
                            <i class="fa-regular fa-calendar"></i>
                            26/06/2026
                        </span>
                        <a href="editar_tarefa.php" class="btn btn-secondary">
                            Editar
                        </a>
                        <button class="btn btn-danger">
                            Apagar
                        </button>
                    </div>

                </article>

            </div>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/menu.js"></script>
</body>
</html>