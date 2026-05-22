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

    /* ===== FECHA ACTUAL SEGURA ===== */

    const ahora = new Date(); //Obtenemos la fecha actual del sistema

    const fechaHoy = new Date(
        ahora.getFullYear(),
        ahora.getMonth(),
        ahora.getDate()
    ); //Creamos una fecha sin horas ni minutos

    fechaHoy.setHours(12,0,0,0); //Establecemos una hora fija para evitar errores de comparación

    /* ===== DÍAS DEL MES ===== */

    const diasMes =
        new Date(anio, mes + 1, 0).getDate(); //Calculamos cuantos días tiene el mes seleccionado

    /* ===== PRIMER DIA DEL MES ===== */

    let primerDia =
        new Date(anio, mes, 1).getDay(); //Se obtiene qué día de la semana corresponde al día 1

    primerDia =
        primerDia === 0 ? 7 : primerDia; //Convertimos domingo (0) a 7

    /* ===== HUECOS VACÍOS ===== */

    for (let i = 1; i < primerDia; i++) { //Generamos espacios vacíos antes del primer día del mes

        const empty =
            document.createElement("div"); //Creamos la celda vacía

        empty.classList.add("dia-vacio");

        grid.appendChild(empty);
    }

    /* ===== DÍAS DEL MES ===== */

    for (let i = 1; i <= diasMes; i++) { //Recorremos todos los días del mes

        const div =
            document.createElement("div"); //Creamos el contenedor del día

        div.classList.add("dia");

        const numero =
            document.createElement("span"); //Insertamos el número correspondiente al día

        numero.textContent = i;

        div.appendChild(numero);

        /* ===== GENERAR FECHA SEGURA ===== */

        const year = anio;

        const month =
            String(mes + 1).padStart(2, "0");

        const day =
            String(i).padStart(2, "0");

        const fecha =
            `${year}-${month}-${day}`; //Construimos la fecha en formato YYYY-MM-DD

        /* ===== CONTADOR DE CITAS POR DÍA ===== */

        if (typeof diasConCitas !== "undefined") { //Comprobamos que exista el array de citas

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

                for (let p = 0; p < totalCitas; p++) { //Creamos un indicador por cada cita

                    const punto =
                        document.createElement("div"); //Creamos el punto

                    punto.classList.add(
                        "punto-cita"
                    );

                    contenedorPuntos.appendChild(
                        punto
                    );
                }

                div.appendChild(contenedorPuntos);
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

        /* ===== FECHA LOCAL ===== */

        const fechaCelda =
            new Date(year, mes, i); //Creamos la fecha correspondiente a la celda

        fechaCelda.setHours(12,0,0,0); //Asignamos una hora fija para comparaciones exactas

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

        const esPasado =
            fechaCelda.getTime()
            <
            fechaHoy.getTime(); //Determina si el día pertenece al pasado

        if (esPasado) { //Si pertenece al pasado...

            div.classList.add(
                "dia-deshabilitado"
            );

            div.style.pointerEvents = "none"; //No se puede clicar

            div.style.opacity = "0.45"; //Añadimos opacidad

        } else {

            div.onclick = () =>
                abrirCitasDelDiaListar(fecha); //Si es válido, permite consultar las citas del día
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
        "index.php?pagina=citasPorDiaAjax&fecha=" + fecha
    ) //Ajax, envía la solicitud al servidor indicando la fecha seleccionada

    .then(res => res.json())

    .then(citas => { //Convertimos la respuesta en objeto JavaScript

        const contenedor =
            modalCita.querySelector(".lista-citas");

        contenedor.innerHTML = "";

        if (citas.length === 0) { //Si no existen citas...

            contenedor.innerHTML =
                "<p class='sin-citas'>No tienes citas este día.</p>";

        } else {

            citas.forEach(c => { //Si existen, recorremos todas las citas y mostramos sus datos

                contenedor.innerHTML += `
                    <li class="item-cita">

                        <h3>${c.servicio}</h3>

                        <p>
                            <strong>Hora:</strong>
                            ${c.hora.substring(0,5)}
                        </p>

                        <p>
                            <strong>Estado:</strong>
                            ${c.estado}
                        </p>

                    </li>
                `;
            });
        }

        modalCita.classList.remove("hidden"); //Mostramos la ventana modal
    })

    .catch(error => { //Capturamos posibles errores de conexión o carga

        console.error(
            "Error cargando citas:",
            error
        );
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

/* ===== INIT ===== */
/*
    Su función es generar automáticamente
    el calendario del mes actual para que
    el usuario pueda consultar las citas
    asociadas a cada día.
*/
renderCalendarioListar();