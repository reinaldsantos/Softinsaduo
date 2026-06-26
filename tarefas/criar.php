<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

protegerPagina();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'] ?? 'pendente';
    $data_limite = $_POST['data_limite'] ?? null;
    
    if (empty($titulo)) {
        $erro = 'O título da tarefa é obrigatório!';
    } else {
        $sql = "INSERT INTO tasks (user_id, titulo, descricao, status, data_limite) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $titulo, $descricao, $status, $data_limite);
        
        if ($stmt->execute()) {
            header('Location: ../dashboard.php?sucesso=Tarefa criada com sucesso!');
            exit();
        } else {
            $erro = 'Erro ao criar tarefa. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Tarefa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-control:focus { outline: none; border-color: #7B3FFC; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #7B3FFC;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: #5E2AD9; }
        .btn-secondary { background: #999; }
        .btn-secondary:hover { background: #777; }
        .error { background: #fee; color: #c00; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .actions { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>➕ Nova Tarefa</h1>
            
            <?php if ($erro): ?>
                <div class="error">⚠️ <?php echo $erro; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="titulo">Título *</label>
                    <input type="text" id="titulo" name="titulo" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao" class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="data_limite">Data Limite</label>
                    <input type="date" id="data_limite" name="data_limite" class="form-control">
                </div>
                
                <div class="actions">
                    <button type="submit" class="btn">💾 Salvar</button>
                    <a href="../dashboard.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>