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
            inputVigencia: "required",  
            inputVin: "required",  
            inputSize:"required",  
        },
        messages: {
        	inputMarca		: "Campo Requerido",
        	inputModelo		: "Campo Requerido",
        	inputColor		: "Campo Requerido",
        	inputPlacas		: "Campo Requerido",
        	inputEstatus	: "Campo Requerido",
            inputSize       : "Campo Requerido",
            inputAno        : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Año debe de ser de 4 dígitos",
                maxlength : "El Año debe de ser de 4 dígitos",          
            },
            inputVigencia: "Campo Requerido",
            inputVin     : "Campo Requerido",            
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    $('#inputVigencia').daterangepicker();
});

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}

function checkfileImages(sender) {
    var validExts = new Array(".png", ".jpeg",".jpg",".pdf");
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
      alert("El archivo seleccionado debe de tener alguna de las siguientes extenciones: " +
               validExts.toString());
      return false;
    }else{
        return true;    
        
    } 
}


function optionAll(inputCheck){
    if(inputCheck){
        $('.chkOn').prop('checked', true);         
    }else{
        $('.chkOn').prop('checked', false);
    }
}

