// ===============================
//   VARIABLES
// ===============================
const modalCita = document.getElementById("modalCita");
const grid = document.getElementById("calGrid");

let hoy = new Date();
let mes = hoy.getMonth();
let anio = hoy.getFullYear();

const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];

// ===============================
//   RENDERIZAR CALENDARIO
// ===============================
function renderCalendarioListar() {

    grid.innerHTML = "";

    const fechaHoy = new Date();
    fechaHoy.setHours(0,0,0,0);

    const diasMes = new Date(anio, mes + 1, 0).getDate();

    // ===============================
    //   PRIMER DÍA DEL MES
    // ===============================
    let primerDia = new Date(anio, mes, 1).getDay();

    // Convertir domingo (0) en 7
    primerDia = primerDia === 0 ? 7 : primerDia;

    // ===============================
    //   HUECOS VACÍOS
    // ===============================
    for (let i = 1; i < primerDia; i++) {

        const empty = document.createElement("div");

        empty.classList.add("dia-vacio");

        grid.appendChild(empty);
    }

    // ===============================
    //   DÍAS DEL MES
    // ===============================
    for (let i = 1; i <= diasMes; i++) {

        const div = document.createElement("div");
        div.classList.add("dia");

        const numero = document.createElement("span");
        numero.textContent = i;
        div.appendChild(numero);

        const fecha = `${anio}-${String(mes+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`;
        
        // ===============================
//   CONTAR CITAS DEL DÍA
// ===============================
if (
    typeof diasConCitas !== "undefined"
    &&
    Array.isArray(diasConCitas)
) {

    const totalCitas =
        diasConCitas.filter(
            d => d === fecha
        ).length;

    if (totalCitas > 0) {

        const contenedorPuntos =
            document.createElement("div");

        contenedorPuntos.classList.add(
            "contenedor-puntos"
        );

        for (
            let p = 0;
            p < totalCitas;
            p++
        ) {

            const punto =
                document.createElement("div");

            punto.classList.add(
                "punto-cita"
            );

            contenedorPuntos.appendChild(
                punto
            );
        }

        div.appendChild(
            contenedorPuntos
        );
    }
}
// ===============================
//   PUNTOS CANCELADAS
// ===============================
if (
    typeof diasCanceladas !== "undefined"
    &&
    Array.isArray(diasCanceladas)
) {

    const totalCanceladas =
        diasCanceladas.filter(
            d => d === fecha
        ).length;

    if (totalCanceladas > 0) {

        const contenedorCanceladas =
            document.createElement("div");

        contenedorCanceladas.classList.add(
            "contenedor-puntos-cancelados"
        );

        const maxPuntos =
            Math.min(totalCanceladas, 4);

        for (
            let p = 0;
            p < maxPuntos;
            p++
        ) {

            const punto =
                document.createElement("div");

            punto.classList.add(
                "punto-cancelado"
            );

            contenedorCanceladas.appendChild(
                punto
            );
        }

        div.appendChild(
            contenedorCanceladas
        );
    }
}
        const fechaCelda = new Date(anio, mes, i);
        fechaCelda.setHours(0,0,0,0);

        if (
            fechaCelda.getTime()
            ===
            fechaHoy.getTime()
        ) {

            div.classList.add(
                "hoy-marcado"
            );
        }

        const esPasado = fechaCelda < fechaHoy;

        if (esPasado) {
            div.classList.add("dia-deshabilitado");
            div.style.pointerEvents = "none";
            div.style.opacity = "0.45";
        } else {
            div.onclick = () => abrirCitasDelDiaListar(fecha);
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText =
        `${meses[mes]} ${anio}`;
}

// ===============================
//   ABRIR MODAL CITAS
// ===============================
function abrirCitasDelDiaListar(fecha) {

    fetch(
        "index.php?pagina=citasPorDiaAdminAjax&fecha=" + fecha
    )

    .then(res => res.json())

    .then(citas => {

        const contenedor =
            modalCita.querySelector(".lista-citas");

        contenedor.innerHTML = "";

        if (citas.length === 0) {

            contenedor.innerHTML =
                "<p class='sin-citas'>No tienes citas este día.</p>";

        } else {

            citas.forEach(c => {

                contenedor.innerHTML += `
<li class="item-cita">

    <div class="acciones-cita-admin">

        <button 
            class="btn-icon editar"
            onclick="event.stopPropagation(); abrirModalEditarCita(${c.id_cita})"
        >
            <img src="assets/img/lapiz.png" alt="Editar">
        </button>

        <button 
            class="btn-icon eliminar"
            onclick="event.stopPropagation(); mostrarPopupEliminar(${c.id_cita}, '${fecha}', '${c.estado}')"
        >
            <img src="assets/img/papelera-de-reciclaje.png" alt="Borrar">
        </button>

    </div>

    <h3>${c.servicio}</h3>
    
    <p>
    <strong>Cliente:</strong>
    ${c.nombre} ${c.apellidos}
</p>

<p>
    <strong>Teléfono:</strong>
    ${c.telefono}
</p>

    <p>
        <strong>Hora:</strong>
        ${c.hora_inicio.substring(0,5)}
        -
        ${c.hora_fin.substring(0,5)}
    </p>

    <p>
        <strong>Estado:</strong>
        ${c.estado}
    </p>

</li>
`;
            });
        }

        modalCita.classList.remove("hidden");
    });
}

// ===============================
//   CERRAR MODAL
// ===============================
function cerrarCita() {

    modalCita.classList.add("hidden");
}

// ===============================
//   NAVEGACIÓN
// ===============================
document.getElementById("prevMes").onclick = () => {

    mes = (mes - 1 + 12) % 12;

    renderCalendarioListar();
};

document.getElementById("nextMes").onclick = () => {

    mes = (mes + 1) % 12;

    renderCalendarioListar();
};

document.getElementById("btnHoy").onclick = () => {

    const f = new Date();

    mes = f.getMonth();
    anio = f.getFullYear();

    renderCalendarioListar();
};

// ===============================
//   ABRIR MODAL EDITAR
// ===============================
function abrirModalEditarCita(id_cita) {

    fetch(
    "index.php?pagina=citaDetalleAdminAjax&id=" + id_cita
)

    .then(res => res.json())

    .then(cita => {

        const form =
            document.getElementById(
                "formEditarCitaAdmin"
            );

        form.querySelector(
            "input[name='id_cita']"
        ).value = cita.id_cita;

        form.querySelector(
            "input[name='fecha']"
        ).value = cita.fecha;

        form.querySelector(
            "textarea[name='descripcion']"
        ).value =
            cita.descripcion || "";

        // =========================
        // ESTADO
        // =========================
        document.getElementById(
            "estadoAdmin"
        ).value = cita.estado;

        // bloquear CANCELADA si ACTIVA
        if (cita.estado === "ACTIVA") {

            document.querySelector(
                '#estadoAdmin option[value="CANCELADA"]'
            ).disabled = true;

        } else {

            document.querySelector(
                '#estadoAdmin option[value="CANCELADA"]'
            ).disabled = false;
        }

        cargarHorasDisponiblesAdmin(
            cita.fecha,
            cita.id_disponibilidad,
            cita.id_cita
        );

        document
            .getElementById(
                "modalEditarAdmin"
            )
            .classList
            .remove("hidden");
    });
}

// ===============================
//   CERRAR EDITAR
// ===============================
function cerrarEditarAdmin() {

    document
        .getElementById("modalEditarAdmin")
        .classList
        .add("hidden");
}

// ===============================
//   CARGAR HORAS
// ===============================
function cargarHorasDisponiblesAdmin(
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
            document.getElementById(
                "horaAdmin"
            );

        select.innerHTML = "";

        horas.forEach(h => {

            const option =
                document.createElement("option");

            option.value =
                h.id_disponibilidad;

            option.textContent =
                h.hora_inicio.substring(0,5);

            if (
                Number(seleccionada) ===
                Number(h.id_disponibilidad)
            ) {
                option.selected = true;
            }

            select.appendChild(option);
        });
    });
}

// ===============================
//   GUARDAR EDICIÓN ADMIN
// ===============================
const btnGuardarEdicion =
    document.getElementById(
        "btnGuardarEdicion"
    );

if (btnGuardarEdicion) {

    btnGuardarEdicion.addEventListener(
        "click",
        async () => {

            try {

                const formEditarAdmin =
                    document.getElementById(
                        "formEditarCitaAdmin"
                    );

                const fechaActual =
                    document.getElementById(
                        "fechaAdmin"
                    ).value;

                const response = await fetch(
                    "index.php?pagina=editarCitaAdminAjax",
                    {
                        method: "POST",

                        body: new FormData(
                            formEditarAdmin
                        )
                    }
                );

                const data =
                    await response.json();

                if (data.ok) {

                    cerrarEditarAdmin();

                    showPopup(
                        "Cita actualizada correctamente"
                    );

                    abrirCitasDelDiaListar(
                        fechaActual
                    );

                } else {

                    showPopup(
                        data.msg ||
                        "Error al actualizar"
                    );
                }

            } catch(error) {

                console.error(error);

                showPopup(
                    "Error del servidor"
                );
            }
        }
    );
}

// ===============================
//   VARIABLES ELIMINAR
// ===============================
let citaAEliminar = null;
let fechaAEliminar = null;

// ===============================
//   MOSTRAR POPUP ELIMINAR
// ===============================
function mostrarPopupEliminar(
    id_cita,
    fecha,
    estado
) {

    if (estado === "CANCELADA") {

        showPopup(
            "La cita ya ha sido cancelada"
        );

        return;
    }

    citaAEliminar = id_cita;
    fechaAEliminar = fecha;

    document
        .getElementById("popupEliminar")
        .classList
        .remove("hidden");
}

// ===============================
//   CANCELAR ELIMINAR
// ===============================
document
.getElementById("btnCancelarEliminar")
.addEventListener("click", () => {

    document
        .getElementById("popupEliminar")
        .classList
        .add("hidden");
});

// ===============================
//   CONFIRMAR ELIMINAR
// ===============================
document
.getElementById("btnConfirmarEliminar")
.addEventListener("click", () => {

    fetch(
        "index.php?pagina=eliminarCitaAjax",
        {
            method: "POST",

            headers: {
                "Content-Type":
                "application/x-www-form-urlencoded"
            },

            body:
                "id_cita=" + citaAEliminar
        }
    )

    .then(res => res.json())

    .then(data => {

        if (data.ok) {

            citaAEliminar = null;

            document
                .getElementById("popupEliminar")
                .classList
                .add("hidden");

            showPopup(
                "La cita ha sido cancelada"
            );

            abrirCitasDelDiaListar(
                fechaAEliminar
            );

        } else {

            showPopup(
                data.msg ||
                "Error eliminando cita"
            );
        }
    });
});

// ===============================
//   POPUP MENSAJES
// ===============================
function showPopup(msg) {

    const popup =
        document.getElementById("popup");

    const text =
        document.getElementById("popup-text");

    text.textContent = msg;

    popup.classList.remove("hidden");

    setTimeout(() => {

        popup.classList.add("hidden");

    }, 3000);
}

document
.getElementById("popup-close")
.addEventListener("click", () => {

    document
        .getElementById("popup")
        .classList
        .add("hidden");
});

// ===============================
//   INIT
// ===============================
renderCalendarioListar();