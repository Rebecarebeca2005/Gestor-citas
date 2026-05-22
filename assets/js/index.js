/* ===== EL SLIDER ===== */
document.addEventListener("DOMContentLoaded", () => {

    const slides = document.querySelectorAll(".slide");
    const indicadores = document.querySelectorAll(".indicador");

    if (!slides.length) return;

    let actual = 0;

    /* ===== MOSTRAR EL SLIDER ===== */
    function mostrarSlide(index) {
        slides.forEach(s => s.classList.remove("activo"));
        indicadores.forEach(i => i.classList.remove("activo"));

        slides[index].classList.add("activo");
        indicadores[index].classList.add("activo");
    }

    indicadores.forEach((indicador, i) => {
        indicador.addEventListener("click", () => {
            actual = i;
            mostrarSlide(actual);
        });
    });

    setInterval(() => {
        actual = (actual + 1) % slides.length;
        mostrarSlide(actual);
    }, 4000);

});
