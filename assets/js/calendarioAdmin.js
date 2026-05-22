/* ===== VARIABLES ===== */
const modalCita = document.getElementById("modalCita");
const grid = document.getElementById("calGrid");

/* ===== VARIABLES  DE FECHA ===== */
let hoy = new Date();
let mes = hoy.getMonth();
let anio = hoy.getFullYear();

const meses = [
    "Enero","Febrero","Marzo","Abril","Mayo","Junio",
    "Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"
];

/* ===== RENDERIZACIÓN DE CALENDARIO ===== */

/* 
    Función principal del script, genera
    dinámicamente todos los días del mes
*/

function renderCalendarioListar() {

    grid.innerHTML = ""; //Limpia cualquier contenido previo

    const fechaHoy = new Date();
    fechaHoy.setHours(0,0,0,0); //Obtenemos la fecha actual y ponemos la hora 0 para comparar solo días

    const diasMes = new Date(anio, mes + 1, 0).getDate(); //Calculamos cuantos días tiene el mes seleccionado
    
/* ===== CONTADOR DE CITAS POR DÍA ===== */
if (
    typeof diasConCitas !== "undefined"
    &&
    Array.isArray(diasConCitas) //Comprobamos que exista un array con las fechas que contienen esos días
) {

    const totalCitas = //Contamos cuantas citas existen para una fecha en especifico
        diasConCitas.filter(
            d => d === fecha
        ).length;

    if (totalCitas > 0) { //Si hay citas...

        const contenedorPuntos =
            document.createElement("div"); //Creamos un div de puntos

        contenedorPuntos.classList.add(
            "contenedor-puntos"
        );

        for (
            let p = 0;
            p < totalCitas; //Creamos los indicadores visuales
            p++
        ) {

            const punto =
                document.createElement("div"); //Creamos el punto

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

    /* ===== PRIMER DIA DEL MES ===== */
    let primerDia = new Date(anio, mes, 1).getDay(); //Se obtiene que dia de la semana corresponde al 1

    primerDia = primerDia === 0 ? 7 : primerDia; //Como JS considera que el domingo es 0, lo convertimos a 7 

    /* ===== HUECOS VACÍOS ===== */
    for (let i = 1; i < primerDia; i++) { //Generamos espacios vacíos antes del primer dia del mes

        const empty = document.createElement("div"); //Se crea la celda vacía

        empty.classList.add("dia-vacio"); 

        grid.appendChild(empty);
    }

    /* ===== DIAS DEL MES ===== */
    for (let i = 1; i <= diasMes; i++) { //Recorremos todos los días del mes

        const div = document.createElement("div"); //Creo el contenedor del dia, cada día del calendario
        div.classList.add("dia");

        const numero = document.createElement("span"); //Insertamos el numero que corresponde al dia
        numero.textContent = i;
        div.appendChild(numero);

        const fecha = `${anio}-${String(mes+1).padStart(2,"0")}-${String(i).padStart(2,"0")}`; //Generamos la fecha completa (La construye en el formato YYYY-MM-DD)

        const fechaCelda = new Date(anio, mes, i);
        fechaCelda.setHours(0,0,0,0);

        if ( //Comprobamos que coincide con la actual
            fechaCelda.getTime()
            ===
            fechaHoy.getTime()
        ) {

            div.classList.add( //Aplicamos el estilo
                "hoy-marcado"
            );
        }

    /* ===== DESHABILITAR FECHAS PASADAS ===== */
        const esPasado = fechaCelda < fechaHoy; //Determina si el dia es pasado

        if (esPasado) { //Si lo es...
            div.classList.add("dia-deshabilitado"); //Se deshabilita
            div.style.pointerEvents = "none"; //No se puede clicar
            div.style.opacity = "0.45"; //Añadimos la opacidad
        } else {
            div.onclick = () => abrirCitasDelDiaListar(fecha); //Si es valida, al hacer clic se ejecuta la función
        }

        grid.appendChild(div);
    }

    document.getElementById("mesActual").innerText = //Actualizar encabezado del calendario, mes y año actual
        `${meses[mes]} ${anio}`;
}
/* ===== ABRIR MODAL CON LAS CITAS DEL DIA ===== */

/* 
    Esta función obtiene las citas del día seleccionado y 
    las muestra dentro de una ventana modal
*/

function abrirCitasDelDiaListar(fecha) {

    fetch("index.php?pagina=citasPorDiaAjax&fecha=" + fecha) //Ajax, envía la solicitud al servidor indicando la fecha seleccionada
        .then(res => res.json())
        .then(citas => { //lo convertimos en objeto

            const contenedor = modalCita.querySelector(".lista-citas");
            contenedor.innerHTML = "";

            if (citas.length === 0) { //Si no hay citas...
                contenedor.innerHTML = "<p class='sin-citas'>No tienes citas este día.</p>";
            } else {
                citas.forEach(c => { //Si hay, recorre todas las citas y muestra para cada una:
                    contenedor.innerHTML += `
                        <li class="item-cita">
                            <h3>${c.servicio}</h3>
                            <p><strong>Hora:</strong> ${c.hora_inicio.substring(0,5)} - ${c.hora_fin.substring(0,5)}</p>
                            <p><strong>Estado:</strong> ${c.estado}</p>
                        </li>
                    `;
                });
            }

            modalCita.classList.remove("hidden"); //Hacemos visible la ventana
        });
}

/* ===== CERRAMOS MODAL ===== */
function cerrarCita() {
    modalCita.classList.add("hidden");
}

/* ===== NAVEGACIÓN ENTRE MESES ===== */
document.getElementById("prevMes").onclick = () => { //Mes anterior, reduce el mes mostrando en una unidad
    mes = (mes - 1 + 12) % 12;
    renderCalendarioListar();
};

document.getElementById("nextMes").onclick = () => { //Mes siguiente, icnrementa el mes mostrado
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
    Su función es generar automáticamente el calendario del mes actual 
    para que el usuario pueda interactuar con él desde el primer momento.
*/
renderCalendarioListar();
