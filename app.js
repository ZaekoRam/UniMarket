// ====== TOGGLE LOGIN / REGISTER ======
const container = document.getElementById("container");

const signUpMini = document.getElementById("signUpMini"); // abre registro
const signInMini = document.getElementById("signInMini"); // vuelve a login

function setLogin(){
  container.classList.add("login-active");
  container.classList.remove("register-active");
}

function setRegister(){
  container.classList.add("register-active");
  container.classList.remove("login-active");
}

// Estado inicial
setLogin();

// Botones
signUpMini.addEventListener("click", setRegister);
signInMini.addEventListener("click", setLogin);

// ====== MENÚ SEMICIRCULAR ======
const semiMenu = document.getElementById("semiMenu");
const semiToggle = document.getElementById("semiToggle");

semiToggle.addEventListener("click", () => {
  semiMenu.classList.toggle("open");
});