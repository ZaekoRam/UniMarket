<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Verificamos si el usuario tiene permiso
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['admin', 'creador'])) {
    die("Error: No tienes permisos para publicar."); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = mysqli_real_escape_string($conexion, $_POST['texto']);
    $link  = mysqli_real_escape_string($conexion, $_POST['link']);
    $usuario_id = $_SESSION['usuario_id'];

    $nombre_imagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) { mkdir($ruta_carpeta, 0777, true); }
        
        $nombre_imagen = time() . "_" . $_FILES['imagen']['name'];
        move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_carpeta . $nombre_imagen);
    }

    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) 
            VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";

    if (mysqli_query($conexion, $sql)) {
        header("location:menu.html");
        exit();
    }
}
?>