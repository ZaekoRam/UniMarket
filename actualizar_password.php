<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_token'])) {
    header("Location: recuperar.php?msg=" . urlencode("❌ Acceso no autorizado. Solicita un nuevo enlace.") . "&type=error");
    exit();
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
    $stmt = mysqli_prepare($conexion, "SELECT id FROM usuarios WHERE id = ? AND token_recuperacion = ? AND token_expira > NOW()");
    mysqli_stmt_bind_param($stmt, "is", $user_id, $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) === 0) {
        $error = "El enlace ha expirado. Solicita uno nuevo.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = mysqli_prepare($conexion, "UPDATE usuarios SET PASSWORD = ?, token_recuperacion = NULL, token_expira = NULL WHERE id = ?");
        mysqli_stmt_bind_param($update, "si", $hash, $user_id);
        if (mysqli_stmt_execute($update)) {
            unset($_SESSION['reset_user_id']);
            unset($_SESSION['reset_token']);
            header("Location: index?msg=" . urlencode("✅ Contraseña actualizada correctamente.") . "&type=success");
            exit();
        } else {
            $error = "Error al actualizar. Intenta de nuevo.";
        }
    }
}

if ($error) {
    header("Location: restablecer.php?token=$token&msg=" . urlencode("❌ $error") . "&type=error");
    exit();
}
mysqli_close($conexion);
?>