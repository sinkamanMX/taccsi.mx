$(document).ready(function() {
    $('#rootwizard').bootstrapWizard({'tabClass': 'bwizard-steps',onTabClick: function(tab, navigation, index) {		
		return false;
	}});       
    window.prettyPrint && prettyPrint();

	$('#rootwizard').bootstrapWizard('show',1);	

    oTable = $('#tableTravels').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": false,
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
      },

    });	
}); 