var mapGeo = null;
var bounds;
var directionsDisplay;
var directionsDisplayNone;
var markerOrigen  = null;
var markerDestino = null;
var bounds;
var directionsService = new google.maps.DirectionsService();
var controlPagination = 1;
var totalRows         = 0;

$( document ).ready(function() {
    totalRows   = $("#totalRows").val();
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var dateInter  = parseInt(nowTemp.getMonth())+1;  
    var todayMonth = (dateInter<10) ? "0"+dateInter : dateInter;
    var todayDay   = (nowTemp.getDate()<10) ? "0"+nowTemp.getDate(): nowTemp.getDate();        

    if($("#inputFechaIn").val()==""){
      $("#inputFechaIn").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 00:00');      
    }

    if($("#inputFechaFin").val()==""){
      $("#inputFechaFin").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 23:59');    
    }
    
    var checkin = $('#inputFechaIn').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() > $('#inputFechaFin').datetimepicker('getDate').valueOf()){
        $('#inputFechaFin').datetimepicker('setDate', ev.date);   
      }

      $('#inputFechaFin').datetimepicker('setStartDate', ev.date);      
      $('#inputFechaFin').prop('disabled', false);
      $('#inputFechaFin')[0].focus();      
    });

    var checkout = $('#inputFechaFin').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() < $('#inputFechaIn').datetimepicker('getDate').valueOf()){
        $('#inputFechaIn').datetimepicker('setDate', ev.date);   
      }
      $('#inputFechaIn').datetimepicker('setEndDate', ev.date);
    }); 


    $(".knob").knob({
        /*change : function (value) {
         //console.log("change : " + value);
         },
         release : function (value) {
         console.log("release : " + value);
         },
         cancel : function () {
         console.log("cancel : " + this.value);
         },*/
        draw: function() {

            // "tron" case
            if (this.$.data('skin') == 'tron') {

                var a = this.angle(this.cv)  // Angle
                        , sa = this.startAngle          // Previous start angle
                        , sat = this.startAngle         // Start angle
                        , ea                            // Previous end angle
                        , eat = sat + a                 // End angle
                        , r = true;

                this.g.lineWidth = this.lineWidth;

                this.o.cursor
                        && (sat = eat - 0.3)
                        && (eat = eat + 0.3);

                if (this.o.displayPrevious) {
                    ea = this.startAngle + this.angle(this.value);
                    this.o.cursor
                            && (sa = ea - 0.3)
                            && (ea = ea + 0.3);
                    this.g.beginPath();
                    this.g.strokeStyle = this.previousColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                    this.g.stroke();
                }

                this.g.beginPath();
                this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                this.g.stroke();

                this.g.lineWidth = 2;
                this.g.beginPath();
                this.g.strokeStyle = this.o.fgColor;
                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                this.g.stroke();

                return false;
            }
        }
    });

  /*
    $('#tableCLients').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": false,
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
    });    */  
    pagination('op',1);
});

function showCloseOptions(inputRow){
    var open  = $("#spanOptions"+inputRow).hasClass('fa-chevron-down');
    var close = $("#spanOptions"+inputRow).hasClass('fa-chevron-up');

    if(open && close == false){
        $("#spanOptions"+inputRow).removeClass('fa-chevron-down').addClass('fa-chevron-up');
        $("#iDivinfo"+inputRow).fadeIn(1000);    
        printMap(inputRow);  
    }
    
    if(close && open == false){
        $("#spanOptions"+inputRow).removeClass('fa-chevron-up').addClass('fa-chevron-down');
        $("#iDivinfo"+inputRow).fadeOut('slow','swing');       
    }
}

function printMap(inputRow){
  directionsDisplay     = new google.maps.DirectionsRenderer({
                  polylineOptions: {
                    strokeColor: "green"
                  }
                });

  directionsDisplayNone = new google.maps.DirectionsRenderer();

  var mapOptions = {
    zoom: 5,
    center: new google.maps.LatLng(19.435113686545755,-99.13316173010253)
  };

  mapGeo = new google.maps.Map(document.getElementById('map'+inputRow),mapOptions);  

  bounds = new google.maps.LatLngBounds();
  directionsDisplay.setMap(mapGeo);
  directionsDisplayNone.setMap(mapGeo);
  calcRoute(inputRow);
}

function calcRoute(inputRow) {
  if($("#iLatO"+inputRow).val()!="" && $("#iLonO"+inputRow).val()!="" && 
    $("#iLatD"+inputRow).val()!="" && $("#iLonD"+inputRow).val()!=""){
    var latsOrigen  = new google.maps.LatLng($("#iLatO"+inputRow).val(), $("#iLonO"+inputRow).val());
    var lastDestino = new google.maps.LatLng($("#iLatD"+inputRow).val(), $("#iLonD"+inputRow).val());
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
  }
}

function pagination(op,indexPagination){
  if(totalRows>10){    
    if(op=='ant'){
      if((controlPagination-1) == 0){
        controlPagination = 1;
      }else{
        controlPagination = controlPagination-1;  
      }       
    }else if(op=='op'){
      controlPagination = indexPagination;
    }else if(op=='next'){      
      var tRowval = Math.round(totalRows / 10);
      if((controlPagination+1) == tRowval){
        controlPagination = controlPagination+1; 
      }else{
        controlPagination = tRowval;  
      }
    }

    $(".btnPagination").removeClass("btn-warning");
    $("#btnP_"+controlPagination).addClass("btn-warning");  

    var indexInit = 0;
    var indexEnd  = 0;

  switch (controlPagination) { 
    case 1: 
        indexInit = 1
        indexEnd  = 10
        break 
    case 2: 
        indexInit = 11
        indexEnd  = 20
        break 
    case 3: 
        indexInit = 21
        indexEnd  = 30
        break 
    case 4: 
        indexInit = 31
        indexEnd  = 40
        break   
    case 5: 
        indexInit = 41
        indexEnd  = 50
        break   
    case 6: 
        indexInit = 51
        indexEnd  = 60
        break
  }    

  var controlInt = 1;
    $(".divInfo").hide('fast');

    $('#tableCLients tbody .rowUpdate').each(function (){
      if(controlInt <=  indexEnd && controlInt >= indexInit){  
        $(this).fadeIn(1000);   
      }else{
        $(this).hide('fast');
      }
      controlInt++;
    });
  }  
}