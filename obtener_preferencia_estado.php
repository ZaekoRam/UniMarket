<?php
session_start();
header('Content-Type: application/json');
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['mostrar_estado' => 1]);
    exit;
}

$id = $_SESSION['usuario_id'];
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$query = "SELECT mostrar_estado FROM usuarios WHERE id = $id";
$result = mysqli_query($conexion, $query);
if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode(['mostrar_estado' => (int)$row['mostrar_estado']]);
} else {
    echo json_encode(['mostrar_estado' => 1]);
}
?>