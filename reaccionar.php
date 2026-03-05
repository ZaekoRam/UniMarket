<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

if (!isset($_SESSION['usuario_id'])) die("Inicia sesión");

$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['usuario_id'];
$tipo = $_POST['tipo'];

// Si ya existía, lo borramos (toggle), si no, lo insertamos
$check = mysqli_query($conexion, "SELECT id FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");

if (mysqli_num_rows($check) > 0) {
    mysqli_query($conexion, "DELETE FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");
} else {
    mysqli_query($conexion, "INSERT INTO reacciones (publicacion_id, usuario_id, tipo) VALUES ('$post_id', '$usuario_id', '$tipo')");
}

// Devolvemos el conteo actual
$conteo = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM reacciones WHERE publicacion_id = '$post_id' AND tipo = '$tipo'"));
echo $conteo['total'];
?>