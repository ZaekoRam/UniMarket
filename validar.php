<?php
session_start();
require 'credenciales.php';
<<<<<<< HEAD

// ========== DETECTAR IDIOMA ==========
$lang = 'es';

// 1. Intentar desde POST (formulario)
if (isset($_POST['lang'])) {
    $lang = $_POST['lang'];
    setcookie('lang', $lang, time() + 86400 * 30, '/');
    $_SESSION['lang'] = $lang;
}
// 2. Intentar desde GET
elseif (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 86400 * 30, '/');
    $_SESSION['lang'] = $lang;
}
// 3. Intentar desde cookie
elseif (isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
    $_SESSION['lang'] = $lang;
}
// 4. Intentar desde sesión
elseif (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
}
// 5. Por defecto español

// Validar que el idioma sea soportado
if (!in_array($lang, ['es', 'en'])) {
    $lang = 'es';
}

// ========== DEFINICIÓN DE MENSAJES POR CÓDIGO ==========
$messages = [
    'es' => [
        'cuenta_no_verificada' => "⚠️ ¡Tu cuenta aún no está activa! Revisa tu correo.",
        'bienvenido' => "✅ ¡Bienvenido, {nombre}!",
        'password_incorrecta' => "❌ Contraseña incorrecta.",
        'usuario_no_existe' => "❌ Ese usuario no existe. ¡Regístrate primero!",
        'error_conexion' => "❌ Error de conexión a la base de datos."
    ],
    'en' => [
        'cuenta_no_verificada' => "⚠️ Your account is not active yet! Check your email.",
        'bienvenido' => "✅ Welcome, {nombre}!",
        'password_incorrecta' => "❌ Incorrect password.",
        'usuario_no_existe' => "❌ That user does not exist. Please register first!",
        'error_conexion' => "❌ Database connection error."
    ]
];

function getMensaje($code, $lang, $params = []) {
    global $messages;
    $text = $messages[$lang][$code] ?? $code;
    foreach ($params as $key => $value) {
        $text = str_replace("{{$key}}", $value, $text);
    }
    return $text;
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    header("Location: index?msg_code=error_conexion&type=error");
    exit();
}

$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario'] ?? '');
$password_ingresada = $_POST['password'] ?? '';
=======
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password_ingresada = $_POST['password'];
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f

$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_array($resultado);
    if (password_verify($password_ingresada, $datos['PASSWORD'])) {
        if ($datos['verificado'] == 0) {
            $correo = $datos['cuenta'];
<<<<<<< HEAD
            header("Location: index?msg_code=cuenta_no_verificada&type=warning&verificar=$correo");
=======
            header("Location: index?verificar=$correo&msg=" . urlencode("⚠️ ¡Tu cuenta aún no está activa! Revisa tu correo.") . "&type=warning");
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
            exit();
        }
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];
        $_SESSION['nombre_completo'] = $datos['nombre_completo'];
<<<<<<< HEAD
        
        // Redirigir con código y nombre para personalizar
        $nombre = $datos['usuario'];
        header("Location: menu?msg_code=bienvenido&nombre=" . urlencode($nombre) . "&type=success");
        exit();
    } else {
        header("Location: index?msg_code=password_incorrecta&type=error");
        exit();
    }
} else {
    header("Location: index?msg_code=usuario_no_existe&type=error");
    exit();
}

=======
        header("Location: menu?msg=" . urlencode("✅ ¡Bienvenido, " . $datos['usuario'] . "!") . "&type=success");
        exit();
    } else {
        header("Location: index?msg=" . urlencode("❌ Contraseña incorrecta.") . "&type=error");
        exit();
    }
} else {
    header("Location: index?msg=" . urlencode("❌ Ese usuario no existe. ¡Regístrate primero!") . "&type=error");
    exit();
}
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
mysqli_free_result($resultado);
mysqli_close($conexion);
?>