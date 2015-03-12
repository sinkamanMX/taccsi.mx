var sName,sApp,sApm,sTel;

$(document).ready(function() {
    $('#rootwizard').bootstrapWizard({'tabClass': 'bwizard-steps',onTabClick: function(tab, navigation, index) {		
		return false;
	}});    
    window.prettyPrint && prettyPrint();

	$('input[type="text"]').keypress(function(event){
		if ( event.which == 13 ) {
			validateForm()
		}		
	});

	$( "#btnSearch" ).click(function() {   
		validateForm()
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
				getClients();
			}	
		}	
}

function getClients(){
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
        { "mData": "USUARIO", sDefaultContent: "" },
      ] , 
		fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			$(nRow).on('click', function() {				
				$('#myModal').modal('show');
				$('#mdInfoDetalle').html('<table>'+
									'<tr><td><b>Nombre Completo:</b></td><td>'+aData.NAME+' '+aData.APP+' '+aData.APM+'</td></tr>'+
										'<tr><td><b>Usuario:</b></td><td>'+aData.USUARIO+'</td></tr>'+
										'<tr><td><b>Teléfono:</b></td><td>'+aData.TELEFONO+'</td>'+
									'</tr></table>');
				$("#mdIdClient").val(aData.ID_CLIENT);
				
				$("#btnSend" ).click(function() {  
					$("#FormDetalle").submit();
				}); 				
			});
		}      
      ,
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
