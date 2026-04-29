const citas = {};
const grid = document.getElementById("calGrid");
const modalCita = document.getElementById("modalCita");

let mes = 0;
let anio = 2026;

const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];

// 1. RENDER CALENDARIO
function renderCalendario() {
    grid.innerHTML = "";

    const fechaHoy = new Date(); // Obtenemos la fecha real de hoy
    const diasMes = new Date(anio, mes + 1, 0).getDate();

    for (let i = 1; i <= diasMes; i++) {
        const div = document.createElement("div");
        div.classList.add("dia");
        div.innerText = i;

        // --- ESTO ES LO NUEVO ---
        // Comprobamos si el día que estamos dibujando es HOY
        if (i === fechaHoy.getDate() && 
            mes === fechaHoy.getMonth() && 
            anio === fechaHoy.getFullYear()) {
            div.classList.add("hoy-marcado");
        }
        // ------------------------

        div.onclick = () => abrirCita(i);
        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText = meses[mes] + " " + anio;
}

// 2. ABRIR MODAL
function abrirCita(dia) {
    modalCita.classList.remove("hidden");
    const fecha = new Date(anio, mes, dia);
    const inputFecha = document.querySelector("#formCita input[name='fecha']");
    if (inputFecha) {
        // Ajuste para evitar desfase de zona horaria al poner la fecha
        inputFecha.value = fecha.toISOString().split("T")[0];
    }
}

// 3. FUNCIÓN CERRAR (ÚNICA Y COMPLETA)
function cerrarCita() {
    if (modalCita) {
        modalCita.classList.add("hidden");
        
        // Volver al primer paso visualmente
        const secciones = document.querySelectorAll(".seccion");
        const pasosIndicador = document.querySelectorAll(".pasos li");
        
        secciones.forEach(sec => sec.classList.remove("activo"));
        pasosIndicador.forEach(p => p.classList.remove("activo"));
        
        if (secciones[0]) secciones[0].classList.add("activo");
        if (pasosIndicador[0]) pasosIndicador[0].classList.add("activo");
        
        document.getElementById("formCita").reset();
    }
}

// 4. BOTONES DE NAVEGACIÓN MES
document.getElementById("prevMes").onclick = () => {
    mes = (mes - 1 + 12) % 12;
    renderCalendario();
};

document.getElementById("nextMes").onclick = () => {
    mes = (mes + 1) % 12;
    renderCalendario();
};

document.getElementById("btnHoy").onclick = () => {
    const hoy = new Date();
    mes = hoy.getMonth();
    anio = hoy.getFullYear();
    renderCalendario();
};

// 5. LÓGICA DE PASOS (SIGUIENTE)
const botonesSiguiente = document.querySelectorAll(".btn-siguiente");
const secciones = document.querySelectorAll(".seccion");
const pasosIndicador = document.querySelectorAll(".pasos li");

botonesSiguiente.forEach((boton, indice) => {
    boton.addEventListener("click", () => {
        if (secciones[indice + 1]) {
            secciones[indice].classList.remove("activo");
            pasosIndicador[indice].classList.remove("activo");

            secciones[indice + 1].classList.add("activo");
            pasosIndicador[indice + 1].classList.add("activo");
        }
    });
});

// 6. SUBMIT FINAL
document.getElementById("formCita").addEventListener("submit", function (e) {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(this));
    console.log("Cita creada correctamente:", data);
    cerrarCita();
});

// INIT
renderCalendario();