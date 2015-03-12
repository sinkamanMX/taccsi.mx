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
            inputRFC        : "required"
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
            inputRFC        : "Campo Requerido"    
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
});

function getoptionsCbo(idCboTo,classObject,idObject,chosen,options){  
  $("#div"+idCboTo).html('<img id="loader1" class="col-xs-offset-4" src="/images/assets/loading.gif" alt="loading gif"/>');
    var classChosen = (chosen) ? 'chosen-select': '';
    var claseFind   = (options=='coloniaO') ? 'colonia': options;
    var optionSelect= (options!='') ? 'getoptionsCbo("'+options+'","'+claseFind+'",this.value,false,"");': '';
    var optsCP      = (idCboTo=='colonia' || idCboTo=='coloniaO') ? 'getCPdir(this.value,"'+idCboTo+'");': '';
    $.ajax({
        url: "/admin/json/getselect",
        type: "GET",
        data: { catId : idObject, 
            oprDb : classObject },
        success: function(data) { 
          $("#div"+idCboTo).html("");
          var dataCbo = '<select class=" form-control'+classChosen+'" id="input'+idCboTo+'" name="input'+idCboTo+'" onChange=\''+optionSelect+' '+optsCP+'\'>';
          if(data!="no-info"){
            dataCbo += '<option value="">Seleccionar una opción</option>'+data+'</select>';
          }else{
        dataCbo += '<option value="">Sin Información</option>';
          }
          dataCbo += '</select>';
                  
          $("#div"+idCboTo).html(dataCbo);
          /*$(".chosen-select").chosen({disable_search_threshold: 10});*/
        }
    });   
}

$().ready(function() {
    $('.upperClass').keyup(function()
    {
        $(this).val($(this).val().toUpperCase());
    }); 
});    
