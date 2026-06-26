<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Editar Tarefa";

$user_id = $_SESSION["user_id"];
$id = $_GET["id"] ?? 0;
$erro = "";

$sql = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: tarefas.php");
    exit();
}

$task = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = trim($_POST["titulo"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $status = $_POST["status"] ?? "pendente";
    $data_limite = $_POST["data_limite"] ?? null;

    if ($data_limite === "") {
        $data_limite = null;
    }

    if ($titulo === "") {
        $erro = "O título da tarefa é obrigatório.";
    } else {

        $sql = "UPDATE tasks 
                SET titulo = ?, descricao = ?, status = ?, data_limite = ?
                WHERE id = ? AND user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssii",
            $titulo,
            $descricao,
            $status,
            $data_limite,
            $id,
            $user_id
        );

        if ($stmt->execute()) {
            header("Location: tarefas.php?sucesso=Tarefa atualizada");
            exit();
        } else {
            $erro = "Erro ao atualizar tarefa.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>

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
                    <h1>Editar Tarefa</h1>
                    <p>Atualiza os dados da tarefa selecionada.</p>
                </div>

                <?php if ($erro): ?>
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Título</label>

                        <input
                            class="input"
                            type="text"
                            name="titulo"
                            value="<?php echo htmlspecialchars($task["titulo"]); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Descrição</label>

                        <textarea
                            class="textarea"
                            name="descricao"
                            rows="5"><?php echo htmlspecialchars($task["descricao"]); ?></textarea>
                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label>Estado</label>

                            <select class="select" name="status">
                                <option value="pendente" <?php echo $task["status"] === "pendente" ? "selected" : ""; ?>>
                                    Por Fazer
                                </option>

                                <option value="em_andamento" <?php echo $task["status"] === "em_andamento" ? "selected" : ""; ?>>
                                    Em Curso
                                </option>

                                <option value="concluida" <?php echo $task["status"] === "concluida" ? "selected" : ""; ?>>
                                    Concluída
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data Limite</label>

                            <input
                                class="input"
                                type="date"
                                name="data_limite"
                                value="<?php echo htmlspecialchars($task["data_limite"]); ?>">
                        </div>

                    </div>

                    <div class="form-actions">

                        <a href="tarefas.php" class="btn btn-secondary">
                            Cancelar
                        </a>

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
</body>
</html>