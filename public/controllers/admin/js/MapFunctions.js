var map = null;
var infoWindow;
var markers = [];
var bounds;
var arrayTravels=Array();

$( document ).ready(function() {
	$('.table').dataTable( {
		"sDom": "<'row'<'col-md-3'l><'col-md-9'f>r>t<'row'<'col-md-3'i><'col-md-9'p>>",
		"sPaginationType": "bootstrap",
		"bDestroy": true,
		"bLengthChange": false,
		"bPaginate": true,
		"bFilter": true,
		"bSort": true,
		"bJQueryUI": true,
		"iDisplayLength": 10,      
		"bProcessing": false,
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
	} );	

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        var currentTab = $(e.target).text(); // get current tab
        var LastTab = $(e.relatedTarget).text(); // get last tab     
        if(currentTab=='Mapa'){
          initMapa();

        }
    });      
});

function initMapa(){
  if(map==null){
	infoWindow = new google.maps.InfoWindow;
    var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(24.52713, -104.41406),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	map = new google.maps.Map(document.getElementById('map'),mapOptions);
	
	bounds = new google.maps.LatLngBounds();
    printTravelsMap();
  }else{
	google.maps.event.trigger(map, 'resize');
  }
}

function printTravelsMap(){
  var result = $("#positions").html();
  if(result!=""){

      arrayTravels=new Array();
        arrayTravels=result.split('!');
    var content     = '';
    var markerTable = null;
    var iconMarker  = '/images/assets/taxi.png';

    for(var i=0;i<arrayTravels.length;i++){    
      var travelInfo = arrayTravels[i].split('|');
        var markerTable = null;
        if(travelInfo[0]!="null" && travelInfo[1]!="null" ){

            if(travelInfo[7]=='Sin viaje'){
              iconMarker  = '/images/assets/taxi_green.png';
            }else if(travelInfo[7]=='En Viaje'){
              iconMarker  = '/images/assets/taxi_red.png';
            }

            content='<table width="350" class="table-striped" >'+  
                '<tr><td align="right"><b>Estatus</b></td><td width="200" align="left">'+travelInfo[7]+'</td><tr>'+
                '<tr><td align="right"><b>Hora del Evento</b></td><td width="200" align="left">'+travelInfo[2]+'</td><tr>'+
                '<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[4]+' kms/h.</td><tr>'+
                '<tr><td align="right"><b>Ubicación</b></td><td align="left">'+travelInfo[3]+'</td><tr>'+
                '</table>';
            var Latitud  = parseFloat(travelInfo[0])
            var Longitud = parseFloat(travelInfo[1])
            var porcent = (travelInfo[6].length*100)/50; 
            var strPos  = (travelInfo[6].length)+porcent;

      			var markerTable = new MarkerWithLabel({
      				map: map,
      				position: new google.maps.LatLng(Latitud,Longitud),
      				title:  travelInfo[0],
      				icon:   iconMarker,
      				draggable: false,
      				raiseOnDrag: false,
      				labelContent: travelInfo[6],
      				labelAnchor: new google.maps.Point(strPos, 1),
      				labelClass: "labels", // the CSS class for the label
      				labelInBackground: false
      			 });            


            markers.push(markerTable);
            infoMarkerTable(markerTable,content);   
            bounds.extend( markerTable.getPosition() );
        }   
      }

      if(arrayTravels.length>1){
        map.fitBounds(bounds);  
      }else if(arrayTravels.length==1){
        map.setZoom(13);
        map.panTo(markerTable.getPosition());  
      }
  }  
}

function centerObject(idValue){
  $("#tabs li:eq(1) a").tab('show'); 

  setTimeout(centerMap(idValue), 5000);
}

function centerMap(idValue){
  var dataTel    = $("#divInfo"+idValue).html();
  var travelInfo = dataTel.split('|');
  
  var content     = '';
  var markerTable2 = null;

  if(travelInfo[0]!="null" && travelInfo[1]!="null" ){

      content='<table width="350" class="table-striped" >'+  
          '<tr><td align="right"><b>Estatus</b></td><td width="200" align="left">'+travelInfo[7]+'</td><tr>'+
          '<tr><td align="right"><b>Hora del Evento</b></td><td width="200" align="left">'+travelInfo[2]+'</td><tr>'+
          '<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[4]+' kms/h.</td><tr>'+
          '<tr><td align="right"><b>Ubicación</b></td><td align="left">'+travelInfo[3]+'</td><tr>'+
          '</table>';    
      var latitude  = parseFloat(travelInfo[0])
      var longitude = parseFloat(travelInfo[1])
      var positionLatLon  = new google.maps.LatLng(latitude,longitude);
      HandleInfoWindow(positionLatLon,content);        
  } 
}

function HandleInfoWindow(latLng, content) {
  if(infoWindow){infoWindow.close();infoWindow.setMap(null);}
    infoWindow.setContent(content);
    infoWindow.setPosition(latLng);
    infoWindow.open(map);
    map.setZoom(13);
    map.setCenter(latLng); 
    map.panTo(latLng);     
}

function infoMarkerTable(marker,content){ 
    google.maps.event.addListener(marker, 'click',function() {
        if(infoWindow){infoWindow.close();infoWindow.setMap(null);}
        var marker = this;
        var latLng = marker.getPosition();
        infoWindow.setContent(content);
        infoWindow.open(map, marker);
        map.setZoom(18);
        map.setCenter(latLng); 
        map.panTo(latLng);     
  });
}

function fitBoundsToVisibleMarkers() {
  if(markers.length>0){
      for (var i=0; i<markers.length; i++) {
      bounds.extend( markers[i].getPosition() );
      }
      if(markers.length==1){
      map.setZoom(13);
        map.panTo(markers[0].getPosition());
      }else{
      map.fitBounds(bounds);
      }
  }
}