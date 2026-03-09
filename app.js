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

// TEMA
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

// IDIOMA
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
    password: "Contraseña",
    fullname: "Nombre completo",
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
    password: "Password",
    fullname: "Full name",
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

  document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
    const key = el.dataset.i18nPlaceholder;
    if (translations[lang][key]) {
      el.placeholder = translations[lang][key];
    }
  });

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

// OJITO CONTRASEÑA
document.querySelectorAll(".toggle-pass").forEach(icon => {
  icon.addEventListener("click", () => {
    const input = document.getElementById(icon.dataset.target);

    if (input.type === "password") {
      input.type = "text";
      icon.src = "img/ojo.png";
    } else {
      input.type = "password";
      icon.src = "img/contrasena-de-ojo.png";
    }
  });
});