$().ready(function() {
  $("[data-toggle='offcanvas']").click();

  $(".only-numbers").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });  

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
        onChangeTaximetro('');
        $("#inputHinicio").val('00:00:00');
        $("#inputHfin").val('23:59:59');
    }

    /*
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
    */

  $('.table').dataTable( {
    "sDom": "<'row'<'col-md-3'l><'col-md-9'f>r>t<'row'<'col-md-3'i><'col-md-9'p>>",
    "sPaginationType": "bootstrap",
    "bDestroy": true,
    "bLengthChange": false,
    "bPaginate": true,
    "bFilter": true,
    "bSort": true,
    "bJQueryUI": true,
    "iDisplayLength": 10,      
    "bProcessing": false,
    "bAutoWidth": true,
    "bSortClasses": false,
        "oLanguage": {
            "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
            "sEmptyTable": "Sin registros.",
            "sInfoEmpty" : "Sin registros.",
            "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
            "sLoadingRecords": "Leyendo información",
            "sProcessing": "Procesando",
            "sSearch": "Buscar:",
            "sZeroRecords": "Sin registros",
            "oPaginate": {
              "sPrevious": "Anterior",
              "sNext": "Siguiente"
            }          
        }
  } );       
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