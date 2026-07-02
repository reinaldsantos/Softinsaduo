<?php
// includes/header.php
$tituloPagina = $tituloPagina ?? "Dashboard";
$nome = $_SESSION['nome'] ?? 'Utilizador';
$user_id = $_SESSION['user_id'] ?? 0;

// Buscar o avatar do utilizador
$avatar = 'imagens/icons/avatar.png';

if ($user_id > 0) {
    require_once "config/database.php";
    $sql = "SELECT avatar, avatar_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Caminho ABSOLUTO para o avatar (a partir da raiz do site)
        if ($user['avatar_type'] == 'upload' && !empty($user['avatar'])) {
            // Caminho absoluto: /gestor-tarefas/uploads/avatars/NOME
            $avatar = '/gestor-tarefas/uploads/avatars/' . $user['avatar'];
        } elseif (!empty($user['avatar'])) {
            $avatar = '/gestor-tarefas/assets/avatars/' . $user['avatar'];
        }
    }
}
?>

<header class="header">

    <button class="menu-toggle" id="menuToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="breadcrumb">
        <strong><?php echo $tituloPagina; ?></strong>
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
            <img src="<?php echo $avatar; ?>" 
                 alt="Avatar" 
                 style="width:36px; height:36px; border-radius:50%; object-fit:cover; border:2px solid #7B3FFC;"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2236%22 height=%2236%22%3E%3Crect fill=%22%237B3FFC%22 width=%2236%22 height=%2236%22/%3E%3Ctext x=%2218%22 y=%2218%22 text-anchor=%22middle%22 dy=%22.35em%22 fill=%22white%22 font-size=%2218%22 font-family=%22Arial%22%3E<?php echo strtoupper(substr($nome, 0, 1)); ?>%3C/text%3E%3C/svg%3E'">
        </div>

    </div>

</header>