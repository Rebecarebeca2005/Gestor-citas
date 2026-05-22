/* ===== CITAS DEL DIA ===== */
function abrirCitasDelDia(fecha) {

    fetch(
        "index.php?pagina=citasPorDiaAjax&fecha=" + fecha
    )

    .then(res => res.json())

    .then(citas => {

        const contenedor =
            document.querySelector(".lista-citas");

        contenedor.innerHTML = "";

        if (citas.length === 0) {

            contenedor.innerHTML =
                "<p>No hay citas ese día.</p>";

        } else {

            citas.forEach(c => {

                contenedor.innerHTML += `
                    <li class="item-cita"
                        onclick="abrirModalEditarCita(${c.id_cita})">

                        <h3>${c.servicio}</h3>

                        <p>
                            ${c.hora_inicio.substring(0,5)}
                        </p>

                    </li>
                `;
            });
        }

        document
            .getElementById("modalCita")
            .classList
            .remove("hidden");
    })

    .catch(err => {
        console.error(err);
    });
}

/* ===== ABRIR EL MODAL DE EDITAR CITA ===== */
function abrirModalEditarCita(id_cita) {

    fetch(
        "index.php?pagina=citaDetalleAjax&id=" + id_cita
    )

    .then(res => res.json())

    .then(cita => {

        console.log(cita);

        const form =
            document.getElementById("formEditarCita");

        form.querySelector("input[name='id_cita']").value =
            cita.id_cita;

        form.querySelector("input[name='fecha']").value =
            cita.fecha;

        form.querySelector("textarea[name='descripcion']").value =
            cita.descripcion || "";

        cargarHorasDisponibles(
        cita.fecha,
        cita.id_disponibilidad,
        cita.id_cita,
        cita.hora_inicio.substring(0,5)
);

        document
            .getElementById("modalEditar")
            .classList
            .remove("hidden");
    })

    .catch(err => {
        console.error(err);
        alert("Error cargando cita");
    });
}

/* ===== CARGAR HORAS DISPONIBLES ===== */
function cargarHorasDisponibles(
    fecha,
    seleccionada = null,
    idCita = null
) {

    fetch(
        "index.php?pagina=horasDisponiblesAjax&fecha="
        + fecha +
        "&id_cita=" + idCita
    )

    .then(res => res.json())

    .then(horas => {

        const select =
            document.getElementById("hora");

        select.innerHTML = "";

        const primera =
            document.createElement("option");

        primera.value = "";
        primera.textContent =
            "Selecciona una hora";

        select.appendChild(primera);

        const horasUnicas = new Set(); //evitamos duplicados

        horas.forEach(h => {

            const horaTexto =
                h.hora_inicio.substring(0,5);

            if (horasUnicas.has(horaTexto)) { //evitamos repeticiones
                return;
            }

            horasUnicas.add(horaTexto);

            const option =
                document.createElement("option");

            option.value =
                h.id_disponibilidad;

            option.textContent =
                horaTexto;

            if (
                Number(seleccionada) === //seleccionamos la actual
                Number(h.id_disponibilidad)
            ) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    });
}
  
/* ===== CAMBIO DE FECHA ===== */
document
.getElementById("fecha")
.addEventListener("change", function() {

    cargarHorasDisponibles(
        this.value
    );
});

/* ===== GUARDAMOS LA CITA ===== */
const formEditar =
    document.getElementById("formEditarCita");

formEditar.addEventListener("submit", async (e) => {

    e.preventDefault();
    e.stopPropagation();

    try {

        const response = await fetch(
            "index.php?pagina=editarCitaAjax",
            {
                method: "POST",
                body: new FormData(formEditar)
            }
        );

        const texto =
            await response.text();

        console.log(texto);

        let data;

        try {

            data = JSON.parse(texto);

        } catch {

    showPopup(
        "Respuesta inválida del servidor"
    );

    return;
}

        if (data.ok) {

    showPopup("Cita actualizada correctamente");

    cerrarEditar();

    setTimeout(() => {
        location.reload();
    }, 1500);

} else {

    showPopup(
        data.msg ||
        "No se pudo actualizar la cita"
    );
}

    } catch(error) {

        console.error(error);

        showPopup("Error del servidor");
    }

    return false;
});

/* ===== CERRAMOS LOS MODALES ===== */
function cerrarCita() {

    document
        .getElementById("modalCita")
        .classList
        .add("hidden");
}

function cerrarEditar() {

    document
        .getElementById("modalEditar")
        .classList
        .add("hidden");
}

/* ===== POPUP ===== */
function showPopup(msg) {

    const popup = document.getElementById("popup");
    const text = document.getElementById("popup-text");

    if (!popup || !text) return;

    text.textContent = msg;

    popup.classList.remove("hidden");

    setTimeout(() => {
        popup.classList.add("hidden");
    }, 3500);
}

document.getElementById("popup-close")
.addEventListener("click", () => {

    document
        .getElementById("popup")
        .classList.add("hidden");
});