var map = null;
var geocoder;
var infoWindow;
var infoLocation;
var markers = [];
var bounds;
var arrayTravels="";
var mon_timer=60;
var startingOp=false;
var aSelected=Array();
var sSucursal=-1;

$( document ).ready(function() {
	initMapToDraw();
	$('#slideTimeUp').slider({
		formater: function(value) {
			mon_timer = value;
			timerUpdate()
			return 'Actualizar cada ' + value + ' segs.';			
		},
		value: 60
	}).on('slide', function(ev){
		var valorSegs = ev.value;
		mon_timer = valorSegs;
		timerUpdate()
    	$("#labelMinutes").html(valorSegs);
  	});

	$('#divSliderC').hide('fast'); 
	$("#countdown").hide('fast'); 
	drawTable();	 	
	drawSelectPersonal();

    $('#iFrameSearch').on('load', function () {        
        $('#loader1').hide();
        $('#iFrameSearch').show();
    }); 	
});

function drawTable(){	
	$('#dataTable').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": false,
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
}

function timerUpdate(){
	$("#countdown").show('slow');
	$('#divSliderC').show('slow');
	if(mon_timer>0){
		$("#countdown").countdown360({
		    radius: 30,
		    seconds: 20,
		    label: ['seg', 'segs'],
		    fontColor: '#FFFFFF',
		    autostart: false,
		    onComplete: function () {
		      mapLoadData()
		    }		
		}).addSeconds(mon_timer);
	}else{
		$("#countdown").countdown360({
		    radius: 30,
		    seconds: 20,
		    label: ['seg', 'segs'],
		    fontColor: '#FFFFFF',
		    autostart: false,
		    onComplete: function () {
		      mapLoadData()
		    }		
		}).stop();
	}
}

function initMapToDraw(){
	infoWindow = new google.maps.InfoWindow;
    var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(24.52713, -104.41406),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	map = new google.maps.Map(document.getElementById('Map'),mapOptions);
	
	bounds = new google.maps.LatLngBounds();
}

function mapClearMap(){
	if(markers || markers.length>-1){
		for (var i = 0; i < markers.length; i++) {
	          markers[i].setMap(null);
		}	
		markers = [];
	}
	arrayTravels=null;
}

function drawSelectPersonal(){
	mapClearMap();
	stopTimer();
	$('#inputAllCheck').prop('checked', false);
	var datapersonal = $("#divDataPersonal").html().split("?");
	$('#dataTable').dataTable().fnClearTable();	
	for(var i=0;i<datapersonal.length;i++){
		var datainfo = datapersonal[i].split("|");
		$("#dataTable tbody").append('<tr><td><input type="checkbox" class="chkMap" name="inputChk'+ $.trim(datainfo[0])+'" id="inputChk'+$.trim(datainfo[0])+'" value="'+$.trim(datainfo[0])+'" onChange="searchSelected(this.value)"/></td><td>'+datainfo[1]+'</td>'+
					'<td> <div style="width: 80px"><button class="btn btn-primary" onClick="centerTel('+$.trim(datainfo[0])+')"><i class="fa fa-map-marker icon-white"></i></button>'+
						 '<button id="btnCenter'+$.trim(datainfo[0])+'" class="btn btn-success btnCenter" onClick="getReport('+$.trim(datainfo[0])+')" style="display:none;"><i class="fa fa-globe icon-white"></i></button>'+
					'</div></td></tr>');
	}
	drawTable();
}

function stopTimer(){
	$("#countdown").hide('slow');
	$('#divSliderC').hide('slow');
	arrayTravels= [];
	mapClearMap();
}

function searchSelected(strSearch){
	if(aSelected.length>0){
		var existe = jQuery.inArray(strSearch, aSelected);
		if(existe<0){
			$('#btnCenter'+strSearch).show('slow');
			aSelected.push(strSearch);			
		}else{
			$('#btnCenter'+strSearch).hide('slow');
			aSelected.splice(existe,1);	
		}
	}else{
		$('#btnCenter'+strSearch).show('slow');
		aSelected.push(strSearch);
	}
	mapLoadData();
}

function optionAll(inputCheck){
	aSelected = [];
	if(inputCheck){
		aSelected = [];
		var datapersonal = $("#divDataPersonal").html().split("?");
		for(var i=0;i<datapersonal.length;i++){
			var datainfo = datapersonal[i].split("|");				
				$('#btnCenter'+$.trim(datainfo[0])).show('slow');
				aSelected.push(datainfo[0]);
		}
		$('.chkMap').prop('checked', true);			
	}else{
		$('.chkMap').prop('checked', false);
		$('.btnCenter').hide('slow');
	}		
	mapLoadData();
}

function mapLoadData(){
	mapClearMap();
	if(aSelected.length>0){
		var idObject = $("#inputId").val();		
		$.ajax({
			type: "GET",
	        url: "/admin/rastreo/getlastp",
			data: { strInput: aSelected},
	        success: function(datos){	 	
				var result = datos;
				if(result!= ""){
					arrayTravels = result.split('!');
					printTravelsMap();
				}
	        }
		});
	}else{
		stopTimer()
	}
}

function printTravelsMap(){	
	
	for(var i=0;i<arrayTravels.length;i++){
		var travelInfo = arrayTravels[i].split('|');
	    var content     = '';
	    var markerTable = null;

	    if(travelInfo[2]!="null" && travelInfo[3]!="null"){
	    	var latitude  = travelInfo[2]; 
	    	var longitude = travelInfo[3]; 

	    	content='<table width="350" class="table-striped" >'+
	    			'<tr><td align="right"><b>Fecha</b></td><td align="left">'+travelInfo[1]+' </td><tr>'+	    			
	    			'<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[4]+' kms/h.</td><tr>'+
	    			'<tr><td align="right"><b>Tipo GPS</b></td><td align="left">'+travelInfo[5]+' </td><tr>'+
	    			'<tr><td align="right"><b>Ubicación</b></td><td align="left">'+travelInfo[7]+'</td><tr>'+
	    			'</table>';	 

            var image = {
              url: '/images/assets/taxi.png',
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(50, 50)
            };

			markerTable = new google.maps.Marker({
				map: map,
				position: new google.maps.LatLng(latitude,longitude),
				title: 	travelInfo[1],
				icon: 	image
			});
			markers.push(markerTable);
			infoMarkerTable(markerTable,content);	    	
	    }
	}
	
	//fitBoundsToVisibleMarkers();
	timerUpdate();
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

function infoMarkerTable(marker,content){	
    google.maps.event.addListener(marker, 'click',function() {
      if(infoWindow){infoWindow.close();infoWindow.setMap(null);}
      var marker = this;
      var latLng = marker.getPosition();
      infoWindow.setContent(content);
      infoWindow.open(map, marker);
      map.setZoom(13);
	  map.setCenter(latLng); 
	  map.panTo(latLng);     
	});
}

function centerTel(idValue){	
	for(var i=0;i<arrayTravels.length;i++){
		var travelInfo = arrayTravels[i].split('|');

		if(idValue == travelInfo[0]){
			var content     = '';
		    var markerTable = null;

		    if(travelInfo[2]!="null"  && travelInfo[3]!="null"){
		    	var latitude  = travelInfo[2]; 
		    	var longitude = travelInfo[3]; 

		    	content='<table width="350" class="table-striped" >'+
		    			'<tr><td align="right"><b>Fecha</b></td><td align="left">'+travelInfo[1]+' </td><tr>'+	    			
		    			'<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[4]+' kms/h.</td><tr>'+
		    			'<tr><td align="right"><b>Tipo GPS</b></td><td align="left">'+travelInfo[5]+' </td><tr>'+
		    			'<tr><td align="right"><b>Ubicación</b></td><td align="left">'+travelInfo[7]+'</td><tr>'+
		    			'</table>';	    	
				
				markerTable = new google.maps.Marker({
					position: new google.maps.LatLng(latitude,longitude)
				});

		      	if(infoWindow){infoWindow.close();infoWindow.setMap(null);}
					var marker = markerTable;
					var latLng = marker.getPosition();
					infoWindow.setContent(content);
					infoWindow.open(map, marker);
					map.setZoom(17);
					map.setCenter(latLng); 
					map.panTo(latLng);     
		    	}			
			break;
		}
	}
}

function getReport(idValue){
	$('#loader1').show();
	$('#iFrameSearch').hide();	
    $('#iFrameSearch').attr('src','/admin/rastreo/reporte?strInput='+idValue);
    $("#MyModalSearch").modal("show");
}
