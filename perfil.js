const themeToggleInput = document.getElementById("themeToggleInput");
const themeText = document.getElementById("themeText");

// ==========================================
// 🌐 SISTEMA DE TRADUCCIÓN PARA PERFIL
// ==========================================
const translationsPerfil = {
  es: {
    // Sidebar
    inicio: "Inicio",
    perfil: "Perfil",
    mensajes: "Mensajes",
    notificaciones: "Notificaciones",
    configuracion: "Configuración",
    cerrarSesion: "Cerrar sesión",
    darkMode: "Dark Mode",
    lightMode: "Light Mode",
    
    // Títulos y encabezados
    miRincon: "Mi rincón en UniMarket",
    perfilTitulo: "Perfil",
    editarPerfil: "Editar perfil",
    guardar: "Guardar",
    cancelar: "Cancelar",
    sobreMi: "Sobre mí",
    misGustos: "Mis gustos",
    libroPerfil: "Libro de perfil",
    presentate: "Una zona estilo retro para presentarte",
    perfilGuardado: "¡Perfil guardado y subido a la base de datos! 🚀",
    errorGuardar: "Error al guardar: ",
    
    // Etiquetas de perfil
    nombre: "Nombre",
    usuario: "@usuario",
    bioCorta: "Bio corta",
    tagsSeparados: "Tags separados por coma",
    carrera: "Carrera",
    campus: "Campus",
    emprendimientos: "Emprendimientos",
    estado: "Estado",
    mood: "Mood",
    moodLabel: "Mood:",
    colorFavorito: "Color favorito",
    colorFavoritoLabel: "Color favorito:",
    metaActual: "Meta actual",
    metaActualLabel: "Meta actual:",
    estilo: "Estilo",
    estiloLabel: "Estilo:",
    gustosSeparados: "Gustos separados por coma",
    
    // Placeholders
    placeholderTags: "retro web, uni vibes, creative",
  },
  en: {
    // Sidebar
    inicio: "Home",
    perfil: "Profile",
    mensajes: "Messages",
    notificaciones: "Notifications",
    configuracion: "Settings",
    cerrarSesion: "Log out",
    darkMode: "Dark Mode",
    lightMode: "Light Mode",
    
    // Títulos y encabezados
    miRincon: "My corner in UniMarket",
    perfilTitulo: "Profile",
    editarPerfil: "Edit profile",
    guardar: "Save",
    cancelar: "Cancel",
    sobreMi: "About me",
    misGustos: "My likes",
    libroPerfil: "Profile book",
    presentate: "A retro-style zone to introduce yourself",
    perfilGuardado: "Profile saved and uploaded to the database! 🚀",
    errorGuardar: "Error saving: ",
    
    // Etiquetas de perfil
    nombre: "Name",
    usuario: "@username",
    bioCorta: "Short bio",
    tagsSeparados: "Tags separated by comma",
    carrera: "Major",
    campus: "Campus",
    emprendimientos: "Entrepreneurships",
    estado: "Status",
    mood: "Mood",
    moodLabel: "Mood:",
    colorFavorito: "Favorite color",
    colorFavoritoLabel: "Favorite color:",
    metaActual: "Current goal",
    metaActualLabel: "Current goal:",
    estilo: "Style",
    estiloLabel: "Style:",
    gustosSeparados: "Likes separated by comma",
    
    // Placeholders
    placeholderTags: "retro web, uni vibes, creative",
  }
};

function getLang() {
  return localStorage.getItem("lang") || "es";
}

function tPerfil(key) {
  const lang = getLang();
  return translationsPerfil[lang] && translationsPerfil[lang][key] ? translationsPerfil[lang][key] : key;
}

// Aplicar idioma a los elementos con data-i18n y data-i18n-placeholder
function aplicarIdiomaPerfil() {
  const lang = getLang();
  
  // Elementos con data-i18n (texto)
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.dataset.i18n;
    if (translationsPerfil[lang] && translationsPerfil[lang][key]) {
      el.textContent = translationsPerfil[lang][key];
    }
  });

  // Elementos con data-i18n-placeholder (placeholders)
  document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
    const key = el.dataset.i18nPlaceholder;
    if (translationsPerfil[lang] && translationsPerfil[lang][key]) {
      el.placeholder = translationsPerfil[lang][key];
    }
  });

  // Actualizar texto del tema
  actualizarTextoTema();
}

function actualizarTextoTema() {
  if (themeText) {
    const isLight = document.body.classList.contains("light-mode");
    themeText.textContent = isLight ? tPerfil("lightMode") : tPerfil("darkMode");
  }
}

function aplicarTema(modo) {
  const isLight = modo === "light";
  document.body.classList.toggle("light-mode", isLight);
  themeToggleInput.checked = isLight;
  themeText.textContent = isLight ? tPerfil("lightMode") : tPerfil("darkMode");
}

const temaGuardado = localStorage.getItem("theme") || "dark";
aplicarTema(temaGuardado);

themeToggleInput.addEventListener("change", () => {
  const nuevoTema = themeToggleInput.checked ? "light" : "dark";
  localStorage.setItem("theme", nuevoTema);
  aplicarTema(nuevoTema);
});

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

// NUEVO: Obtener el perfil desde PHP
async function getProfile() {
  try {
    const respuesta = await fetch('obtener_perfil.php');
    const data = await respuesta.json();
    
    if (data.error) {
      console.warn(data.error);
      return defaultProfile;
    }

    // Como guardamos tags y gustos separados por comas, los volvemos a convertir en listas (arrays)
    if (typeof data.tags === 'string' && data.tags !== "") data.tags = data.tags.split(',');
    if (typeof data.gustos === 'string' && data.gustos !== "") data.gustos = data.gustos.split(',');

    // Mezclamos el defaultProfile con los datos de la base de datos (por si alguno está vacío)
    return { ...defaultProfile, ...data };
  } catch (error) {
    console.error("Error cargando el perfil:", error);
    return defaultProfile;
  }
}

// NUEVO: Guardar el perfil en PHP
async function saveProfile(profile) {
  try {
    const respuesta = await fetch('actualizar_perfil.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(profile)
    });
    
    const resultado = await respuesta.json();
    
    if (resultado.success) {
      alert("¡Perfil guardado y subido a la base de datos! 🚀");
    } else {
      alert("Error al guardar: " + resultado.error);
    }
  } catch (error) {
    console.error("Error al conectar con el servidor:", error);
  }
}
// Le decimos que reciba el "profile" ya cargado
function renderProfile(profile) {
  document.getElementById("viewNombre").textContent = profile.nombre;
  document.getElementById("viewUsuario").textContent = profile.usuario;
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
  // Prevenir error si el nombre está vacío
  if (profile.nombre) {
      avatar.textContent = profile.nombre.trim().charAt(0).toUpperCase();
  } else {
      avatar.textContent = "U";
  }

  const tagsContainer = document.getElementById("viewTags");
  tagsContainer.innerHTML = "";
  if (profile.tags && Array.isArray(profile.tags)) {
      profile.tags.forEach(tag => {
        const span = document.createElement("span");
        span.textContent = tag;
        tagsContainer.appendChild(span);
      });
  }

  const gustosList = document.getElementById("viewGustos");
  gustosList.innerHTML = "";
  if (profile.gustos && Array.isArray(profile.gustos)) {
      profile.gustos.forEach(gusto => {
        const li = document.createElement("li");
        li.textContent = gusto;
        gustosList.appendChild(li);
      });
  }
}

// También recibe el "profile" ya cargado
function fillInputs(profile) {
  document.getElementById("inputNombre").value = profile.nombre;
  document.getElementById("inputUsuario").value = profile.usuario;
  document.getElementById("inputBio").value = profile.bio;
  
  if (profile.tags && Array.isArray(profile.tags)) {
      document.getElementById("inputTags").value = profile.tags.join(", ");
  }

  document.getElementById("inputCarrera").value = profile.carrera;
  document.getElementById("inputCampus").value = profile.campus;
  document.getElementById("inputEmprendimientos").value = profile.emprendimientos;
  document.getElementById("inputEstado").value = profile.estado;

  document.getElementById("inputSobreMi").value = profile.sobreMi;
  
  if (profile.gustos && Array.isArray(profile.gustos)) {
      document.getElementById("inputGustos").value = profile.gustos.join(", ");
  }

  document.getElementById("inputMood").value = profile.mood;
  document.getElementById("inputColor").value = profile.color;
  document.getElementById("inputMeta").value = profile.meta;
  document.getElementById("inputEstilo").value = profile.estilo;
}

// Hacemos esta función asíncrona para que espere los datos antes de llenar los inputs
async function setEditMode(editing) {
  document.getElementById("editBtn").classList.toggle("hidden", editing);
  document.getElementById("saveBtn").classList.toggle("hidden", !editing);
  document.getElementById("cancelBtn").classList.toggle("hidden", !editing);

  document.getElementById("editFieldsTop").classList.toggle("hidden", !editing);
  document.getElementById("editTagsWrap").classList.toggle("hidden", !editing);
  document.getElementById("editDetails").classList.toggle("hidden", !editing);
  document.getElementById("editSobreMiWrap").classList.toggle("hidden", !editing);
  document.getElementById("editGustosWrap").classList.toggle("hidden", !editing);
  document.getElementById("editGuestbook").classList.toggle("hidden", !editing);

  document.getElementById("viewBio").classList.toggle("hidden", editing);
  document.getElementById("viewDetails").classList.toggle("hidden", editing);
  document.getElementById("viewSobreMi").classList.toggle("hidden", editing);
  document.getElementById("viewGustos").classList.toggle("hidden", editing);

  if (editing) {
      // ⏳ Esperamos a que la base de datos nos dé el perfil
      const profile = await getProfile();
      fillInputs(profile);
  }
}

document.getElementById("editBtn").addEventListener("click", () => {
  setEditMode(true);
});

document.getElementById("cancelBtn").addEventListener("click", () => {
  setEditMode(false);
});

// El botón de guardar también debe ser asíncrono
document.getElementById("saveBtn").addEventListener("click", async () => {
  const nuevoPerfil = {
    // Nota: Como no te dejan editar nombre ni usuario, los mantenemos ocultos en el HTML y no los sobreescribimos aquí,
    // pero si tienes inputs para ellos, descomenta las dos líneas de abajo.
    // nombre: document.getElementById("inputNombre").value.trim() || defaultProfile.nombre,
    // usuario: document.getElementById("inputUsuario").value.trim() || defaultProfile.usuario,
    bio: document.getElementById("inputBio").value.trim() || defaultProfile.bio,
    tags: document.getElementById("inputTags").value
      .split(",")
      .map(t => t.trim())
      .filter(t => t !== ""),
    carrera: document.getElementById("inputCarrera").value.trim() || defaultProfile.carrera,
    campus: document.getElementById("inputCampus").value.trim() || defaultProfile.campus,
    emprendimientos: document.getElementById("inputEmprendimientos").value.trim() || defaultProfile.emprendimientos,
    estado: document.getElementById("inputEstado").value.trim() || defaultProfile.estado,
    sobreMi: document.getElementById("inputSobreMi").value.trim() || defaultProfile.sobreMi,
    gustos: document.getElementById("inputGustos").value
      .split(",")
      .map(g => g.trim())
      .filter(g => g !== ""),
    mood: document.getElementById("inputMood").value.trim() || defaultProfile.mood,
    color: document.getElementById("inputColor").value.trim() || defaultProfile.color,
    meta: document.getElementById("inputMeta").value.trim() || defaultProfile.meta,
    estilo: document.getElementById("inputEstilo").value.trim() || defaultProfile.estilo
  };

  if (nuevoPerfil.tags.length === 0) nuevoPerfil.tags = defaultProfile.tags;
  if (nuevoPerfil.gustos.length === 0) nuevoPerfil.gustos = defaultProfile.gustos;

  // ⏳ Esperamos a que se guarde en la base de datos
  await saveProfile(nuevoPerfil);
  
  // ⏳ Volvemos a jalar los datos fresquecitos de la BD para mostrarlos
  const profileActualizado = await getProfile();
  renderProfile(profileActualizado);
  
  setEditMode(false);
});

// 🔥 LA MAGIA DE INICIO: Cuando la página carga, esperamos los datos y luego pintamos
async function iniciarPerfil() {
    const profile = await getProfile();
    renderProfile(profile);
}

// ==========================================
// 🚀 INICIALIZACIÓN
// ==========================================

// Aplicar idioma antes de cargar el perfil
aplicarIdiomaPerfil();

// Arrancamos el motor
iniciarPerfil();