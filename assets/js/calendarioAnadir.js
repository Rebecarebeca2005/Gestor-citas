/* ===== VARIABLES ===== */
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
    para permitir añadir una cita.
*/
function renderCalendarioAñadir() {

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

        const fechaObj = new Date(
            anio,
            mes,
            i,
            12,
            0,
            0
        );

        const fecha =
            fechaObj.getFullYear()
            + "-"
            + String(fechaObj.getMonth() + 1).padStart(2, "0")
            + "-"
            + String(fechaObj.getDate()).padStart(2, "0"); //Generamos la fecha completa en formato YYYY-MM-DD

        /* ===== PUNTOS DE CITAS ===== */

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

        const fechaCelda = new Date(
            anio,
            mes,
            i,
            12,
            0,
            0
        );

        const hoyLocal = new Date(
            fechaHoy.getFullYear(),
            fechaHoy.getMonth(),
            fechaHoy.getDate(),
            12,
            0,
            0
        );

        /* ===== DESHABILITAR FECHAS PASADAS ===== */

        const esPasado =
            fechaCelda.getTime()
            <
            hoyLocal.getTime(); //Determina si el día pertenece al pasado

        if (esPasado) { //Si pertenece al pasado...
            div.classList.add("dia-deshabilitado"); //Se deshabilita
            div.style.pointerEvents = "none"; //No se puede clicar
            div.style.opacity = "0.45"; //Añadimos opacidad
        } else {
            div.onclick = () => abrirCita(fecha); //Si es válido, permite crear una cita
        }

        /* ===== MARCAR DÍA ACTUAL ===== */

        if (
            i === fechaHoy.getDate() &&
            mes === fechaHoy.getMonth() &&
            anio === fechaHoy.getFullYear()
        ) {
            div.classList.add("hoy-marcado"); //Resaltamos visualmente el día actual
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText = //Actualizamos el encabezado con el mes y año mostrados
        `${meses[mes]} ${anio}`;
}

/* ===== NAVEGACIÓN ENTRE MESES ===== */

document.getElementById("prevMes").onclick = () => { //Mes anterior
    mes = (mes - 1 + 12) % 12;
    renderCalendarioAñadir();
};

document.getElementById("nextMes").onclick = () => { //Mes siguiente
    mes = (mes + 1) % 12;
    renderCalendarioAñadir();
};

document.getElementById("btnHoy").onclick = () => { //Volver al mes actual
    const f = new Date();
    mes = f.getMonth();
    anio = f.getFullYear();
    renderCalendarioAñadir();
};

/* ===== INIT ===== */
/*
    Su función es generar automáticamente
    el calendario del mes actual para que
    el usuario pueda seleccionar una fecha
    y crear una nueva cita.
*/
renderCalendarioAñadir();