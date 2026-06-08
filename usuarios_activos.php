<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

// Usuarios activos en los últimos 5 minutos (300 segundos)
$sql = "SELECT COUNT(*) as activos FROM usuarios 
        WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
$res = mysqli_query($conexion, $sql);
$activos = mysqli_fetch_assoc($res)['activos'];

echo $activos;
?>