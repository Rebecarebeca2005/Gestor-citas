/* ===== VARIABLES ===== */
const modalCita = document.getElementById("modalCita");
const grid = document.getElementById("calGrid");

/* ===== VARIABLES DE FECHA ===== */
let hoy = new Date();
let mes = hoy.getMonth();
let anio = hoy.getFullYear();

const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];

/* ===== RENDERIZACIÓN DEL CALENDARIO ===== */

/*
    Función principal del script, genera
    dinámicamente todos los días del mes
    para consultar las citas existentes.
*/

function renderCalendarioListar() {

    grid.innerHTML = ""; //Limpia cualquier contenido previo

    const fechaHoy = new Date();
    fechaHoy.setHours(0,0,0,0); //Obtenemos la fecha actual y ponemos la hora 0 para comparar solo días

    const diasMes = new Date(anio, mes + 1, 0).getDate(); //Calculamos cuantos días tiene el mes seleccionado

    /* ===== PRIMER DIA DEL MES ===== */
    let primerDia = new Date(anio, mes, 1).getDay(); //Se obtiene qué día de la semana corresponde al día 1

    primerDia = primerDia === 0 ? 7 : primerDia; //Convertimos domingo (0) a 7

    /* ===== HUECOS VACÍOS ===== */
    for (let i = 1; i < primerDia; i++) { //Generamos espacios vacíos antes del primer día del mes

        const empty = document.createElement("div"); //Creamos la celda vacía

        empty.classList.add("dia-vacio");

        grid.appendChild(empty);
    }

    /* ===== DÍAS DEL MES ===== */
    for (let i = 1; i <= diasMes; i++) { //Recorremos todos los días del mes

        const div = document.createElement("div"); //Creamos el contenedor del día
        div.classList.add("dia");

        const numero = document.createElement("span"); //Insertamos el número correspondiente al día
        numero.textContent = i;
        div.appendChild(numero);

        const fecha = `${anio}-${String(mes+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`; //Generamos la fecha completa en formato YYYY-MM-DD
        
         /* ===== CONTADOR DE CITAS POR DÍA ===== */

        if (
            typeof diasConCitas !== "undefined"
            &&
            Array.isArray(diasConCitas) //Comprobamos que exista un array con días que contienen citas
        ) {

            const totalCitas = //Contamos cuántas citas existen para esa fecha
                diasConCitas.filter(
                    d => d === fecha
                ).length;

            if (totalCitas > 0) { //Si existen citas...

                const contenedorPuntos =
                    document.createElement("div"); //Creamos el contenedor de puntos

                contenedorPuntos.classList.add(
                    "contenedor-puntos"
                );

                for (
                    let p = 0;
                    p < totalCitas; //Creamos los indicadores visuales
                    p++
                ) {

                    const punto =
                        document.createElement("div"); //Creamos cada punto

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

        /* ===== PUNTOS DE CITAS CANCELADAS ===== */

        if (
            typeof diasCanceladas !== "undefined"
            &&
            Array.isArray(diasCanceladas) //Comprobamos que exista un array con citas canceladas
        ) {

            const totalCanceladas = //Contamos las cancelaciones de la fecha
                diasCanceladas.filter(
                    d => d === fecha
                ).length;

            if (totalCanceladas > 0) { //Si existen cancelaciones...

                const contenedorCanceladas =
                    document.createElement("div"); //Creamos el contenedor de puntos cancelados

                contenedorCanceladas.classList.add(
                    "contenedor-puntos-cancelados"
                );

                const maxPuntos =
                    Math.min(totalCanceladas, 4); //Limitamos a un máximo de 4 indicadores

                for (
                    let p = 0;
                    p < maxPuntos;
                    p++
                ) {

                    const punto =
                        document.createElement("div"); //Creamos cada punto de cancelación

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

        /* ===== MARCAR DÍA ACTUAL ===== */

        if (
            fechaCelda.getTime()
            ===
            fechaHoy.getTime()
        ) {

            div.classList.add(
                "hoy-marcado"
            ); //Resaltamos visualmente el día actual
        }

        /* ===== DESHABILITAR FECHAS PASADAS ===== */

        const esPasado = fechaCelda < fechaHoy; //Determina si el día pertenece al pasado

        if (esPasado) { //Si pertenece al pasado...

            div.classList.add("dia-deshabilitado"); //Se deshabilita
            div.style.pointerEvents = "none"; //No se puede clicar
            div.style.opacity = "0.45"; //Añadimos opacidad

        } else {

            div.onclick = () => abrirCitasDelDiaListar(fecha); //Si es válido, permite consultar las citas del día
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText = //Actualizamos el encabezado con el mes y año mostrados
        `${meses[mes]} ${anio}`;
}

/* ===== ABRIR MODAL CON LAS CITAS DEL DÍA ===== */

/*
    Esta función obtiene las citas del día seleccionado
    y las muestra dentro de una ventana modal.
*/
function abrirCitasDelDiaListar(fecha) {

    fetch(
        "index.php?pagina=citasPorDiaAdminAjax&fecha=" + fecha
    ) //Ajax, envía la solicitud al servidor indicando la fecha seleccionada

    .then(res => res.json())

    .then(citas => { //Convertimos la respuesta en objeto JavaScript

        const contenedor =
            modalCita.querySelector(".lista-citas");

        contenedor.innerHTML = ""; //Limpiamos las citas mostradas anteriormente

        if (citas.length === 0) { //Si no existen citas...

            contenedor.innerHTML =
                "<p class='sin-citas'>No tienes citas este día.</p>";

        } else {

            citas.forEach(c => { //Si existen, recorremos todas las citas y mostramos sus datos

                contenedor.innerHTML += `
<li class="item-cita">

    <!-- Acciones disponibles para el administrador -->
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

    <!-- Información de la cita -->
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

        modalCita.classList.remove("hidden"); //Mostramos la ventana modal con las citas
    });
}

/* ===== CERRAMOS MODAL ===== */
function cerrarCita() {

    modalCita.classList.add("hidden");
}

/* ===== NAVEGACIÓN ENTRE MESES ===== */

document.getElementById("prevMes").onclick = () => { //Mes anterior

    mes = (mes - 1 + 12) % 12;

    renderCalendarioListar();
};

document.getElementById("nextMes").onclick = () => { //Mes siguiente

    mes = (mes + 1) % 12;

    renderCalendarioListar();
};

document.getElementById("btnHoy").onclick = () => { //Mes actual, restablece el calendario a mes y año actual

    const f = new Date();

    mes = f.getMonth();
    anio = f.getFullYear();

    renderCalendarioListar();
};

/* ===== ABRIR MODAL EDITAR ===== */

/*
    Obtiene la información completa de la cita
    seleccionada y carga los datos en el formulario.
*/
function abrirModalEditarCita(id_cita) {

    fetch(
        "index.php?pagina=citaDetalleAdminAjax&id=" + id_cita
    ) //Solicita al servidor los datos completos de la cita

    .then(res => res.json())

    .then(cita => { //Rellenamos automáticamente los campos del formulario

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
            cita.descripcion || ""; //Si no existe descripción se deja vacío

        /* ===== ESTADO DE LA CITA ===== */

        document.getElementById(
            "estadoAdmin"
        ).value = cita.estado; //Asignamos el estado actual

        cargarHorasDisponiblesAdmin(
            cita.fecha,
            cita.id_disponibilidad,
            cita.id_cita
        ); //Cargamos las horas disponibles para la cita

        document
            .getElementById(
                "modalEditarAdmin"
            )
            .classList
            .remove("hidden"); //Mostramos la ventana de edición
    });
}

/* ===== CERRAMOS MODAL DE EDICIÓN ===== */
function cerrarEditarAdmin() {

    document
        .getElementById("modalEditarAdmin")
        .classList
        .add("hidden");
}

/* ===== CARGAR HORAS DISPONIBLES ===== */

/*
    Obtiene las horas disponibles para una fecha
    determinada y las carga en el desplegable.
*/
function cargarHorasDisponiblesAdmin(
    fecha,
    seleccionada = null,
    idCita = null
) {

    fetch(
        "index.php?pagina=horasDisponiblesAjax&fecha="
        + fecha +
        "&id_cita=" + idCita
    ) //Solicita al servidor las horas disponibles

    .then(res => res.json())

    .then(horas => {

        const select =
            document.getElementById(
                "horaAdmin"
            );

        select.innerHTML = ""; //Eliminamos las opciones anteriores

        horas.forEach(h => { //Recorremos todas las horas disponibles

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
                option.selected = true; //Marcamos la hora actualmente asignada a la cita
            }

            select.appendChild(option);
        });
    });
}

/* ===== GUARDAR CAMBIOS DE LA CITA ===== */

const btnGuardarEdicion =
    document.getElementById(
        "btnGuardarEdicion"
    );

if (btnGuardarEdicion) {

    btnGuardarEdicion.addEventListener(
        "click",
        async () => { //Escucha el clic sobre el botón guardar

            try {

                const formEditarAdmin =
                    document.getElementById(
                        "formEditarCitaAdmin"
                    );

                const fechaActual =
                    document.getElementById(
                        "fechaAdmin"
                    ).value; //Obtenemos la fecha actual de la cita

                const response = await fetch(
                    "index.php?pagina=editarCitaAdminAjax",
                    {
                        method: "POST",

                        body: new FormData(
                            formEditarAdmin
                        )
                    }
                ); //Envía los datos modificados al servidor

                const data =
                    await response.json(); //Convertimos la respuesta en objeto JavaScript

                if (data.ok) { //Si la actualización se realiza correctamente

                    cerrarEditarAdmin(); //Cerramos la ventana de edición

                    showPopup(
                        "Cita actualizada correctamente"
                    ); //Mostramos mensaje de confirmación

                    abrirCitasDelDiaListar(
                        fechaActual
                    ); //Recargamos las citas del día para mostrar los cambios

                } else { //Si ocurre algún error durante la actualización

                    showPopup(
                        data.msg ||
                        "Error al actualizar"
                    );
                }

            } catch(error) { //Capturamos posibles errores del servidor

                console.error(error);

                showPopup(
                    "Error del servidor"
                );
            }
        }
    );
}

/* ===== VARIABLES PARA CANCELAR CITAS ===== */

//Almacenan temporalmente la cita seleccionada
//para realizar la cancelación
let citaAEliminar = null;
let fechaAEliminar = null;

/* ===== MOSTRAR CONFIRMACIÓN DE CANCELACIÓN ===== */

/*
    Muestra una ventana de confirmación antes
    de cancelar la cita seleccionada.
*/
function mostrarPopupEliminar(
    id_cita,
    fecha,
    estado
) {

    if (estado === "CANCELADA") { //Impide cancelar una cita que ya está cancelada

        showPopup(
            "La cita ya ha sido cancelada"
        );

        return;
    }

    citaAEliminar = id_cita; //Guardamos el identificador de la cita
    fechaAEliminar = fecha; //Guardamos la fecha de la cita

    document
        .getElementById("popupEliminar")
        .classList
        .remove("hidden"); //Mostramos la ventana de confirmación
}

/* ===== CANCELAR ELIMINACIÓN ===== */

//Oculta la ventana sin realizar ninguna acción
document
.getElementById("btnCancelarEliminar")
.addEventListener("click", () => {

    document
        .getElementById("popupEliminar")
        .classList
        .add("hidden");
});

/* ===== CONFIRMAR CANCELACIÓN ===== */

/*
    Envía la solicitud al servidor para
    cancelar la cita seleccionada.
*/
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
    ) //Solicita al servidor la cancelación de la cita

    .then(res => res.json())

    .then(data => {

        if (data.ok) { //Si la cita se cancela correctamente

            citaAEliminar = null;

            document
                .getElementById("popupEliminar")
                .classList
                .add("hidden"); //Ocultamos la ventana de confirmación

            showPopup(
                "La cita ha sido cancelada"
            ); //Mostramos mensaje de confirmación

            abrirCitasDelDiaListar(
                fechaAEliminar
            ); //Actualizamos el listado mostrado en pantalla

        } else { //Si ocurre algún error durante la cancelación

            showPopup(
                data.msg ||
                "Error eliminando cita"
            );
        }
    });
});

/* ===== MENSAJES EMERGENTES ===== */

/*
    Muestra mensajes informativos al usuario
    de forma temporal.
*/
function showPopup(msg) {

    const popup =
        document.getElementById("popup");

    const text =
        document.getElementById("popup-text");

    text.textContent = msg; //Insertamos el mensaje recibido

    popup.classList.remove("hidden"); //Mostramos la ventana emergente

    setTimeout(() => {

        popup.classList.add("hidden");

    }, 3000); //Oculta automáticamente el mensaje tras unos segundos
}

/* ===== CIERRE MANUAL DEL POPUP ===== */

//Permite cerrar manualmente el mensaje emergente
document
.getElementById("popup-close")
.addEventListener("click", () => {

    document
        .getElementById("popup")
        .classList
        .add("hidden");
});

/* ===== INIT ===== */

/*
    Su función es generar automáticamente
    el calendario del mes actual para que
    el administrador pueda consultar,
    editar y cancelar citas.
*/
renderCalendarioListar();