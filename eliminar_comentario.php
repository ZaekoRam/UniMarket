<?php
session_start();
require 'credenciales.php';

ob_clean(); // Limpiar cualquier salida previa
header('Content-Type: application/json');
error_reporting(0);

$response = ["status" => "error", "msg" => "Error desconocido"];

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "msg" => "No autenticado"]);
    exit();
}

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'] ?? '';
$comentario_id = (int)$_POST['comentario_id'];

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(["status" => "error", "msg" => "Error de conexión a BD"]);
    exit();
}

$query = "SELECT usuario_id FROM comentarios WHERE id = $comentario_id";
$res = mysqli_query($conexion, $query);
if (!$res) {
    echo json_encode(["status" => "error", "msg" => "Error en consulta"]);
    mysqli_close($conexion);
    exit();
}

$comentario = mysqli_fetch_assoc($res);
if (!$comentario) {
    echo json_encode(["status" => "error", "msg" => "Comentario no encontrado"]);
    mysqli_close($conexion);
    exit();
}

if ($mi_id == $comentario['usuario_id'] || $mi_rol == 'admin') {
    $sql = "DELETE FROM comentarios WHERE id = $comentario_id";
    if (mysqli_query($conexion, $sql)) {
        $response = ["status" => "ok"];
    } else {
        $response = ["status" => "error", "msg" => "Error al eliminar"];
    }
} else {
    $response = ["status" => "error", "msg" => "No autorizado"];
}

mysqli_close($conexion);
echo json_encode($response);
exit();