<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Perfil";

$user_id = $_SESSION["user_id"];

$erro = "";
$sucesso = "";

$sql = "SELECT id, nome, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: logout.php");
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $nova_senha = $_POST["nova_senha"] ?? "";
    $confirmar_senha = $_POST["confirmar_senha"] ?? "";

    if ($nome === "" || $email === "") {
        $erro = "O nome e o email são obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Insere um email válido.";
    } elseif ($nova_senha !== "" && strlen($nova_senha) < 6) {
        $erro = "A nova palavra-passe deve ter pelo menos 6 caracteres.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "As palavras-passe não coincidem.";
    } else {

        if ($nova_senha !== "") {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET nome = ?, email = ?, senha = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nome, $email, $senha_hash, $user_id);
        } else {
            $sql = "UPDATE users SET nome = ?, email = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nome, $email, $user_id);
        }

        if ($stmt->execute()) {

            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;

            $sucesso = "Perfil atualizado com sucesso.";

        } else {
            $erro = "Erro ao atualizar perfil.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>

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
                    <h1>Perfil</h1>
                    <p>Consulta e altera os dados do utilizador.</p>
                </div>

                <?php if ($erro): ?>
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?php echo htmlspecialchars($erro); ?>
                    </div>
                <?php endif; ?>

                <?php if ($sucesso): ?>
                    <div class="alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <?php echo htmlspecialchars($sucesso); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">

                    <div class="form-row">

                        <div class="form-group">
                            <label>Nome</label>
                            <input
                                class="input"
                                type="text"
                                name="nome"
                                value=""
                                placeholder="Escreva o seu nome"
                                autocomplete="off"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input
                                class="input"
                                type="email"
                                name="email"
                                value=""
                                placeholder="Escreva o seu email"
                                autocomplete="off"
                                required>
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label>Nova Palavra-passe</label>
                            <input
                                class="input"
                                type="password"
                                name="nova_senha"
                                placeholder="Nova palavra-passe"
                                autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label>Confirmar Palavra-passe</label>
                            <input
                                class="input"
                                type="password"
                                name="confirmar_senha"
                                placeholder="Confirmar palavra-passe"
                                autocomplete="new-password">
                        </div>

                    </div>

                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-secondary">
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