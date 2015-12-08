var mapGeo 		  = null;
var geocoder 	  = new google.maps.Geocoder();
var infoLocation;
var markerOrigen  = null;
var markerDestino = null;
var bounds;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();

$( document ).ready(function() {
	
	if (navigator.geolocation){
		var content = document.getElementById("geolocation-test");
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(function(objPosition){
				var lon = objPosition.coords.longitude;
				var lat = objPosition.coords.latitude;

				codeLatLng(lat,lon,0);
				$("#inputLatOrigen").val(lat);
				$("#inputLonOrigen").val(lon);
				setMarker(0);
				setOrigen();
			}, function(objPositionError){
				bError = true;
				switch (objPositionError.code){
					case objPositionError.PERMISSION_DENIED:
						bError = false;
					break;
					case objPositionError.POSITION_UNAVAILABLE:
						bError = false;
					break;
					case objPositionError.TIMEOUT:
						bError = false;
					break;
					default:
						bError = false;
						//content.innerHTML = "Error desconocido.";
				}

				if(bError){

				}
			},{
				maximumAge: 75000,
				timeout: 15000
			});
		}
	}

	$('.noEnterSubmit').keypress(function(e){
	    if ( e.which == 13 ) return false;
	    if ( e.which == 13 ) e.preventDefault();
	});

    $('.upperClass').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });

    initMapToDraw();

    var nowTemp    = new Date();
    var time 	   = nowTemp.getHours()+1;
    var minute 	   = (nowTemp.getMinutes()<10) ? "0"+nowTemp.getMinutes() : nowTemp.getMinutes();
    var dateInter  = parseInt(nowTemp.getMonth())+1;  
    var todayMonth = (dateInter<10) ? "0"+dateInter : dateInter;
    var todayDay   = (nowTemp.getDate()<10) ? "0"+nowTemp.getDate(): nowTemp.getDate();  
    var timeFormat = nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+" "+time+":"+minute+":"+nowTemp.getSeconds();

    if($("#inputFechaViaje").val()==""){
     	$("#inputFechaViaje").val(timeFormat);
    }
	    
	$('#inputFechaViaje').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate: timeFormat,
    });
	
	$("#FormData").validate({
		rules: {
			inputOrigen 	: "required",
			inputRefsO 		: "required",
			inputWhen 		: "required",
			inputFechaViaje : "required",
			inputTarjeta 	: "required"
		},
		messages: {
			inputOrigen 	: "Campo requerido",
			inputRefsO 		: "Campo requerido",
			inputWhen 		: "Campo requerido",
			inputFechaViaje : "Campo requerido",
			inputTarjeta 	: "Campo requerido"	
		},
        submitHandler: function(form) {        	
        	if( $("#inputLatOrigen").val()!="" && $("#inputLatOrigen").val()!="0" &&
					$("#inputLonOrigen").val()!="" && $("#inputLonOrigen").val()!="0"){
					 form.submit();
			}else{
				alert("No se ha ingresado un lugar de origen válido.");
				return false;
			}
			 form.submit();
        }
    });	

	$("#inputFechaViaje").rules("remove", "required");
	
	$('.nopaste').bind("cut copy paste",function(e) {
      e.preventDefault();
      alert("La dirección se tiene que ingresar de manera manual.");
    });    	
});

function initMapToDraw(){
	directionsDisplay = new google.maps.DirectionsRenderer();
	geocoder = new google.maps.Geocoder();
	var mapOptions = {
		zoom: 5,
		center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
	};

	mapGeo = new google.maps.Map(document.getElementById('myMapDraw'),mapOptions);	

	var valInputOrigen 		= (document.getElementById('inputOrigen'));
	var autCompleteOrigen   = new google.maps.places.Autocomplete(valInputOrigen);

	google.maps.event.addListener(autCompleteOrigen, 'place_changed', function() {
	    var place = autCompleteOrigen.getPlace();
	    if (!place.geometry) {
	      return;
	    }

		$("#inputLatOrigen").val(place.geometry.location.lat());
		$("#inputLonOrigen").val(place.geometry.location.lng());
		if( $("#inputLatOrigen").val()!="" && $("#inputLatOrigen").val()!="0" &&
			$("#inputLonOrigen").val()!="" && $("#inputLonOrigen").val()!="0"
			){
			setMarker(0);	
			setOrigen();
			calcRoute() 
		}
	});

	var valInputDestino 	= (document.getElementById('inputDestino'));
	var autCompleteDestino  = new google.maps.places.Autocomplete(valInputDestino);

	google.maps.event.addListener(autCompleteDestino, 'place_changed', function() {	    
	    var place = autCompleteDestino.getPlace();
	    if (!place.geometry) {
	      return;
	    }

		$("#inputLatDestino").val(place.geometry.location.lat());
		$("#inputLonDestino").val(place.geometry.location.lng());
		if( $("#inputLatDestino").val()!="" && $("#inputLatDestino").val()!="0" &&
			$("#inputLonDestino").val()!="" && $("#inputLonDestino").val()!="0"
			){
			setMarker(1);
			setDestino()
		}
	});

	bounds = new google.maps.LatLngBounds();	
	directionsDisplay.setMap(mapGeo);
	google.maps.event.addListener(directionsDisplay, 'directions_changed', function() {
		computeTotalDistance(directionsDisplay.directions);
	});	
}

function setMarker(optionMarker){
	var latMarker = 0;
	var lonMarker = 0;
	var position  = null;
	removeMap(optionMarker);
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
			icon: 	'/images/assets/icono_94.png'
	    });	

	    google.maps.event.addListener(markerOrigen, 'click', toggleBounce);		    
	    google.maps.event.addListener(markerOrigen, "dragend", function(event) {
			$("#inputLatOrigen").val(event.latLng.lat());
			$("#inputLonOrigen").val(event.latLng.lng());
			if( $("#inputLatOrigen").val()!="" && $("#inputLatDestino").val()!="0" &&
				$("#inputLonOrigen").val()!="" && $("#inputLonOrigen").val()!="0"
				){
				setMarker(0);	
				codeLatLng(event.latLng.lat(),event.latLng.lng(),0);	
				/*
				  geocoder.geocode({'location': latlng}, function(results, status) {
				    if (status == google.maps.GeocoderStatus.OK) {
				      if (results[1]) {
				        map.setZoom(11);
				        marker = new google.maps.Marker({
				          position: latlng,
				          map: map
				        });
				        infowindow.setContent(results[1].formatted_address);
				        infowindow.open(map, marker);
				      } else {
				        window.alert('No results found');
				      }
				    } else {
				      window.alert('Geocoder failed due to: ' + status);
				    }
				  });		
				  */		

			}
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

	    google.maps.event.addListener(markerDestino, 'click', toggleBounce);	

	    google.maps.event.addListener(markerDestino, "dragend", function(event) {
			$("#inputLatDestino").val(event.latLng.lat());
			$("#inputLonDestino").val(event.latLng.lng());
			if( $("#inputLatDestino").val()!="" && $("#inputLatDestino").val()!="0" &&
				$("#inputLonDestino").val()!="" && $("#inputLonDestino").val()!="0"
				){
				setMarker(1);
				codeLatLng(event.latLng.lat(),event.latLng.lng(),1);	
			}
	    });	
	}
	
	mapGeo.setZoom(18);
	mapGeo.panTo(position);	
}

function toggleBounce() {
  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function removeMap(optionMarker){
	if(optionMarker==0 && markerOrigen!=null){		
		markerOrigen.setMap(null);
	}else if(optionMarker==1 && markerDestino!=null){		
		markerDestino.setMap(null);
	}
}

function calcRoute() {
	if($("#inputLatOrigen").val()!="" && $("#inputLonOrigen").val()!="" && 
		$("#inputLatDestino").val()!="" && $("#inputLonDestino").val()!=""){
		$("#divInformacion").hide('slow');
		var latsOrigen  = new google.maps.LatLng($("#inputLatOrigen").val(), $("#inputLonOrigen").val());
		var lastDestino = new google.maps.LatLng($("#inputLatDestino").val(), $("#inputLonDestino").val());
		var request = {
		  origin: latsOrigen,
		  destination: lastDestino,
		  provideRouteAlternatives: true,
		  travelMode: google.maps.TravelMode.DRIVING
		};		
		directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
		  directionsDisplay.setDirections(response);
		  computeTotalDistance(directionsDisplay.directions);
		}
		});
	}
}

function setOrigen(){
	/*$("#spanOrigen").html("");
	$("#spanOrigen").html('<i class="fa fa-check"></i>').removeClass("btn-warning").addClass("btn-success");*/
	$("#inputDestino").removeAttr("disabled");
	$("#inputDestino").focus();
}

function setDestino(){
	/*
	$("#spanDestino").html("");
	$("#spanDestino").html('<i class="fa fa-check"></i>').removeClass("btn-warning").addClass("btn-success");
	*/
	calcRoute()
	$("#divBtnSubmit").show('slow');
	$("#divBtnSubmit").focus();
}

function codeLatLng(inputLat,inputLon,optionMarker) {
  var lat = parseFloat(inputLat);
  var lng = parseFloat(inputLon);
  var latlng = new google.maps.LatLng(lat, lng);

  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[1]) {
      	
      	if(optionMarker==0){
      		$("#inputOrigen").val(results[0].formatted_address);
      		calcRoute()
      	}else{
			$("#inputDestino").val(results[0].formatted_address);
			calcRoute()
      	}

      } else {
        alert('No results found');
      }
    } else {
      alert('Geocoder failed due to: ' + status);
    }
  });
}

function programarViaje(inputSelected){
	if(inputSelected==1){
		$("#divFechaHora").show("slow");
		$("#inputFechaViaje").rules("add", {required:true});
	}else{
		$("#divFechaHora").hide("slow");
    	$("#inputFechaViaje").rules("remove", "required");
	}
}

function computeTotalDistance(result) {
  var total = 0;
  var time= 0;
  var from=0;
  var to=0;
  var myroute = result.routes[0];
  for (var i = 0; i < myroute.legs.length; i++) {
  	console.log(myroute.legs[i].distance.value);
    total += myroute.legs[i].distance.value;
    time +=myroute.legs[i].duration.text;
    from =myroute.legs[i].start_address;
    to =myroute.legs[i].end_address;
  }

  time = time.replace('hours','H');
  time = time.replace('mins','M');
  total = total / 1000;

  $("#inputDistancialbl").html(Math.round(total)+" kms.");
  $("#inputTiempolbl").html(time);
  $("#inputDistancia").val(Math.round(total));
  $("#inputTiempo").val(time);
  
  $("#divTEA").show('slow');  
}

function submitForm(){
	$("#FormData").submit();
}

function changeReservacion(idOption){
	if(idOption==1){
		$("#inputOpr").val('new');
		$("#divFechaHora").hide('slow');
		$("#inputFechaViaje").rules("remove", "required");
	}else{
		$("#inputOpr").val('newres');
		$("#divFechaHora").show('slow');
        $("#inputFechaViaje").rules("add",  {required:true});		
	}
}