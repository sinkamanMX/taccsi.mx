var iValCheck = 0;
var mapGeo = null;
var aMarkerTaxi=null;
var bounds;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var bEstatus = false;
var infoWindow;

$().ready(function() {
	var taxiExist = $("#inputTaxi").val();
	

    oTable = $('#tableIncidencias').dataTable({
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

    bEstatus = ($("#inputEstatus").val()==5) ? true:false;

	initMapToDraw();

	$('#iFrameInc').on('load', function () {		
    	$('#loader1').hide();
    	$('#iFrameInc').show();
    });	

	$('#ModalIncidencia').on('hidden.bs.modal', function () {
        location.reload();
    });    
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

function initMapToDraw(){
	infoWindow = new google.maps.InfoWindow;
	directionsDisplay = new google.maps.DirectionsRenderer();
	var mapOptions = {
		zoom: 5,
		center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
	};

	mapGeo = new google.maps.Map(document.getElementById('myMapDraw'),mapOptions);	

	bounds = new google.maps.LatLngBounds();
	directionsDisplay.setMap(mapGeo);
	calcRoute();
}

function calcRoute() {
	if($("#inputLatOrigen").val()!="" && $("#inputLonOrigen").val()!="" && 
		$("#inputLatDestino").val()!="" && $("#inputLonDestino").val()!=""){
		var latsOrigen  = new google.maps.LatLng($("#inputLatOrigen").val(), $("#inputLonOrigen").val());
		var lastDestino = new google.maps.LatLng($("#inputLatDestino").val(), $("#inputLonDestino").val());
		var request = {
		  origin: latsOrigen,
		  destination: lastDestino,
		  travelMode: google.maps.TravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
		  directionsDisplay.setDirections(response);
		}
		});

		startTrace();		
	}
}

function startTrace(){
	if(bEstatus){
		mapLoadData()
		$("#countdownTrace").show('slow');
		$("#countdownTrace").countdown360({
		    radius: 30,
		    seconds: 20,
		    label: ['seg', 'segs'],
		    fontColor: '#FFFFFF',
		    autostart: true,
		    onComplete: function () {
		      mapLoadData()
		    }		
		}).addSeconds(20);
	}else{
		$("#countdownTrace").hide('slow');
	}
}

function mapLoadData(){
	removeMap(aMarkerTaxi);
	if(bEstatus){
		var idObject = $("#idViaje").val();		
		$.ajax({
			type: "POST",
	        url: "/callcenter/services/getlastp",
			data: { strViaje: idObject},
	        success: function(datos){	
				var result = datos;
				if(result!= ""){
					arrayTravel = result.split('|');
					
					if(arrayTravel[0]!=5){
						bEstatus=false;	
						location.reload();
					}else{						
						var latTaxi		= arrayTravel[1];
						var lonTaxi		= arrayTravel[2];
						var nameTaxi	= arrayTravel[3];
						var descTaxi	= arrayTravel[4];
						var fechaTaxi	= arrayTravel[5];
				    	var content='<table width="350" class="table-striped" ><tr><td align="right"><b>Taxista</b></td><td width="200" align="left">'+nameTaxi+'</td><tr>'+
				    			'<tr><td align="right"><b>Fecha</b></td><td align="left">'+descTaxi+' </td><tr>'+	    			
				    			'<tr><td align="right"><b>Taxi</b></td><td align="left">'+fechaTaxi+' </td><tr>'+				    			
				    			'</table>';	
				    	var content = descTaxi;

						aMarkerTaxi = new google.maps.Marker({
							map: mapGeo,
							position: new google.maps.LatLng(latTaxi,lonTaxi),
							title: 	nameTaxi,
							icon: 	'/images/assets/taxi.png'
						});
						infoMarkerTable(aMarkerTaxi,content);
						$("#countdownTrace").countdown360({
						    radius: 30,
						    seconds: 20,
						    label: ['seg', 'segs'],
						    fontColor: '#FFFFFF',
						    autostart: true,
						    onComplete: function () {
						      mapLoadData()
						    }		
						}).addSeconds(20);						
					}					
				}
	        }
		});
	}
}

function fitBoundsToVisibleMarkers() {
	if(markers.length>0){
	    for (var i=0; i<markers.length; i++) {
			bounds.extend( markers[i].getPosition() );
	    }
	    if(markers.length==1){
			mapGeo.setZoom(13);
		  	mapGeo.panTo(markers[0].getPosition());
	    }else{
			mapGeo.fitBounds(bounds);
	    }
	}
}

function infoMarkerTable(marker,content){	
    google.maps.event.addListener(marker, 'click',function() {
      if(infoWindow){infoWindow.close();infoWindow.setMap(null);}
      var marker = this;
      var latLng = marker.getPosition();
      infoWindow.setContent(content);
      infoWindow.open(mapGeo, marker);
      mapGeo.setZoom(13);
	  mapGeo.setCenter(latLng); 
	  mapGeo.panTo(latLng);     
	});
}

function removeMap(marker){
	if(marker!=null){		
		marker.setMap(null);
	}
}