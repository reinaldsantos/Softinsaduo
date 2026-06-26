<header class="header">

    <button class="menu-toggle" id="menuToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="breadcrumb">
        <strong><?php echo $tituloPagina ?? "Dashboard"; ?></strong>
    </div>

    <div class="header-right">

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text" placeholder="Pesquisar tarefas...">

        </div>

        <button class="btn btn-secondary">

            <i class="fa-regular fa-bell"></i>

        </button>

        <div class="avatar">

            <img src="imagens/icons/avatar.png" alt="Avatar">

        </div>

    </div>

</header>