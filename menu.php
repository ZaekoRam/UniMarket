<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    die("Error: Sesión no iniciada.");
}

if (!in_array($_SESSION['rol'], ['admin', 'creador'])) {
    die("Error: Tu rol [" . $_SESSION['rol'] . "] no tiene permiso para publicar.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = mysqli_real_escape_string($conexion, $_POST['texto']);
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
    } else {
        echo "Error en la base de datos: " . mysqli_error($conexion);
    }
}
?>