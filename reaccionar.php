<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

if (!isset($_SESSION['usuario_id'])) die("0");

$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['usuario_id'];
$tipo = $_POST['tipo'];

// Checamos si ya hay una reacción tuya
$check = mysqli_query($conexion, "SELECT id, tipo FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");

if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    if ($row['tipo'] == $tipo) {
        // Si le das clic al mismo, lo borramos (quitar like)
        mysqli_query($conexion, "DELETE FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");
    } else {
        // Si cambias de like a dislike, actualizamos
        mysqli_query($conexion, "UPDATE reacciones SET tipo = '$tipo' WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");
    }
} else {
    // Si es nuevo, lo insertamos
    mysqli_query($conexion, "INSERT INTO reacciones (publicacion_id, usuario_id, tipo) VALUES ('$post_id', '$usuario_id', '$tipo')");
}

// Devolvemos solo el número de reacciones de ese tipo para actualizar la pantalla
$conteo = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM reacciones WHERE publicacion_id = '$post_id' AND tipo = '$tipo'"));
echo $conteo['total'];
?>