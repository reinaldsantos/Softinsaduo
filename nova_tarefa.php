<?php
session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Nova Tarefa";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_SESSION["user_id"];
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

        $sql = "INSERT INTO tasks (user_id, titulo, descricao, status, data_limite)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "issss",
            $user_id,
            $titulo,
            $descricao,
            $status,
            $data_limite
        );

        if ($stmt->execute()) {

            header("Location: dashboard.php?sucesso=Tarefa criada com sucesso");
            exit();

        } else {

            $erro = "Erro ao criar tarefa.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="UTF-8">

    <title>Nova Tarefa</title>

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

                    <h1>Criar Nova Tarefa</h1>

                    <p>Preenche os dados para adicionar uma nova tarefa.</p>

                </div>

                <?php if ($erro): ?>

                    <div class="alert-error">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        <?php echo htmlspecialchars($erro); ?>

                    </div>

                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">

                        <label>Título da tarefa</label>

                        <input
                            class="input"
                            type="text"
                            name="titulo"
                            placeholder="Ex: Atualizar Website"
                            required>

                    </div>

                    <div class="form-group">

                        <label>Descrição</label>

                        <textarea
                            class="textarea"
                            name="descricao"
                            rows="5"
                            placeholder="Escreve uma breve descrição da tarefa"></textarea>

                    </div>

                    <div class="form-row">

                        <div class="form-group">

                            <label>Estado</label>

                            <select class="select" name="status">

                                <option value="pendente">
                                    Por Fazer
                                </option>

                                <option value="em_andamento">
                                    Em Curso
                                </option>

                                <option value="concluida">
                                    Concluída
                                </option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label>Data Limite</label>

                            <input
                                class="input"
                                type="date"
                                name="data_limite">

                        </div>

                    </div>

                    <div class="form-actions">

                        <a href="tarefas.php" class="btn btn-secondary">

                            Cancelar

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fa-solid fa-floppy-disk"></i>

                            Guardar Tarefa

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