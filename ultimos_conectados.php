<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$sql = "SELECT nombre_completo, usuario, foto_perfil, last_activity,
        CASE 
            WHEN last_activity IS NOT NULL AND last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1
            ELSE 0
        END as is_online
        FROM usuarios
        WHERE last_activity IS NOT NULL
        ORDER BY last_activity DESC
        LIMIT 5";

$res = mysqli_query($conexion, $sql);
$usuarios = mysqli_fetch_all($res, MYSQLI_ASSOC);
echo json_encode($usuarios);
mysqli_close($conexion);
?>