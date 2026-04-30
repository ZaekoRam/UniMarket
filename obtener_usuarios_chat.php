<?php
session_start();
require 'credenciales.php'; // Incluimos las credenciales desde un archivo separado
if (!isset($_SESSION['usuario_id'])) exit();

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$mi_id = $_SESSION['usuario_id'];

$sql = "SELECT id, usuario, nombre_completo FROM usuarios WHERE id != '$mi_id'";
$resultado = mysqli_query($conexion, $sql);

$usuarios = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $usuarios[] = $fila;
}

echo json_encode($usuarios);
mysqli_close($conexion);
?>