$(function () {

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

    // 1. LINKS normales
    $(document).on("click", "a", function (e) {

        const url = $(this).attr("href");

        if (url && url.includes("pagina=")) {
            e.preventDefault();
            goTo(url);
        }
    });

    // 2. BOTONES con onclick o sin onclick
    $(document).on("click", "button", function (e) {

        const onclick = $(this).attr("onclick");

        if (onclick) {

            // detecta location.href='...'
            const match = onclick.match(/location\.href\s*=\s*['"](.+?)['"]/);

            if (match && match[1]) {
                e.preventDefault();
                goTo(match[1]);
            }
        }
    });

    // 3. onclick directo en cualquier elemento (div, etc)
    $(document).on("click", "[onclick]", function (e) {

        const onclick = $(this).attr("onclick");

        const match = onclick.match(/location\.href\s*=\s*['"](.+?)['"]/);

        if (match && match[1]) {
            e.preventDefault();
            goTo(match[1]);
        }
    });

    // 4. FORMULARIOS
    $(document).on("submit", "form", function () {

        showLoader();

        setTimeout(() => {
            this.submit();
        }, 700);

        return false;
    });

});