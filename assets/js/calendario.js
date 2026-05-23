// ===============================
//   VARIABLES PRINCIPALES
// ===============================
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
function renderCalendario() {

    grid.innerHTML = "";

    const fechaHoy = new Date();
    fechaHoy.setHours(0,0,0,0);

    const diasMes = new Date(anio, mes + 1, 0).getDate();

    // Día de la semana del día 1
    // getDay(): 0 = domingo, 1 = lunes...
    let primerDia = new Date(anio, mes, 1).getDay();

    // Convertir domingo (0) a 7
    primerDia = primerDia === 0 ? 7 : primerDia;

    // ===============================
    //   HUECOS VACÍOS ANTES DEL DÍA 1
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

        const fechaCelda = new Date(anio, mes, i);
        fechaCelda.setHours(0,0,0,0);

        const esPasado = fechaCelda < fechaHoy;

        // Marcar hoy
        if (
            i === fechaHoy.getDate() &&
            mes === fechaHoy.getMonth() &&
            anio === fechaHoy.getFullYear()
        ) {
            div.classList.add("hoy-marcado");
        }

        // Bloquear días pasados
        if (esPasado) {
            div.classList.add("dia-deshabilitado");
            div.style.pointerEvents = "none";
            div.style.opacity = "0.45";
        }

        div.onclick = () =>
            abrirCita(
                `${anio}-${String(mes+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`
            );

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText =
        meses[mes] + " " + anio;
}

// ===============================
//   NAVEGACIÓN ENTRE MESES
// ===============================
document.getElementById("prevMes").onclick = () => {
    mes = (mes - 1 + 12) % 12;
    renderCalendario();
};

document.getElementById("nextMes").onclick = () => {
    mes = (mes + 1) % 12;
    renderCalendario();
};

document.getElementById("btnHoy").onclick = () => {
    const fecha = new Date();
    mes = fecha.getMonth();
    anio = fecha.getFullYear();
    renderCalendario();
};

// ===============================
//   INICIALIZACIÓN
// ===============================
renderCalendario();
