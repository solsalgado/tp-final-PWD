$(document).on('click', '#boton_editarDatos', function() {

    document.getElementById('editarDatos').classList.remove('d-none');
    document.getElementById('editarContraseña').classList.add('d-none');

});

$(document).on('click', '#boton_contra', function() {

    document.getElementById('editarDatos').classList.add('d-none');
    document.getElementById('editarContraseña').classList.remove('d-none');

});


$(document).on('click', '#boton_cancelar', function() {

    document.getElementById('editarDatos').classList.add('d-none');
    document.getElementById('editarContraseña').classList.add('d-none');

});

$(document).ready(function () {
    $('#form-editar').submit(function (e) {
        e.preventDefault();
        const forms = document.querySelectorAll('.needs-validation');
        if (forms[0].checkValidity()) {
            
            
            $.ajax({
                type: "POST",
                url: 'Accion/accionActualizarPerfil.php',
                data: $(this).serialize(),
                success: function (response) {
                    var jsonData = JSON.parse(response);

                    if (jsonData.success == "1") {
                        success();
                    }
                    else if (jsonData.success == "0") {
                        failure();
                    } 
                }
            });
        } else {
            forms[0].classList.add('was-validated');
        }
    });
});

$(document).ready(function () {
    $('#form-contraseña').submit(function (e) {
        e.preventDefault(); // Evita la recarga de la página.

        const forms = document.querySelectorAll('.needs-validation');
        var passActual = document.getElementById('usPassVieja').value;
        passActual = hex_md5(passActual).toString(); // Cifra la contraseña actual.

        var passSesion = document.getElementById('usPassSesion').value;
        if (passActual == passSesion) { // Verifica si la contraseña actual coincide.
            if (verificarContraseñaIgual(document.getElementById('usPassNueva'), document.getElementById('usPassRep')) && forms[0].checkValidity()) {
                var password = document.getElementById("usPassNueva").value;
                var passhash = hex_md5(password).toString(); // Cifra la nueva contraseña.

                // Limpia los campos del formulario.
                document.getElementById('usPassNueva').value = "";
                document.getElementById('usPassRep').value = "";
                document.getElementById('usPassVieja').value = "";
                document.getElementById("usPass").value = passhash;

                $.ajax({
                    type: "POST",
                    url: '../Accion/accionActualizarPerfil.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        var jsonData = JSON.parse(response);
                        if (jsonData.success == "1") {
                            contraSucces(); // Muestra un mensaje de éxito.
                            forms[1].reset(); // Resetea el formulario.
                        } else if (jsonData.success == "0") {
                            contraFailure(); // Muestra un mensaje de error.
                        } 
                    }
                });
            } else {
                location.reload(); // Recarga la página si no es válido.
                forms[1].classList.add('was-validated');
            }
        } else {
            failureContra(); // Mensaje de error si las contraseñas no coinciden.
            forms[1].reset();
        }
    });
});


function success() {
    Swal.fire({
        icon: 'success',
        title: 'Tu email se ha modificado! 😉',
        showConfirmButton: false,
        timer: 1500
    })
    setTimeout(function () {
        location.reload();
    }, 1500);
}

function failure() {
    Swal.fire({
        icon: 'error',
        title: 'No se ha podido modificar el email! 😥',
        showConfirmButton: false,
        timer: 1500
    })
    setTimeout(function () {
        location.reload();
    }, 1500);
}

function failureContra() {
    Swal.fire({
        icon: 'error',
        title: 'La contraseña no coincide con la actual! 😬',
        showConfirmButton: false,
        timer: 1500
    })
    setTimeout(function () {
    }, 1500);
}

function contraFailure(){
    Swal.fire({
        icon: 'error',
        title: 'No se ha podido modificar la contraseña! 😥',
        showConfirmButton: false,
        timer: 1500
    })
    setTimeout(function () {
        location.reload();
    }, 1500);
}

function contraSucces(){
    Swal.fire({
        icon: 'success',
        title: 'Se ha modificado la contraseña! 😉',
        showConfirmButton: false,
        timer: 1500
    })
    setTimeout(function () {
        location.reload();
    }, 1500);
}


