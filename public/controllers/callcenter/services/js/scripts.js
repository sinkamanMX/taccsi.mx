var sName,sApp,sApm,sTel;
var iTotalRows=0;

$(document).ready(function() {
	$('input[type="text"]').keypress(function(event){
		if ( event.which == 13 ) {
			validateForm()
		}		
	});

	$( "#btnSearch" ).click(function() {   
		validateForm()
	});	


	$('#tableCLients').dataTable({
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
      }
    });		
}); 


function validateForm(){
	$("#divAlert").addClass( "hide" );

	var error 		= 0;
	var validate	= 0;
	var errorDesc 	= "";

	sName	= $("#inputFindName").val(); 
	sApp	= $("#inputFindApp").val(); 
	sApm 	= $("#inputFindApm").val(); 
	sTel	= $("#inputFindNumber").val(); 

	if(sName.length > 0){			
		if(sName.length == 0 || $.trim(sName) == ""){
			errorDesc += "<br /><b>Debe de ingresar un nombre para la búsqueda.</b>";
			error++;		
		}
		validate++;
	}

	if(sApp.length > 0){
		if(sApp.length == 0 || $.trim(sApp) == ""){
			errorDesc += "<br /><b>Debe de ingresar un Apellido paterno para la búsqueda.</b>";
			error++;			
		}
		validate++;
	}

	if(sApm.length > 0){				
		if(sApm.length == 0 || $.trim(sApm) == ""){
			errorDesc += "<br /><b>Debe de ingresar un Apellido materno para la búsqueda.</b>";
			error++;			
		}
		validate++;
	}

	if(sTel.length > 0){
		if(sTel.length == 0 || sTel.length != 10  || $.trim(sTel) == "" || isNaN(sTel)){
			errorDesc += "<br /><b>Debe de ingresar un número teléfonico de 10 digitos.</b>";
			error++;			
		}
		validate++;
	}

	if(error>0){
		$("#divAlert").removeClass( "hide" );
		$("#divErrorContent").html(errorDesc);
	}else{
		if(validate==0){
			$("#divAlert").removeClass( "hide" );
			errorDesc += "<b>Para realizar la búsqueda, favor de ingresar por lo menos un dato solicitado.</b>";
			$("#divErrorContent").html(errorDesc);
		}else{
			$("#FormData").submit();
		}	
	}	
}

/*



function getClients(){
	iTotalRows=0;
	$("#divAlert").addClass( "hide" );

	var variables = "namefilter="+sName+"&"+
	    	"appfilter="+sApp+"&"+
	    	"apmfilter="+sApm+"&"+
	    	"telfilter="+sTel;
    oTable = $('#tableCLients').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": false,
      "bFilter": false,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 20,      
      "bProcessing": true,
      "bAutoWidth": true,
      "bSortClasses": false,
      "sAjaxSource": "/admin/json/getclientes?"+variables,
      "aoColumns": [
        { "mData": "NAME", sDefaultContent: "" },
        { "mData": "APP", sDefaultContent: "" },
        { "mData": "APM", sDefaultContent: "" },
        { "mData": "TELEFONO", sDefaultContent: "" },
        { "mData": "", sDefaultContent: "" }
      ] , 
	      "aoColumnDefs": [
	        {"aTargets": [4],
	          "sWidth": "65px",
	          "bSortable": false,        
	          "mRender": function (data, type, full) {
	          		iTotalRows++;
              		var detImage = '<button type="button" class="btn btn-primary" onClick="getToServicio('+full.ID_CLIENTE+')">'
                					+'<i class="fa fa-arrow-circle-right"></i> </button>';
                  return detImage;
	        }}
	      ],	          
		"fnInitComplete": function(oSettings, json) {
			if(iTotalRows==0){
				$("#ModalConfirm").modal('show');
			}
    	},      
      "oLanguage": {
          "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
          "sEmptyTable": "No hay registros.",
          "sInfoEmpty" : "No hay registros.",
          "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
          "sLoadingRecords": "Leyendo información",
          "sProcessing": "Procesando",
          "sSearch": "Buscar:",
          "sZeroRecords": "No hay registros",
      }
    });
}
*/

function getToServicio(idClient){
	location.href='/callcenter/client/clientinfo?strClient='+idClient;
}

function goToSetClient(){
	location.href='/callcenter/client/new';
}
