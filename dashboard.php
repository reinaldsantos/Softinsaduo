<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Dashboard";
$user_id = $_SESSION["user_id"];
$nome = $_SESSION["nome"] ?? "Utilizador";

$stats = [
    "total" => 0,
    "pendente" => 0,
    "em_andamento" => 0,
    "concluida" => 0
];

$sql = "SELECT status, COUNT(*) AS count FROM tasks WHERE user_id = ? GROUP BY status";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $stats[$row["status"]] = $row["count"];
    $stats["total"] += $row["count"];
}

$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY criado_em DESC LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$ultimas_tarefas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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
                    <p>Bem-vindo, <?php echo htmlspecialchars($nome); ?>.</p>
                </div>

                <a href="nova_tarefa.php" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Nova Tarefa
                </a>
            </div>

            <div class="stats-grid">

                <div class="card stat-card">
                    <div class="stat-label">Total de Tarefas</div>
                    <div class="stat-value purple">
                        <?php echo $stats["total"]; ?>
                    </div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Por Fazer</div>
                    <div class="stat-value yellow">
                        <?php echo $stats["pendente"]; ?>
                    </div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Em Curso</div>
                    <div class="stat-value">
                        <?php echo $stats["em_andamento"]; ?>
                    </div>
                </div>

                <div class="card stat-card">
                    <div class="stat-label">Concluídas</div>
                    <div class="stat-value green">
                        <?php echo $stats["concluida"]; ?>
                    </div>
                </div>

            </div>

            <section class="tasks-section">

                <div class="section-top">
                    <h2>Últimas tarefas</h2>
                    <a href="tarefas.php">Ver todas →</a>
                </div>

                <?php if ($ultimas_tarefas->num_rows > 0): ?>

                    <?php while ($task = $ultimas_tarefas->fetch_assoc()): ?>

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

                        <div class="card task-item">

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

                            </div>

                        </div>

                    <?php endwhile; ?>

                <?php else: ?>

                    <div class="card task-item">
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
                    </div>

                <?php endif; ?>

            </section>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/menu.js"></script>
</body>
</html>