/* ===== ABRIR MODAL EDITAR ===== */
function abrirModalEditarCita(id_cita) {

    fetch("index.php?pagina=citaDetalleAjax&id=" + id_cita)
    .then(res => res.json())
    .then(cita => {

        const form = document.getElementById("formEditarCita");

        form.querySelector("input[name='id_cita']").value = cita.id_cita;
        form.querySelector("input[name='fecha']").value = cita.fecha;
        form.querySelector("textarea[name='descripcion']").value = cita.descripcion || "";

        cargarHorasDisponibles(cita.fecha, cita.id_disponibilidad, cita.id_cita);

        document.getElementById("modalEditar").classList.remove("hidden");
    })
    .catch(() => showPopup("Error cargando la cita"));
}

/* ===== CARGAR HORAS ===== */
function cargarHorasDisponibles(fecha, seleccionada = null, idCita = null) {

    fetch("index.php?pagina=horasDisponiblesAjax&fecha=" + fecha + "&id_cita=" + idCita)
    .then(res => res.json())
    .then(horas => {

        const select = document.getElementById("hora");
        select.innerHTML = "";

        const primera = document.createElement("option");
        primera.value = "";
        primera.textContent = "Selecciona una hora";
        select.appendChild(primera);

        const horasUnicas = new Set();

        horas.forEach(h => {
            const horaTexto = h.hora_inicio.substring(0, 5);
            if (horasUnicas.has(horaTexto)) return;
            horasUnicas.add(horaTexto);

            const option = document.createElement("option");
            option.value = h.id_disponibilidad;
            option.textContent = horaTexto;
            if (Number(seleccionada) === Number(h.id_disponibilidad)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    });
}

/* ===== CAMBIO DE FECHA ===== */
document.getElementById("fecha").addEventListener("change", function() {
    cargarHorasDisponibles(this.value);
});

/* ===== GUARDAR EDICIÓN ===== */
const formEditarCita = document.getElementById("formEditarCita");
document.getElementById("btnGuardarEdicion").addEventListener("click", async function() {

    try {
        const response = await fetch("index.php?pagina=editarCitaAjax", {
            method: "POST",
            body: new FormData(formEditarCita)
        });

        const data = await response.json();

        if (data.ok) {
            showPopup("Cita actualizada correctamente");
            cerrarEditar();
            setTimeout(() => location.reload(), 1500);
        } else {
            showPopup(data.msg || "No se pudo actualizar la cita");
        }

    } catch {
        showPopup("Error del servidor");
    }
});

/* ===== POPUP ELIMINAR ===== */
let citaAEliminar = null;

function mostrarPopupEliminar(id_cita) {
    citaAEliminar = id_cita;
    document.getElementById("popupEliminar").classList.remove("hidden");
}

document.getElementById("btnCancelarEliminar").onclick = () => {
    document.getElementById("popupEliminar").classList.add("hidden");
};

document.getElementById("btnConfirmarEliminar").onclick = () => {

    fetch("index.php?pagina=eliminarCitaAjax", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id_cita=" + citaAEliminar
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("popupEliminar").classList.add("hidden");

        if (data.ok) {
            showPopup("Cita cancelada correctamente");
            setTimeout(() => location.reload(), 1500);
        } else {
            showPopup(data.msg || "Error al cancelar la cita");
        }
    })
    .catch(() => showPopup("Error del servidor"));
};

/* ===== CERRAR MODALES ===== */
function cerrarEditar() {
    document.getElementById("modalEditar").classList.add("hidden");
}

/* ===== POPUP ===== */
function showPopup(msg) {
    const popup = document.getElementById("popup");
    const text = document.getElementById("popup-text");
    if (!popup || !text) return;
    text.textContent = msg;
    popup.classList.remove("hidden");
    setTimeout(() => popup.classList.add("hidden"), 3500);
}

document.getElementById("popup-close").addEventListener("click", () => {
    document.getElementById("popup").classList.add("hidden");
});