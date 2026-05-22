$(function () {

    /* ===== LOADER Y NAVEGACIÓN ===== */

    //Muestra la pantalla de carga
    function showLoader() {
        $("#loader").removeClass("hidden");
    }

    //Realiza la navegación mostrando previamente el loader
    function goTo(url) {
        if (!url) return;

        showLoader();

        setTimeout(() => {
            window.location.href = url;
        }, 700); //Retraso para visualizar la animación de carga
    }

    //Intercepta los enlaces internos de la aplicación
    $(document).on("click", "a", function (e) {

        const url = $(this).attr("href");

        if (url && url.includes("pagina=")) {
            e.preventDefault();
            goTo(url);
        }
    });

    //Detecta botones que realizan redirecciones mediante location.href
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

    //Detecta cualquier elemento con atributo onclick que realice una redirección
    $(document).on("click", "[onclick]", function (e) {

        const onclick = $(this).attr("onclick");

        const match = onclick.match(/location\.href\s*=\s*['"](.+?)['"]/);

        if (match && match[1]) {
            e.preventDefault();
            goTo(match[1]);
        }
    });

    //Muestra el loader antes de enviar cualquier formulario
    $(document).on("submit", "form", function () {

        showLoader();

        setTimeout(() => {
            this.submit();
        }, 700);

        return false;
    });

    //Oculta el loader al restaurar una página desde el historial del navegador
    window.addEventListener("pageshow", function () {

        $("#loader").addClass("hidden");
    });

    /* ===== BOTÓN VOLVER ATRÁS ===== */

    //Muestra el loader antes de regresar a la página anterior
    $(document).on("click", ".volver-atras", function (e) {

        e.preventDefault();

        showLoader();

        setTimeout(() => {

            history.back();

        }, 700);
    });

    /* ===== COOKIES ===== */

    //Crea una cookie con el nombre, valor y duración indicados
    function setCookie(nombre, valor, dias) {
        const fecha = new Date();
        fecha.setTime(fecha.getTime() + (dias * 24 * 60 * 60 * 1000));
        document.cookie = nombre + "=" + valor + ";expires=" + fecha.toUTCString() + ";path=/";
    }

    //Obtiene el valor de una cookie mediante su nombre
    function getCookie(nombre) {
        return document.cookie.split("; ").find(row => row.startsWith(nombre + "="));
    }

    const banner = $("#cookieBanner");
    const btn = $("#acceptCookies");

    //Si el usuario no ha aceptado las cookies mostramos el aviso
    if (!getCookie("cookies_aceptadas")) {
        banner.removeClass("hidden");
    }

    //Guarda la aceptación de cookies y oculta el banner
    btn.on("click", function () {
        setCookie("cookies_aceptadas", "si", 365);
        banner.addClass("hidden");
    });

});