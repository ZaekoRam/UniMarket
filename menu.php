<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Verificación robusta de sesión
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    die("Error: Sesión no iniciada. Por favor ve a Login.html e inicia sesión.");
}

if (!in_array($_SESSION['rol'], ['admin', 'creador'])) {
    die("Error: Tu rol [" . $_SESSION['rol'] . "] no tiene permiso para publicar.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Usamos trim() para borrar espacios en blanco por si el usuario solo puso barras espaciadoras
    $texto = trim(mysqli_real_escape_string($conexion, $_POST['texto']));
    $usuario_id = $_SESSION['usuario_id'];

    $nombres_media = [];
    $tiene_imagen = false;

    // Verificamos si subió archivos de media
    if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) { mkdir($ruta_carpeta, 0777, true); }

        foreach ($_FILES['media']['name'] as $indice => $nombreArchivo) {
            if ($_FILES['media']['error'][$indice] === 0) {
                $nombreSeguro = time() . "_" . basename($nombreArchivo);
                if (move_uploaded_file($_FILES['media']['tmp_name'][$indice], $ruta_carpeta . $nombreSeguro)) {
                    $nombres_media[] = $nombreSeguro;
                }
            }
        }
    }

    if (!empty($nombres_media)) {
        $tiene_imagen = true;
    }

    $nombre_imagen = $tiene_imagen ? implode(',', $nombres_media) : null;

    // 🛑 AQUÍ ESTÁ EL CANDADO ANTI-POSTS VACÍOS 🛑
    // Si el texto está completamente vacío Y además no subió ninguna imagen...
    if (empty($texto) && !$tiene_imagen) {
        echo "<script>
                alert('¡No puedes publicar un post vacío! Escribe algo o sube una imagen.');
                window.location.href = 'menu.html';
              </script>";
        exit(); // Detenemos el código aquí para que no se guarde en la BD
    }

    /* NOTA: Si quieres que el texto sea OBLIGATORIO siempre (incluso si sube imagen), 
    solo cambia el if de arriba por este:
    if (empty($texto)) { ... }
    */

    // Si pasó la prueba, guardamos en la base de datos
    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) 
            VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";

    if (mysqli_query($conexion, $sql)) {
        header("location:menu.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($conexion);
    } 
}
?>