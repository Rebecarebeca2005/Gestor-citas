$(function () {

    /* ===== POPUP ===== */
    function showPopup(msg) {
        $("#popup-text").text(msg);
        $("#popup").removeClass("hidden");

        setTimeout(function () {
            $("#popup").addClass("hidden");
        }, 4000);
    }

    $("#popup-close").on("click", function () {
        $("#popup").addClass("hidden");
    });

    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has("success")) {
        showPopup(urlParams.get("success"));
    }

    if (urlParams.has("error")) {
        showPopup(urlParams.get("error"));
    }

   /* ===== EL OJO DE LA CONTRASEÑA ===== */

    /* Este script utiliza jQuery para implementar la funcionalidad de mostrar u ocultar contraseñas. 
    Al pulsar sobre el icono asociado al campo, cambia dinámicamente el atributo type entre password y text,
    permitiendo visualizar o esconder la contraseña. Actualiza la imagen del icono para reflejar el estado actual del campo. */
    
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


});