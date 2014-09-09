$(document).ready(function() {
    oTable = $('#tableViajes').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 9,      
      "bProcessing": true,
      "bAutoWidth": true,
      "bSortClasses": true,
      "oLanguage": {
          "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
          "sEmptyTable": "No hay registros.",
          "sInfoEmpty" : "No hay registros.",
          "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
          "sLoadingRecords": "Leyendo información",
          "sProcessing": "Procesando",
          "sSearch": "Buscar:",
          "sZeroRecords": "No hay registros",
          "oPaginate": {
	        "sFirst": "Inicio",
	        "sLast": "Fin",
	        "sNext": "Siguiente",
	        "sPrevious": "Anterior"
	      }
      }
    });
}); 

function getToServicio(idViaje){
	location.href='/callcenter/services/serviceinfo?strViaje='+idViaje;
}

function goToSetClient(){
	location.href='/callcenter/client/new';
}