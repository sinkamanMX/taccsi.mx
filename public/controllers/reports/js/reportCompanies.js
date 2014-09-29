var chart1;  
var chartIngreso;
var chartFormaPago;
var chartCurso;
var chartRatings;

$( document ).ready(function() {    
    $('.daterange').daterangepicker(
            {
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                startDate: moment().subtract('days', 29),
                endDate: moment()
            },
    function(start, end) {
        alert("You chose: " + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    });

    $('#tableData').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 10,      
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

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection(); 

    chart1 = new cfx.Chart();
    chart1.setGallery(cfx.Gallery.Bar);  
    chart1.setDataSource(aResumeTravels);
    chart1.getAnimations().getLoad().setEnabled(true);
    var divHolder = document.getElementById('ChartDiv');
    chart1.create(divHolder); 

    chartIngreso = new cfx.Chart();
    chartIngreso.setGallery(cfx.Gallery.Bar); 
    chartIngreso.setDataSource(dataIngresos);
    chartIngreso.getAnimations().getLoad().setEnabled(true);
    var divHolIngreso = document.getElementById('chartIngreso');
    chartIngreso.create(divHolIngreso);   

    chartFormaPago = new cfx.Chart();
    chartFormaPago.setGallery(cfx.Gallery.Bar);  
    chartFormaPago.setDataSource(dataFpagos);
    chartFormaPago.getAnimations().getLoad().setEnabled(true);
    var divFpago = document.getElementById('chartForma');
    chartFormaPago.create(divFpago); 

    chartRatings = new cfx.Chart();
    chartRatings.setGallery(cfx.Gallery.Bar);  
    chartRatings.setDataSource(dataRating);
    chartRatings.getAnimations().getLoad().setEnabled(true);
    var divRating = document.getElementById('chartRating');
    chartRatings.create(divRating);    

  $('#dataTable').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 10,      
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
});