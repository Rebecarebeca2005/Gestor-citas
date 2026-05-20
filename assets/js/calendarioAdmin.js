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
//   ABRIR MODAL CON CITAS DEL DÍA
// ===============================
function abrirCitasDelDiaListar(fecha) {

    fetch("index.php?pagina=citasPorDiaAjax&fecha=" + fecha)
        .then(res => res.json())
        .then(citas => {

            const contenedor = modalCita.querySelector(".lista-citas");
            contenedor.innerHTML = "";

            if (citas.length === 0) {
                contenedor.innerHTML = "<p class='sin-citas'>No tienes citas este día.</p>";
            } else {
                citas.forEach(c => {
                    contenedor.innerHTML += `
                        <li class="item-cita">
                            <h3>${c.servicio}</h3>
                            <p><strong>Hora:</strong> ${c.hora_inicio.substring(0,5)} - ${c.hora_fin.substring(0,5)}</p>
                            <p><strong>Estado:</strong> ${c.estado}</p>
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
//   NAVEGACIÓN ENTRE MESES
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
//   INIT
// ===============================
renderCalendarioListar();
