var mapGeo 		  = null;
var geocoder 	  = new google.maps.Geocoder();
var infoLocation;
var markerOrigen  = null;
var markerDestino = null;
var bounds;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();

$( document ).ready(function() {
	initMapToDraw();

    $('.noEnterSubmit').keypress(function(e){
        if ( e.which == 13 ) return false;
        //or...
        if ( e.which == 13 ) e.preventDefault();
    });   	

    $('.upperClass').keyup(function()
    {
        $(this).val($(this).val().toUpperCase());
    });  

    var nowTemp = new Date();

	$('#inputFechaViaje').datetimepicker({
        format: "yyyy-mm-dd HH:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate: nowTemp,
    });

	$("#FormData").validate({
        rules: {
			inputNoPasajeros	: "required",
			inputFormaPago		: "required",
			inputViajeP			: "required",
			inputFechaViaje		: "required",
			inputOrigen 		: "required",
			inputDestino 		: "required",
			inputRefsO			: "required",
        },
        messages: {
			inputNoPasajeros	: "Campo requerido",
			inputFormaPago		: "Campo requerido",
			inputViajeP			: "Campo requerido",
			inputFechaViaje		: "Campo requerido",
			inputOrigen 		: "Campo requerido",
			inputDestino 		: "Campo requerido",
			inputRefsO			: "Campo requerido"
        },
        
        submitHandler: function(form) {
        	if( $("#inputLatOrigen").val()!="" && $("#inputLatOrigen").val()!="0" &&
					$("#inputLonOrigen").val()!="" && $("#inputLonOrigen").val()!="0" &&
					$("#inputLatDestino").val()!="" && $("#inputLatDestino").val()!="0" &&
					$("#inputLonDestino").val()!="" && $("#inputLonDestino").val()!="0"
					){
					 form.submit();
			}else{
				alert("No se ha ingresado un lugar o un destino válido.");
				return false;
			}
			 form.submit();
        }
    });	

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
			icon: 	'/images/assets/origen.png'
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

$('.noEnterSubmit').keypress(function(e){
    if ( e.which == 13 ) return false;
    //or...
    if ( e.which == 13 ) e.preventDefault();
});

function computeTotalDistance(result) {
	$("#divInformacion").show('slow');
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

  $("#inputDistancia").val(Math.round( total));
  $("#inputTiempo").val(time);

  $("#inputInfo").val(Math.round(total)+" kms./"+time);
 /*
  console.log(from + '-'+to);
  console.log(time);
  console.log(Math.round( total)+"KM");
  document.getElementById('from').innerHTML = from + '-'+to;
  document.getElementById('duration').innerHTML = time ;
  document.getElementById('total').innerHTML =Math.round( total)+"KM" ;*/
}