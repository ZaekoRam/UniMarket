<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$sql = "SELECT COUNT(*) as visitas FROM usuarios 
        WHERE DATE(last_activity) = CURDATE()";
$res = mysqli_query($conexion, $sql);
$visitas = mysqli_fetch_assoc($res)['visitas'];

echo $visitas;
?>
