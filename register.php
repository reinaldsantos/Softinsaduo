<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Se já estiver logado, redirecionar para dashboard
redirecionarSeLogado();

$erro = '';
$sucesso = '';
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = 'Todos os campos são obrigatórios!';
    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Por favor, insira um email válido!';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres!';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem!';
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $erro = 'Este email já está registado!';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nome, $email, $senha_hash);
            
            if ($stmt->execute()) {
                $sucesso = 'Conta criada com sucesso! Faça login para continuar.';
                $nome = '';
                $email = '';
            } else {
                $erro = 'Erro ao criar conta. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo - Gestor de Tarefas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: #f5f5f5; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container { 
            background: white; 
            padding: 40px; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 { text-align: center; margin-bottom: 10px; color: #333; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        input:focus { outline: none; border-color: #7B3FFC; }
        .btn {
            width: 100%;
            padding: 12px;
            background: #7B3FFC;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover { background: #5E2AD9; }
        .error { 
            background: #fee; 
            color: #c00; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px;
            border-left: 4px solid #c00;
        }
        .success {
            background: #efe;
            color: #060; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px;
            border-left: 4px solid #060;
        }
        .link { text-align: center; margin-top: 20px; color: #666; }
        .link a { color: #7B3FFC; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
        .form-helper { font-size: 12px; color: #999; margin-top: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Gestor de Tarefas</h1>
        <p class="subtitle">Crie a sua conta gratuita</p>
        
        <?php if ($erro): ?>
            <div class="error">⚠️ <?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if ($sucesso): ?>
            <div class="success">✅ <?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
                <div class="form-helper">A senha deve ter pelo menos 6 caracteres</div>
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Digite a senha novamente" required>
            </div>
            
            <button type="submit" class="btn">Criar Conta</button>
        </form>
        
        <div class="link">
            Já tem uma conta? <a href="login.php">Fazer login</a>
        </div>
    </div>
</body>
</html>