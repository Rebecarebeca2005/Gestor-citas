/* ===== VARIABLES ===== */
const modalCita = document.getElementById("modalCita");
const popupEliminar = document.getElementById("popupEliminar");
const popup = document.getElementById("popup");
const popupText = document.getElementById("popup-text");

let citaAEliminar = null;
let fechaAEliminar = null;

/* ===== ABRIR MODAL DE CITAS DEL DIA ===== */
function abrirCitasDelDia(fecha) {

    fetch("index.php?pagina=citasPorDiaAjax&fecha=" + fecha)
        .then(res => res.json())
        .then(citas => {

            const contenedor = modalCita.querySelector(".lista-citas");
            contenedor.innerHTML = "";

            if (citas.length === 0) {
                contenedor.innerHTML = "<p class='sin-citas'>No tienes citas este día.</p>";
            } else {
                citas.forEach(c => {

                    const esCancelada = c.estado === "CANCELADA";

                    contenedor.innerHTML += `
                        <li class="item-cita ${esCancelada ? "cancelada" : ""}"
                            ${!esCancelada ? `onclick="mostrarPopupEliminar(${c.id_cita}, '${fecha}', '${c.estado}')"` : ""}>
                            
                            <h3>${c.servicio}</h3>
                            <p><strong>Hora:</strong> ${c.hora_inicio.substring(0,5)} - ${c.hora_fin.substring(0,5)}</p>
                            <p><strong>Estado:</strong> ${c.estado}</p>

                            ${esCancelada ? `<p class="ya-cancelada">Esta cita ya está cancelada</p>` : ""}
                        </li>
                    `;
                });
            }

            modalCita.classList.remove("hidden");
        });
}

/* ===== MOSTRAR POP UP DE ELIMINACION ===== */
function mostrarPopupEliminar(id_cita, fecha, estado) {

    if (estado === "CANCELADA") {
        showPopup("Esta cita ya está cancelada");
        return;
    }

    citaAEliminar = id_cita;
    fechaAEliminar = fecha;

    popupEliminar.classList.remove("hidden");
}

/* ===== CANCELAR CITA ===== */
document.getElementById("btnCancelarEliminar").onclick = () => {
    popupEliminar.classList.add("hidden");
};

/* =====ELIMINAR CITA AJAX ===== */
document.getElementById("btnConfirmarEliminar").onclick = () => {

    fetch("index.php?pagina=eliminarCitaAjax", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id_cita=" + citaAEliminar
    })
    .then(res => res.json())
    .then(data => {

    if (data.ok) {

        popupEliminar.classList.add("hidden");

        showPopup("Cita eliminada correctamente");

        setTimeout(() => {

            abrirCitasDelDia(fechaAEliminar);

        }, 800);

    } else {

        showPopup(
            data.msg ||
            "Error al eliminar la cita"
        );
    }
})
.catch(() => {

    showPopup("Error del servidor");
});
};

/* ===== CERRAR EL MODAL ===== */
function cerrarCita() {
    modalCita.classList.add("hidden");
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

/* ===== POPUP ===== */
function showPopup(msg) {

    if (!popup || !popupText) return;

    popupText.textContent = msg;

    popup.classList.remove("hidden");

    setTimeout(() => {
        popup.classList.add("hidden");
    }, 3500);
}

document.getElementById("popup-close")
.addEventListener("click", () => {

    popup.classList.add("hidden");
});