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

        <form action="auth/login_processar.php" method="POST">

            <div class="form-group">
                <label>Email</label>
                <input class="input" type="email" name="email" placeholder="exemplo@email.com" required>
            </div>

            <div class="form-group">
                <label>Palavra-passe</label>
                <input class="input" type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary full-btn">
                Entrar
            </button>

        </form>

        <p class="auth-link">
            Ainda não tens conta?
            <a href="registo.php">Criar conta</a>
        </p>

    </div>

</section>

</body>
</html>