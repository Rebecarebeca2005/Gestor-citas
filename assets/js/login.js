$(function () {

    /* POPUP */
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

    /* LEER MENSAJES DE LA URL */
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has("success")) {
        showPopup(urlParams.get("success"));
    }

    if (urlParams.has("error")) {
        showPopup(urlParams.get("error"));
    }

    // OJO PASSWORD
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