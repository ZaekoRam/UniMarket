<?php
session_start();
require 'credenciales.php';
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}
$id = $_SESSION['usuario_id'];
$nuevoUsuario = trim($_POST['usuario'] ?? '');
if (strlen($nuevoUsuario) < 3) {
    echo json_encode(['success' => false, 'error' => 'El usuario debe tener al menos 3 caracteres']);
    exit;
}
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$nuevoUsuario = mysqli_real_escape_string($conexion, $nuevoUsuario);
$check = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$nuevoUsuario' AND id != $id");
if (mysqli_num_rows($check) > 0) {
    echo json_encode(['success' => false, 'error' => 'El nombre de usuario ya está en uso']);
    exit;
}
$sql = "UPDATE usuarios SET usuario = '$nuevoUsuario' WHERE id = $id";
if (mysqli_query($conexion, $sql)) {
    $_SESSION['usuario'] = $nuevoUsuario; // Actualizar sesión
    echo json_encode(['success' => true, 'usuario' => $nuevoUsuario]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
}
mysqli_close($conexion);
?>