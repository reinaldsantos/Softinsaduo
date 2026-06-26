<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Tarefas";
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY criado_em DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tarefas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Tarefas</title>

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

                <?php if ($tarefas->num_rows > 0): ?>

                    <?php while ($task = $tarefas->fetch_assoc()): ?>

                        <?php
                        $hoje = date("Y-m-d");

                        $atrasada = (
                            !empty($task["data_limite"]) &&
                            $task["data_limite"] < $hoje &&
                            $task["status"] !== "concluida"
                        );

                        $badgeClasse = "todo";
                        $badgeTexto = "Por Fazer";

                        if ($task["status"] === "em_andamento") {
                            $badgeClasse = "progress";
                            $badgeTexto = "Em Curso";
                        } elseif ($task["status"] === "concluida") {
                            $badgeClasse = "done";
                            $badgeTexto = "Concluída";
                        }

                        if ($atrasada) {
                            $badgeClasse = "late";
                            $badgeTexto = "Atrasada";
                        }
                        ?>

                        <article class="card task-item">

                            <div class="task-left">

                                <div class="task-check <?php echo $task["status"] === "concluida" ? "checked" : ""; ?>"></div>

                                <div class="task-info">
                                    <h3><?php echo htmlspecialchars($task["titulo"]); ?></h3>

                                    <?php if (!empty($task["descricao"])): ?>
                                        <p><?php echo htmlspecialchars($task["descricao"]); ?></p>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <div class="task-right">

                                <span class="badge <?php echo $badgeClasse; ?>">
                                    <?php echo $badgeTexto; ?>
                                </span>

                                <?php if (!empty($task["data_limite"])): ?>
                                    <span class="task-date <?php echo $atrasada ? "overdue" : ""; ?>">
                                        <i class="fa-regular fa-calendar"></i>
                                        <?php echo date("d/m/Y", strtotime($task["data_limite"])); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if ($task["status"] !== "concluida"): ?>
                                    <a
                                        href="tarefas/concluir.php?id=<?php echo $task["id"]; ?>"
                                        class="btn btn-secondary"
                                        onclick="return confirm('Marcar esta tarefa como concluída?')">

                                        <i class="fa-solid fa-check"></i>
                                        Concluir

                                    </a>
                                <?php endif; ?>

                                <a
                                    href="editar_tarefa.php?id=<?php echo $task["id"]; ?>"
                                    class="btn btn-secondary">

                                    <i class="fa-solid fa-pen"></i>
                                    Editar

                                </a>

                                <a
                                    href="tarefas/apagar.php?id=<?php echo $task["id"]; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Tens a certeza que queres apagar esta tarefa?')">

                                    <i class="fa-solid fa-trash"></i>
                                    Apagar

                                </a>

                            </div>

                        </article>

                    <?php endwhile; ?>

                <?php else: ?>

                    <article class="card task-item">

                        <div class="task-left">

                            <div class="task-info">
                                <h3>Nenhuma tarefa encontrada</h3>
                                <p>Começa por criar a tua primeira tarefa.</p>
                            </div>

                        </div>

                        <div class="task-right">

                            <a href="nova_tarefa.php" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i>
                                Criar Tarefa
                            </a>

                        </div>

                    </article>

                <?php endif; ?>

            </div>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/menu.js"></script>
</body>
</html>