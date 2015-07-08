var sName,sApp,sApm,sTel;
var iTotalRows=0;

$(document).ready(function() {
    $('.noEnterSubmit').keypress(function(e){
        if ( e.which == 13 ) return false;
        //or...
        if ( e.which == 13 ) e.preventDefault();
    });   		

	$('#tableViajes').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 10,      
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
      }
    });		
}); 

function assignDriver(inputValue){
	$("#inputDriver").val(inputValue);
	$("#FormData").submit();
}