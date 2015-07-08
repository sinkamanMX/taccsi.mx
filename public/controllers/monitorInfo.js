var bValidate 	 = false;
var idUserClient = null;
var sCallNumber  = "";

$( document ).ready(function() {
	bValidate = true;
	$("#callIncoming").hide("slow");
	getStatusExt();	
});	

function getStatusExt(){	
	$("#callIncoming").hide("slow");
	$("#callWait").hide("slow");

	if(bValidate){
		$("#divShowAll").hide();
		bValidate = false;
		$.ajax({
		    url: "/admin/json/validatext",
		    type: "GET",
		    dataType : 'json',
		    data: { 
		    	typaction: 'validate'
		    },
		    success: function(data) {
	            var aDataExt = data.aData;

	            var lcaller  = (aDataExt.NO_LLAMADA!=null) ? aDataExt.NO_LLAMADA.length : ''; 

	            if(aDataExt.ESTATUS=='1'){
	            	$("#callWait").show("slow");
				}else if(aDataExt.NO_LLAMADA!="" && lcaller > 8  && aDataExt.ESTATUS=='3'){
					sCallNumber = aDataExt.NO_LLAMADA;
					$("#callIncoming").show("slow");	

					if(data.bUserExist==0){			
	      				registerNewCLient();
	            	}else if(data.bUserExist==1){	            		
	            		var aDataClient  = data.aDataClient;   
	            			idUserClient = aDataClient.ID_SRV_USUARIO;

	            		var tableDataClient = "<table  class='table table-bordered table-striped'>"+
	            								"<tr><td>Nombre Completo</td><td><b>"+aDataClient.NOMBRE+" "+aDataClient.APATERNO+" "+aDataClient.AMATERNO+"</b></td></tr>"+
	            								"<tr><td>Teléfono</td><td><b>"+aDataClient.TELEFONO+"</b></td></tr>"+
	            								"<tr><td>E-mail</td><td><b>"+aDataClient.EMAIL+"</b></td></tr>"+
	            								"</table>";
	            		$("#infoCliente").html(tableDataClient);
	            		$("#MyModalSearch").modal("show");
	      			}else if(data.bUserExist==2){
						var url = '/callcenter/client/clientsbynumber?inputPhone='+sCallNumber;
						$( location ).attr("href", url);
	            	}
	            }else{
	            	$("#callWait").show("slow");
	            }
	            //bValidate=true;	        
	            callTimer();
		    }
		});
	}
}

function callTimer(){	    	
	var timeoutId = setTimeout(function(){	 
	  getStatusExt();	
	},5000);
}

function gotoClient(){
	if(idUserClient!=""){
		var url = '/callcenter/client/clientinfo?strClient='+idUserClient;
		$( location ).attr("href", url);	
	}
}

function registerNewCLient(){
	var url = '/callcenter/client/new?inputPhone='+sCallNumber;
	$( location ).attr("href", url);	
}