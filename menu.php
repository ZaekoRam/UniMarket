<?php
session_start();
require 'credenciales.php';

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
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $tags_normalizados = [];
    foreach ($tags_array as $tag) {
        $tag_limpio = strtolower(trim(preg_replace('/\s+/', ' ', $tag)));
        if (!empty($tag_limpio)) {
            $tags_normalizados[] = $tag_limpio;
        }
    }

    // Procesar archivos subidos
    $nombres_media = [];
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
    $tiene_archivo = !empty($nombres_media);
    $nombre_imagen = $tiene_archivo ? implode(',', $nombres_media) : null;

    if (empty($texto) && !$tiene_archivo) {
        header("Location: menu?msg=" . urlencode(t('error_vacio', $lang)) . "&type=warning");
        exit();
    }

    $sql = "INSERT INTO publicaciones (usuario_id, texto, imagen, fecha) VALUES ('$usuario_id', '$texto', '$nombre_imagen', NOW())";
    
    if (mysqli_query($conexion, $sql)) {
        $post_id = mysqli_insert_id($conexion);
        
        // Guardar tags
        if (!empty($tags_normalizados)) {
            // Crear tablas si no existen
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(50) NOT NULL UNIQUE,
                creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS post_tags (
                post_id INT NOT NULL,
                tag_id INT NOT NULL,
                PRIMARY KEY (post_id, tag_id)
            )");
            
            foreach ($tags_normalizados as $tag_nombre) {
                $check = mysqli_query($conexion, "SELECT id FROM tags WHERE nombre = '$tag_nombre'");
                if (mysqli_num_rows($check) > 0) {
                    $row = mysqli_fetch_assoc($check);
                    $tag_id = $row['id'];
                } else {
                    mysqli_query($conexion, "INSERT INTO tags (nombre) VALUES ('$tag_nombre')");
                    $tag_id = mysqli_insert_id($conexion);
                }
                mysqli_query($conexion, "INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES ($post_id, $tag_id)");
            }
        }
        
        header("Location: menu?msg=" . urlencode(t('success_publicar', $lang)) . "&type=success");
        exit();
    } else {
        header("Location: menu?msg=" . urlencode(t('error_publicar', $lang) . mysqli_error($conexion)) . "&type=error");
        exit();
    }
}

mysqli_close($conexion);
?>