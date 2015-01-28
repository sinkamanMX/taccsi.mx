$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputUser  : {
                required: true,
                email: true
            }
        },
        messages: {
            inputUser  : {
                required   : "Campo Requerido",
                email      : "Favor de ingresar un E-mail válido."
            }                                          
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	    
});