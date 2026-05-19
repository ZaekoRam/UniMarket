<?php
session_start();
require 'credenciales.php';
<<<<<<< HEAD

// ========== DETECTAR IDIOMA ==========
$lang = 'es';

// 1. Intentar desde parámetro GET (viene del formulario)
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 86400 * 30, '/');
    $_SESSION['lang'] = $lang;
}
// 2. Intentar desde cookie
elseif (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
    $_SESSION['lang'] = $lang;
}
// 3. Intentar desde sesión
elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}
// 4. Por defecto español
// Validar que el idioma sea soportado (solo 'es' o 'en')
if (!in_array($lang, ['es', 'en'])) {
    $lang = 'es';
}
// Traducciones
$translations = [
    'es' => [
        'error_permiso' => "❌ Error: No tienes permiso para publicar.",
        'error_vacio' => "⚠️ No puedes publicar un post vacío.",
        'success_publicar' => "✅ Publicación creada exitosamente.",
        'error_publicar' => "❌ Error al publicar: ",
        'error_conexion' => "❌ Error de conexión a la base de datos."
    ],
    'en' => [
        'error_permiso' => "❌ Error: You don't have permission to post.",
        'error_vacio' => "⚠️ You cannot post an empty post.",
        'success_publicar' => "✅ Post created successfully.",
        'error_publicar' => "❌ Error posting: ",
        'error_conexion' => "❌ Database connection error."
    ]
];

function t($key, $lang, $params = []) {
    global $translations;
    $text = $translations[$lang][$key] ?? $key;
    foreach ($params as $k => $v) {
        $text = str_replace("{{$k}}", $v, $text);
    }
    return $text;
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    header("Location: menu?msg=" . urlencode(t('error_conexion', $lang)) . "&type=error");
    exit();
}

// Verificar permisos
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['admin', 'creador'])) {
    header("Location: menu?msg=" . urlencode(t('error_permiso', $lang)) . "&type=error");
=======
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['admin', 'creador'])) {
    header("Location: menu?msg=" . urlencode("❌ Error: No tienes permiso para publicar.") . "&type=error");
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
<<<<<<< HEAD
    // Obtener idioma desde POST (enviado por el formulario)
    if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    // Validar idioma
    if (!in_array($lang, ['es', 'en'])) {
        $lang = 'es';
    }
    setcookie('lang', $lang, time() + 86400 * 30, '/');
    $_SESSION['lang'] = $lang;
}
    
    $texto = trim(mysqli_real_escape_string($conexion, $_POST['texto'] ?? ''));
    $usuario_id = $_SESSION['usuario_id'];
    
    // === Recibir tags ===
    $tags_input = isset($_POST['tags']) ? trim($_POST['tags']) : '';
    $tags_array = array_map('trim', explode(',', $tags_input));
    $tags_array = array_filter($tags_array);
    $tags_array = array_unique($tags_array);
    
    // Normalizar tags
=======
    $texto = trim(mysqli_real_escape_string($conexion, $_POST['texto'] ?? ''));
    $usuario_id = $_SESSION['usuario_id'];
    
    // === NUEVO: Recibir tags ===
    $tags_input = isset($_POST['tags']) ? trim($_POST['tags']) : '';
    $tags_array = array_map('trim', explode(',', $tags_input));
    $tags_array = array_filter($tags_array); // eliminar vacíos
    $tags_array = array_unique($tags_array);
    
    // Normalizar cada tag (minúsculas, sin espacios dobles)
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
    $tags_normalizados = [];
    foreach ($tags_array as $tag) {
        $tag_limpio = strtolower(trim(preg_replace('/\s+/', ' ', $tag)));
        if (!empty($tag_limpio)) {
            $tags_normalizados[] = $tag_limpio;
        }
    }

<<<<<<< HEAD
    // Procesar archivos subidos
    $nombres_media = [];
    if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) {
            mkdir($ruta_carpeta, 0777, true);
        }
=======
    $nombres_media = [];
    if (isset($_FILES['media']) && is_array($_FILES['media']['name'])) {
        $ruta_carpeta = "uploads/";
        if (!file_exists($ruta_carpeta)) mkdir($ruta_carpeta, 0777, true);
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
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
<<<<<<< HEAD
        header("Location: menu?msg=" . urlencode(t('error_vacio', $lang)) . "&type=warning");
=======
        header("Location: menu?msg=" . urlencode("⚠️ No puedes publicar un post vacío.") . "&type=warning");
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
        exit();
    }

    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";
<<<<<<< HEAD
    
    if (mysqli_query($conexion, $sql)) {
        $post_id = mysqli_insert_id($conexion);
        
        // Guardar tags
        if (!empty($tags_normalizados)) {
            // Crear tablas si no existen
=======
    if (mysqli_query($conexion, $sql)) {
        $post_id = mysqli_insert_id($conexion); // Obtener el ID del nuevo post
        
        // === NUEVO: Guardar tags ===
        if (!empty($tags_normalizados)) {
            // Asegurar que la tabla tags existe (crearla si no)
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(50) NOT NULL UNIQUE,
                creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS post_tags (
                post_id INT NOT NULL,
                tag_id INT NOT NULL,
<<<<<<< HEAD
                PRIMARY KEY (post_id, tag_id)
            )");
            
            foreach ($tags_normalizados as $tag_nombre) {
=======
                PRIMARY KEY (post_id, tag_id),
                FOREIGN KEY (post_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
            )");
            
            foreach ($tags_normalizados as $tag_nombre) {
                // Verificar si el tag ya existe
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
                $check = mysqli_query($conexion, "SELECT id FROM tags WHERE nombre = '$tag_nombre'");
                if (mysqli_num_rows($check) > 0) {
                    $row = mysqli_fetch_assoc($check);
                    $tag_id = $row['id'];
                } else {
                    mysqli_query($conexion, "INSERT INTO tags (nombre) VALUES ('$tag_nombre')");
                    $tag_id = mysqli_insert_id($conexion);
                }
<<<<<<< HEAD
=======
                // Asociar tag al post
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
                mysqli_query($conexion, "INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
            }
        }
        
<<<<<<< HEAD
        header("Location: menu?msg=" . urlencode(t('success_publicar', $lang)) . "&type=success");
        exit();
    } else {
        header("Location: menu?msg=" . urlencode(t('error_publicar', $lang) . mysqli_error($conexion)) . "&type=error");
        exit();
    }
}

=======
        header("Location: menu?msg=" . urlencode("✅ Publicación creada exitosamente.") . "&type=success");
        exit();
    } else {
        header("Location: menu?msg=" . urlencode("❌ Error al publicar: " . mysqli_error($conexion)) . "&type=error");
        exit();
    }
}
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
mysqli_close($conexion);
?>