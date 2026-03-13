<?php
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// 1. Recoger los datos enviados desde el formulario de registro
$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']); // Nombre completo
$num      = mysqli_real_escape_string($conexion, $_POST['num']);     // Número de cuenta
$cuenta   = mysqli_real_escape_string($conexion, $_POST['cuenta']);  // Correo
$password = mysqli_real_escape_string($conexion, $_POST['password']); // Contraseña
$rol      = 'lector'; // Le ponemos un rol por defecto para que no tenga privilegios de admin

// 2. Revisar si ese número de cuenta o correo ya existen para evitar duplicados
$check_query = "SELECT * FROM usuarios WHERE num = '$num' OR cuenta = '$cuenta'";
$check_result = mysqli_query($conexion, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // Si ya existe, lo regresamos con un aviso
    //yes
    echo "<script>
            alert('¡Aguanta! Ese correo o número de cuenta ya está registrado. Intenta iniciar sesión.');
            window.location='Login.html';
          </script>";
    exit();
}

// 3. Si no existe, lo insertamos en la base de datos
// OJO: Asegúrate de que las columnas de tu tabla "usuarios" se llamen así (usuario, num, cuenta, password, rol)
$sql = "INSERT INTO usuarios (usuario, num, cuenta, password, rol) 
        VALUES ('$usuario', '$num', '$cuenta', '$password', '$rol')";

if (mysqli_query($conexion, $sql)) {
    // ¡Éxito! Lo mandamos de regreso al login para que entre con su cuenta nuevecita
    echo "<script>
            alert('¡Registro exitoso, twiin! Ya puedes iniciar sesión.');
            window.location='Login.html';
          </script>";
} else {
    echo "Error al registrar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>