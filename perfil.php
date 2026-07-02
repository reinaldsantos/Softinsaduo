<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once "config/database.php";
require_once "includes/auth.php";

protegerPagina();

$tituloPagina = "Perfil";

$user_id = $_SESSION["user_id"] ?? 0;

$erro = "";
$sucesso = "";

if ($user_id == 0) {
    header("Location: login.php");
    exit();
}

// Buscar os dados do utilizador
$sql = "SELECT id, nome, email, avatar, avatar_type FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: logout.php");
    exit();
}

$user = $result->fetch_assoc();

$user['nome'] = $user['nome'] ?? 'Utilizador';
$user['email'] = $user['email'] ?? '';
$user['avatar'] = $user['avatar'] ?? null;
$user['avatar_type'] = $user['avatar_type'] ?? 'default';

// Atualizar perfil
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $avatar_selecionado = $_POST["avatar"] ?? "";
    $tipo_avatar = $_POST["tipo_avatar"] ?? "galeria";
    $foto_camera = $_POST["foto_camera"] ?? "";
    
    $avatar = $user['avatar'];
    $avatar_type = $user['avatar_type'];

    if ($tipo_avatar === 'galeria' && !empty($avatar_selecionado)) {
        $avatar = $avatar_selecionado;
        $avatar_type = 'default';
    } elseif ($tipo_avatar === 'upload') {
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === 0) {
            $arquivo = $_FILES['foto_perfil'];
            $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $extensoes)) {
                if ($user['avatar_type'] == 'upload' && !empty($user['avatar'])) {
                    $caminho_antigo = 'uploads/avatars/' . $user['avatar'];
                    if (file_exists($caminho_antigo)) {
                        @unlink($caminho_antigo);
                    }
                }
                
                $novo_nome = 'user_' . $user_id . '_' . time() . '.' . $ext;
                $caminho = 'uploads/avatars/' . $novo_nome;
                
                if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                    $avatar = $novo_nome;
                    $avatar_type = 'upload';
                } else {
                    $erro = 'Erro ao fazer upload.';
                }
            } else {
                $erro = 'Formato não permitido. Use JPG, PNG, GIF ou WEBP.';
            }
        }
    } elseif ($tipo_avatar === 'camera' && !empty($foto_camera)) {
        $dados_foto = str_replace('data:image/png;base64,', '', $foto_camera);
        $dados_foto = str_replace(' ', '+', $dados_foto);
        $dados_decodificados = base64_decode($dados_foto);
        
        if ($dados_decodificados !== false) {
            if ($user['avatar_type'] == 'upload' && !empty($user['avatar'])) {
                $caminho_antigo = 'uploads/avatars/' . $user['avatar'];
                if (file_exists($caminho_antigo)) {
                    @unlink($caminho_antigo);
                }
            }
            
            $novo_nome = 'camera_' . $user_id . '_' . time() . '.png';
            $caminho = 'uploads/avatars/' . $novo_nome;
            
            if (file_put_contents($caminho, $dados_decodificados)) {
                $avatar = $novo_nome;
                $avatar_type = 'upload';
            }
        }
    }

    $sql = "UPDATE users SET avatar=?, avatar_type=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $avatar, $avatar_type, $user_id);

    if ($stmt->execute()) {
        $user["avatar"] = $avatar;
        $user["avatar_type"] = $avatar_type;
        $sucesso = "Avatar atualizado com sucesso!";
        // Recarregar a página para mostrar o novo avatar
        header("Refresh:0");
        exit();
    } else {
        $erro = "Erro ao atualizar o avatar.";
    }
}

// Lista de avatares da galeria
$avatares = [];
$pasta_avatars = 'assets/avatars/';
if (is_dir($pasta_avatars)) {
    $arquivos = scandir($pasta_avatars);
    foreach ($arquivos as $arquivo) {
        $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
        if (in_array($extensao, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
            $avatares[] = $arquivo;
        }
    }
}
if (empty($avatares)) {
    $avatares = ['avatar1.jpeg'];
}

// Caminho do avatar atual
$avatar_atual = 'assets/avatars/avatar1.jpeg';
if ($user['avatar_type'] == 'upload' && !empty($user['avatar'])) {
    $avatar_atual = 'uploads/avatars/' . $user['avatar'];
} elseif (!empty($user['avatar'])) {
    $avatar_atual = 'assets/avatars/' . $user['avatar'];
}

// Verificar se o ficheiro existe, senão usar fallback
if (!file_exists($avatar_atual)) {
    $avatar_atual = 'assets/avatars/avatar1.jpeg';
}
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
    
    <style>
        .avatar-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .avatar-tabs .tab-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            flex: 1;
            min-width: 80px;
            text-align: center;
            font-size: 14px;
        }
        .avatar-tabs .tab-btn.active {
            border-color: #7B3FFC;
            background: #F0EBFF;
            color: #7B3FFC;
        }
        .avatar-tabs .tab-btn:hover {
            border-color: #7B3FFC;
        }
        .tab-panel {
            display: none;
            padding: 12px 0;
        }
        .tab-panel.active {
            display: block;
        }
        
        .avatar-atual {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 16px;
        }
        .avatar-atual img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7B3FFC;
        }
        .avatar-atual .info {
            flex: 1;
        }
        .avatar-atual .info small {
            color: #7B3FFC;
            font-weight: 600;
        }
        
        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 10px;
        }
        .avatar-grid .avatar-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px;
            border: 3px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        .avatar-grid .avatar-item:hover {
            transform: scale(1.05);
            border-color: #7B3FFC;
        }
        .avatar-grid .avatar-item.selected {
            border-color: #7B3FFC;
            background: #F0EBFF;
        }
        .avatar-grid .avatar-item img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-grid .avatar-item input[type="radio"] {
            display: none;
        }
        .avatar-grid .avatar-item .name {
            font-size: 10px;
            color: #6b7280;
            margin-top: 4px;
        }
        
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #7B3FFC;
            background: #f9fafb;
        }
        .upload-area input[type="file"] {
            display: none;
        }
        .upload-area .icon {
            font-size: 40px;
        }
        .upload-preview {
            display: none;
            text-align: center;
            margin-top: 12px;
        }
        .upload-preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7B3FFC;
        }
        
        .camera-container {
            text-align: center;
        }
        .camera-container video {
            width: 100%;
            max-width: 320px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            background: #000;
        }
        .camera-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 12px;
            flex-wrap: wrap;
        }
        .camera-buttons .btn {
            min-width: 120px;
        }
        .camera-preview {
            display: none;
            text-align: center;
            margin-top: 12px;
        }
        .camera-preview img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #7B3FFC;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 20px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>

<div class="app">

    <?php include "includes/sidebar.php"; ?>

    <main class="main">

        <?php include "includes/header.php"; ?>

        <section class="form-page">

            <div class="form-card card">

                <div class="form-title">
                    <h1><i class="fa-solid fa-user-circle" style="color:#7B3FFC;"></i> Perfil</h1>
                    <p>Altera a tua foto de perfil.</p>
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

                <form method="POST" enctype="multipart/form-data">

                    <!-- Avatar Atual -->
                    <div class="avatar-atual">
                        <img src="<?php echo $avatar_atual; ?>" 
                             alt="Avatar"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2280%22 height=%2280%22%3E%3Crect fill=%22%237B3FFC%22 width=%2280%22 height=%2280%22/%3E%3Ctext x=%2240%22 y=%2240%22 text-anchor=%22middle%22 dy=%22.35em%22 fill=%22white%22 font-size=%2236%22 font-family=%22Arial%22%3E<?php echo strtoupper(substr($user['nome'] ?? 'U', 0, 1)); ?>%3C/text%3E%3C/svg%3E'">
                        <div class="info">
                            <strong><?php echo htmlspecialchars($user['nome'] ?? 'Utilizador'); ?></strong>
                            <br>
                            <small>
                                <?php if (($user['avatar_type'] ?? 'default') == 'upload'): ?>
                                    📸 Foto personalizada
                                <?php else: ?>
                                    🎨 Avatar da galeria
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>

                    <input type="hidden" name="tipo_avatar" id="tipo_avatar" value="galeria">
                    <input type="hidden" name="foto_camera" id="foto_camera" value="">

                    <div class="form-group" style="margin-top:20px; border-top:1px solid #e5e7eb; padding-top:20px;">
                        <label style="font-size:16px; font-weight:600; display:block; margin-bottom:12px;">
                            <i class="fa-solid fa-image"></i> Escolher novo avatar
                        </label>
                        
                        <div class="avatar-tabs">
                            <button type="button" class="tab-btn active" data-tab="galeria" onclick="mostrarTab('galeria')">
                                <i class="fa-solid fa-images"></i> Galeria
                            </button>
                            <button type="button" class="tab-btn" data-tab="camera" onclick="mostrarTab('camera')">
                                <i class="fa-solid fa-camera"></i> Câmera
                            </button>
                            <button type="button" class="tab-btn" data-tab="upload" onclick="mostrarTab('upload')">
                                <i class="fa-solid fa-upload"></i> Upload
                            </button>
                        </div>
                        
                        <div id="tab-galeria" class="tab-panel active">
                            <div class="avatar-grid">
                                <?php foreach ($avatares as $avatar): ?>
                                    <label class="avatar-item <?php echo (($user['avatar_type'] ?? 'default') == 'default' && ($user['avatar'] ?? '') == $avatar) ? 'selected' : ''; ?>">
                                        <input type="radio" name="avatar" value="<?php echo $avatar; ?>" 
                                               <?php echo (($user['avatar_type'] ?? 'default') == 'default' && ($user['avatar'] ?? '') == $avatar) ? 'checked' : ''; ?>
                                               onchange="selecionarAvatar(this)">
                                        <img src="assets/avatars/<?php echo $avatar; ?>" 
                                             alt="Avatar"
                                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22%3E%3Crect fill=%22%237B3FFC%22 width=%2256%22 height=%2256%22/%3E%3Ctext x=%2228%22 y=%2228%22 text-anchor=%22middle%22 dy=%22.35em%22 fill=%22white%22 font-size=%2224%22 font-family=%22Arial%22%3E<?php echo strtoupper(substr($avatar, 6, 1)); ?>%3C/text%3E%3C/svg%3E'">
                                        <span class="name"><?php echo pathinfo($avatar, PATHINFO_FILENAME); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div id="tab-camera" class="tab-panel">
                            <div class="camera-container">
                                <video id="video" autoplay playsinline style="display:none;"></video>
                                <div class="camera-buttons">
                                    <button type="button" class="btn btn-secondary" onclick="iniciarCamera()">
                                        <i class="fa-solid fa-play"></i> Iniciar
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="tirarFoto()">
                                        <i class="fa-solid fa-camera"></i> Tirar Foto
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="pararCamera()">
                                        <i class="fa-solid fa-stop"></i> Parar
                                    </button>
                                </div>
                                <div class="camera-preview" id="cameraPreview">
                                    <img id="capturedImage" src="" alt="Foto capturada">
                                    <p style="font-size:12px; color:#6b7280; margin-top:8px;">Foto capturada</p>
                                    <button type="button" class="btn btn-secondary" onclick="refazerFoto()">
                                        <i class="fa-solid fa-rotate-left"></i> Refazer
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="tab-upload" class="tab-panel">
                            <div class="upload-area" onclick="document.getElementById('foto_perfil').click()">
                                <div class="icon"><i class="fa-solid fa-cloud-upload-alt"></i></div>
                                <p><strong>Clique para escolher uma foto</strong></p>
                                <p style="font-size:12px; color:#6b7280;">Formatos: JPG, PNG, GIF, WEBP</p>
                                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewUpload(this)">
                            </div>
                            <div class="upload-preview" id="uploadPreview">
                                <img id="previewImg" src="" alt="Pré-visualização">
                                <p style="font-size:12px; color:#6b7280; margin-top:8px;">Pré-visualização</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Atualizar Avatar
                        </button>
                    </div>

                </form>

            </div>

        </section>

        <?php include "includes/footer.php"; ?>

    </main>

</div>

<script src="js/menu.js"></script>

<script>
    function mostrarTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        document.getElementById('tab-' + tab).classList.add('active');
        document.querySelector(`.tab-btn[data-tab="${tab}"]`).classList.add('active');
        
        document.getElementById('tipo_avatar').value = tab;
        
        if (tab !== 'camera') {
            pararCamera();
        }
    }

    function selecionarAvatar(element) {
        document.querySelectorAll('.avatar-item').forEach(el => el.classList.remove('selected'));
        element.closest('.avatar-item').classList.add('selected');
        document.getElementById('tipo_avatar').value = 'galeria';
    }

    let stream = null;
    let video = document.getElementById('video');

    async function iniciarCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'user', width: 320, height: 320 },
                audio: false 
            });
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('cameraPreview').style.display = 'none';
        } catch (err) {
            alert('Não foi possível aceder à câmera: ' + err.message);
        }
    }

    function tirarFoto() {
        if (!stream) {
            alert('Inicia a câmera primeiro!');
            return;
        }
        
        const canvas = document.createElement('canvas');
        canvas.width = 320;
        canvas.height = 320;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, 320, 320);
        
        const dataUrl = canvas.toDataURL('image/png');
        
        document.getElementById('capturedImage').src = dataUrl;
        document.getElementById('cameraPreview').style.display = 'block';
        document.getElementById('foto_camera').value = dataUrl;
        document.getElementById('tipo_avatar').value = 'camera';
        
        pararCamera();
    }

    function pararCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            video.srcObject = null;
            video.style.display = 'none';
        }
    }

    function refazerFoto() {
        document.getElementById('cameraPreview').style.display = 'none';
        document.getElementById('foto_camera').value = '';
        iniciarCamera();
    }

    function previewUpload(input) {
        const preview = document.getElementById('uploadPreview');
        const img = document.getElementById('previewImg');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('tipo_avatar').value = 'upload';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>