$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre	: "required",
            inputApaterno	: "required",
            inputAmaterno  : "required",
            inputPhone    : {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            }
            /*,            
              inputEmail  : {
                required: true,
                email: true
              }  */
        },
        messages: {
            inputNombre     : "Campo requerido",
            inputApaterno   : "Campo requerido",
            inputAmaterno   : "Campo requerido",
            inputPhone    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            },            
            /*inputEmail  : {
                required: "Campo Requerido",
                email: "Debe de ingresar un mail válido"
            },*/
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