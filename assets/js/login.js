$(function () {

    // /* POPUP */
    // function showPopup(msg) {
    //     $("#popup-text").text(msg);
    //     $("#popup").removeClass("hidden");

    //     setTimeout(() => {
    //         $("#popup").addClass("hidden");
    //     }, 4000);
    // }

    // $("#popup-close").on("click", function () {
    //     $("#popup").addClass("hidden");
    // });

    // // Mostrar popup si viene desde PHP
    // const urlParams = new URLSearchParams(window.location.search);
    // if (urlParams.has("success")) showPopup(urlParams.get("success"));
    // if (urlParams.has("error")) showPopup(urlParams.get("error"));

    // OJO PASSWORD
    $(".mostrar-password").on("click", function () {
        const id = $(this).data("target");
        const input = $("#" + id);

        const isPass = input.attr("type") === "password";
        input.attr("type", isPass ? "text" : "password");

        $(this).attr("src",
            isPass
                ? "assets/img/esconder.png"
                : "assets/img/ojo-abierto.png"
        );
    });

});
