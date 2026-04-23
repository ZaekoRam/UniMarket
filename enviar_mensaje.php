<?php
session_start();
if (!isset($_SESSION['usuario_id'])) exit();

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$mi_id = $_SESSION['usuario_id'];
$destinatario_id = (int)$_POST['destinatario_id'];
$mensaje = trim(mysqli_real_escape_string($conexion, $_POST['mensaje'] ?? ''));

if (!empty($mensaje) && $destinatario_id > 0) {
    $sql = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje) 
            VALUES ('$mi_id', '$destinatario_id', '$mensaje')";
    mysqli_query($conexion, $sql);
    echo json_encode(["status" => "ok"]);
}
mysqli_close($conexion);
?>