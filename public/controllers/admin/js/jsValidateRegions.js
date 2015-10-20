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
  $("[data-toggle='offcanvas']").click();

    $("#FormData").validate({
        rules: {
            inputNombre     : "required",
            inputTipo       : "required",
            inputCosto      : {
              required: true,
              number: true
            },
            inputAcumulable : "required",
            inputLatOrigen  : {
              required: true,
              number: true
            },
            inputLonOrigen  : {
              required: true,
              number: true
            },
            inputRadio      : {
              required: true,
              number: true
            },
            inputEstado     : "required"
        },
        messages: {
            inputNombre     : "required",
            inputTipo       : "required",
            inputCosto      : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputAcumulable : "required",
            inputLatOrigen  : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputLonOrigen  : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputRadio      : {
              required: "Campo Requerido",    
              number: "Este campo acepta solo números"
            },
            inputEstado     : "required"       
        },
        submitHandler: function(form) {
          if($("#optReg").val()=='searchState'){
            form.submit();
          }else if(getPositionsGeo()){
              form.submit();
              return false;
          }
        }
    });

    if($("#inputTipo").val()==0){
      autoCompleteFunction();
      $("#inputEstado").rules("remove", "required");
      $("#inputLatOrigen").rules("add",  {required:true});
      $("#inputLonOrigen").rules("add",  {required:true});
      $("#inputRadio").rules("add",  {required:true});      
    }else if($("#inputTipo").val()==1){
      $("#inputEstado").rules("add",  {required:true});
      $("#inputRadio").rules("remove", "required");
      $("#inputLatOrigen").rules("remove", "required");    
      $("#inputLonOrigen").rules("remove", "required");  
    } 

    if($("#usTaximetro").val()==0){
      $("#inputCosto").rules("remove", "required");
      $("#inputAcumulable").rules("remove", "required");
    }
    initMapToDraw();
});

function selectOption(inputValue){
  if(geos_polygon!=null){
    geos_polygon.setMap(null);
  }  
  if(inputValue==0){
    $("#isearchRegion").show('fast');
    $(".optsCircle").show('fast');
    $("#divEstado").hide('fast');
    $("#inputEstado").val(-1);

    $("#inputEstado").rules("remove", "required");
    $("#inputLatOrigen").rules("add",  {required:true});
    $("#inputLonOrigen").rules("add",  {required:true});
    $("#inputRadio").rules("add",  {required:true});    
    autoCompleteFunction();    
  }else if(inputValue==1){
    $("#isearchRegion").hide('fast');
    $(".optsCircle").hide('fast');
    $("#divEstado").show('fast');

    $("#inputLongitud").val("");
    $("#inputRadio").val("");
    $("#inputLatitud").val("");

    $("#inputEstado").rules("add",  {required:true});
    $("#inputRadio").rules("remove", "required");
    $("#inputLatOrigen").rules("remove", "required");    
    $("#inputLonOrigen").rules("remove", "required");    
  }
}

function selectEstado(inputValue){
  $("#optReg").val("searchState");
  $("#FormData").submit();
}

function initMapToDraw(){
    latlngbounds  = new google.maps.LatLngBounds( );    
    var mapOptions = {
        zoom: 5,
        center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
    };

    mapGeo = new google.maps.Map(document.getElementById('myMapDraw'),mapOptions);        
    printCircle();
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

function distanciaEntrePuntos(lat1,lon1,lat2,lon2){
  rad = function(x) {return x*Math.PI/180;}

  var R     = 6378.137;                     //Radio de la tierra en km
  var dLat  = rad( lat2 - lat1 );
  var dLong = rad( lon2 - lon1 );

  var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLong/2) * Math.sin(dLong/2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c;

  return d.toFixed(2);                      //Retorna tres decimales
}

function printCircle(){
  if(geos_polygon!=null){
    geos_polygon.setMap(null);
  }
  var iLatitud = $("#inputLatOrigen").val();
  var iLongitud= $("#inputLonOrigen").val();
  var iRadio   = $("#inputRadio").val();

  if(iLatitud!="" && iLongitud!="" && iRadio!=""){
    geos_polygon = new google.maps.Circle({
      strokeColor: '#FF0000',
      draggable   : true,
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: mapGeo,
      center: new google.maps.LatLng(iLatitud,iLongitud),
      radius: (iRadio*1000)
    });
    mapGeo.fitBounds(geos_polygon.getBounds());
  }
}

function autoCompleteFunction(){
  geocoder      = new google.maps.Geocoder();
  var autCompleteOrigen = new google.maps.places.Autocomplete(
     (
      document.getElementById('inputOrigen')), {
      types: ['(cities)'],
      componentRestrictions: countryRestrict
    });

  google.maps.event.addListener(autCompleteOrigen, 'place_changed', function() {
      var place = autCompleteOrigen.getPlace();
      if (!place.geometry) {
        return;
      }   

      var ne = place.geometry.viewport.getNorthEast();
      var sw = place.geometry.viewport.getSouthWest();
      var distancia = distanciaEntrePuntos(ne.lat(), ne.lng(),place.geometry.location.lat(),place.geometry.location.lng());
      
      var resultBounds = new google.maps.LatLngBounds(
          place.geometry.viewport.getSouthWest(),
          place.geometry.viewport.getNorthEast()
      );

      mapGeo.fitBounds(resultBounds);        
      $("#inputLatOrigen").val(place.geometry.location.lat().toFixed(6));
      $("#inputLonOrigen").val(place.geometry.location.lng().toFixed(6));
      $("#inputRadio").val(distancia);
      printCircle();
  });
}

function getPositionsGeo(){
  var bResult = false;

  if($("#inputTipo").val()==1){
      console.log("Es tipo 1");
      $('#inputPoints').html('');  
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
      console.log(contentString);
      $('#inputPoints').html(contentString);  

      if(contentString!=""){
        bResult = true;
      }    
  }else{
    console.log("Es tipo 0");
    bResult = true;
  }

  return bResult;
}
/*
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
*/