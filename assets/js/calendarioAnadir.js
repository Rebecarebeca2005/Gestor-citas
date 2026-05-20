// ===============================
//   CALENDARIO PARA AÑADIR CITA
// ===============================
const grid = document.getElementById("calGrid");

let hoy = new Date();
let mes = hoy.getMonth();
let anio = hoy.getFullYear();
const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];
function renderCalendarioAñadir() {

    grid.innerHTML = "";

    const fechaHoy = new Date();
    fechaHoy.setHours(0,0,0,0);

    const diasMes = new Date(anio, mes + 1, 0).getDate();

    // ===============================
    //   PRIMER DÍA DEL MES
    // ===============================
    let primerDia = new Date(anio, mes, 1).getDay();

    // Convertir domingo (0) a 7
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
    + String(fechaObj.getDate()).padStart(2, "0");
        
        // ===============================
//   PUNTOS DE CITAS
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

const esPasado =
    fechaCelda.getTime()
    <
    hoyLocal.getTime();

        if (esPasado) {
            div.classList.add("dia-deshabilitado");
            div.style.pointerEvents = "none";
            div.style.opacity = "0.45";
        } else {
            // SOLO AÑADIR CITA
            div.onclick = () => abrirCita(fecha);
        }

        // Marcar hoy
        if (
            i === fechaHoy.getDate() &&
            mes === fechaHoy.getMonth() &&
            anio === fechaHoy.getFullYear()
        ) {
            div.classList.add("hoy-marcado");
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText =
        `${meses[mes]} ${anio}`;
}

// Navegación
document.getElementById("prevMes").onclick = () => { mes = (mes - 1 + 12) % 12; renderCalendarioAñadir(); };
document.getElementById("nextMes").onclick = () => { mes = (mes + 1) % 12; renderCalendarioAñadir(); };
document.getElementById("btnHoy").onclick = () => { const f=new Date(); mes=f.getMonth(); anio=f.getFullYear(); renderCalendarioAñadir(); };

// INIT
renderCalendarioAñadir();
