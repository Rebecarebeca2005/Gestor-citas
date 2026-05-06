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

    for (let i = 1; i <= diasMes; i++) {

        const div = document.createElement("div");
        div.classList.add("dia");

        const numero = document.createElement("span");
        numero.textContent = i;
        div.appendChild(numero);

        const fecha = `${anio}-${String(mes+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`;

        const fechaCelda = new Date(anio, mes, i);
        fechaCelda.setHours(0,0,0,0);

        const esPasado = fechaCelda < fechaHoy;

        if (esPasado) {
            div.classList.add("dia-deshabilitado");
            div.style.pointerEvents = "none";
            div.style.opacity = "0.45";
        } else {
            // SOLO AÑADIR CITA
            div.onclick = () => abrirCitasDelDia(fecha);
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
