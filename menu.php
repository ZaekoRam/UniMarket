<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['admin', 'creador'])) {
    header("Location: menu?msg=" . urlencode("❌ Error: No tienes permiso para publicar.") . "&type=error");
        exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = trim(mysqli_real_escape_string($conexion, $_POST['texto'] ?? ''));
    $usuario_id = $_SESSION['usuario_id'];

    $nombres_media = [];
    if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) mkdir($ruta_carpeta, 0777, true);
        foreach ($_FILES['media']['name'] as $indice => $nombreArchivo) {
            if ($_FILES['media']['error'][$indice] === 0) {
                $nombreSeguro = time() . "_" . basename($nombreArchivo);
                if (move_uploaded_file($_FILES['media']['tmp_name'][$indice], $ruta_carpeta . $nombreSeguro)) {
                    $nombres_media[] = $nombreSeguro;
                }
            }
        }
    }
    $tiene_archivo = !empty($nombres_media);
    $nombre_imagen = $tiene_archivo ? implode(',', $nombres_media) : null;

    if (empty($texto) && !$tiene_archivo) {
        // Redirigir de vuelta a menu con mensaje de error
        header("Location: menu?msg=" . urlencode("⚠️ No puedes publicar un post vacío.") . "&type=warning");
        exit();
    }

    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";
    if (mysqli_query($conexion, $sql)) {
        header("Location: menu?msg=" . urlencode("✅ Publicación creada exitosamente.") . "&type=success");
        exit();
    } else {
        header("Location: menu?msg=" . urlencode("❌ Error al publicar: " . mysqli_error($conexion)) . "&type=error");
        exit();
    }
}
mysqli_close($conexion);
?>