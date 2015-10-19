$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : "required",
            inputClase      : "required",
            inputEstado     : "required",    
            inputEstatus    : "required",
            inputuTaximetro : "required",
            inputBanderazo  : "required",
            inputHinicio    : "required",
            inputHfin       : "required",
            inputMinsCobro  : {
              required: true,
              number: true
            },
            inputKmsCobro   : {
              required: true,
              number: true
            },
            inputMinsFhor   : {
              required: true,
              number: true
            },
            inputKmsFhor    : {
              required: true,
              number: true
            },
            inputMinsFzona  : {
              required: true,
              number: true
            },
            inputKmsFzona   : {
              required: true,
              number: true
            },
            inputCobroFhor  : {
              required: true,
              number: true
            }
        },
        messages: {
            inputNombre     : "Campo Requerido",    
            inputClase      : "Campo Requerido",    
            inputEstado     : "Campo Requerido",    
            inputEstatus    : "Campo Requerido",    
            inputuTaximetro : "Campo Requerido",    
            inputBanderazo  : "Campo Requerido",    
            inputHinicio    : "Campo Requerido",    
            inputHfin       : "Campo Requerido",    
            inputMinsCobro  : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputKmsCobro   : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputMinsFhor   : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputKmsFhor    : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputMinsFzona  : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputKmsFzona   : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputCobroFhor  : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            }                       
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

    if($("#catId").val()>-1){
        var idTaximetro = $("#inputuTaximetro").val();
        onChangeTaximetro(idTaximetro)
    }else{
        onChangeTaximetro('')
    }

    var nowTemp = new Date();
    $('#inputHinicio').timepicker({
        minuteStep: 1,
        template: 'modal',
        appendWidgetTo: 'body',
        showSeconds: true,
        showMeridian: false,
        defaultTime: false
    });

    $('#inputHfin').timepicker({
        minuteStep: 1,
        template: 'modal',
        appendWidgetTo: 'body',
        showSeconds: true,
        showMeridian: false,
        defaultTime: false
    });    
});

function onChangeTaximetro(inputValue){
    if(inputValue==""){ 
        $(".optTaximetro").hide('fast');
        $(".optNotaximetro").hide('fast');

        $("#inputMinsCobro").rules("remove", "required");
        $("#inputKmsCobro").rules("remove", "required");
        $("#inputMinsFhor").rules("remove", "required");
        $("#inputKmsFhor").rules("remove", "required");
        $("#inputMinsFzona").rules("remove", "required");
        $("#inputKmsFzona").rules("remove", "required");
        $("#inputCobroFhor").rules("remove", "required");

    }else if(inputValue==0){
        $(".optTaximetro").hide('fast');
        $(".optNotaximetro").show('fast');

        $("#inputMinsCobro").rules("remove", "required");
        $("#inputKmsCobro").rules("remove", "required");
        $("#inputMinsFhor").rules("remove", "required");
        $("#inputKmsFhor").rules("remove", "required");
        $("#inputMinsFzona").rules("remove", "required");
        $("#inputKmsFzona").rules("remove", "required");
        $("#inputCobroFhor").rules("add",  {required:true});
    }else if(inputValue==1){
        $(".optTaximetro").show('fast');
        $(".optNotaximetro").hide('fast');

        $("#inputMinsCobro").rules("add",  {required:true});
        $("#inputKmsCobro").rules("add",  {required:true});
        $("#inputMinsFhor").rules("add",  {required:true});
        $("#inputKmsFhor").rules("add",  {required:true});
        $("#inputMinsFzona").rules("add",  {required:true});
        $("#inputKmsFzona").rules("add",  {required:true});
        $("#inputCobroFhor").rules("remove", "required");
    }
}

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}