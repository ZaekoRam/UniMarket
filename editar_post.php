<?php
require_once 'credenciales.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die("Error: No autenticado");
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$nuevo_texto = mysqli_real_escape_string($conexion, $_POST['texto']);
$remove_media = isset($_POST['remove_media']) ? json_decode($_POST['remove_media'], true) : [];
if (!is_array($remove_media)) $remove_media = [];

// Tags nuevos
$tags_input = isset($_POST['tags']) ? trim($_POST['tags']) : '';
$tags_array = array_map('trim', explode(',', $tags_input));
$tags_array = array_filter(array_unique($tags_array));
$tags_normalizados = array_map(function($tag) {
    return strtolower(trim(preg_replace('/\s+/', ' ', $tag)));
}, $tags_array);
$tags_normalizados = array_filter($tags_normalizados);

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'];

$query = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
$resultado = mysqli_query($conexion, $query);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $autor_del_post = $fila['usuario_id'];

    if ($mi_id == $autor_del_post || $mi_rol == 'admin') {
        
        // Actualizar imagen
        $sql_actual = "SELECT imagen FROM publicaciones WHERE id = '$post_id'";
        $res_actual = mysqli_query($conexion, $sql_actual);
        $media_actual = [];
        if ($fila_actual = mysqli_fetch_assoc($res_actual)) {
            if (!empty($fila_actual['imagen'])) {
                $media_actual = array_filter(array_map('trim', explode(',', $fila_actual['imagen'])));
            }
        }

        if (!empty($remove_media)) {
            foreach ($remove_media as $removido) {
                $archivo = basename(parse_url($removido, PHP_URL_PATH));
                $media_actual = array_filter($media_actual, fn($item) => $item !== $archivo);
                $archivoPath = "uploads/" . $archivo;
                if (file_exists($archivoPath)) @unlink($archivoPath);
            }
            $media_actual = array_values($media_actual);
        }

        if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
            $ruta_carpeta = "uploads/";
            if (!file_exists($ruta_carpeta)) mkdir($ruta_carpeta, 0777, true);
            foreach ($_FILES['media']['name'] as $indice => $nombreArchivo) {
                if ($_FILES['media']['error'][$indice] === 0) {
                    $nombreSeguro = time() . "_" . basename($nombreArchivo);
                    if (move_uploaded_file($_FILES['media']['tmp_name'][$indice], $ruta_carpeta . $nombreSeguro)) {
                        $media_actual[] = $nombreSeguro;
                    }
                }
            }
        }

        $imagen_final = !empty($media_actual) ? implode(',', $media_actual) : null;
        $sql_update = "UPDATE publicaciones SET texto = '$nuevo_texto', imagen = " . ($imagen_final !== null ? "'$imagen_final'" : "NULL") . " WHERE id = '$post_id'";
        
        if (mysqli_query($conexion, $sql_update)) {
            // === ACTUALIZAR TAGS ===
            // Asegurar tablas
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
            
            // Eliminar tags antiguos
            mysqli_query($conexion, "DELETE FROM post_tags WHERE post_id = $post_id");
            
            // Insertar nuevos tags
            foreach ($tags_normalizados as $tag_nombre) {
                if (empty($tag_nombre)) continue;
                $check = mysqli_query($conexion, "SELECT id FROM tags WHERE nombre = '$tag_nombre'");
                if (mysqli_num_rows($check) > 0) {
                    $tag = mysqli_fetch_assoc($check);
                    $tag_id = $tag['id'];
                } else {
                    mysqli_query($conexion, "INSERT INTO tags (nombre) VALUES ('$tag_nombre')");
                    $tag_id = mysqli_insert_id($conexion);
                }
                mysqli_query($conexion, "INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
            }
            
            echo "ok";
        } else {
            echo "Error al guardar en BD: " . mysqli_error($conexion);
        }
    } else {
        echo "Error: ¡No intentes hackear, no es tu post! 🚫";
    }
} else {
    echo "Error: El post no existe.";
}

mysqli_close($conexion);
?>