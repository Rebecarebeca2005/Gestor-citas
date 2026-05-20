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

    // ===============================
    //   FECHA HOY SEGURA
    // ===============================
    const ahora = new Date();

    const fechaHoy = new Date(
        ahora.getFullYear(),
        ahora.getMonth(),
        ahora.getDate()
    );

    fechaHoy.setHours(12,0,0,0);

    // ===============================
    //   DÍAS DEL MES
    // ===============================
    const diasMes =
        new Date(anio, mes + 1, 0).getDate();

    // ===============================
    //   PRIMER DÍA DEL MES
    // ===============================
    let primerDia =
        new Date(anio, mes, 1).getDay();

    // Domingo = 7
    primerDia =
        primerDia === 0 ? 7 : primerDia;

    // ===============================
    //   HUECOS VACÍOS
    // ===============================
    for (let i = 1; i < primerDia; i++) {

        const empty =
            document.createElement("div");

        empty.classList.add("dia-vacio");

        grid.appendChild(empty);
    }

    // ===============================
    //   DÍAS
    // ===============================
    for (let i = 1; i <= diasMes; i++) {

        const div =
            document.createElement("div");

        div.classList.add("dia");

        const numero =
            document.createElement("span");

        numero.textContent = i;

        div.appendChild(numero);

        // ===============================
        //   FECHA SEGURA
        // ===============================
        const year = anio;

        const month =
            String(mes + 1).padStart(2, "0");

        const day =
            String(i).padStart(2, "0");

        const fecha =
            `${year}-${month}-${day}`;

       // ===============================
//   CONTAR CITAS DEL DÍA
// ===============================
if (typeof diasConCitas !== "undefined") {

    const totalCitas =
        diasConCitas.filter(
            d => d === fecha
        ).length;

    // crear contenedor puntos
    if (totalCitas > 0) {

        const contenedorPuntos =
            document.createElement("div");

        contenedorPuntos.classList.add(
            "contenedor-puntos"
        );

        // crear un punto por cita
        for (let p = 0; p < totalCitas; p++) {

            const punto =
                document.createElement("div");

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
        // ===============================
        //   FECHA LOCAL
        // ===============================
        const fechaCelda =
            new Date(year, mes, i);

        fechaCelda.setHours(12,0,0,0);

        // ===============================
        //   MARCAR HOY
        // ===============================
        if (
            fechaCelda.getTime()
            ===
            fechaHoy.getTime()
        ) {

            div.classList.add(
                "hoy-marcado"
            );
        }

        // ===============================
        //   DÍAS PASADOS
        // ===============================
        const esPasado =
            fechaCelda.getTime()
            <
            fechaHoy.getTime();

        if (esPasado) {

            div.classList.add(
                "dia-deshabilitado"
            );

            div.style.pointerEvents = "none";

            div.style.opacity = "0.45";

        } else {

            div.onclick = () =>
                abrirCitasDelDiaListar(fecha);
        }

        grid.appendChild(div);
    }

    // ===============================
    //   TÍTULO MES
    // ===============================
    document.getElementById("mesActual").innerText =
        `${meses[mes]} ${anio}`;
}

// ===============================
//   ABRIR MODAL
// ===============================
function abrirCitasDelDiaListar(fecha) {

    fetch(
        "index.php?pagina=citasPorDiaAjax&fecha=" + fecha
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

        modalCita.classList.remove("hidden");
    })

    .catch(error => {

        console.error(
            "Error cargando citas:",
            error
        );
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
//   INIT
// ===============================
renderCalendarioListar();