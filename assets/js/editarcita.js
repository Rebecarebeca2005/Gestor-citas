// ===============================
//   VARIABLES
// ===============================
const modalCita = document.getElementById("modalCita");

let citaSeleccionada = null;
let fechaSeleccionada = null;

// ===============================
//   ABRIR MODAL CON CITAS DEL DÍA
// ===============================
function abrirEdicionCita(id) {

    fetch("index.php?pagina=citaDetalleAjax&id=" + id)
        .then(res => res.json())
        .then(cita => {

            const modal = document.getElementById("modalEditar");
            const form = document.getElementById("formEditarCita");

            form.id_cita.value = cita.id_cita;
            form.fecha.value = cita.fecha;
            form.hora_inicio.value = cita.hora_inicio;
            form.hora_fin.value = cita.hora_fin;
            form.descripcion.value = cita.descripcion || "";

            modal.classList.remove("hidden");
        })
        .catch(err => {
            console.error("Error cargando cita:", err);
        });
}

function editarCita(fecha) {
    abrirCitasDelDia(fecha);
}

// ===============================
//   ABRIR EDICIÓN DE CITA
// ===============================


document.getElementById("formEditarCita").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("index.php?pagina=editarCitaAjax", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.ok) {
            document.getElementById("modalEditar").classList.add("hidden");

            // refrescar vista
            location.reload(); // o recargar citas del día
        } else {
            alert("Error al editar la cita");
        }
    });
});

// ===============================
//   CERRAR MODAL
// ===============================
function cerrarCita() {
    modalCita.classList.add("hidden");
}

function abrirCitasDelDia(fecha) {

    fetch("index.php?pagina=citasPorDiaAjax&fecha=" + fecha)
        .then(res => res.json())
        .then(citas => {

            const contenedor = document.querySelector(".lista-citas");
            contenedor.innerHTML = "";

            if (citas.length === 0) {
                contenedor.innerHTML = "<p>No hay citas ese día.</p>";
                return;
            }

            citas.forEach(c => {

                contenedor.innerHTML += `
    <li class="item-cita"
        onclick="abrirModalEditarCita(${c.id_cita})">

        <h3>${c.servicio}</h3>
        <p>${c.hora_inicio.substring(0,5)} - ${c.hora_fin.substring(0,5)}</p>
    </li>
`;
            });

            document.getElementById("modalCita").classList.remove("hidden");
        });
}

function abrirModalEditarCita(id_cita) {

    fetch("index.php?pagina=citaDetalleAjax&id=" + id_cita)
        .then(res => {
            if (!res.ok) throw new Error("Error en la respuesta del servidor");
            return res.json();
        })
        .then(cita => {

            const modal = document.getElementById("modalEditar");
            const form = document.getElementById("formEditarCita");

            // rellenar campos del formulario
            form.querySelector("input[name='id_cita']").value = cita.id_cita;
            form.querySelector("input[name='fecha']").value = cita.fecha;
            form.querySelector("input[name='hora_inicio']").value = cita.hora_inicio;
            form.querySelector("input[name='hora_fin']").value = cita.hora_fin;
            form.querySelector("textarea[name='descripcion']").value = cita.descripcion || "";

            // abrir modal
            modal.classList.remove("hidden");
        })
        .catch(err => {
            console.error(err);
            alert("No se pudo cargar la cita");
        });
}
// ===============================
//   PASOS FORMULARIO (SIGUIENTE / ATRÁS)
// ===============================
const botonesSiguiente = document.querySelectorAll(".btn-siguiente");
const botonesAtras = document.querySelectorAll(".btn-atras");
const secciones = document.querySelectorAll(".seccion");
const pasos = document.querySelectorAll(".pasos li");


document.getElementById("formEditarCita").addEventListener("submit", function(e) {
    e.preventDefault();

    fetch("index.php?pagina=editarCitaAjax", {
        method: "POST",
        body: new FormData(this)
    })
    .then(r => r.json())
    .then(res => {

        if (res.ok) {
            document.getElementById("modalEditar").classList.add("hidden");
            location.reload();
        } else {
            alert("No se pudo actualizar la cita");
        }
    });
});

function cerrarEditar() {
    document.getElementById("modalEditar").classList.add("hidden");
}

