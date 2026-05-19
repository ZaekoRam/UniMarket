<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!isset($_SESSION['usuario_id'])) exit;
mysqli_query($conexion, "DELETE FROM notificaciones WHERE usuario_id = {$_SESSION['usuario_id']}");
?>