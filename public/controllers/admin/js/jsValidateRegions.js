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
var markersGeo  = [];

$().ready(function() {
  $("[data-toggle='offcanvas']").click();
  $(".only-numbers").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });  
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
          }
           return false;
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
    }else if($("#inputTipo").val()==2 ){
      $("#isearchRegion").show('fast');
      $("#inputEstado").rules("remove", "required");
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
  }else if(inputValue==2){
    $("#isearchRegion").show('fast');
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
    if($("#inputTipo").val()==1 || $("#inputTipo").val()==2 ){
      drawinit(1);    
    }else{
      printCircle();
    }
}

function drawinit(option){
    if(geos_poins_polygon!=null && geos_poins_polygon.length>0){
      geos_polygon = new google.maps.Polygon(geos_options);
      geos_polygon.setMap(mapGeo); 

      for (i = 0; i < geos_poins_polygon.length; i++) {
        latlngbounds.extend( geos_poins_polygon[i] );
      }

      mapGeo.fitBounds( latlngbounds );  
    }
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
  var iLatitud = $("#inputLatOrigen").val();
  var iLongitud= $("#inputLonOrigen").val();
  var iRadio   = $("#inputRadio").val();

  if(iLatitud!="" && iLongitud!="" && iRadio!=""){
    if(geos_polygon!=null){
      geos_polygon.setMap(null);
    }    
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

    google.maps.event.addListener(geos_polygon, 'dragend', function() {
      var iLatitud  = this.getCenter().lat().toFixed(6); 
      var iLongitud = this.getCenter().lng().toFixed(6); 
      $("#inputLatOrigen").val(iLatitud);
      $("#inputLonOrigen").val(iLongitud);
      printCircle()
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

      var resultBounds = new google.maps.LatLngBounds(
          place.geometry.viewport.getSouthWest(),
          place.geometry.viewport.getNorthEast()
      );

      mapGeo.fitBounds(resultBounds);        

      if($("#inputTipo").val()==2){
        var viewportPoints = [
            ne, new google.maps.LatLng(ne.lat(), sw.lng()),
            sw, new google.maps.LatLng(sw.lat(), ne.lng()), ne
        ];

        geos_options = {
          paths: viewportPoints,
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 3,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          editable:true
        }

      geos_polygon = new google.maps.Polygon(geos_options);
      geos_polygon.setMap(mapGeo); 

      }else{
        var distancia = distanciaEntrePuntos(ne.lat(), ne.lng(),place.geometry.location.lat(),place.geometry.location.lng());      
        $("#inputLatOrigen").val(place.geometry.location.lat().toFixed(6));
        $("#inputLonOrigen").val(place.geometry.location.lng().toFixed(6));
        $("#inputRadio").val(distancia);
        printCircle();
      }
  });
}

function getPositionsGeo(){
  var bResult = false;

  if($("#inputTipo").val()==1 || $("#inputTipo").val()==2 ){
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
      $('#inputPoints').html(contentString);  

      if(contentString!=""){
        var PointGeo = geos_polygon.getCenter();
        $("#inputLatOrigen").val(PointGeo.lat().toFixed(6));
        $("#inputLonOrigen").val(PointGeo.lng().toFixed(6));        
        bResult = true;
      }    
  }else{
    var path = circlePath(geos_polygon.getCenter());
    console.log("Es tipo 0 "+path);
    bResult = true;
  }

  return bResult;
}

function optionAll(inputCheck){
    if(inputCheck){
        $('.chkOn').prop('checked', true);         
    }else{
        $('.chkOn').prop('checked', false);
    }
    timeUpGeos();
}

function circlePath(center){
  var bResult= false;  
  var radius = $("#inputRadio").val()*1000;
  var points = 360 ;
    var a=[],p=360/points,d=0;
    var contentString='';
    var firstposition = '';
    for(var i=0;i<points;++i,d+=p){
      var point = google.maps.geometry.spherical.computeOffset(center,radius,d);

      if(contentString==""){
          firstposition  = (','+point.lat().toFixed(6)+" "+point.lng().toFixed(6));
      }
      contentString += (contentString!="") ? ',':'';
      contentString += point.lat().toFixed(6)+" "+point.lng().toFixed(6);         
    }

    contentString += firstposition;   
    $('#inputPoints').html(contentString);  
    if(contentString!=""){
      bResult = true;
    }     

    return bResult;
}

function calcularDistancia(lat1, lat2, lon1, lon2){
    var R = 6371; // Radio del planeta tierra en km
    var phi1 = lat1.toRadians();
    var phi2 = lat2.toRadians();
    var deltaphi = (lat2-lat1).toRadians();
    var deltalambda = (lon2-lon1).toRadians();

    var a = Math.sin(deltaphi/2) * Math.sin(deltaphi/2) +
            Math.cos(phi1) * Math.cos(phi2) *
            Math.sin(deltalambda/2) * Math.sin(deltalambda/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

    var d = R * c
    return d;
}

function timeUpGeos(){
  setTimeout(printGeos(), 2000);
}

function mapClearMap(){
  if(markersGeo || markersGeo.length>-1){
    for (var i = 0; i < markersGeo.length; i++) {
        markersGeo[i].setMap(null);
    } 
    markersGeo = [];
  }
}

function printGeos(){
  mapClearMap();
  var myVarGeo =null;
  $('#dataTable input[type=checkbox]').each(function(){
    var id = $(this).val();    
    if(id>-1){             
      myVarGeo = eval('Polygono_'+id);    

      if(this.checked){        
          myVarGeo = new google.maps.Polygon(eval('sPolygono_'+id));
          myVarGeo.setMap(mapGeo);   
          markersGeo.push(myVarGeo);
      }
    }
  }); 
}