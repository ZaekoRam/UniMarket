<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

// Verificar que la sesión tenga la autorización (viene de restablecer.php)
if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_token'])) {
    die("Acceso no autorizado. Por favor, solicita un nuevo enlace de recuperación.");
}

$user_id = $_SESSION['reset_user_id'];
$token = $_SESSION['reset_token'];
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

$error = '';

if (strlen($password) < 8) {
    $error = "La contraseña debe tener al menos 8 caracteres.";
} elseif ($password !== $confirm) {
    $error = "Las contraseñas no coinciden.";
} else {
    // Verificar nuevamente que el token siga siendo válido en la BD (por si pasó más de 1 hora)
    $stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE id = ? AND token_recuperacion = ? AND token_expira > NOW()");
    mysqli_stmt_bind_param($stmt, "is", $user_id, $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) === 0) {
        $error = "El enlace ha expirado. Solicita uno nuevo.";
    } else {
        // Actualizar contraseña y limpiar token
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_prepare($conexion, "UPDATE usuarios SET PASSWORD = ?, token_recuperacion = NULL, token_expira = NULL WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $hash, $user_id);
        if (mysqli_stmt_execute($update)) {
            // Limpiar variables de sesión
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_token']);
            header("Location: index.html?reset=ok");
            exit;
        } else {
            $error = "Error al actualizar. Intenta de nuevo.";
        }
    }
}

// Si llegamos aquí es porque hubo error
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error al restablecer</title>
    <link rel="stylesheet" href="style_login.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; }
        .error-card { background: #081f1d; padding: 40px; border-radius: 30px; text-align: center; border: 1px solid #ff7d92; }
        .back-link { color: #39c5bb; }
    </style>
</head>
<body>
<div class="error-card">
    <h2 style="color:#ff7d92">❌ Error</h2>
    <p><?= htmlspecialchars($error) ?></p>
    <a href="recuperar.php" class="back-link">Solicitar nuevo enlace</a><br>
    <a href="index.html" class="back-link">Volver al inicio</a>
</div>
</body>
</html>