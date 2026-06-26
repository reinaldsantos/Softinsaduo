<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Proteger página
protegerPagina();

$user_id = $_SESSION['user_id'];
$nome = $_SESSION['nome'];

// Buscar estatísticas
$stats = [
    'total' => 0,
    'pendente' => 0,
    'em_andamento' => 0,
    'concluida' => 0
];

$sql = "SELECT status, COUNT(*) as count FROM tasks WHERE user_id = ? GROUP BY status";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $stats[$row['status']] = $row['count'];
    $stats['total'] += $row['count'];
}

// Buscar tarefas
$sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY criado_em DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$tarefas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestor de Tarefas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { color: #333; }
        .header .user { color: #666; }
        .header .logout { color: #e74c3c; text-decoration: none; }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card .number { font-size: 32px; font-weight: bold; color: #7B3FFC; }
        .stat-card .label { color: #666; margin-top: 5px; }
        .stat-card.pendente .number { color: #f39c12; }
        .stat-card.em_andamento .number { color: #3498db; }
        .stat-card.concluida .number { color: #2ecc71; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #7B3FFC;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: #5E2AD9; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #2ecc71; }
        .btn-success:hover { background: #27ae60; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .tasks-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .tasks-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .task-item {
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .task-item:hover { background: #f9f9f9; }
        .task-info .title { font-weight: bold; color: #333; }
        .task-info .desc { color: #666; font-size: 14px; margin-top: 5px; }
        .task-info .meta { margin-top: 5px; font-size: 12px; color: #999; }
        .task-status {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .task-status.pendente { background: #fef3c7; color: #92400e; }
        .task-status.em_andamento { background: #dbeafe; color: #1e40af; }
        .task-status.concluida { background: #d1fae5; color: #065f46; }
        .task-actions { display: flex; gap: 8px; }
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state .icon { font-size: 48px; margin-bottom: 10px; }
        .atrasada { color: #e74c3c; font-size: 12px; }
        @media (max-width: 768px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            .header { flex-direction: column; gap: 10px; }
            .task-item { flex-direction: column; gap: 10px; }
            .task-actions { width: 100%; justify-content: flex-end; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📋 Gestor de Tarefas</h1>
                <span class="user">👋 Olá, <?php echo htmlspecialchars($nome); ?></span>
            </div>
            <div>
                <a href="logout.php" class="logout">🚪 Sair</a>
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="number"><?php echo $stats['total']; ?></div>
                <div class="label">Total</div>
            </div>
            <div class="stat-card pendente">
                <div class="number"><?php echo $stats['pendente']; ?></div>
                <div class="label">Pendentes</div>
            </div>
            <div class="stat-card em_andamento">
                <div class="number"><?php echo $stats['em_andamento']; ?></div>
                <div class="label">Em Andamento</div>
            </div>
            <div class="stat-card concluida">
                <div class="number"><?php echo $stats['concluida']; ?></div>
                <div class="label">Concluídas</div>
            </div>
        </div>
        
        <div class="tasks-section">
            <div class="tasks-header">
                <h2>📋 Minhas Tarefas</h2>
                <a href="tarefas/criar.php" class="btn">+ Nova Tarefa</a>
            </div>
            
            <?php if ($tarefas->num_rows > 0): ?>
                <?php while($task = $tarefas->fetch_assoc()): ?>
                    <?php 
                        $hoje = date('Y-m-d');
                        $atrasada = ($task['data_limite'] && $task['data_limite'] < $hoje && $task['status'] != 'concluida');
                    ?>
                    <div class="task-item">
                        <div class="task-info">
                            <div class="title">
                                <?php echo htmlspecialchars($task['titulo']); ?>
                                <?php if($atrasada): ?>
                                    <span class="atrasada">⚠️ Atrasada</span>
                                <?php endif; ?>
                            </div>
                            <?php if($task['descricao']): ?>
                                <div class="desc"><?php echo htmlspecialchars($task['descricao']); ?></div>
                            <?php endif; ?>
                            <div class="meta">
                                <span class="task-status <?php echo $task['status']; ?>">
                                    <?php echo $task['status']; ?>
                                </span>
                                <?php if($task['data_limite']): ?>
                                    <span style="margin-left:10px;">📅 <?php echo date('d/m/Y', strtotime($task['data_limite'])); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="task-actions">
                            <?php if($task['status'] != 'concluida'): ?>
                                <a href="tarefas/concluir.php?id=<?php echo $task['id']; ?>" 
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Marcar como concluída?')">
                                    ✓ Concluir
                                </a>
                            <?php endif; ?>
                            <a href="tarefas/editar.php?id=<?php echo $task['id']; ?>" 
                               class="btn btn-sm">
                                ✏️ Editar
                            </a>
                            <a href="tarefas/apagar.php?id=<?php echo $task['id']; ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja apagar?')">
                                🗑️ Apagar
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">📭</div>
                    <p>Nenhuma tarefa encontrada</p>
                    <p style="font-size:14px; margin-top:10px;">Comece criando a sua primeira tarefa!</p>
                    <a href="tarefas/criar.php" class="btn" style="margin-top:15px;">+ Criar Tarefa</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>