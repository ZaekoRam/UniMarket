<?php
session_start();
header('Content-Type: application/json');
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

$mostrar = isset($_POST['mostrar_estado']) ? (int)$_POST['mostrar_estado'] : 1;
$id = $_SESSION['usuario_id'];

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$query = "UPDATE usuarios SET mostrar_estado = $mostrar WHERE id = $id";
if (mysqli_query($conexion, $query)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
}
?>