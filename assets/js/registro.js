/* =========================
   FORMULARIO MULTIPASO
========================= */
$(function () {

    $(".formulario .btn-siguiente").on("click", function () {

        const $seccionActual = $(this).closest(".seccion");
        const $secciones = $(".formulario .seccion");
        const $pasos = $(".pasos li");

        const indiceSeccion = $secciones.index($seccionActual);

        let esValido = true;

        // Validar campos obligatorios de la sección actual
        $seccionActual.find("input[required]").each(function () {
            if (!this.checkValidity()) {
                this.reportValidity();
                esValido = false;
                return false; // corta el each
            }
        });

        if (!esValido) return;

        // Quitar activa a la sección actual
        $seccionActual.removeClass("activo");
        // Activar la siguiente sección
        $secciones.eq(indiceSeccion + 1).addClass("activo");

        // Actualizar los pasos (barra superior)
        $pasos.eq(indiceSeccion).removeClass("activo");
        $pasos.eq(indiceSeccion + 1).addClass("activo");

    });

});


/* =========================
   MOSTRAR / OCULTAR CONTRASEÑA
========================= */
$(function () {

    $(".mostrar-password").on("click", function () {

        const idObjetivo = $(this).data("target");
        const $campoPassword = $("#" + idObjetivo);

        const esTipoPassword = $campoPassword.attr("type") === "password";

        // Cambiar tipo de input
        $campoPassword.attr("type", esTipoPassword ? "text" : "password");

        // Cambiar icono según estado
        $(this).attr("src",
            esTipoPassword
                ? "assets/img/esconder.png"
                : "assets/img/ojo-abierto.png"
        );

    });

});
