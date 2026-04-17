<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die("Error: No autenticado");
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Recibimos los datos del JS
$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$nuevo_texto = mysqli_real_escape_string($conexion, $_POST['texto']);
$remove_media = isset($_POST['remove_media']) ? json_decode($_POST['remove_media'], true) : [];
if (!is_array($remove_media)) {
    $remove_media = [];
}
// Si también guardas la URL, descomenta la siguiente línea:
// $url = mysqli_real_escape_string($conexion, $_POST['url']);

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'];

// 1. Verificamos quién es el dueño
$query = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
$resultado = mysqli_query($conexion, $query);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $autor_del_post = $fila['usuario_id'];

    // 2. ¿Soy el dueño o soy admin?
    if ($mi_id == $autor_del_post || $mi_rol == 'admin') {
        
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
                if (file_exists($archivoPath)) {
                    @unlink($archivoPath);
                }
            }
            $media_actual = array_values($media_actual);
        }

        if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
            $ruta_carpeta = "uploads/";
            if (!file_exists($ruta_carpeta)) { mkdir($ruta_carpeta, 0777, true); }

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
