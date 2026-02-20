<?php
// Iniciar sesión para guardar el rol del usuario
session_start();

// 1. Conexión a la base de datos (Servidor, Usuario, Pass, Nombre BD)
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// 2. Recoger los datos enviados desde el HTML
$usuario  = $_POST['usuario'];
$password = $_POST['password'];

// 3. Consultar si el usuario y la contraseña existen
$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND password = '$password'";
$resultado = mysqli_query($conexion, $consulta);

// 4. Verificar el resultado
if (mysqli_num_rows($resultado) > 0) {
    // Guardar datos del usuario en la sesión
    $datos = mysqli_fetch_array($resultado);
    $_SESSION['usuario'] = $datos['usuario'];
    $_SESSION['rol'] = $datos['rol'];

    // Si es correcto, lo mandamos al menú
    header("location:menu.html");
} else {
    // Si es incorrecto, mostrar error y regresar al login
    echo "<script>
            alert('Usuario o contraseña incorrectos');
            window.location='Login.html';
          </script>";
}

mysqli_free_result($resultado);
mysqli_close($conexion);
?>