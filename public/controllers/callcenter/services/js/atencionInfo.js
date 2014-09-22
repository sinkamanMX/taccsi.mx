$().ready(function() {
    $('#tableNotas').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 5,      
      "bProcessing": true,
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
      },
    });	

  $("#FormData").validate({
        rules: {
          inputDescripcion  : "required",
          inputEstatus      : "required",
          inputTipo         : "required",
          inputComment      : "required"
        },
          messages: {
            inputDescripcion: "Campo requerido",
            inputEstatus    : "Debe seleccionar una opción",
            inputTipo       : "Debe seleccionar una opción",
            inputComment    : "Campo requerido" 
        },
        
        submitHandler: function(form) {
            $('#loader1').show();
            form.submit();
        }
    });

    var sOption = $("#optReg").val();
    if(sOption=='new'){
      $("#inputComment").rules("remove", "required");
    }
});