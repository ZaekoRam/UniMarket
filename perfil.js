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

function getProfile() {
  const saved = localStorage.getItem("unimarketProfile");
  return saved ? JSON.parse(saved) : defaultProfile;
}

function saveProfile(profile) {
  localStorage.setItem("unimarketProfile", JSON.stringify(profile));
}

function renderProfile() {
  const profile = getProfile();

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
  avatar.textContent = profile.nombre.trim().charAt(0).toUpperCase();

  const tagsContainer = document.getElementById("viewTags");
  tagsContainer.innerHTML = "";
  profile.tags.forEach(tag => {
    const span = document.createElement("span");
    span.textContent = tag;
    tagsContainer.appendChild(span);
  });

  const gustosList = document.getElementById("viewGustos");
  gustosList.innerHTML = "";
  profile.gustos.forEach(gusto => {
    const li = document.createElement("li");
    li.textContent = gusto;
    gustosList.appendChild(li);
  });
}

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

function setEditMode(editing) {
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

  if (editing) fillInputs();
}

document.getElementById("editBtn").addEventListener("click", () => {
  setEditMode(true);
});

document.getElementById("cancelBtn").addEventListener("click", () => {
  setEditMode(false);
});

document.getElementById("saveBtn").addEventListener("click", () => {
  const nuevoPerfil = {
    nombre: document.getElementById("inputNombre").value.trim() || defaultProfile.nombre,
    usuario: document.getElementById("inputUsuario").value.trim() || defaultProfile.usuario,
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

  saveProfile(nuevoPerfil);
  renderProfile();
  setEditMode(false);
});

renderProfile();