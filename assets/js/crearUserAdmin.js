document.addEventListener("DOMContentLoaded", () => {

    // ===============================
    // VALIDACIONES
    // ===============================
    function validarFormulario() {

        let nombre =
            document.querySelector(
                "input[name='nombre']"
            ).value.trim();

        let apellidos =
            document.querySelector(
                "input[name='apellidos']"
            ).value.trim();

        let correo =
            document.querySelector(
                "input[name='correo']"
            ).value.trim();

        let telefono =
            document.querySelector(
                "input[name='telefono']"
            ).value.trim();

        let password =
            document.querySelector(
                "input[name='password']"
            ).value;

        if (nombre.length < 2) {
            return "Nombre mínimo 2 caracteres";
        }

        if (apellidos.length < 2) {
            return "Apellidos mínimo 2 caracteres";
        }

        if (
            !correo.includes("@")
            ||
            !correo.includes(".")
        ) {
            return "Correo no válido";
        }

        if (
            !/^[0-9]{9}$/.test(telefono)
        ) {
            return "El teléfono debe tener 9 números";
        }

        if (password.length < 8) {
            return "La contraseña debe tener mínimo 8 caracteres";
        }

        if (!/[0-9]/.test(password)) {
            return "La contraseña debe tener al menos un número";
        }

        return true;
    }

    // ===============================
    // FORM SUBMIT
    // ===============================
    const form =
        document.getElementById(
            "formCrearUsuario"
        );

    form.addEventListener(
        "submit",
        async (e) => {

            e.preventDefault();

            const validacion =
                validarFormulario();

            if (validacion !== true) {

                showPopup(validacion);

                return;
            }

            try {

                const response = await fetch(
                    "index.php?pagina=crearUsuarioAjax",
                    {
                        method: "POST",
                        body: new FormData(form)
                    }
                );

                const text =
                await response.text();

            console.log(text);

            const data =
                JSON.parse(text);

                if (data.ok) {

                    showPopup(
                        "Usuario creado correctamente"
                    );

                } else {

                    showPopup(
                        data.msg ||
                        "Error creando usuario"
                    );
                }

            } catch(error) {

                console.error(error);

                showPopup(
                    "Error del servidor"
                );
            }
        }
    );

    // ===============================
    // POPUP
    // ===============================
    function showPopup(msg) {

        const popup =
            document.getElementById("popup");

        const text =
            document.getElementById("popup-text");

        text.textContent = msg;

        popup.classList.remove("hidden");

        setTimeout(() => {

            popup.classList.add("hidden");

        }, 3000);
    }

});