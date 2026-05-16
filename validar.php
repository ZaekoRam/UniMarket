<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password_ingresada = $_POST['password'];

$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_array($resultado);
    if (password_verify($password_ingresada, $datos['PASSWORD'])) {
        if ($datos['verificado'] == 0) {
            $correo = $datos['cuenta'];
            header("Location: index?verificar=$correo&msg=" . urlencode("⚠️ ¡Tu cuenta aún no está activa! Revisa tu correo.") . "&type=warning");
            exit();
        }
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];
        $_SESSION['nombre_completo'] = $datos['nombre_completo'];
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
mysqli_free_result($resultado);
mysqli_close($conexion);
?>