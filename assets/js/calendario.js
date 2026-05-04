const citas = {};
const grid = document.getElementById("calGrid");
const modalCita = document.getElementById("modalCita");

let hoy = new Date();
let mes = hoy.getMonth();
let anio = hoy.getFullYear();

let citasMes = {};

const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];

// 1. RENDER CALENDARIO
function renderCalendario() {
    grid.innerHTML = "";

    const hoy = new Date();
    hoy.setHours(0,0,0,0); 

    const diasMes = new Date(anio, mes + 1, 0).getDate();

    for (let i = 1; i <= diasMes; i++) {

        const div = document.createElement("div");
        div.classList.add("dia");

        // número del día
        const numero = document.createElement("span");
        numero.textContent = i;
        div.appendChild(numero);

        const fechaCelda = new Date(anio, mes, i);
        fechaCelda.setHours(0,0,0,0);

        const esPasado = fechaCelda < hoy;

        // marcar hoy
        if (
            i === hoy.getDate() &&
            mes === hoy.getMonth() &&
            anio === hoy.getFullYear()
        ) {
            div.classList.add("hoy-marcado");
        }

        // 🚫 bloquear días pasados
        if (esPasado) {
            div.classList.add("dia-deshabilitado");
        } else {
            div.onclick = () => abrirCita(i);
        }

        // 🔵 PUNTOS DE CITAS
        if (citasMes[i] && citasMes[i] > 0) {

            const contenedorPuntos = document.createElement("div");
            contenedorPuntos.classList.add("puntos-citas");

            const total = citasMes[i];

            for (let j = 0; j < total; j++) {
                const punto = document.createElement("span");
                punto.classList.add("punto-cita");
                contenedorPuntos.appendChild(punto);
            }

            div.appendChild(contenedorPuntos);
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText =
        meses[mes] + " " + anio;
}

// 2. ABRIR MODAL
function abrirCita(dia) {
    modalCita.classList.remove("hidden");

    const fecha = new Date(anio, mes, dia);
    const fechaStr = fecha.getFullYear() + "-" +
    String(fecha.getMonth() + 1).padStart(2, "0") + "-" +
    String(fecha.getDate()).padStart(2, "0"); //Esto salía el dia anterior x la franja horaria

    const inputFecha = document.querySelector("#formCita input[name='fecha']");
    if (inputFecha) {
        inputFecha.value = fechaStr;
    }

    cargarDisponibilidad(fechaStr);
}

// 3. CERRAR MODAL
function cerrarCita() {
    if (!modalCita) return;

    modalCita.classList.add("hidden");

    const secciones = document.querySelectorAll(".seccion");
    const pasosIndicador = document.querySelectorAll(".pasos li");

    secciones.forEach(sec => sec.classList.remove("activo"));
    pasosIndicador.forEach(p => p.classList.remove("activo"));

    if (secciones[0]) secciones[0].classList.add("activo");
    if (pasosIndicador[0]) pasosIndicador[0].classList.add("activo");

    document.getElementById("formCita").reset();
}

// 4. NAVEGACIÓN MESES
document.getElementById("prevMes").onclick = () => {
    mes = (mes - 1 + 12) % 12;
    cargarCitasMes();
};

document.getElementById("nextMes").onclick = () => {
    mes = (mes + 1) % 12;
    cargarCitasMes();
};

document.getElementById("btnHoy").onclick = () => {
    const hoy = new Date();
    mes = hoy.getMonth();
    anio = hoy.getFullYear();
    renderCalendario();
};

// 5. PASOS FORMULARIO
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

const botonesAtras = document.querySelectorAll(".btn-atras");

botonesAtras.forEach((boton, indice) => {
    boton.addEventListener("click", () => {

        const pasoActual = Array.from(secciones).findIndex(sec =>
            sec.classList.contains("activo")
        );

        if (pasoActual > 0) {
            secciones[pasoActual].classList.remove("activo");
            pasosIndicador[pasoActual].classList.remove("activo");

            secciones[pasoActual - 1].classList.add("activo");
            pasosIndicador[pasoActual - 1].classList.add("activo");
        }
    });
});

// 6. DISPONIBILIDAD AJAX
function cargarDisponibilidad(fecha) {

    fetch("index.php?pagina=disponibilidadAjax&fecha=" + fecha)
        .then(res => res.json())
        .then(data => {

            const select = document.querySelector("select[name='id_disponibilidad']");

            if (!select) return;

            select.innerHTML = "<option value=''>Selecciona horario disponible</option>";

            data.forEach(d => {
                const opt = document.createElement("option");
                opt.value = d.id_disponibilidad;
                opt.textContent =
                    d.hora_inicio.substring(0,5) +
                    " - " +
                    d.hora_fin.substring(0,5);

                select.appendChild(opt);
            });
        });
}

function cargarCitasMes() {

    fetch(`index.php?pagina=citasMesAjax&mes=${mes+1}&anio=${anio}`)
        .then(res => res.json())
        .then(data => {

            console.log("Citas recibidas:", data);

            citasMes = {};

            data.forEach(c => {

                const dia = Number(c.dia ?? c.DIA ?? c.day);
                const total = Number(c.total ?? c.TOTAL ?? 0);

                if (!isNaN(dia)) {
                    citasMes[dia] = total;
                }
            });

            renderCalendario();
        })
        .catch(err => console.error("Error AJAX:", err));
}

// 7. SUBMIT
document.getElementById("formCita").addEventListener("submit", function () {
    console.log("Enviando cita...");
});

function showPopup(msg) {
    const popup = document.getElementById("popup");
    const text = document.getElementById("popup-text");

    text.textContent = msg;
    popup.classList.remove("hidden");

    setTimeout(() => {
        popup.classList.add("hidden");
    }, 3500);
}

document.getElementById("popup-close").addEventListener("click", () => {
    document.getElementById("popup").classList.add("hidden");
});

const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has("ok")) {
    showPopup("Cita creada correctamente");
}

if (urlParams.has("error")) {
    showPopup("Error al crear la cita");
}



// INIT
renderCalendario();
cargarCitasMes();