$().ready(function() {
	$("#FormData").validate({
        rules: {
        	inputMarca		: "required",
        	inputModelo		: "required",
        	inputColor		: "required",
        	inputPlacas		: "required",
        	inputEstatus	: "required"  
        },
        messages: {
        	inputMarca		: "Campo Requerido",
        	inputModelo		: "Campo Requerido",
        	inputColor		: "Campo Requerido",
        	inputPlacas		: "Campo Requerido",
        	inputEstatus	: "Campo Requerido"                      
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