<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

$post_id = $_POST['post_id'];
$comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
$usuario_id = $_SESSION['usuario_id'];

mysqli_query($conexion, "INSERT INTO comentarios (publicacion_id, usuario_id, comentario) VALUES ('$post_id', '$usuario_id', '$comentario')");
?>