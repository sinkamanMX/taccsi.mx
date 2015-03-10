$( document ).ready(function() {
	$("#callIncoming").hide("slow");
	getStatusExt();	
});	

function getStatusExt(){	
	$("#callIncoming").hide("slow");
	$("#callWait").hide("slow");

	$.ajax({
	    url: "/admin/json/validatext",
	    type: "GET",
	    dataType : 'json',
	    data: { 
	    	typaction: 'validate'
	    },
	    success: function(data) {
            var aDataExt = data.aData;

            if(aDataExt.ESTATUS=='1'){
            	$("#callWait").show("slow");
			}else if(aDataExt.ESTATUS=='2' || aDataExt.ESTATUS=='3'){
				$("#callIncoming").show("slow");	
				if(data.bUserExist==1){
					var aDataClient = data.aDataClient;

            		alert("el teléfono ya se encuentra registado");
            		 //location.href='/callcenter/client/clientinfo?strClient='+idClient;            		 
					var url = '/callcenter/client/clientinfo?strClient='+aDataClient.ID_SRV_USUARIO;
      				$( location ).attr("href", url);
            	}else{
            		alert("el teléfono aun no esta registrado, favor de realizar el registro.");
					var url = '/callcenter/client/new?inputPhone='+aDataExt.NO_LLAMADA;
      				$( location ).attr("href", url);
            	}			
            }else{
            	$("#callWait").show("slow");
            }
            callTimer();
	    }
	});
}

function callTimer(){	            	
	var timeoutId = setTimeout(function(){	 
	  getStatusExt();	
	},5000);
}