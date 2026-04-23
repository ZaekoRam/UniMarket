<?php
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Recibimos los datos del Pop-Up
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$codigo = mysqli_real_escape_string($conexion, $_POST['codigo']);

// Buscamos si existe un usuario con ese correo y ese código exacto
$consulta = "SELECT * FROM usuarios WHERE cuenta = '$correo' AND codigo_verificacion = '$codigo'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    // ¡El código es correcto! 
    // Actualizamos la cuenta a verificado = 1 y limpiamos el código para que no se vuelva a usar
    $actualizar = "UPDATE usuarios SET verificado = 1, codigo_verificacion = NULL WHERE cuenta = '$correo'";
    
    if (mysqli_query($conexion, $actualizar)) {
        echo "<script>
                alert('¡Cuenta activada con éxito! Ya puedes iniciar sesión con Unibot. 🤖✨');
                window.location='Login.html';
              </script>";
    } else {
        echo "<script>
                alert('Hubo un error al guardar la activación. Contacta al administrador.');
                window.location='Login.html?verificar=" . urlencode($correo) . "';
              </script>";
    }
} else {
    // El código no coincide
    echo "<script>
            alert('❌ El código es incorrecto. Por favor, revisa bien el correo de UniMarket.');
            window.location='Login.html?verificar=" . urlencode($correo) . "';
          </script>";
}

mysqli_close($conexion);
?>