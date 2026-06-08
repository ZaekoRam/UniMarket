<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$sql = "SELECT nombre FROM tags ORDER BY nombre ASC";
$res = mysqli_query($conexion, $sql);
$tags = [];
while ($row = mysqli_fetch_assoc($res)) {
    $tags[] = $row['nombre'];
}
echo json_encode($tags);
mysqli_close($conexion);
?>