<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - UniMarket</title>
    <link rel="stylesheet" href="style_login.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .rec-card { background: rgba(8,31,29,0.9); backdrop-filter: blur(8px); padding: 40px; border-radius: 30px; max-width: 450px; width: 90%; text-align: center; border: 1px solid #39c5bb; }
        h2 { color: #39c5bb; }
        input { width: 100%; padding: 12px; margin: 20px 0; border-radius: 40px; border: none; background: #eafffb; color: #061b1a; font-weight: bold; text-align: center; }
        button { background: #39c5bb; border: none; padding: 12px; border-radius: 40px; font-weight: bold; cursor: pointer; width: 100%; }
        button:hover { background: #0f8f87; }
        .back-link { display: block; margin-top: 20px; color: #9bded7; }
    </style>
</head>
<body>
    <div class="rec-card">
        <h2>🔐 Recuperar contraseña</h2>
        <p>Ingresa tu correo institucional y te enviaremos un enlace para crear una nueva contraseña.</p>
        <form action="enviar_recuperacion.php" method="POST">
            <input type="email" name="email" placeholder="tucorreo@ucol.mx" required>
            <button type="submit">Enviar enlace</button>
        </form>
        <a href="index.html" class="back-link">← Volver al inicio</a>
    </div>
</body>
</html>