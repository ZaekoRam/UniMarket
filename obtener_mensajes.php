<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["mi_id" => 0, "mensajes" => []]);
    exit();
}

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'] ?? '';

// 🔥 Los lectores NO pueden ver mensajes
if ($mi_rol === 'lector') {
    echo json_encode(["mi_id" => $mi_id, "mensajes" => []]);
    exit();
}

if (!isset($_GET['con_quien'])) {
    echo json_encode(["mi_id" => $mi_id, "mensajes" => []]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(["mi_id" => $mi_id, "mensajes" => []]);
    exit();
}

$con_quien = (int)$_GET['con_quien'];

// Verificar que el destinatario NO sea lector (opcional pero recomendado)
$check = mysqli_query($conexion, "SELECT rol FROM usuarios WHERE id = $con_quien");
$dest_rol = mysqli_fetch_assoc($check)['rol'] ?? '';
if ($dest_rol === 'lector') {
    echo json_encode(["mi_id" => $mi_id, "mensajes" => []]);
    mysqli_close($conexion);
    exit();
}

$sql = "SELECT m.*, u.usuario, u.nombre_completo 
        FROM mensajes m 
        JOIN usuarios u ON m.remitente_id = u.id 
        WHERE (m.remitente_id = $mi_id AND m.destinatario_id = $con_quien) 
           OR (m.remitente_id = $con_quien AND m.destinatario_id = $mi_id)
        ORDER BY m.fecha ASC";

$resultado = mysqli_query($conexion, $sql);
$mensajes = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

echo json_encode([
    "mi_id" => $mi_id,
    "mensajes" => $mensajes
]);
mysqli_close($conexion);
?>