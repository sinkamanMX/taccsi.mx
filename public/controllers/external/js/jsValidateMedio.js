$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputTipo       : "required", 
            inputNombreTdc  : "required",
            inputTdc        : {
                required: true,
                number: true,
            }, 
            inputAno        : "required",
            inputMes        : "required",
            inputCode       : {
                required: true,
                number: true,
                maxlength: 3
            }, 
            inputEstatus    : "required",
            inputDefault    : "required"   
        },
        messages: {
            inputTipo       : "Campo Requerido",
            inputNombreTdc  : "Campo Requerido",
            inputTdc        : {
                required    : "Campo Requerido",
                number      : "Este campo acepta solo números",
                maxlength   : "El código debe de ser de 3 digitos."
            }, 
            inputAno        : "Campo Requerido",
            inputMes        : "Campo Requerido",
            inputCode       : "Campo Requerido",
            inputEstatus    : "Campo Requerido",
            inputDefault    : "Campo Requerido"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	

    if($("#catId").val()>-1){
        validateDigits($("#inputTipo").val())
    }
});

function validateDigits(inputValue){
    var iNoDigits  = aOptions[inputValue];

    $( "#inputTdc" ).rules( "add", {
      required: true,
      minlength: iNoDigits,
      maxlength: iNoDigits,
      messages: {
        required: "Campo Requerido",
        minlength: "El No. de Tarjeta debe de ser de "+iNoDigits+" digitos.",
        maxlength: "El No. de Tarjeta debe de ser de "+iNoDigits+" digitos.",
      }
    });    
}
