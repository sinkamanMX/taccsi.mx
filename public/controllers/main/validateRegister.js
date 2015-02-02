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
            inputCpassword  : {
                required: true,
                equalTo: "#inputPassword",
            },
            inputTaxip      : "required",
            inputMarca      : "required",
            inputModelo     : "required",
            inputColor      : "required",
            inputPlacas     : "required",
            inputEstatus    : "required",  
            inputAno        : {
                required: true,
                number: true,
                minlength: 4,
                maxlength: 4
            }            
        },
        messages: {
            inputNombre     : "Campo Requerido",
            inputApaterno   : "Campo Requerido",
            inputAmaterno   : "Campo Requerido",
            inputUsuario    : "Campo Requerido",
            inputTipo       : "Campo Requerido",
            inputPassword   : "Campo Requerido",
            inputCpassword  : {
                required    : "Campo Requerido",
                equalTo     : "La contraseña no coincide."
            }, 
            inputPhone    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            },
            inputTaxip      : "Campo Requerido",
            inputMarca      : "Campo Requerido",
            inputModelo     : "Campo Requerido",
            inputColor      : "Campo Requerido",
            inputPlacas     : "Campo Requerido",
            inputEstatus    : "Campo Requerido",
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

    /*    
    if($("#catId").val()>-1){
        $("#inputPassword").rules("remove", "required");
        $("#inputCpassword").rules("remove", "required");          
    }

    //$(".chosen-select").chosen();
    $("#btnSearch").click(function() { openSearch(); return false; });
    $("#btnDelRel").click(function() { deleteRowRel(); return false; });  

    $('#iFrameSearch').on('load', function () {        
        $('#loader1').hide();
        $('#iFrameSearch').show();
    }); */
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
