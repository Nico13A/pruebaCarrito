$(document).ready(function() {
    $("#formulario").submit(function(event) {
        event.preventDefault();  // Prevenir el envío tradicional del formulario

        let uspass = $("#uspass").val();  // Obtener el valor del campo de contraseña

        // Convertir la contraseña a hash MD5
        let passhash = CryptoJS.MD5(uspass).toString();
        $("#uspass").val(passhash);
        
        // Enviar los datos mediante AJAX
        $.ajax({
            url: './accion/verificarLogin.php',
            type: 'POST',
            data: $("#formulario").serialize(),
            success: function(response) {
                if(response.trim() === "Entro") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Inicio de sesión exitoso.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "../home/index.php";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Usuario o contraseña incorrectos.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
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
});