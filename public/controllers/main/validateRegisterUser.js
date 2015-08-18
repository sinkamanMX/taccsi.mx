$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : "required",
            inputApaterno   : "required",
            inputAmaterno   : "required",
            inputUsuario    : "required",
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
            inputNombre     : "Campo Requerido",
            inputApaterno   : "Campo Requerido",
            inputAmaterno   : "Campo Requerido",
            inputUsuario    : "Campo Requerido",
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