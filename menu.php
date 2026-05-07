<?php
session_start();
require 'credenciales.php';

// Conexión a la base de datos
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

// Verificar que la sesión esté iniciada
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['rol'])) {
    die("Error: Sesión no iniciada. Por favor ve a index.html e inicia sesión.");
}

// Solo administradores y creadores pueden publicar
if (!in_array($_SESSION['rol'], ['admin', 'creador'])) {
    die("Error: Tu rol [" . $_SESSION['rol'] . "] no tiene permiso para publicar.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = trim(mysqli_real_escape_string($conexion, $_POST['texto'] ?? ''));
    $usuario_id = $_SESSION['usuario_id'];
    $nombres_media = [];
    $tiene_archivo = false;

    // Procesar archivos subidos (imágenes o videos)
    if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) {
            mkdir($ruta_carpeta, 0777, true);
        }

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
        $tiene_archivo = true;
    }

    $nombre_imagen = $tiene_archivo ? implode(',', $nombres_media) : null;

    // Validar que no sea un post vacío (sin texto y sin archivos)
    if (empty($texto) && !$tiene_archivo) {
        echo "<script>
                alert('¡No puedes publicar un post vacío! Escribe algo o sube una imagen/video.');
                window.location.href = 'menu.html';
              </script>";
        exit();
    }

    // Insertar el post con estado 'pendiente' (esperando aprobación)
    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, estado, fecha) 
            VALUES ('$usuario_id', '$texto', '$nombre_imagen', 'pendiente', NOW())";

    if (mysqli_query($conexion, $sql)) {
        // Redirigir al feed (los posts pendientes aún no se ven, pero se avisa al usuario)
        echo "<script>
                alert('Publicación enviada para revisión. Será visible una vez aprobada por el administrador.');
                window.location.href = 'menu.html';
              </script>";
        exit();
    } else {
        echo "Error al guardar la publicación: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);
?>