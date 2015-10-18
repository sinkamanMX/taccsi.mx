var mapGeo        = null;
var geocoder      = null;
var infoLocation;
var markerOrigen  = null;
var markerDestino = null;
var bounds;
var directionsDisplay;
var valInputOrigen  = null;
var countryRestrict = {'country': 'mx'};
var LatLngBounds    = null;
var geos_poins_polygon = [];
var geos_polygon    = null;

$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : "required",
            inputEstatus    : "required"
        },
        messages: {
            inputNombre     : "Campo Requerido",            
            inputEstatus    : "Campo Requerido"            
        },
        submitHandler: function(form) {
          if(getPositionsGeo()){
              form.submit();
          }
        }
    });	
    initMapToDraw();

    $('#dataTableTar').dataTable({
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
});

function selectEstado(inputValue){
  if(geos_polygon!=null){
    geos_polygon.setMap(null);
  }

  if(inputValue==-1){
    $("#divSearch").show('slow');
    directionsDisplay = new google.maps.DirectionsRenderer();
    geocoder      = new google.maps.Geocoder();
    var autCompleteOrigen = new google.maps.places.Autocomplete(
       (
          document.getElementById('inputOrigen')), {
        types: ['(cities)'],
        componentRestrictions: countryRestrict
      });
    //places = new google.maps.places.PlacesService(map);
    google.maps.event.addListener(autCompleteOrigen, 'place_changed', function() {
        var place = autCompleteOrigen.getPlace();
        if (!place.geometry) {
          return;
        }

        searchOpt();
    });

  }else{
    $("#divSearch").hide('slow');
    $("#optReg").val("searchState");
    $("#FormData").submit();
  }
}

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}

function initMapToDraw(){
    latlngbounds  = new google.maps.LatLngBounds( );    
    var mapOptions = {
        zoom: 5,
        center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
    };

    mapGeo = new google.maps.Map(document.getElementById('myMapDraw'),mapOptions);    
    drawinit();    
}

function drawinit(){
    if(geos_poins_polygon!=null && geos_poins_polygon.length>0){
      geos_polygon = new google.maps.Polygon(geos_options);
      geos_polygon.setMap(mapGeo); 

      for (i = 0; i < geos_poins_polygon.length; i++) {
        latlngbounds.extend( geos_poins_polygon[i] );
      }

      mapGeo.fitBounds( latlngbounds );  
    }
}

function searchOpt(){
    var direccion = $("#inputOrigen").val();
      geocoder.geocode({'address': direccion, 'region': 'mx'}, function(results, status) {

        if (status == google.maps.GeocoderStatus.OK) {
            mapGeo.setCenter(results[0].geometry.location);
            var resultBounds = new google.maps.LatLngBounds(
                results[0].geometry.viewport.getSouthWest(),
                results[0].geometry.viewport.getNorthEast()
            );
            mapGeo.fitBounds(resultBounds);
            // get cities in the map
            var service = new google.maps.places.PlacesService(mapGeo);
            var request = {
                bounds: resultBounds,
                types: ['locality']
            };
            service.search(request, function (results, status) {

                 var ne = results[0].geometry.viewport.getNorthEast();
                 var sw = results[0].geometry.viewport.getSouthWest();

                 mapGeo.fitBounds(results[0].geometry.viewport);               

                 var boundingBoxPoints = [
                    ne, new google.maps.LatLng(ne.lat(), sw.lng()),
                    sw, new google.maps.LatLng(sw.lat(), ne.lng()), ne
                 ];

                 var geos_polygon = new google.maps.Polygon({
                    path: boundingBoxPoints,
                    editable: true,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35                    
                 });

                 geos_polygon.setMap(mapGeo);

            });                        
        } else {
          alert('Geocoder failed due to: ' + status);
        }
      });
}

function getPositionsGeo(){
  var resOp=false;

  if(geos_polygon!=null){
    var contentString='';
    var polygonBounds = geos_polygon.getPath();
    var xy;
    var firstposition = '';
    for (var i = 0; i < polygonBounds.length; i++) {
        xy = polygonBounds.getAt(i);

        if(contentString==""){
            firstposition  = (','+xy.lat().toFixed(6)+" "+xy.lng().toFixed(6));
        }
        contentString += (contentString!="") ? ',':'';
        contentString += xy.lat().toFixed(6)+" "+xy.lng().toFixed(6);        
    }

    contentString += firstposition;
    $('#inputPoints').html(contentString);

    if(contentString!=""){
      resOp=true;
    }    
  }

  if($("#catId").val()==-1){
    resOp=true;
  }
  
  return resOp;
}
