$(document).ready(function() {
    // Función para manejar el envío del formulario
    async function enviarFormulario(url, datos, modalId, exitoMensaje, errorMensaje) {
        try {
            const response = await $.ajax({
                type: "POST",
                url: url,
                data: datos,
                dataType: 'json'
            });

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: exitoMensaje,
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    $(modalId).modal('hide');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: errorMensaje,
                icon: 'error',
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    // Abre el modal para cambiar contraseña
    $("#botonContrasenia").click(function() {
        $("#modalContrasenia").modal('show');
    });

    // Abre el modal para editar email
    $("#botonEmail").click(function() {
        $("#modalEmail").modal('show');
    });

    // Enviar el formulario para cambiar contraseña.
    $("#formContrasenia").submit(function(event) {
        event.preventDefault();
        // Encriptar la contraseña.
        let contrasenia = $("#uspass").val();
        let contraseniaEncriptada = CryptoJS.MD5(contrasenia).toString();

        // Reemplazar la contraseña original con la encriptada.
        let datos = $(this).serializeArray();
        datos.forEach(function(item) {
            if (item.name === "uspass") {
                item.value = contraseniaEncriptada;
            }
        });

        enviarFormulario(
            "./accion/accionActualizarPerfil.php",
            $.param(datos), // Convertir a string con formato query
            "#modalContrasenia",
            "Contraseña actualizada",
            "Hubo un problema con la conexión al servidor."
        );
    });

    // Enviar el formulario para editar email
    $("#formEmail").submit(function(event) {
        event.preventDefault();
        let datos = $(this).serialize();
        enviarFormulario(
            "./accion/accionActualizarPerfil.php",
            datos,
            "#modalEmail",
            "Email actualizado",
            "Hubo un problema con la conexión al servidor."
        );
    });
});
