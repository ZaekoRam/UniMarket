<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']);
$password_ingresada = $_POST['password'];

$consulta = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {
    $datos = mysqli_fetch_array($resultado); 
    
    // Verificamos la contraseña
    if (password_verify($password_ingresada, $datos['PASSWORD'])) {
        
        // ==========================================
        // 🛑 CANDADO FINAL: ¿Ya verificó su correo?
        // ==========================================
        if ($datos['verificado'] == 0) {
            $correo = $datos['cuenta']; // Sacamos el correo para mandarlo por la URL
            echo "<script>
                    alert('¡Tu cuenta aún no está activa! Por favor, ingresa el código que enviamos a tu correo.');
                    window.location='Login.html?verificar=$correo';
                  </script>";
            exit(); // 🚫 ¡Detenemos el login aquí para que no pase!
        }
        // ==========================================

        // ✅ Si pasó el candado, ¡Metemos todo a la mochila de la sesión!
        $_SESSION['usuario_id'] = $datos['id'];
        $_SESSION['usuario'] = $datos['usuario'];
        $_SESSION['rol'] = $datos['rol'];
        $_SESSION['nombre_completo'] = $datos['nombre_completo']; 
    
        header("location:menu.html");
        exit();
        
    } else {
        echo "<script>
                alert('Contraseña incorrecta. Intenta de nuevo.');
                window.location='Login.html';
              </script>";
    }
} else {
    echo "<script>
            alert('Ese usuario no existe. ¡Regístrate primero!');
            window.location='Login.html';
          </script>";
}

mysqli_free_result($resultado);
mysqli_close($conexion);
?>