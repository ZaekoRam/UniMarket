<?php
session_start();
require 'credenciales.php';
if (!isset($_SESSION['usuario_id'])) exit;
$mi_id = $_SESSION['usuario_id'];
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
mysqli_query($conexion, "UPDATE usuarios SET last_activity = NOW() WHERE id = $mi_id");
?>