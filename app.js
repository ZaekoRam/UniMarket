const container = document.getElementById("container");
const signUpMini = document.getElementById("signUpMini");
const signInMini = document.getElementById("signInMini");

function setLogin() {
  container.classList.add("login-active");
  container.classList.remove("register-active");
}

function setRegister() {
  container.classList.add("register-active");
  container.classList.remove("login-active");
}

setLogin();

signUpMini?.addEventListener("click", setRegister);
signInMini?.addEventListener("click", setLogin);

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
    if (translations[lang][key]) {
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
let happyTimer = null;

/* 👁️ PARPADEO */
function parpadear() {
  if (!ojos) return;

  ojos.src = "img/ojos_cerrados.png";

  setTimeout(() => {
    ojos.src = "img/ojos_abiertos.png";
  }, 120);
}

function loopParpadeo() {
  const tiempo = 2000 + Math.random() * 4000;

  setTimeout(() => {
    parpadear();
    loopParpadeo();
  }, tiempo);
}
loopParpadeo();

/* 👀 MOVER OJOS */
function moverOjos(x, y) {
  if (!ojos) return;
  ojos.style.transform = `translate(${x}px, ${y}px)`;
}

/* 👀 MIRAR INPUT */
function mirarInput(input) {
  if (!ojos || !input || !robot) return;

  const rectInput = input.getBoundingClientRect();
  const rectRobot = robot.getBoundingClientRect();

  const inputX = rectInput.left + rectInput.width / 2;
  const inputY = rectInput.top + rectInput.height / 2;

  const robotX = rectRobot.left + rectRobot.width / 2;
  const robotY = rectRobot.top + rectRobot.height / 2;

let dx = (inputX - robotX) / 15 - 3;  // 👈 izquierda
let dy = (inputY - robotY) / 15 + 2;  // 👈 abajo

  dx = Math.max(-10, Math.min(10, dx));
  dy = Math.max(-10, Math.min(10, dy));

  moverOjos(dx, dy);
}

/* 😊 BOCA */
function bocaFeliz() {
  if (boca) boca.src = "img/boca_feliz.png";
}

function bocaNormal() {
  if (boca) boca.src = "img/boca_normal.png";
}

/* ESTADOS */
function clearRobotStates() {
  if (!robot) return;
  robot.classList.remove("escribiendo", "cubrir", "feliz", "bailando");
}

function setRobotState(state) {
  if (!robot) return;
  clearRobotStates();
  if (state) robot.classList.add(state);
}

/* ACTUALIZAR */
function actualizarEstadoRobot() {
  if (robot && robot.classList.contains("bailando")) return;

  const active = document.activeElement;

  if (!active || active.tagName !== "INPUT") {
    setRobotState("");
    moverOjos(0, 0);
    bocaNormal();
    return;
  }

  setRobotState("cubrir");
  bocaNormal();
}

/* IDLE */
function resetIdleTimer() {
  clearTimeout(idleTimer);

  if (robot && robot.classList.contains("bailando")) {
    robot.classList.remove("bailando");
  }

  idleTimer = setTimeout(() => {
    const active = document.activeElement;

    if (!active || active.tagName !== "INPUT") {
      clearRobotStates();
      robot?.classList.add("bailando");
    }
  }, 5000);
}

/* INPUTS */
allInputs.forEach(input => {

  input.addEventListener("focus", () => {
    mirarInput(input);
    actualizarEstadoRobot();
    resetIdleTimer();
  });

  input.addEventListener("input", () => {
// TODO tu código igual arriba sin cambios...

/* INPUTS */
allInputs.forEach(input => {

  input.addEventListener("focus", () => {
    mirarInput(input);
    actualizarEstadoRobot();
    resetIdleTimer();
  });

  input.addEventListener("input", () => {

    const pos = input.selectionStart || 0;

    // 🔥 LOOP en lugar de límite
    let dx = (pos * 2) % 16 - 8;   // rango -8 a 8 en loop
    let dy = 2;

    moverOjos(dx, dy);

    setRobotState("cubrir");
    bocaNormal();
    if (ojos) ojos.src = "img/ojos_abiertos.png";

    resetIdleTimer();
  });

  input.addEventListener("blur", () => {
    setTimeout(() => {
      actualizarEstadoRobot();
      resetIdleTimer();
    }, 30);
  });

});});

  input.addEventListener("blur", () => {
    setTimeout(() => {
      actualizarEstadoRobot();
      resetIdleTimer();
    }, 30);
  });

});

/* BOTONES */
document.querySelectorAll('button[type="submit"], .ghost').forEach(btn => {

  btn.addEventListener("mouseenter", () => {
    const active = document.activeElement;

    if (active && active.tagName === "INPUT") return;

    clearTimeout(happyTimer);
    setRobotState("feliz");
    bocaFeliz();
    resetIdleTimer();
  });

  btn.addEventListener("mouseleave", () => {
    clearTimeout(happyTimer);
    actualizarEstadoRobot();
    bocaNormal();
  });

});

/* 👁️ TOGGLE PASSWORD */
document.querySelectorAll(".toggle-pass").forEach(icon => {
  icon.addEventListener("click", () => {
    const input = document.getElementById(icon.dataset.target);
    if (!input) return;

    const mostrando = input.type === "password";

    if (mostrando) {
      input.type = "text";
      icon.src = "img/ojo.png";

      setRobotState("cubrir");
      bocaNormal();
      if (ojos) ojos.src = "img/ojos_abiertos.png";

    } else {
      input.type = "password";
      icon.src = "img/contrasena-de-ojo.png";

      actualizarEstadoRobot();
    }

    resetIdleTimer();
  });
});

/* ACTIVIDAD */
["mousemove", "keydown", "click", "touchstart"].forEach(eventName => {
  document.addEventListener(eventName, resetIdleTimer, { passive: true });
});

resetIdleTimer();
actualizarEstadoRobot();