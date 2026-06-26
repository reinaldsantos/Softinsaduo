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
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos!';
    } else {
        // Buscar utilizador
        $sql = "SELECT id, nome, email, senha FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nome'] = $user['nome'];
                $_SESSION['email'] = $user['email'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $erro = 'Email ou senha inválidos!';
            }
        } else {
            $erro = 'Email ou senha inválidos!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestor de Tarefas</title>
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
        input[type="email"], input[type="password"] {
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
        .link { text-align: center; margin-top: 20px; color: #666; }
        .link a { color: #7B3FFC; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
        .teste-info {
            margin-top: 20px;
            padding: 12px;
            background: #f0f0f0;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Gestor de Tarefas</h1>
        <p class="subtitle">Faça login para continuar</p>
        
        <?php if ($erro): ?>
            <div class="error">⚠️ <?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn">Entrar</button>
        </form>
        
        <div class="link">
            Não tem uma conta? <a href="register.php">Criar conta</a>
        </div>
        
        <div class="teste-info">
            🔑 <strong>Teste:</strong> admin@teste.com / password
        </div>
    </div>
</body>
</html>