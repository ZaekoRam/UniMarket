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
    
    // === NUEVO: Recibir tags ===
    $tags_input = isset($_POST['tags']) ? trim($_POST['tags']) : '';
    $tags_array = array_map('trim', explode(',', $tags_input));
    $tags_array = array_filter($tags_array); // eliminar vacíos
    $tags_array = array_unique($tags_array);
    
    // Normalizar cada tag (minúsculas, sin espacios dobles)
    $tags_normalizados = [];
    foreach ($tags_array as $tag) {
        $tag_limpio = strtolower(trim(preg_replace('/\s+/', ' ', $tag)));
        if (!empty($tag_limpio)) {
            $tags_normalizados[] = $tag_limpio;
        }
    }

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
        header("Location: menu?msg=" . urlencode("⚠️ No puedes publicar un post vacío.") . "&type=warning");
        exit();
    }

    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";
    if (mysqli_query($conexion, $sql)) {
        $post_id = mysqli_insert_id($conexion); // Obtener el ID del nuevo post
        
        // === NUEVO: Guardar tags ===
        if (!empty($tags_normalizados)) {
            // Asegurar que la tabla tags existe (crearla si no)
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(50) NOT NULL UNIQUE,
                creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS post_tags (
                post_id INT NOT NULL,
                tag_id INT NOT NULL,
                PRIMARY KEY (post_id, tag_id),
                FOREIGN KEY (post_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            )");
            
            foreach ($tags_normalizados as $tag_nombre) {
                // Verificar si el tag ya existe
                $check = mysqli_query($conexion, "SELECT id FROM tags WHERE nombre = '$tag_nombre'");
                if (mysqli_num_rows($check) > 0) {
                    $row = mysqli_fetch_assoc($check);
                    $tag_id = $row['id'];
                } else {
                    mysqli_query($conexion, "INSERT INTO tags (nombre) VALUES ('$tag_nombre')");
                    $tag_id = mysqli_insert_id($conexion);
                }
                // Asociar tag al post
                mysqli_query($conexion, "INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
            }
        }
        
        header("Location: menu?msg=" . urlencode("✅ Publicación creada exitosamente.") . "&type=success");
        exit();
    } else {
        header("Location: menu?msg=" . urlencode("❌ Error al publicar: " . mysqli_error($conexion)) . "&type=error");
        exit();
    }
}
mysqli_close($conexion);
?>