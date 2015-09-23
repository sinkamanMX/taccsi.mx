$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : {
                required: true,
                minlength: 3,
            }, 
            inputApaterno   : {
                required: true,
                minlength: 3,
            }, 
            inputAmaterno   : {
                required: true,
                minlength: 3,
            }, 
            inputUsuario    : {
                required: true,
                email: true
            },
            inputPhone      : {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            }, 
            inputPassword   : "required",
            inputCpassword  : {
                required: true,
                equalTo: "#inputPassword",
            },
        },
        messages: {
            inputNombre     : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un nombre válido",
            }, 
            inputApaterno   : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un apellido paterno válido",
            },
            inputAmaterno   : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un apellido materno válido",
            },
            inputUsuario    : {
                required  : "Campo Requerido",
                email     : "Debde ingresar un email válido."  
            },
            inputTipo       : "Campo Requerido",
            inputPassword   : "Campo Requerido",
            inputPhone    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            },
            inputCpassword  : {
                required    : "Campo Requerido",
                equalTo     : "La contraseña no coincide."
            }, 
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	

    $('.upperClass').keyup(function()
    {
        $(this).val($(this).val().toUpperCase());
    }); 
});