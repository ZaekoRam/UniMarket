const container = document.getElementById("container");
const signUpMini = document.getElementById("signUpMini");
const signInMini = document.getElementById("signInMini");

function setLogin() {
  container?.classList.add("login-active");
  container?.classList.remove("register-active");
}

function setRegister() {
  container?.classList.add("register-active");
  container?.classList.remove("login-active");
}

setLogin();

/* 🔥 FIX: recalcular mirada al cambiar layout */
signUpMini?.addEventListener("click", () => {
  setRegister();
  setTimeout(() => {
    const active = document.activeElement;
    if (active && active.tagName === "INPUT") {
      mirarInput(active);
    }
  }, 50);
});

signInMini?.addEventListener("click", () => {
  setLogin();
  setTimeout(() => {
    const active = document.activeElement;
    if (active && active.tagName === "INPUT") {
      mirarInput(active);
    }
  }, 50);
});

/* =========================
   THEME
========================= */
const themeBtn = document.getElementById("themeBtn");
const themeEmoji = document.getElementById("themeEmoji");
const themeText = document.getElementById("themeText");

function updateThemeTextByLanguage(lang) {
  const isLight = document.body.classList.contains("light");
  if (themeText) {
    themeText.textContent = isLight
      ? (lang === "es" ? "Modo claro" : "Light mode")
      : (lang === "es" ? "Modo oscuro" : "Dark mode");
  }
}

function setTheme(light) {
  document.body.classList.toggle("light", light);
  localStorage.setItem("theme", light ? "light" : "dark");

  if (themeEmoji) {
    themeEmoji.textContent = light ? "☀️" : "🌙";
  }

  updateThemeTextByLanguage(localStorage.getItem("lang") || "es");
}

setTheme(localStorage.getItem("theme") === "light");

themeBtn?.addEventListener("click", () => {
  setTheme(!document.body.classList.contains("light"));
});

/* =========================
   LANGUAGE
========================= */
const langBtn = document.getElementById("langBtn");
const langText = document.getElementById("langText");
const langFlag = document.getElementById("langFlag");

const translations = {
  es: {
    back: "Inicio",
    loginTitle: "Inicio de sesión",
    loginSubtitle: "Entra con tus datos",
    forgot: "¿Olvidó su contraseña?",
    loginButton: "Iniciar",
    miniLoginTitle: "Inicio de sesión",
    miniLogin: "Inicia sesión",
    registerTitle: "Registro",
    registerSubtitle: "Crea tu cuenta",
    miniRegisterTitle: "Registro",
    miniRegister: "Quiero registrarme",
    registerButton: "Registrarse",
    username: "Nombre de usuario",
    fullname: "Nombre completo",
    password: "Contraseña",
    account: "Número de cuenta",
    email: "Correo institucional"
  },
  en: {
    back: "Home",
    loginTitle: "Sign in",
    loginSubtitle: "Enter your details",
    forgot: "Forgot your password?",
    loginButton: "Login",
    miniLoginTitle: "Sign in",
    miniLogin: "Sign in",
    registerTitle: "Register",
    registerSubtitle: "Create your account",
    miniRegisterTitle: "Register",
    miniRegister: "Create account",
    registerButton: "Register",
    username: "Username",
    fullname: "Full name",
    password: "Password",
    account: "Student ID",
    email: "Institutional email"
  }
};

function setLanguage(lang) {
  localStorage.setItem("lang", lang);

  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.dataset.i18n;
    if (translations[lang] && translations[lang][key]) {
      el.textContent = translations[lang][key];
    }
  });

  const glitchHome = document.querySelector(".glitch-home-glitch");
  if (glitchHome) {
    glitchHome.textContent = lang === "es" ? "Inicio" : "Home";
  }

  if (lang === "es") {
    if (langFlag) {
      langFlag.src = "img/es.png";
      langFlag.alt = "Español";
    }
    if (langText) {
      langText.textContent = "Español";
    }
  } else {
    if (langFlag) {
      langFlag.src = "img/en.png";
      langFlag.alt = "English";
    }
    if (langText) {
      langText.textContent = "English";
    }
  }

  updateThemeTextByLanguage(lang);
}

setLanguage(localStorage.getItem("lang") || "es");

langBtn?.addEventListener("click", () => {
  const current = localStorage.getItem("lang") || "es";
  setLanguage(current === "es" ? "en" : "es");
});

/* =========================
   ROBOT
========================= */
const robot = document.getElementById("loginRobot");
const ojos = document.getElementById("ojosRobot");
const boca = document.getElementById("bocaRobot");

const allInputs = document.querySelectorAll(
  "#loginUsuario, #passwordLogin, #registerNombreCompleto, #registerUsuario, #registerNum, #registerCorreo, #passwordRegister"
);

let idleTimer = null;
let typingTimer = null;
let blinkTimer = null;
let currentState = "";

let baseOjosX = 0;
let baseOjosY = 0;
let ojosX = 0;
let ojosY = 0;

function clearRobotStates() {
  if (!robot) return;
  robot.classList.remove("escribiendo", "cubrir", "feliz", "bailando");
}

function setRobotState(state) {
  if (!robot) return;
  if (currentState === state) return;

  clearRobotStates();

  if (state) {
    robot.classList.add(state);
  }

  currentState = state;
}

function moverOjos(x, y) {
  if (!ojos) return;

  ojosX += (x - ojosX) * 0.2;
  ojosY += (y - ojosY) * 0.2;

  ojos.style.transform = `translate(${ojosX}px, ${ojosY}px)`;
}

function resetOjos() {
  ojosX = 0;
  ojosY = 0;
  moverOjos(0, 0);
}

function bocaFeliz() {
  if (boca) boca.src = "img/boca_feliz.png";
}

function bocaNormal() {
  if (boca) boca.src = "img/boca_normal.png";
}

function ojosAbiertos() {
  if (ojos) ojos.src = "img/ojos_abiertos.png";
}

function ojosCerrados() {
  if (ojos) ojos.src = "img/ojos_cerrados.png";
}

function esInputPassword(el) {
  return el && el.type === "password";
}

function programarParpadeo() {
  clearTimeout(blinkTimer);

  const tiempo = 2500 + Math.random() * 2500;

  blinkTimer = setTimeout(() => {
    if (!ojos) return;

    ojosCerrados();
    setTimeout(() => {
      ojosAbiertos();
      programarParpadeo();
    }, 120);
  }, tiempo);
}

programarParpadeo();

function mirarInput(input) {
  if (!ojos || !input || !robot) return;

  const rectInput = input.getBoundingClientRect();
  const rectRobot = robot.getBoundingClientRect();

  const inputX = rectInput.left + rectInput.width / 2;
  const inputY = rectInput.top + rectInput.height / 2;

  const robotX = rectRobot.left + rectRobot.width / 2;
  const robotY = rectRobot.top + rectRobot.height / 2;

  let dx = (inputX - robotX) / 18;
  let dy = (inputY - robotY) / 14;

  dx = Math.max(-8, Math.min(8, dx));
  dy = Math.max(-8, Math.min(8, dy));

  baseOjosX = dx;
  baseOjosY = dy;

  moverOjos(dx, dy);
}

function mirarEscritura(input) {
  if (!input) return;

  const pos = input.selectionStart || input.value.length || 0;

  let offsetX = Math.sin(pos * 0.5) * 4;
  let offsetY = Math.sin(pos * 0.3) * 1.5;

  moverOjos(baseOjosX + offsetX, baseOjosY + offsetY);
}

/* 🔥 YA NO cubre automáticamente */
function actualizarEstadoRobot() {
  if (!robot) return;

  const active = document.activeElement;

  if (!active || active.tagName !== "INPUT") {
    setRobotState("");
    bocaNormal();
    resetOjos();
    return;
  }

  setRobotState("");
  bocaNormal();
  mirarInput(active);
}

function resetIdleTimer() {
  clearTimeout(idleTimer);

  if (robot?.classList.contains("bailando")) {
    robot.classList.remove("bailando");
    currentState = "";
    actualizarEstadoRobot();
  }

  idleTimer = setTimeout(() => {
    const active = document.activeElement;
    if (!active || active.tagName !== "INPUT") {
      setRobotState("bailando");
      bocaFeliz();
      resetOjos();
    }
  }, 5000);
}

allInputs.forEach(input => {
  input.addEventListener("focus", () => {
    clearTimeout(typingTimer);
    mirarInput(input);
    actualizarEstadoRobot();
    resetIdleTimer();
  });

  input.addEventListener("input", () => {
    clearTimeout(typingTimer);

    if (esInputPassword(input)) {
      setRobotState(""); // 🔥 CAMBIO
      bocaNormal();
      resetOjos();
      ojosAbiertos();
    } else {
      setRobotState("escribiendo");
      bocaNormal();
      ojosAbiertos();
      mirarEscritura(input);
    }

    resetIdleTimer();

    typingTimer = setTimeout(() => {
      if (document.activeElement === input) {
        actualizarEstadoRobot();
      }
    }, 140);
  });

  input.addEventListener("blur", () => {
    clearTimeout(typingTimer);

    setTimeout(() => {
      actualizarEstadoRobot();
      resetIdleTimer();
    }, 30);
  });
});

/* =========================
   TOGGLE PASSWORD
========================= */
document.querySelectorAll(".toggle-pass").forEach(icon => {
  icon.addEventListener("click", () => {
    const input = document.getElementById(icon.dataset.target);
    if (!input) return;

    const mostrando = input.type === "text";

    if (!mostrando) {
      input.type = "text";
      icon.src = "img/ojo.png";

      setRobotState("cubrir"); // 🔥 SOLO AQUÍ
      bocaNormal();
      resetOjos();
      ojosAbiertos();

    } else {
      input.type = "password";
      icon.src = "img/contrasena-de-ojo.png";

      setRobotState("");
      bocaNormal();
      mirarInput(input);
      ojosAbiertos();
    }

    resetIdleTimer();
  });
});

/* =========================
   GENERAL ACTIVITY
========================= */
["mousemove", "keydown", "click", "touchstart"].forEach(eventName => {
  document.addEventListener(eventName, resetIdleTimer, { passive: true });
});

window.addEventListener("resize", () => {
  const active = document.activeElement;
  if (active && active.tagName === "INPUT") {
    actualizarEstadoRobot();
  }
});

resetIdleTimer();
actualizarEstadoRobot();

/* =========================
   🔥 FIX BOTÓN MENÚ
========================= */

const robotToggle = document.getElementById("robotToggle");
const sideTools = document.getElementById("sideTools");

robotToggle?.addEventListener("click", () => {
  console.log("CLICK ROBOT 😎");
  sideTools.classList.toggle("open");
});