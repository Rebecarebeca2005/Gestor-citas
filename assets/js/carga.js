$(function () {

    /* ===============================
        LOADER + NAVEGACIÓN
    =============================== */

    function showLoader() {
        $("#loader").removeClass("hidden");
    }

    function goTo(url) {
        if (!url) return;

        showLoader();

        setTimeout(() => {
            window.location.href = url;
        }, 700);
    }

    $(document).on("click", "a", function (e) {

        const url = $(this).attr("href");

        if (url && url.includes("pagina=")) {
            e.preventDefault();
            goTo(url);
        }
    });

    $(document).on("click", "button", function (e) {

        const onclick = $(this).attr("onclick");

        if (onclick) {
            const match = onclick.match(/location\.href\s*=\s*['"](.+?)['"]/);

            if (match && match[1]) {
                e.preventDefault();
                goTo(match[1]);
            }
        }
    });

    $(document).on("click", "[onclick]", function (e) {

        const onclick = $(this).attr("onclick");

        const match = onclick.match(/location\.href\s*=\s*['"](.+?)['"]/);

        if (match && match[1]) {
            e.preventDefault();
            goTo(match[1]);
        }
    });

    $(document).on("submit", "form", function () {

        showLoader();

        setTimeout(() => {
            this.submit();
        }, 700);

        return false;
    });

    window.addEventListener("pageshow", function () {

    $("#loader").addClass("hidden");
});

// detectar botones volver atrás
$(document).on("click", ".volver-atras", function (e) {

    e.preventDefault();

    showLoader();

    setTimeout(() => {

        history.back();

    }, 700);
});

    /* ===============================
                 COOKIES
    =============================== */

    function setCookie(nombre, valor, dias) {
        const fecha = new Date();
        fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
        document.cookie = nombre + "=" + valor + ";expires=" + fecha.toUTCString() + ";path=/";
    }

    function getCookie(nombre) {
        return document.cookie.split("; ").find(row => row.startsWith(nombre + "="));
    }

    const banner = $("#cookieBanner");
    const btn = $("#acceptCookies");

    if (!getCookie("cookies_aceptadas")) {
        banner.removeClass("hidden");
    }

    btn.on("click", function () {
        setCookie("cookies_aceptadas", "si", 365);
        banner.addClass("hidden");
    });

});