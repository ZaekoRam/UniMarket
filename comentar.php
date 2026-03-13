<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

if (!isset($_SESSION['usuario_id'])) die("Inicia sesión");

$post_id = $_POST['post_id'];
$comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
$usuario_id = $_SESSION['usuario_id'];

// Verificamos si es una respuesta o un comentario principal
$padre_id = (isset($_POST['padre_id']) && $_POST['padre_id'] != '0') ? (int)$_POST['padre_id'] : "NULL";

$sql = "INSERT INTO comentarios (publicacion_id, usuario_id, comentario, padre_id) 
        VALUES ('$post_id', '$usuario_id', '$comentario', $padre_id)";

mysqli_query($conexion, $sql);
?>