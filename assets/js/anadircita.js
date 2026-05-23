/* 
    Este script gestiona la interacción del usuario durante la creación de citas. 
    Sus funciones principales son la apertura y cierre de ventanas modales, 
    la carga dinámica de horarios disponibles mediante peticiones AJAX, 
    la visualización de mensajes emergentes de confirmación y la navegación entre los distintos pasos del formulario. 
*/

/* ===== VARIABLES ===== */
const modalCita = document.getElementById("modalCita");
const formCita = document.getElementById("formCita");

/* ===== MODAL PARA ABRIR LA CITA ===== */

/* 
    Esta función se ejecuta cuando el 
    usuario selecciona una fecha en el calendario
*/

/* ===== MODAL PARA ABRIR LA CITA ===== */
function abrirCita(fecha) {

    if (!modalCita) return;

    // Primero comprobamos si hay huecos disponibles
    fetch(
        "index.php?pagina=calendarioAñadir&ajax=disponibilidad&fecha=" + fecha
    )
    .then(res => res.json())
    .then(data => {

        if (data.length === 0) {
            // Sin huecos: mostramos popup y no abrimos el modal
            showPopup("Este día no tiene huecos libres");
            return;
        }

        // Con huecos: abrimos el modal normalmente
        modalCita.classList.remove("hidden");

        const inputFecha = document.querySelector("#formCita input[name='fecha']");
        if (inputFecha) inputFecha.value = fecha;

        cargarDisponibilidad(fecha);
    })
    .catch(() => {
        showPopup("Error al comprobar disponibilidad");
    });
}

/* ===== CERRAR EL MODAL ===== */

/* 
    Esta función oculta el formulario y 
    restablece su contenido
*/

function cerrarCita() {

    if (!modalCita) return;

 /* 
    Añade nuevamente la clase hidden
    ocultando la ventana
*/

    modalCita.classList.add("hidden");

    if (formCita) formCita.reset(); //Reinicia el formulario

    if (listaHorarios) listaHorarios.innerHTML = ""; //Elimina los horarios que salían antes para evitar info obsoleta
}

 /* 
    Realiza una petición al servidor para obtener
    los horarios libres de una fecha concreta
*/

function cargarDisponibilidad(fecha) {

/* 
    Solicitud AJAX: Se envía una petición HTTP
    al controlador PHP incluyendo la fecha seleccionada
*/

    fetch(
        "index.php?pagina=calendarioAñadir&ajax=disponibilidad&fecha=" + fecha
    )

    .then(res => res.json()) //Transforma la respuesta recibida en un objeto JS

    .then(data => {

        const select =
            document.querySelector(
                "select[name='id_disponibilidad']"  //Selecciona el elemento <select> en el que se muestran los horarios
            );

        if (!select) return;

        select.innerHTML = ""; //Se eliminan las peticiones antes de añadir nuevas

        const opcionInicial =
            document.createElement("option"); //Creamos la opción

        opcionInicial.value = ""; 

        opcionInicial.textContent =
            "Selecciona horario disponible"; //Con este texto, seleccionada y por defecto

        opcionInicial.disabled = true; //No se pueda elegir

        opcionInicial.selected = true; //Pero aprezca seleccionada

        select.appendChild(opcionInicial); //Se añade el "hijo" al selector

        /* ===== SIN HORARIOS DISPONIBLES ===== */
        if (data.length === 0) { //Si el server no devuelve horarios...

            const sinHoras =
                document.createElement("option"); //Se crea la opción

            sinHoras.value = "sin_horas"; //Con el siguiente valor

            sinHoras.textContent =
                "No hay horarios disponibles"; //Con este texto

            sinHoras.disabled = true; //Y que no se pueda seleccionar

            select.appendChild(sinHoras); //le añadimos el "hijo" al selector

            return;
        /* 
            Resumidamente, si no hay horarios se muestra el 
            mensaje dentro del desplegable y se detiene la ejecución
        */
            
        }

        /* ===== GENERACIÓN DE HORARIOS ===== */
        data.forEach(d => { //Recorre todos los horarios devueltos por el server

            const option =
                document.createElement("option"); //Para cada uno de los horarios...

            option.value =
                d.id_disponibilidad; //...guarda el identificación interno

            option.textContent = `${d.hora_inicio.substring(0,5)} - ${d.hora_fin.substring(0,5)}`;

            select.appendChild(option); //le añadimos el "hijo" al selector
        });
    })


/* ===== GESTIÓN DE ERRORES ===== */
    .catch(error => { //Captura posibles errores de conexión o de respuesta

        console.error(
            "Error cargando disponibilidad:", //Lo muestra en consola
            error
        );
    });
}
/* ===== SUBMIT DEL FORMULARIO ===== */
if (formCita) {
    formCita.addEventListener("submit", () => { //Escucha el evento de envio de formulario
        console.log("Enviando cita..."); //Muestra un mensaje de depuración en la consola, así verifico que se envía correctamente 
    });
}

/* ===== POPUP DE CONFIRMACIÓN - SISTEMA DE NOTIFICACIONES ===== */
function showPopup(msg) { //Permite mostrar mensajes temporales al usuario
    const popup = document.getElementById("popup");
    const text = document.getElementById("popup-text");

    text.textContent = msg; //Con esto colocamos el texto recibido dentro del popup
    popup.classList.remove("hidden"); //Hacemos visible la ventana emergente

    setTimeout(() => {
        popup.classList.add("hidden"); //La ocultamos automaticamente, después de 3.5 segundos desaparece
    }, 3500);
}

const popupClose = document.getElementById("popup-close"); //la x

if (popupClose) {

    popupClose.addEventListener("click", () => { //La cerramos manualmente

        document
            .getElementById("popup")
            .classList.add("hidden"); //Al darle click a la x, el popup se oculta
    });
}

/* ===== POPUP SEGÚN PARÁMETROS DE URL ===== */
const urlParams = new URLSearchParams(window.location.search); //Obtenemos los parámetros incluidos en la URL

if (urlParams.has("ok")) { //Si en la URL aparece "ok"
    showPopup("Cita creada correctamente"); //Sale un popup con este mensaje
}

if (urlParams.has("error")) { //Si en la URL aparece "error"
    showPopup("Error al crear la cita"); //Sale un popup con este mensaje
}

if (urlParams.has("sinHora")) { //Si en la URL aparece "sinHora"
    showPopup("No se ha seleccionado una hora"); //Sale un popup con este mensaje
}

/* ===== NAVEGACIÓN ENTRE PASOS DEL FORMULARIO ===== */
const botonesSiguiente = document.querySelectorAll(".btn-siguiente");
const botonesAtras = document.querySelectorAll(".btn-atras");
const secciones = document.querySelectorAll(".seccion");
const pasos = document.querySelectorAll(".pasos li");

// SIGUIENTE
botonesSiguiente.forEach((btn, i) => { //Cuando el usuario pulsa siguiente...
    btn.addEventListener("click", () => {
        if (secciones[i + 1]) {
            secciones[i].classList.remove("activo");  //Se oculta el paso acual
            pasos[i].classList.remove("activo"); //Se desactiva el indicador actual

            secciones[i + 1].classList.add("activo"); //Se muestra la siguiente sección
            pasos[i + 1].classList.add("activo"); //Se activa el siguiente indicador 
        }
    });
});

// ATRÁS
botonesAtras.forEach((btn) => { //Cuando el usuario pulsa atrás...
    btn.addEventListener("click", () => {

        const actual = [...secciones].findIndex(s => //Busca la sección actualmente visible, si existe una sección anterior...
            s.classList.contains("activo")
        );

        if (actual > 0) {
            secciones[actual].classList.remove("activo"); //Ocultar la actual
            pasos[actual].classList.remove("activo");

            secciones[actual - 1].classList.add("activo"); //Mostrar la anterior
            pasos[actual - 1].classList.add("activo");
        }
    });
});