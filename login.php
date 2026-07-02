<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

redirecionarSeLogado();

$erro = "";
$sucesso = "";
$email = "";

// Verificar se a conta foi eliminada
if (isset($_GET['conta_eliminada']) && $_GET['conta_eliminada'] == 1) {
    $sucesso = "A tua conta foi eliminada com sucesso.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {
        $erro = "Por favor, preenche todos os campos.";
    } else {

        $sql = "SELECT id, nome, email, senha FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            if (password_verify($senha, $user["senha"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["nome"] = $user["nome"];
                $_SESSION["email"] = $user["email"];

                header("Location: dashboard.php");
                exit();

            } else {
                $erro = "Email ou palavra-passe inválidos.";
            }

        } else {
            $erro = "Email ou palavra-passe inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<section class="auth-page">

    <div class="auth-card card">

        <div class="auth-logo">
            <i class="fa-solid fa-list-check"></i>
            <h1>Gestor de Tarefas</h1>
            <p>Inicia sessão para continuares.</p>
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

        <form method="POST">

            <div class="form-group">
                <label>Email</label>

                <input
                    class="input"
                    type="email"
                    name="email"
                    placeholder="exemplo@email.com"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Palavra-passe</label>

                <input
                    class="input"
                    type="password"
                    name="senha"
                    placeholder="••••••••"
                    required>
            </div>

            <button type="submit" class="btn btn-primary full-btn">
                Entrar
            </button>

        </form>

        <p class="auth-link">
            Ainda não tens conta?
            <a href="register.php">Criar conta</a>
        </p>

    </div>

</section>

</body>
</html>