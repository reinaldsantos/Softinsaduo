<?php
session_start();
require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$user_id = $_SESSION['user_id'];
$erro = "";
$sucesso = "";

$tituloPagina = "Eliminar Conta";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $confirmar = $_POST["confirmar"] ?? "";
    $senha = $_POST["senha"] ?? "";
    
    if ($confirmar !== "ELIMINAR") {
        $erro = 'Escreve "ELIMINAR" para confirmar a eliminação da conta.';
    } else {
        // Verificar a senha do utilizador
        $sql = "SELECT senha FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($senha, $user['senha'])) {
            // Eliminar o utilizador (as tarefas são apagadas automaticamente por CASCADE)
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                // Terminar sessão
                session_destroy();
                
                // Redirecionar com mensagem de sucesso
                header("Location: login.php?conta_eliminada=1");
                exit();
            } else {
                $erro = "Erro ao eliminar a conta. Tenta novamente.";
            }
        } else {
            $erro = "Senha incorreta. Tenta novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Eliminar Conta</title>

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
                    <h1 style="color:#DC2626;"><i class="fa-solid fa-triangle-exclamation"></i> Eliminar Conta</h1>
                    <p>Esta ação é permanente e não pode ser desfeita.</p>
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

                <!-- Zona de Perigo -->
                <div style="border:2px solid #DC2626; border-radius:12px; padding:24px; background:#FEF2F2; margin-bottom:20px;">
                    <h2 style="color:#DC2626; font-size:18px; margin-bottom:8px;">
                        <i class="fa-solid fa-skull"></i> Zona de Perigo
                    </h2>
                    <p style="color:#6b7280; font-size:14px; margin-bottom:16px;">
                        Esta ação é <strong>permanente</strong>. A tua conta e todas as tarefas associadas serão removidas.
                    </p>
                </div>

                <form method="POST">

                    <div class="form-group">
                        <label style="color:#DC2626; font-weight:700;">
                            <i class="fa-solid fa-key"></i> Palavra-passe
                        </label>
                        <input class="input" type="password" name="senha" placeholder="Insere a tua palavra-passe" required>
                    </div>

                    <div class="form-group">
                        <label style="color:#DC2626; font-weight:700;">
                            <i class="fa-solid fa-check-circle"></i> Confirmação
                        </label>
                        <input class="input" type="text" name="confirmar" placeholder='Escreve "ELIMINAR" para confirmar' required>
                        <div class="form-helper" style="color:#6b7280; margin-top:4px;">
                            Escreve <strong>ELIMINAR</strong> (em maiúsculas) para confirmar a eliminação.
                        </div>
                    </div>

                    <div class="form-actions" style="justify-content:space-between;">

                        <a href="definicoes.php" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Voltar
                        </a>

                        <button type="submit" class="btn" style="background:#DC2626; color:white; border:none; padding:10px 24px; border-radius:8px; font-weight:700; cursor:pointer; transition:0.3s;"
                                onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='#DC2626'">
                            <i class="fa-solid fa-trash"></i> Eliminar Conta
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