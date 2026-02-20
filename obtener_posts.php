<?php
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$res = mysqli_query($conexion, "SELECT * FROM publicaciones ORDER BY fecha DESC");
$posts = [];

while($row = mysqli_fetch_assoc($res)) {
    $posts[] = $row;
}

echo json_encode($posts); // Le manda la lista al HTML en formato que entiende JS
?>