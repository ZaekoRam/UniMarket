<?php
session_start();
require 'credenciales.php';
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}
$id = $_SESSION['usuario_id'];
$passActual = $_POST['password_actual'] ?? '';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$sql = "SELECT password FROM usuarios WHERE id = $id";
$res = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($res);
if (password_verify($passActual, $row['password'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Contraseña actual incorrecta']);
}
mysqli_close($conexion);
?>