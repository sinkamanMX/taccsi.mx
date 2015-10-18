$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : "required",
            inputEstatus    : "required"
        },
        messages: {
            inputNombre     : "Campo Requerido",            
            inputEstatus    : "Campo Requerido"            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
    initMapToDraw();
});

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}