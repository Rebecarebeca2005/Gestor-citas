// ===============================
//   VARIABLES
// ===============================
const modalCita = document.getElementById("modalCita");
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

function cargarDisponibilidad(fecha) {

    fetch(
        "index.php?pagina=calendarioAñadir&ajax=disponibilidad&fecha=" + fecha
    )

    .then(res => res.json())

    .then(data => {

        const select =
            document.querySelector(
                "select[name='id_disponibilidad']"
            );

        if (!select) return;

        // limpiar
        select.innerHTML = "";

        // opción inicial
        const opcionInicial =
            document.createElement("option");

        opcionInicial.value = "";

        opcionInicial.textContent =
            "Selecciona horario disponible";

        opcionInicial.disabled = true;

        opcionInicial.selected = true;

        select.appendChild(opcionInicial);

        // =========================
        // NO HAY HORAS
        // =========================
        if (data.length === 0) {

            const sinHoras =
                document.createElement("option");

            sinHoras.value = "sin_horas";

            sinHoras.textContent =
                "No hay horarios disponibles";

            sinHoras.disabled = true;

            select.appendChild(sinHoras);

            return;
        }

        // =========================
        // PINTAR HORAS
        // =========================
        data.forEach(d => {

            const option =
                document.createElement("option");

            option.value =
                d.id_disponibilidad;

            option.textContent =
                `${d.hora_inicio.substring(0,5)}
                - 
                ${d.hora_fin.substring(0,5)}`;

            select.appendChild(option);
        });
    })

    .catch(error => {

        console.error(
            "Error cargando disponibilidad:",
            error
        );
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

const popupClose = document.getElementById("popup-close");

if (popupClose) {

    popupClose.addEventListener("click", () => {

        document
            .getElementById("popup")
            .classList.add("hidden");
    });
}

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