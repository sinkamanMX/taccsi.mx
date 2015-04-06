var iValCheck = 0;
var mapGeo = null;
var aMarkerTaxi=null;
var infoLocation;
var markers = [];
var bounds;
var arrayTravels=Array();
var directionsDisplay;
var directionsDisplayNone;
var markerOrigen  = null;
var markerDestino = null;
var bounds;
var directionsService = new google.maps.DirectionsService();
var bEstatus = false;
var infoWindow;
var bDrawTravel = false;

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

    bEstatus 	= ($("#inputEstatus").val()==5) ? true:false;
	bDrawTravel = ($("#inputEstatus").val()==6) ? true:false;

	initMapToDraw();

	$('#iFrameInc').on('load', function () {		
    	$('#loader1').hide();
    	$('#iFrameInc').show();
    });	

	$('#ModalIncidencia').on('hidden.bs.modal', function () {
        location.reload();
    });    

    if($("#inputEstatus").val()==2){
		bEstatus=true;
		mapLoadData()
    }
});

function calcAndDraw(inputLatitud,inputLongitud){
	if($("#inputLatOrigen").val()!="" && $("#inputLonOrigen").val()!="" && 
		inputLatitud !="" && inputLongitud!=""){
		var latsOrigen  = new google.maps.LatLng($("#inputLatOrigen").val(), $("#inputLonOrigen").val());
		var lastDestino = new google.maps.LatLng(inputLatitud, inputLongitud);

		var request = {
		  origin: latsOrigen,
		  destination: lastDestino,
		  travelMode: google.maps.TravelMode.DRIVING
		};

		directionsService.route(request, function(response, status) {
			if (status == google.maps.DirectionsStatus.OK) {
			  directionsDisplayNone.setDirections(response);
			}
		});	

	}
}

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
	infoWindow 				= new google.maps.InfoWindow;
	directionsDisplay 		= new google.maps.DirectionsRenderer({
							    polylineOptions: {
							      strokeColor: "green"
							    }
							  });
	directionsDisplayNone	= new google.maps.DirectionsRenderer();
	var mapOptions = {
		zoom: 5,
		center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
	};

	mapGeo = new google.maps.Map(document.getElementById('myMapDraw'),mapOptions);	

	bounds = new google.maps.LatLngBounds();
	directionsDisplay.setMap(mapGeo);
	directionsDisplayNone.setMap(mapGeo);
	calcRoute();

	google.maps.event.addListener(directionsDisplayNone, 'directions_changed', function() {
		computeTotalDistance(directionsDisplayNone.directions);
	});		
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
		setMarker(0);
		setMarker(1);
		startTrace();
		printTravelsMap();				
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
					var iStatus = arrayTravel[0];

					if(iStatus==5 || iStatus==2){
						var latTaxi		= arrayTravel[1];
						var lonTaxi		= arrayTravel[2];
						var nameTaxi	= arrayTravel[3];
						var descTaxi	= arrayTravel[4];
						var fechaTaxi	= arrayTravel[5];						

						if(iStatus==2){
							calcAndDraw(latTaxi,lonTaxi);
						}
						
				    	var content='<table width="350" class="table-striped" ><tr><td align="right"><b>Taxista</b></td><td width="200" align="left">'+nameTaxi+'</td><tr>'+
				    			'<tr><td align="right"><b>Fecha</b></td><td align="left">'+descTaxi+' </td><tr>'+	    			
				    			'<tr><td align="right"><b>Taxi</b></td><td align="left">'+fechaTaxi+' </td><tr>'+				    			
				    			'</table>';	
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
					}else{
						bEstatus=false;	
						location.reload();
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
	$("#spanCalcTime").html("Calculando...");	
	if(marker!=null){		
		marker.setMap(null);
	}
}

function loadFrame(){
	$('#iFrameInc').hide();
	$('#loader1').show();
}

function addIncidencia(idIncidencia){
	loadFrame();
	var idObject = $("#idViaje").val();	
    $('#iFrameInc').attr('src','/callcenter/services/reportincidencia?strInc='+idIncidencia+'&idViaje='+idObject);
    $("#ModalIncidencia").modal("show");
}

function setMarker(optionMarker){
	var latMarker = 0;
	var lonMarker = 0;
	var position  = null;
	if(optionMarker==0){			
		latMarker	= $("#inputLatOrigen").val();
		lonMarker	= $("#inputLonOrigen").val();
		position    = new google.maps.LatLng(latMarker, lonMarker);

		markerOrigen = new google.maps.Marker({
		    map: mapGeo,
		    position: position,
		    draggable:true,
			animation: google.maps.Animation.DROP,
		    title: 	"Origen",
			icon: 	'/images/assets/origen.png'
	    });		    
	}else{
		latMarker	= $("#inputLatDestino").val();
		lonMarker	= $("#inputLonDestino").val();
		position = new google.maps.LatLng(latMarker, lonMarker);

		markerDestino = new google.maps.Marker({
		    map: mapGeo,
		    position: position,
		    draggable:true,
			animation: google.maps.Animation.DROP,
		    title: 	"Origen",
			icon: 	'/images/assets/destino.png'
	    });	
	}
	
	mapGeo.setZoom(18);
	mapGeo.panTo(position);	
}


function printTravelsMap(){
	var result = $("#positions").html();
	  if(result!=""){

	     	arrayTravels=new Array();
	        arrayTravels=result.split('!');
	    var content     = '';
	    var markerTable = null;

	    for(var i=0;i<arrayTravels.length;i++){    
	      var travelInfo = arrayTravels[i].split('|');
	        var markerTable = null;
	        if(travelInfo[0]!="null" && travelInfo[1]!="null" ){
	            content='<table width="350" class="table-striped" >'+  
	                '<tr><td align="right"><b>Hora</b></td><td width="200" align="left">'+travelInfo[2]+'</td><tr>'+
	                '<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[4]+' kms/h.</td><tr>'+
	                '<tr><td align="right"><b>Angulo</b></td><td align="left">'+travelInfo[5]+'</td><tr>'+	                
	                '<tr><td align="right"><b>Ubicación</b></td><td align="left">'+travelInfo[3]+'</td><tr>'+
	                '</table>';
	            var Latitud  = parseFloat(travelInfo[0])
	            var Longitud = parseFloat(travelInfo[1])

	            markerTable = new google.maps.Marker({
	              map: mapGeo,
	              position: new google.maps.LatLng(Latitud,Longitud),
	              title:  travelInfo[0],
	              icon:   '/images/carMarker.png'
	            });
	            markers.push(new google.maps.LatLng(Latitud,Longitud));
	            infoMarkerTable(markerTable,content);   
	            bounds.extend( markerTable.getPosition() );
	        }   
	      }

	      var iconsetngs = {
	          path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
	          strokeColor: '#155B90',
	          fillColor: '#155B90',
	          fillOpacity: 1,
	          strokeWeight: 4        
	      };

	      var line = new google.maps.Polyline({
	        map: mapGeo,
	        path: markers,
	        strokeColor: "#098EF3",
	        strokeOpacity: 1.0,
	        strokeWeight: 2,
	          icons: [{
	              icon: iconsetngs,
	              repeat:'35px',         
	              offset: '100%'}]
	      });   
	      if(arrayTravels.length>1){
	        mapGeo.fitBounds(bounds);  
	      }else if(arrayTravels.length==1){
	        mapGeo.setZoom(13);
	        mapGeo.panTo(markerTable.getPosition());  
	      }
		}		
		}  


function computeTotalDistance(result) {
  var total = 0;
  var time= 0;
  var from=0;
  var to=0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
    total += myroute.legs[i].distance.value;
    time +=myroute.legs[i].duration.text;
    from =myroute.legs[i].start_address;
    to =myroute.legs[i].end_address;


  }
  time = time.replace('hours','H');
  time = time.replace('mins','M');
  total = total / 1000.

  $("#spanCalcTime").html(time+" ("+Math.round(total)+" kms.)");
}		