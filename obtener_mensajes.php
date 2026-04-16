<?php
session_start();
if (!isset($_SESSION['usuario_id']) || !isset($_GET['con_quien'])) exit();

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$mi_id = $_SESSION['usuario_id'];
$con_quien = (int)$_GET['con_quien']; // El ID del amigo que seleccionaste

$sql = "SELECT m.*, u.usuario, u.nombre_completo 
        FROM mensajes m 
        JOIN usuarios u ON m.remitente_id = u.id 
        WHERE (m.remitente_id = '$mi_id' AND m.destinatario_id = '$con_quien') 
           OR (m.remitente_id = '$con_quien' AND m.destinatario_id = '$mi_id')
        ORDER BY m.fecha ASC";

$resultado = mysqli_query($conexion, $sql);
$mensajes = [];

while ($fila = mysqli_fetch_assoc($resultado)) {
    $mensajes[] = $fila;
}

echo json_encode([
    "mi_id" => $mi_id,
    "mensajes" => $mensajes
]);
mysqli_close($conexion);
?>