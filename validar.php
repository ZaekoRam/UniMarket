<?php
<<<<<<< HEAD
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password_ingresada = $_POST['password'];

$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_array($resultado); 
    
    // 👇 AQUÍ ESTÁ EL CAMBIO IMPORTANTE: Escribe el nombre de la columna EXACTAMENTE como está en tu BD
    if (password_verify($password_ingresada, $datos['PASSWORD'])) {
        
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];
    
        header("location:menu.html");
        exit();
        
    } else {
        echo "<script>
                alert('Contraseña incorrecta, twiin. Intenta de nuevo.');
                window.location='Login.html';
              </script>";
    }
} else {
    echo "<script>
            alert('Ese usuario no existe. ¡Regístrate primero!');
=======
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
    // ESTA LÍNEA ES VITAL: Extrae los datos de la fila encontrada
    $datos = mysqli_fetch_array($resultado); 
    
    // Ahora sí, guardamos en la sesión usando los nombres exactos de tus columnas en SQL
    $_SESSION['usuario_id'] = $datos['id']; // Asegúrate que en tu tabla se llame 'id'
    $_SESSION['usuario'] = $datos['usuario'];
    $_SESSION['rol'] = $datos['rol'];

    header("location:menu.html");
} else {
    // Si es incorrecto, mostrar error y regresar al login
    echo "<script>
            alert('Usuario o contraseña incorrectos');
>>>>>>> 12a2dfb7b4b39ac70cee7e9c68ce90a6e0d3f085
            window.location='Login.html';
          </script>";
}

mysqli_free_result($resultado);
mysqli_close($conexion);
?>