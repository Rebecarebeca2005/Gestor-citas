// ===============================
//   VARIABLES
// ===============================
const modalCita = document.getElementById("modalCita");
const listaHorarios = document.getElementById("listaHorarios");
const inputDisponibilidad = document.getElementById("inputDisponibilidad");
const formCita = document.getElementById("formCita");

// ===============================
//   ABRIR MODAL PARA CREAR CITA
// ===============================
function abrirCita(fecha) {

    if (!modalCita) return;

    modalCita.classList.remove("hidden");

    // Rellenar input fecha
    const inputFecha = document.querySelector("#formCita input[name='fecha']");
    if (inputFecha) inputFecha.value = fecha;

    // Cargar horarios disponibles
    cargarDisponibilidad(fecha);
}

// ===============================
//   CERRAR MODAL
// ===============================
function cerrarCita() {
    if (!modalCita) return;

    modalCita.classList.add("hidden");

    // Resetear formulario
    if (formCita) formCita.reset();

    // Limpiar horarios
    if (listaHorarios) listaHorarios.innerHTML = "";
}

// ===============================
//   CARGAR HORARIOS DISPONIBLES
// ===============================
function cargarDisponibilidad(fecha) {

    fetch("index.php?pagina=calendarioAñadir&ajax=disponibilidad&fecha=" + fecha)
        .then(res => res.json())
        .then(data => {

            if (!listaHorarios || !inputDisponibilidad) return;

            listaHorarios.innerHTML = "";
            inputDisponibilidad.value = "";

            if (data.length === 0) {
                listaHorarios.innerHTML = "<p class='msg'>No hay horarios disponibles</p>";
                return;
            }

            data.forEach(d => {

                const div = document.createElement("div");
                div.classList.add("hora-card");

                div.textContent =
                    d.hora_inicio.substring(0,5) +
                    " - " +
                    d.hora_fin.substring(0,5);

                div.onclick = () => {
                    document.querySelectorAll(".hora-card")
                        .forEach(el => el.classList.remove("selected"));

                    div.classList.add("selected");
                    inputDisponibilidad.value = d.id_disponibilidad;
                };

                listaHorarios.appendChild(div);
            });
        });
}

// ===============================
//   SUBMIT FORMULARIO
// ===============================
if (formCita) {
    formCita.addEventListener("submit", () => {
        console.log("Enviando cita...");
    });
}

// ===============================
//   POPUP CONFIRMACIÓN
// ===============================
function showPopup(msg) {
    const popup = document.getElementById("popup");
    const text = document.getElementById("popup-text");

    text.textContent = msg;
    popup.classList.remove("hidden");

    setTimeout(() => {
        popup.classList.add("hidden");
    }, 3500);
}

document.getElementById("popup-close").addEventListener("click", () => {
    document.getElementById("popup").classList.add("hidden");
});

// Mostrar popup según parámetros URL
const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has("ok")) {
    showPopup("Cita creada correctamente");
}

if (urlParams.has("error")) {
    showPopup("Error al crear la cita");
}

if (urlParams.has("sinHora")) {
    showPopup("No se ha seleccionado una hora");
}
const botonesSiguiente = document.querySelectorAll(".btn-siguiente");
const botonesAtras = document.querySelectorAll(".btn-atras");
const secciones = document.querySelectorAll(".seccion");
const pasos = document.querySelectorAll(".pasos li");

// SIGUIENTE
botonesSiguiente.forEach((btn, i) => {
    btn.addEventListener("click", () => {
        if (secciones[i + 1]) {
            secciones[i].classList.remove("activo");
            pasos[i].classList.remove("activo");

            secciones[i + 1].classList.add("activo");
            pasos[i + 1].classList.add("activo");
        }
    });
});

// ATRÁS
botonesAtras.forEach((btn) => {
    btn.addEventListener("click", () => {

        const actual = [...secciones].findIndex(s =>
            s.classList.contains("activo")
        );

        if (actual > 0) {
            secciones[actual].classList.remove("activo");
            pasos[actual].classList.remove("activo");

            secciones[actual - 1].classList.add("activo");
            pasos[actual - 1].classList.add("activo");
        }
    });
});