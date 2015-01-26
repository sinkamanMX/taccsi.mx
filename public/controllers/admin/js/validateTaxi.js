$().ready(function() {
	$("#FormData").validate({
        rules: {
        	inputMarca		: "required",
        	inputModelo		: "required",
        	inputColor		: "required",
        	inputPlacas		: "required",
        	inputEstatus	: "required",  
            inputAno        : {
                required: true,
                number: true,
                minlength: 4,
                maxlength: 4
            }, 
        },
        messages: {
        	inputMarca		: "Campo Requerido",
        	inputModelo		: "Campo Requerido",
        	inputColor		: "Campo Requerido",
        	inputPlacas		: "Campo Requerido",
        	inputEstatus	: "Campo Requerido",
            inputAno        : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Año debe de ser de 4 dígitos",
                maxlength : "El Año debe de ser de 4 dígitos",          
            }                    
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}