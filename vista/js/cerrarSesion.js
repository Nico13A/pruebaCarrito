// Manejo del cierre de sesión
$(document).ready(function() {
    $("#cierreSesion").click(async function() {
        try {
            const response = await $.ajax({
                url: '../accion/cerrarSesion.php',
                type: 'POST',
                dataType: 'json' // Indica que se espera recibir una respuesta en formato JSON desde el servidor.
            });

            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sesión finalizada',
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "../../index.php";
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
                text: 'Hubo un problema con la conexión al servidor.',
                icon: 'error',
                timer: 3000,
                showConfirmButton: false
            });
        }
    });
});