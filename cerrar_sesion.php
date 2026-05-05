<?php
session_start(); // Primero conectamos con la sesión actual

// 1. Limpiamos todas las variables de la sesión
$_SESSION = array();

// 2. Si quieres ser ultra seguro, borramos la cookie de sesión del navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destruimos la sesión en el servidor
session_destroy();

// 4. Mandamos al usuario de vuelta al login (index.html)
header("Location: index.html");
exit();
?>