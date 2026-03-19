/* =========================
   TEMA DARK / LIGHT
========================= */

const themeToggleInput = document.getElementById("themeToggleInput");
const themeText = document.getElementById("themeText");

function aplicarTema(modo) {
  if (modo === "light") {
    document.body.classList.add("light-mode");
    themeToggleInput.checked = true;
    themeText.textContent = "Light Mode";
  } else {
    document.body.classList.remove("light-mode");
    themeToggleInput.checked = false;
    themeText.textContent = "Dark Mode";
  }
}

const temaGuardado = localStorage.getItem("theme") || "dark";
aplicarTema(temaGuardado);

themeToggleInput.addEventListener("change", () => {
  const nuevoTema = themeToggleInput.checked ? "light" : "dark";
  localStorage.setItem("theme", nuevoTema);
  aplicarTema(nuevoTema);
});

/* =========================
   TRADUCCIONES
========================= */

const translations = {
  es: {
    inicio: "Inicio",
    perfil: "Perfil",
    mensajes: "Mensajes",
    notificaciones: "Notificaciones",
    configuraciones: "Configuraciones",
    cerrarSesion: "Cerrar sesión",
    miRincon: "Mi rincón en UniMarket",
    editarPerfil: "Editar perfil",
    guardar: "Guardar",
    cancelar: "Cancelar",
    nombre: "Nombre",
    usuarioArroba: "@usuario",
    bioCorta: "Bio corta",
    tagsComa: "Tags separados por coma",
    carrera: "Carrera",
    campus: "Campus",
    emprendimientos: "Emprendimientos",
    estado: "Estado",
    sobreMi: "Sobre mí",
    misGustos: "Mis gustos",
    gustosComa: "Gustos separados por coma",
    libroPerfil: "Libro de perfil",
    zonaRetro: "Una zona estilo retro para presentarte",
    colorFavorito: "Color favorito",
    colorFavoritoLabel: "Color favorito:",
    metaActual: "Meta actual",
    metaActualLabel: "Meta actual:",
    estilo: "Estilo",
    estiloLabel: "Estilo:"
  },
  en: {
    inicio: "Home",
    perfil: "Profile",
    mensajes: "Messages",
    notificaciones: "Notifications",
    configuraciones: "Settings",
    cerrarSesion: "Log out",
    miRincon: "My corner in UniMarket",
    editarPerfil: "Edit profile",
    guardar: "Save",
    cancelar: "Cancel",
    nombre: "Name",
    usuarioArroba: "@username",
    bioCorta: "Short bio",
    tagsComa: "Tags separated by comma",
    carrera: "Major",
    campus: "Campus",
    emprendimientos: "Ventures",
    estado: "Status",
    sobreMi: "About me",
    misGustos: "My likes",
    gustosComa: "Likes separated by comma",
    libroPerfil: "Profile book",
    zonaRetro: "A retro-style area to introduce yourself",
    colorFavorito: "Favorite color",
    colorFavoritoLabel: "Favorite color:",
    metaActual: "Current goal",
    metaActualLabel: "Current goal:",
    estilo: "Style",
    estiloLabel: "Style:"
  }
};

function aplicarIdioma(lang) {
  localStorage.setItem("lang", lang);
  document.documentElement.lang = lang;

  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.dataset.i18n;
    if (translations[lang][key]) {
      el.textContent = translations[lang][key];
    }
  });

  const inputTags = document.getElementById("inputTags");
  if (inputTags) {
    inputTags.placeholder = lang === "es"
      ? "retro web, uni vibes, creative"
      : "retro web, uni vibes, creative";
  }
}

const idiomaGuardado = localStorage.getItem("lang") || "es";
aplicarIdioma(idiomaGuardado);

/* =========================
   PERFIL BASE
========================= */

const defaultProfile = {
  nombre: "Invitado",
  usuario: "@invitado",
  bio: "Sin biografía.",
  tags: ["Sin etiquetas"],
  carrera: "Sin carrera.",
  campus: "Sin campus.",
  emprendimientos: "Sin emprendimientos.",
  estado: "Sin estado.",
  sobreMi: "Sin descripción.",
  gustos: ["Sin gustos."],
  mood: "Sin mood.",
  color: "Sin color favorito.",
  meta: "Sin meta actual.",
  estilo: "Sin estilo."
};

let usuarioActual = "Invitado";
let esInvitado = true;

/* =========================
   LOCAL STORAGE
========================= */

function getProfile() {
  const saved = localStorage.getItem("unimarketProfile");
  return saved ? JSON.parse(saved) : defaultProfile;
}

function saveProfile(profile) {
  localStorage.setItem("unimarketProfile", JSON.stringify(profile));
}

/* =========================
   RENDER PERFIL
========================= */

function renderProfile() {
  const profile = getProfile();

  const nombre = esInvitado ? "Invitado" : profile.nombre;
  const usuario = esInvitado ? "@invitado" : profile.usuario;

  document.getElementById("viewNombre").textContent = nombre;
  document.getElementById("viewUsuario").textContent = usuario;
  document.getElementById("viewBio").textContent = profile.bio;

  document.getElementById("viewCarrera").textContent = profile.carrera;
  document.getElementById("viewCampus").textContent = profile.campus;
  document.getElementById("viewEmprendimientos").textContent = profile.emprendimientos;
  document.getElementById("viewEstado").textContent = profile.estado;

  document.getElementById("viewSobreMi").textContent = profile.sobreMi;
  document.getElementById("viewMood").textContent = profile.mood;
  document.getElementById("viewColor").textContent = profile.color;
  document.getElementById("viewMeta").textContent = profile.meta;
  document.getElementById("viewEstilo").textContent = profile.estilo;

  const avatar = document.getElementById("profileAvatar");
  avatar.textContent = nombre.charAt(0).toUpperCase();

  const tagsContainer = document.getElementById("viewTags");
  tagsContainer.innerHTML = "";
  profile.tags.forEach(tag => {
    const span = document.createElement("span");
    span.textContent = tag;
    tagsContainer.appendChild(span);
  });

  const gustosList = document.getElementById("viewGustos");
  gustosList.innerHTML = "";
  profile.gustos.forEach(g => {
    const li = document.createElement("li");
    li.textContent = g;
    gustosList.appendChild(li);
  });
}

/* =========================
   LLENAR INPUTS
========================= */

function fillInputs() {
  const profile = getProfile();

  document.getElementById("inputNombre").value = profile.nombre;
  document.getElementById("inputUsuario").value = profile.usuario;
  document.getElementById("inputBio").value = profile.bio;
  document.getElementById("inputTags").value = profile.tags.join(", ");
  document.getElementById("inputCarrera").value = profile.carrera;
  document.getElementById("inputCampus").value = profile.campus;
  document.getElementById("inputEmprendimientos").value = profile.emprendimientos;
  document.getElementById("inputEstado").value = profile.estado;
  document.getElementById("inputSobreMi").value = profile.sobreMi;
  document.getElementById("inputGustos").value = profile.gustos.join(", ");
  document.getElementById("inputMood").value = profile.mood;
  document.getElementById("inputColor").value = profile.color;
  document.getElementById("inputMeta").value = profile.meta;
  document.getElementById("inputEstilo").value = profile.estilo;
}

/* =========================
   MODO EDICION
========================= */

function setEditMode(edit) {
  document.getElementById("editBtn").classList.toggle("hidden", edit);
  document.getElementById("saveBtn").classList.toggle("hidden", !edit);
  document.getElementById("cancelBtn").classList.toggle("hidden", !edit);

  document.getElementById("editFieldsTop").classList.toggle("hidden", !edit);
  document.getElementById("editTagsWrap").classList.toggle("hidden", !edit);
  document.getElementById("editDetails").classList.toggle("hidden", !edit);
  document.getElementById("editSobreMiWrap").classList.toggle("hidden", !edit);
  document.getElementById("editGustosWrap").classList.toggle("hidden", !edit);
  document.getElementById("editGuestbook").classList.toggle("hidden", !edit);

  document.getElementById("viewBio").classList.toggle("hidden", edit);
  document.getElementById("viewDetails").classList.toggle("hidden", edit);
  document.getElementById("viewSobreMi").classList.toggle("hidden", edit);
  document.getElementById("viewGustos").classList.toggle("hidden", edit);

  if (edit) fillInputs();
}

/* =========================
   BLOQUEO SI ES INVITADO
========================= */

function bloquearEdicion() {
  const editBtn = document.getElementById("editBtn");

  if (esInvitado) {
    editBtn.disabled = true;
    editBtn.textContent = (localStorage.getItem("lang") || "es") === "en"
      ? "Profile not editable"
      : "Perfil no editable";
    editBtn.style.opacity = "0.5";
    editBtn.style.cursor = "not-allowed";
  }

  const inputUsuario = document.getElementById("inputUsuario");
  if (inputUsuario) {
    inputUsuario.disabled = true;
    inputUsuario.style.opacity = "0.6";
  }
}

/* =========================
   SESION DEL USUARIO
========================= */

function cargarSesionUsuario() {
  fetch("obtener_sesion.php")
    .then(res => res.json())
    .then(data => {
      if (data.usuario && data.usuario !== "") {
        usuarioActual = data.usuario;
        esInvitado = false;

        let profile = getProfile();
        profile.nombre = data.usuario;
        profile.usuario = "@" + data.usuario.toLowerCase().replace(/\s/g, "");
        saveProfile(profile);
      }

      renderProfile();
      bloquearEdicion();
    })
    .catch(() => {
      renderProfile();
      bloquearEdicion();
    });
}

/* =========================
   BOTONES
========================= */

document.getElementById("editBtn").onclick = () => {
  if (!esInvitado) setEditMode(true);
};

document.getElementById("cancelBtn").onclick = () => {
  setEditMode(false);
};

document.getElementById("saveBtn").onclick = () => {
  const nuevoPerfil = {
    nombre: document.getElementById("inputNombre").value,
    usuario: getProfile().usuario,
    bio: document.getElementById("inputBio").value,
    tags: document.getElementById("inputTags").value.split(",").map(t => t.trim()).filter(Boolean),
    carrera: document.getElementById("inputCarrera").value,
    campus: document.getElementById("inputCampus").value,
    emprendimientos: document.getElementById("inputEmprendimientos").value,
    estado: document.getElementById("inputEstado").value,
    sobreMi: document.getElementById("inputSobreMi").value,
    gustos: document.getElementById("inputGustos").value.split(",").map(g => g.trim()).filter(Boolean),
    mood: document.getElementById("inputMood").value,
    color: document.getElementById("inputColor").value,
    meta: document.getElementById("inputMeta").value,
    estilo: document.getElementById("inputEstilo").value
  };

  if (nuevoPerfil.tags.length === 0) nuevoPerfil.tags = defaultProfile.tags;
  if (nuevoPerfil.gustos.length === 0) nuevoPerfil.gustos = defaultProfile.gustos;

  saveProfile(nuevoPerfil);
  renderProfile();
  setEditMode(false);
};

/* =========================
   INICIO
========================= */

cargarSesionUsuario();