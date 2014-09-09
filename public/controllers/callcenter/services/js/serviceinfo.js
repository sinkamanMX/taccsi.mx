var iValCheck = 0;

$().ready(function() {
	var taxiExist = $("#inputTaxi").val();

	if(taxiExist==0){
		$("#countdown").countdown360({
		    radius: 40,
		    seconds: 120,
		    label: ['seg', 'segs'],
		    fontColor: '#FFFFFF',
		    autostart: true,
		    onComplete: function () {		      
		      cancelService();
		    }		
		});
		setTimeout("waitService()",7000);
	}
});

function waitService(){
	var strViaje = $("#strViaje").val();
	if(iValCheck==0){
		$.ajax({
		    url: "/callcenter/services/getassign",
		    type: "GET",
		    dataType : 'json',
		    data: { 
		    	strViaje : strViaje
		    },
		    success: function(data) {
	            var result = data.answer; 
	                
	            if(result == 'assign'){
	                location.reload();
	            }else{
	            	setTimeout("waitService()",7000);
	            }	        
		    }
		});	
	}
}

function cancelService(){
	iValCheck = 1;
	$("#buttonNext").show('slow');
	$("#ModalConfirm").modal("show");

	$("#divOptions").hide("slow");
	$("#divOptionsRenew").show("slow");
}