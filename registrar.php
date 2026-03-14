<?php
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// 1. Recoger los 5 datos enviados desde tu HTML
// Asegurándonos de que coincidan exactamente con los 'name' de tu formulario
$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']); 
$usuario  = mysqli_real_escape_string($conexion, $_POST['usuario']); 
$num      = mysqli_real_escape_string($conexion, $_POST['num']);     
$cuenta   = mysqli_real_escape_string($conexion, $_POST['cuenta']);  
$password_plana = $_POST['password']; 
$rol      = 'lector'; // Asignamos el rol automático

// 🛑 CANDADO ANTI-ESPACIOS: Confirmar que el usuario no tenga espacios en blanco
if (preg_match('/\s/', $usuario)) {
    echo "<script>
            alert('¡Ey! El nombre de usuario no puede tener espacios. Intenta con algo como $usuario" . "123');
            window.location='Login.html';
          </script>";
    exit();
}

// 🔒 LA MAGIA: Hasheamos la contraseña
$password_hasheada = password_hash($password_plana, PASSWORD_DEFAULT); 

// 2. Revisar si ese usuario, número de cuenta o correo ya existen en la base de datos
$check_query = "SELECT * FROM usuarios WHERE num = '$num' OR cuenta = '$cuenta' OR usuario = '$usuario'";
$check_result = mysqli_query($conexion, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>
            alert('¡Aguanta! Ese nombre de usuario, correo o número de cuenta ya está en uso. Intenta con otros.');
            window.location='Login.html';
          </script>";
    exit();
}

// 3. Insertamos en la base de datos TODOS los campos
$sql = "INSERT INTO usuarios (nombre_completo, usuario, num, cuenta, password, rol) 
        VALUES ('$nombre_completo', '$usuario', '$num', '$cuenta', '$password_hasheada', '$rol')";

if (mysqli_query($conexion, $sql)) {
    // ¡Éxito!
    echo "<script>
            alert('¡Registro exitoso, twiin! Ya puedes iniciar sesión con tu cuenta nuevecita.');
            window.location='Login.html';
          </script>";
} else {
    // Si hay algún error en la base de datos, te lo mostramos para saber qué falló
    echo "Error al registrar: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>