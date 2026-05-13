<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit();
}

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'] ?? '';

// Si el usuario actual es lector, no debe ver ningún contacto
if ($mi_rol === 'lector') {
    echo json_encode([]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode([]);
    exit();
}

// 🔥 Excluir al propio usuario y a los lectores
$sql = "SELECT id, usuario, nombre_completo, rol 
        FROM usuarios 
        WHERE id != $mi_id AND rol != 'lector'
        ORDER BY nombre_completo ASC";

$resultado = mysqli_query($conexion, $sql);
$usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

echo json_encode($usuarios);
mysqli_close($conexion);
?>