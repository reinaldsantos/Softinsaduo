<?php
$tituloPagina = "Editar Tarefa";
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

<p>Atualiza os dados da tarefa.</p>

</div>

<form action="#" method="POST">

<div class="form-group">

<label>Título</label>

<input
class="input"
type="text"
value="Atualizar Website">

</div>

<div class="form-group">

<label>Descrição</label>

<textarea
class="textarea"
rows="5">Melhorar a página inicial e corrigir erros.</textarea>

</div>

<div class="form-row">

<div class="form-group">

<label>Estado</label>

<select class="select">

<option>Por Fazer</option>

<option selected>Em Curso</option>

<option>Concluída</option>

</select>

</div>

<div class="form-group">

<label>Data Limite</label>

<input
class="input"
type="date"
value="2026-06-28">

</div>

</div>

<div class="form-actions">

<a
href="tarefas.php"
class="btn btn-secondary">

Cancelar

</a>

<button
class="btn btn-primary">

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