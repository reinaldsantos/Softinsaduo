<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

protegerPagina();

$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Verificar se a tarefa pertence ao utilizador
$sql = "SELECT id FROM tasks WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Marcar como concluída
    $sql = "UPDATE tasks SET status = 'concluida' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
}

header('Location: ../dashboard.php?sucesso=Tarefa concluída!');
exit();
?>