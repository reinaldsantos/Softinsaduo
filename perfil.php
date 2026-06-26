<?php
$tituloPagina = "Perfil";
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

                <form action="#" method="POST">

                    <div class="form-row">

                        <div class="form-group">
                            <label>Nome</label>
                            <input class="input" type="text" value="Rodrigo Afonso">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="input" type="email" value="rodrigo@email.com">
                        </div>

                    </div>

                    <div class="form-row">

                        <div class="form-group">
                            <label>Nova Palavra-passe</label>
                            <input class="input" type="password" placeholder="Nova palavra-passe">
                        </div>

                        <div class="form-group">
                            <label>Confirmar Palavra-passe</label>
                            <input class="input" type="password" placeholder="Confirmar palavra-passe">
                        </div>

                    </div>

                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>

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