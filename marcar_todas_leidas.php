<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!isset($_SESSION['usuario_id'])) exit;
mysqli_query($conexion, "UPDATE notificaciones SET leida = 1 WHERE usuario_id = {$_SESSION['usuario_id']}");
?>