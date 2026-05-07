<?php
session_start();
echo json_encode([
    'usuario_id' => $_SESSION['usuario_id'] ?? null,
    'usuario' => $_SESSION['usuario'] ?? null,
    'nombre_completo' => $_SESSION['nombre_completo'] ?? null, // 👈 Se lo pasamos a JS
    'rol' => $_SESSION['rol'] ?? null
]);
?>