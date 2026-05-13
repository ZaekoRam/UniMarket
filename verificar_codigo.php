<?php
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);

$consulta = "SELECT * FROM usuarios WHERE cuenta = '$correo' AND codigo_verificacion = '$codigo'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $actualizar = "UPDATE usuarios SET verificado = 1, codigo_verificacion = NULL WHERE cuenta = '$correo'";
    if (mysqli_query($conexion, $actualizar)) {
        header("Location: index.html?msg=" . urlencode("✅ ¡Cuenta activada con éxito! Ya puedes iniciar sesión con Unibot. 🤖✨") . "&type=success");
        exit();
    } else {
        header("Location: index.html?verificar=" . urlencode($correo) . "&msg=" . urlencode("❌ Hubo un error al guardar la activación. Contacta al administrador.") . "&type=error");
        exit();
    }
} else {
    header("Location: index.html?verificar=" . urlencode($correo) . "&msg=" . urlencode("❌ El código es incorrecto. Por favor, revisa bien el correo de UniMarket.") . "&type=error");
    exit();
}
mysqli_close($conexion);
?>