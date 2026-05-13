<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) exit();

// 🔥 Verificar que el remitente NO sea lector
if ($_SESSION['rol'] === 'lector') {
    echo json_encode(["status" => "error", "msg" => "Los lectores no pueden enviar mensajes."]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$mi_id = $_SESSION['usuario_id'];
$destinatario_id = (int)$_POST['destinatario_id'];
$mensaje = trim(mysqli_real_escape_string($conexion, $_POST['mensaje'] ?? ''));

if (!empty($mensaje) && $destinatario_id > 0) {
    // Opcional: también verificar que el destinatario no sea lector? (según requieras)
    // Si no quieres que los lectores reciban mensajes, puedes agregar:
    $check = mysqli_query($conexion, "SELECT rol FROM usuarios WHERE id = $destinatario_id");
    $dest_rol = mysqli_fetch_assoc($check)['rol'] ?? '';
    if ($dest_rol === 'lector') {
        echo json_encode(["status" => "error", "msg" => "No puedes enviar mensajes a un lector."]);
        exit();
    }
    
    $sql = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje) VALUES ('$mi_id', '$destinatario_id', '$mensaje')";
    mysqli_query($conexion, $sql);
    echo json_encode(["status" => "ok"]);
}
mysqli_close($conexion);
?>