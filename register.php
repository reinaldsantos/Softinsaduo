<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

redirecionarSeLogado();

$erro = "";
$sucesso = "";
$nome = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar_senha = $_POST["confirmar_senha"] ?? "";

    if ($nome === "" || $email === "" || $senha === "" || $confirmar_senha === "") {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (strlen($nome) < 3) {
        $erro = "O nome deve ter pelo menos 3 caracteres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Insere um email válido.";
    } elseif (strlen($senha) < 6) {
        $erro = "A palavra-passe deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As palavras-passe não coincidem.";
    } else {

        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $erro = "Este email já está registado.";
        } else {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senha_hash);

            if ($stmt->execute()) {
                $sucesso = "Conta criada com sucesso. Já podes fazer login.";
                $nome = "";
                $email = "";
            } else {
                $erro = "Erro ao criar conta. Tenta novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Registo</title>

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
            <i class="fa-solid fa-user-plus"></i>
            <h1>Criar Conta</h1>
            <p>Regista-te para começares a usar a aplicação.</p>
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
                <label>Nome</label>

                <input
                    class="input"
                    type="text"
                    name="nome"
                    placeholder="Nome completo"
                    value="<?php echo htmlspecialchars($nome); ?>"
                    required>
            </div>

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
                    placeholder="Mínimo 6 caracteres"
                    required>
            </div>

            <div class="form-group">
                <label>Confirmar Palavra-passe</label>

                <input
                    class="input"
                    type="password"
                    name="confirmar_senha"
                    placeholder="Repete a palavra-passe"
                    required>
            </div>

            <button type="submit" class="btn btn-primary full-btn">
                Criar Conta
            </button>

        </form>

        <p class="auth-link">
            Já tens conta?
            <a href="login.php">Entrar</a>
        </p>

    </div>

</section>

</body>
</html>