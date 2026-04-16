<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$id = $_SESSION['usuario_id'];

// Recibimos el JSON que nos manda Javascript
$datos = json_decode(file_get_contents('php://input'), true);

// Limpiamos los datos para evitar inyecciones SQL
$bio = mysqli_real_escape_string($conexion, $datos['bio']);
// Como tags y gustos son arreglos (arrays) en JS, los unimos con comas para guardarlos como texto
$tags = mysqli_real_escape_string($conexion, implode(',', $datos['tags']));
$carrera = mysqli_real_escape_string($conexion, $datos['carrera']);
$campus = mysqli_real_escape_string($conexion, $datos['campus']);
$emprendimientos = mysqli_real_escape_string($conexion, $datos['emprendimientos']);
$estado = mysqli_real_escape_string($conexion, $datos['estado']);
$sobreMi = mysqli_real_escape_string($conexion, $datos['sobreMi']);
$gustos = mysqli_real_escape_string($conexion, implode(',', $datos['gustos']));
$mood = mysqli_real_escape_string($conexion, $datos['mood']);
$color = mysqli_real_escape_string($conexion, $datos['color']);
$meta = mysqli_real_escape_string($conexion, $datos['meta']);
$estilo = mysqli_real_escape_string($conexion, $datos['estilo']);

$sql = "UPDATE usuarios SET 
        bio = '$bio', tags = '$tags', carrera = '$carrera', campus = '$campus', 
        emprendimientos = '$emprendimientos', estado = '$estado', sobre_mi = '$sobreMi', 
        gustos = '$gustos', mood = '$mood', color = '$color', meta = '$meta', estilo = '$estilo' 
        WHERE id = '$id'";

if (mysqli_query($conexion, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
}

mysqli_close($conexion);
?>