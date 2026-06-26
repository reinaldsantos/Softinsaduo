<aside class="sidebar">

    <div class="logo">

        <img src="imagens/icons/logo.png" alt="Logo">

        <div class="logo-text">
            <h2>Gestor</h2>
            <small>De Tarefas</small>
        </div>

    </div>

    <nav class="menu">

        <div class="menu-title">Principal</div>

        <a href="dashboard.php" class="<?php echo (($tituloPagina ?? '') == 'Dashboard') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="tarefas.php" class="<?php echo (($tituloPagina ?? '') == 'Tarefas') ? 'active' : ''; ?>">
            <i class="fa-solid fa-list-check"></i>
            <span>Tarefas</span>
        </a>

        <a href="nova_tarefa.php" class="<?php echo (($tituloPagina ?? '') == 'Nova Tarefa') ? 'active' : ''; ?>">
            <i class="fa-solid fa-plus"></i>
            <span>Nova Tarefa</span>
        </a>

        <div class="menu-title">Conta</div>

        <a href="perfil.php" class="<?php echo (($tituloPagina ?? '') == 'Perfil') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user"></i>
            <span>Perfil</span>
        </a>

        <a href="#">
            <i class="fa-solid fa-gear"></i>
            <span>Definições</span>
        </a>

        <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Sair</span>
        </a>

    </nav>

</aside>