function getoptionsCbo(idCboTo,classObject,idObject,chosen,options){  
  $("#div"+idCboTo).html('<img id="loader1" class="col-xs-offset-4" src="/images/assets/loading.gif" alt="loading gif"/>');
    var classChosen = (chosen) ? 'chosen-select': '';
    var claseFind   = (options=='coloniaO') ? 'colonia': options;
    var optionSelect= (options!='') ? 'getoptionsCbo("'+options+'","'+claseFind+'",this.value,false,"");': '';
    var optsCP      = (idCboTo=='colonia' || idCboTo=='coloniaO') ? 'getCPdir(this.value,"'+idCboTo+'");': '';
    $.ajax({
        url: "/admin/main/getselect",
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