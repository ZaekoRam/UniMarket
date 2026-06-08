<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "msg" => "No autenticado"]);
    exit();
}

$mi_id = (int)$_SESSION['usuario_id'];
$mensaje_id = (int)$_POST['mensaje_id'];

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

// Solo el remitente puede eliminar
$sql = "DELETE FROM mensajes WHERE id = $mensaje_id AND remitente_id = $mi_id";
if (mysqli_query($conexion, $sql)) {
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "msg" => mysqli_error($conexion)]);
}
mysqli_close($conexion);
?>