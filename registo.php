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

        <form action="auth/registo_processar.php" method="POST">

            <div class="form-group">
                <label>Nome</label>
                <input class="input" type="text" name="nome" placeholder="Nome completo" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="input" type="email" name="email" placeholder="exemplo@email.com" required>
            </div>

            <div class="form-group">
                <label>Palavra-passe</label>
                <input class="input" type="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label>Confirmar Palavra-passe</label>
                <input class="input" type="password" name="confirmar" placeholder="••••••••" required>
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