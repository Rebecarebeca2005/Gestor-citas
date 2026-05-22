$(function () {

    /* ===== PUPUP ===== */
    function showPopup(msg) {
        $("#popup-text").text(msg);
        $("#popup").removeClass("hidden");

        setTimeout(() => {
            $("#popup").addClass("hidden");
        }, 4000);
    }

    $("#popup-close").on("click", function () {
        $("#popup").addClass("hidden");
    });

    /* ===== VALIDACION PASO 1 ===== */
    function validarPaso1() {

        let nombre = $("input[name='nombre']").val().trim();
        let apellidos = $("input[name='apellidos']").val().trim();
        let email = $("input[name='email']").val().trim();
        let telefono = $("input[name='telefono']").val().trim();
        let genero = $("input[name='genero']:checked").val();

        if (nombre.length < 2) return "Nombre mínimo 2 caracteres";
        if (apellidos.length < 2) return "Apellidos mínimo 2 caracteres";

        if (!email.includes("@")) return "Email no válido";

        if (!/^[0-9]{9}$/.test(telefono)) {
            return "Teléfono debe tener 9 números";
        }

        if (!genero) return "Debes seleccionar un género";

        return true;
    }

    /* ===== VALIDACION PASO 2 ===== */
    function validarPaso2() {

        let pass1 = $("#password").val();
        let pass2 = $("#password2").val();

        if (pass1.length < 8) {
            return "La contraseña debe tener mínimo 8 caracteres";
        }

        if (!/[0-9]/.test(pass1)) {
            return "La contraseña debe tener al menos un número";
        }

        if (pass1 !== pass2) {
            return "Las contraseñas no coinciden";
        }

        return true;
    }

    /* ===== CONTROL MULTIPASO ===== */
    $(".btn-siguiente").on("click", function () {

        const $seccionActual = $(this).closest(".seccion");
        const $secciones = $(".seccion");
        const $pasos = $(".pasos li");

        const indice = $secciones.index($seccionActual);

        let resultado = true;

        if (indice === 0) {
            resultado = validarPaso1();
        }

        if (indice === 1) {
            resultado = validarPaso2();
        }

        if (resultado !== true) {
            showPopup(resultado);
            return;
        }

        $seccionActual.removeClass("activo");
        $secciones.eq(indice + 1).addClass("activo");

        $pasos.eq(indice).removeClass("activo");
        $pasos.eq(indice + 1).addClass("activo");
    });

});

    /* ===== MOSTRAR CONTREÑA O OCULTAR ===== */
    $(function () {

    $(".mostrar-password").on("click", function () {

        const id = $(this).data("target");
        const $input = $("#" + id);

        if ($input.length === 0) return;

        const isPassword = $input.attr("type") === "password";

        $input.attr("type", isPassword ? "text" : "password");

        $(this).attr("src",
            isPassword
                ? "assets/img/esconder.png"
                : "assets/img/ojo-abierto.png"
        );
    });

});
