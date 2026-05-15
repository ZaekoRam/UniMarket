<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña · UniMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-main: linear-gradient(135deg, #051312, #081f1d, #03100f);
            --accent: #39c5bb;
            --accent-2: #66fff0;
            --accent-gradient: linear-gradient(135deg, #66fff0, #39c5bb);
            --text: #eafffb;
            --muted: #9bded7;
            --panel: rgba(10, 24, 23, 0.88);
            --panel-soft: rgba(255, 255, 255, 0.04);
            --shadow: 0 18px 45px rgba(0, 0, 0, 0.45);
            --radius: 28px;
            --border-glow: rgba(102, 255, 240, 0.25);
        }

        body.light-mode {
            --bg-main: linear-gradient(135deg, #dffcf9, #c9f6f0, #eefefd);
            --accent: #39c5bb;
            --accent-2: #0f8f87;
            --text: #10211c;
            --muted: #4f7c76;
            --panel: rgba(236, 250, 248, 0.88);
            --panel-soft: rgba(6, 27, 26, 0.05);
            --shadow: 0 18px 45px rgba(20, 70, 50, 0.12);
            --border-glow: rgba(15, 143, 135, 0.25);
        }

        body {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
            transition: background 0.5s ease, color 0.5s ease;
        }

        /* Efecto CRT scanlines */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0, 0, 0, 0.08) 0px, rgba(0, 0, 0, 0.08) 2px, transparent 2px, transparent 6px);
            pointer-events: none;
            z-index: 997;
            animation: scanlines 8s linear infinite;
        }

        @keyframes scanlines {
            from { background-position: 0 0; }
            to { background-position: 0 20px; }
        }

        /* Fondo animado */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(57, 197, 187, 0.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: 996;
            animation: bgPulse 4s ease-in-out infinite;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        /* Partículas flotantes */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 995;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.4;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            from { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            to { transform: translateY(-20vh) rotate(360deg); opacity: 0; }
        }

        /* Tarjeta principal */
        .recovery-card {
            position: relative;
            z-index: 10;
            background: var(--panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glow);
            border-radius: var(--radius);
            padding: 48px 40px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: cardReveal 0.6s ease;
        }

        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .recovery-card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow), 0 0 40px rgba(57, 197, 187, 0.2);
            transform: translateY(-5px);
        }

        /* Icono */
        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, rgba(57, 197, 187, 0.15), rgba(102, 255, 240, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: var(--accent);
            border: 2px solid rgba(57, 197, 187, 0.3);
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(57, 197, 187, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(57, 197, 187, 0); }
        }

        /* Título */
        h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 12px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Campo de email */
        .input-group {
            position: relative;
            margin-bottom: 28px;
        }

        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 18px;
            z-index: 2;
        }

        .input-group input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            background: var(--panel-soft);
            border: 2px solid rgba(102, 255, 240, 0.15);
            border-radius: 50px;
            color: var(--text);
            font-size: 16px;
            font-family: monospace;
            transition: all 0.3s;
            outline: none;
        }

        body.light-mode .input-group input {
            border-color: rgba(15, 143, 135, 0.2);
        }

        .input-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(57, 197, 187, 0.15);
        }

        .input-group input::placeholder {
            color: var(--muted);
            opacity: 0.5;
            font-family: monospace;
        }

        /* Botón */
        .btn-send {
            position: relative;
            width: 100%;
            padding: 16px;
            background: var(--accent-gradient);
            border: none;
            border-radius: 50px;
            color: #082016;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s;
            margin-bottom: 24px;
        }

        .btn-send::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-send:hover::before {
            left: 100%;
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px var(--accent);
        }

        .btn-send:active {
            transform: translateY(0);
        }

        /* Enlace de vuelta */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 16px;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.03);
        }

        body.light-mode .back-link {
            background: rgba(0, 0, 0, 0.03);
        }

        .back-link:hover {
            color: var(--accent-2);
            background: rgba(57, 197, 187, 0.1);
            gap: 12px;
        }

        /* Home chip */
        .home-chip {
            position: fixed;
            top: 20px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            border-radius: 999px;
            background: linear-gradient(180deg, #39c5bb, #0f8f87);
            border: 1px solid rgba(102, 255, 240, 0.25);
            color: #ffffff;
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .22);
            backdrop-filter: blur(12px);
            transition: all 0.3s;
            z-index: 100;
        }

        .home-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .28), 0 0 22px rgba(102, 255, 240, 0.30);
        }

        /* Toast de notificación */
        .toast-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            text-align: center;
        }

        .toast {
            padding: 12px 24px;
            background: var(--panel);
            border: 1px solid var(--accent);
            border-radius: 50px;
            color: var(--accent-2);
            font-family: monospace;
            font-size: 14px;
            backdrop-filter: blur(10px);
            animation: toastSlide 0.3s ease;
        }

        @keyframes toastSlide {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 550px) {
            .recovery-card { padding: 32px 24px; }
            h1 { font-size: 26px; }
            .lock-icon { width: 65px; height: 65px; font-size: 32px; }
            .home-chip { padding: 8px 16px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <!-- Partículas flotantes -->
    <div class="particles" id="particles"></div>

    <!-- Botón home -->
    <a href="index" class="home-chip">
        <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>

    <div class="recovery-card">
        <div class="lock-icon">
            <i class="fas fa-unlock-alt"></i>
        </div>
        <h1>Recuperar contraseña</h1>
        <p class="subtitle">Ingresa tu correo institucional y te enviaremos un enlace para crear una nueva contraseña.</p>
        
        <form action="enviar_recuperacion.php" method="POST" id="recoveryForm">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="tucorreo@ucol.mx" required autocomplete="off">
            </div>
            <button type="submit" class="btn-send">
                <i class="fas fa-paper-plane"></i> Enviar enlace
            </button>
        </form>
        
        <a href="index" class="back-link">
            <i class="fas fa-arrow-left"></i> Volver al inicio
        </a>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        // ========== TEMA (LEE EL TEMA GUARDADO DEL USUARIO) ==========
        function applySavedTheme() {
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme === "light") {
                document.body.classList.add("light-mode");
            } else {
                document.body.classList.remove("light-mode");
            }
        }
        
        // Aplicar tema al cargar
        applySavedTheme();
        
        // Escuchar cambios de tema (por si el usuario cambia en otra pestaña)
        window.addEventListener("storage", (e) => {
            if (e.key === "theme") {
                if (e.newValue === "light") {
                    document.body.classList.add("light-mode");
                } else {
                    document.body.classList.remove("light-mode");
                }
            }
        });

        // ========== PARTÍCULAS ==========
        function createParticles() {
            const container = document.getElementById("particles");
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement("div");
                particle.className = "particle";
                particle.style.left = Math.random() * 100 + "%";
                particle.style.width = Math.random() * 4 + 2 + "px";
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 15 + "s";
                particle.style.animationDuration = Math.random() * 10 + 10 + "s";
                particle.style.opacity = Math.random() * 0.4 + 0.2;
                container.appendChild(particle);
            }
        }
        createParticles();

        // ========== TOAST ==========
        function showToast(message, type = "error") {
            const container = document.getElementById("toastContainer");
            const toast = document.createElement("div");
            toast.className = "toast";
            toast.innerHTML = `<i class="fas ${type === "error" ? "fa-exclamation-triangle" : "fa-check-circle"}"></i> ${message}`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        // ========== PROCESAR MENSAJE DE URL ==========
        function processUrlMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');
            const type = urlParams.get('type');
            if (msg) {
                showToast(decodeURIComponent(msg), type || "error");
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        }

        // ========== VALIDACIÓN DEL FORMULARIO ==========
        const form = document.getElementById("recoveryForm");
        form.addEventListener("submit", (e) => {
            const emailInput = form.querySelector("input[name='email']");
            const email = emailInput.value.trim();
            
            if (!email) {
                e.preventDefault();
                showToast("❌ Ingresa tu correo institucional", "error");
                emailInput.focus();
                return;
            }
            
            if (!email.includes("@") || !email.includes(".")) {
                e.preventDefault();
                showToast("❌ Ingresa un correo válido", "error");
                emailInput.focus();
                return;
            }
        });

        // Inicializar
        processUrlMessage();
    </script>
</body>
</html>