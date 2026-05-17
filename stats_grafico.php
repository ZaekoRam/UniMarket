<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$res_conectados = mysqli_query($conexion,
    "SELECT COUNT(*) as n FROM usuarios 
     WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");
$conectados = (int) mysqli_fetch_assoc($res_conectados)['n'];

$res_totales = mysqli_query($conexion,
    "SELECT COUNT(*) as n FROM usuarios");
$totales = (int) mysqli_fetch_assoc($res_totales)['n'];

echo json_encode([
    'conectados'    => $conectados,
    'desconectados' => $totales - $conectados,
    'totales'       => $totales
]);
?>
