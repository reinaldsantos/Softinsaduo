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
$avatar_selecionado = "";

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
    $avatares = ['avatar1.png', 'avatar2.png', 'avatar3.png', 'avatar4.png', 'avatar5.png'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar_senha = $_POST["confirmar_senha"] ?? "";
    $avatar_selecionado = $_POST["avatar"] ?? "";
    $tipo_avatar = $_POST["tipo_avatar"] ?? "galeria";
    $foto_camera = $_POST["foto_camera"] ?? "";

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
            $avatar = '';
            $avatar_type = 'default';

            // Processar conforme o tipo escolhido
            if ($tipo_avatar === 'galeria') {
                $avatar = $avatar_selecionado ?: $avatares[0];
                $avatar_type = 'default';
            } elseif ($tipo_avatar === 'upload') {
                // Upload de ficheiro
                if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === 0) {
                    $arquivo = $_FILES['foto_perfil'];
                    $extensoes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    $ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
                    
                    if (in_array($ext, $extensoes)) {
                        $novo_nome = 'user_' . time() . '_' . uniqid() . '.' . $ext;
                        $caminho = 'uploads/avatars/' . $novo_nome;
                        
                        if (move_uploaded_file($arquivo['tmp_name'], $caminho)) {
                            $avatar = $novo_nome;
                            $avatar_type = 'upload';
                        }
                    }
                }
            } elseif ($tipo_avatar === 'camera') {
                // Foto da câmera (dados base64)
                if (!empty($foto_camera)) {
                    // Remover o cabeçalho "data:image/png;base64,"
                    $dados_foto = str_replace('data:image/png;base64,', '', $foto_camera);
                    $dados_foto = str_replace(' ', '+', $dados_foto);
                    $dados_decodificados = base64_decode($dados_foto);
                    
                    if ($dados_decodificados !== false) {
                        $novo_nome = 'camera_' . time() . '_' . uniqid() . '.png';
                        $caminho = 'uploads/avatars/' . $novo_nome;
                        
                        if (file_put_contents($caminho, $dados_decodificados)) {
                            $avatar = $novo_nome;
                            $avatar_type = 'upload';
                        }
                    }
                }
            }

            // Se não tiver avatar, usar o primeiro da galeria
            if (empty($avatar)) {
                $avatar = $avatares[0] ?? 'avatar1.png';
                $avatar_type = 'default';
            }

            $sql = "INSERT INTO users (nome, email, senha, avatar, avatar_type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nome, $email, $senha_hash, $avatar, $avatar_type);

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
        
        /* Galeria */
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
        
        /* Upload */
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
        
        /* Câmera */
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
        .hidden {
            display: none;
        }
    </style>
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

        <form method="POST" enctype="multipart/form-data" id="registerForm">

            <div class="form-group">
                <label>Nome</label>
                <input class="input" type="text" name="nome" placeholder="Nome completo" 
                       value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input class="input" type="email" name="email" placeholder="exemplo@email.com" 
                       value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label>Palavra-passe</label>
                <input class="input" type="password" name="senha" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="form-group">
                <label>Confirmar Palavra-passe</label>
                <input class="input" type="password" name="confirmar_senha" placeholder="Repete a palavra-passe" required>
            </div>

            <!-- Avatar -->
            <div class="form-group">
                <label>Foto de Perfil</label>
                
                <input type="hidden" name="tipo_avatar" id="tipo_avatar" value="galeria">
                <input type="hidden" name="foto_camera" id="foto_camera" value="">
                
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
                
                <!-- Tab: Galeria -->
                <div id="tab-galeria" class="tab-panel active">
                    <div class="avatar-grid">
                        <?php foreach ($avatares as $avatar): ?>
                            <label class="avatar-item <?php echo $avatar == $avatar_selecionado ? 'selected' : ''; ?>">
                                <input type="radio" name="avatar" value="<?php echo $avatar; ?>" 
                                       <?php echo $avatar == $avatar_selecionado ? 'checked' : ''; ?>
                                       onchange="selecionarAvatar(this)">
                                <img src="assets/avatars/<?php echo $avatar; ?>" 
                                     alt="Avatar"
                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2256%22 height=%2256%22%3E%3Crect fill=%22%237B3FFC%22 width=%2256%22 height=%2256%22/%3E%3Ctext x=%2228%22 y=%2228%22 text-anchor=%22middle%22 dy=%22.35em%22 fill=%22white%22 font-size=%2224%22 font-family=%22Arial%22%3E<?php echo strtoupper(substr($avatar, 6, 1)); ?>%3C/text%3E%3C/svg%3E'">
                                <span class="name"><?php echo pathinfo($avatar, PATHINFO_FILENAME); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Tab: Câmera -->
                <div id="tab-camera" class="tab-panel">
                    <div class="camera-container">
                        <video id="video" autoplay playsinline></video>
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
                
                <!-- Tab: Upload -->
                <div id="tab-upload" class="tab-panel">
                    <div class="upload-area" onclick="document.getElementById('foto_perfil').click()">
                        <div class="icon"><i class="fa-solid fa-cloud-upload-alt"></i></div>
                        <p><strong>Clique para escolher uma foto</strong></p>
                        <p style="font-size:12px; color:#6b7280;">Formatos: JPG, PNG, GIF, WEBP (máx 5MB)</p>
                        <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewUpload(this)">
                    </div>
                    <div class="upload-preview" id="uploadPreview">
                        <img id="previewImg" src="" alt="Pré-visualização">
                        <p style="font-size:12px; color:#6b7280; margin-top:8px;">Pré-visualização</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary full-btn">
                <i class="fa-solid fa-user-plus"></i> Criar Conta
            </button>

        </form>

        <p class="auth-link">
            Já tens conta?
            <a href="login.php">Entrar</a>
        </p>

    </div>

</section>

<script>
    // Variáveis para a câmera
    let stream = null;
    let video = document.getElementById('video');
    let fotoCapturada = false;

    // --- Funções das Tabs ---
    function mostrarTab(tab) {
        // Esconder todas as tabs
        document.querySelectorAll('.tab-panel').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        // Mostrar a tab selecionada
        document.getElementById('tab-' + tab).classList.add('active');
        document.querySelector(`.tab-btn[data-tab="${tab}"]`).classList.add('active');
        
        // Atualizar o tipo de avatar
        document.getElementById('tipo_avatar').value = tab;
        
        // Parar a câmera se não estiver na tab câmera
        if (tab !== 'camera') {
            pararCamera();
        }
    }

    // --- Funções para selecionar avatar ---
    function selecionarAvatar(element) {
        // Remover seleção anterior
        document.querySelectorAll('.avatar-item').forEach(el => el.classList.remove('selected'));
        // Adicionar seleção no atual
        element.closest('.avatar-item').classList.add('selected');
        document.getElementById('tipo_avatar').value = 'galeria';
    }

    // --- Funções da Câmera ---
    async function iniciarCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'user', width: 320, height: 320 },
                audio: false 
            });
            video.srcObject = stream;
            video.style.display = 'block';
            document.getElementById('cameraPreview').style.display = 'none';
            fotoCapturada = false;
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
        
        // Converter para base64
        const dataUrl = canvas.toDataURL('image/png');
        
        // Mostrar preview
        document.getElementById('capturedImage').src = dataUrl;
        document.getElementById('cameraPreview').style.display = 'block';
        document.getElementById('foto_camera').value = dataUrl;
        document.getElementById('tipo_avatar').value = 'camera';
        fotoCapturada = true;
        
        // Parar a câmera após tirar a foto
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
        fotoCapturada = false;
        iniciarCamera();
    }

    // --- Funções Upload ---
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

    // --- Iniciar câmera automaticamente ao mudar para a tab ---
    // (Já feito no mostrarTab)
</script>

</body>
</html>