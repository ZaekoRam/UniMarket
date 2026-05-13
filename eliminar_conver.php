<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "msg" => "No autenticado"]);
    exit();
}

if ($_SESSION['rol'] === 'lector') {
    echo json_encode(["status" => "error", "msg" => "Los lectores no pueden eliminar conversaciones."]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$mi_id = $_SESSION['usuario_id'];
$destinatario_id = (int)$_POST['destinatario_id'];

// Eliminar todos los mensajes entre ambos usuarios
$sql = "DELETE FROM mensajes 
        WHERE (remitente_id = $mi_id AND destinatario_id = $destinatario_id) 
           OR (remitente_id = $destinatario_id AND destinatario_id = $mi_id)";

if (mysqli_query($conexion, $sql)) {
    echo json_encode(["status" => "ok", "msg" => "Conversación eliminada"]);
} else {
    echo json_encode(["status" => "error", "msg" => "Error al eliminar: " . mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>