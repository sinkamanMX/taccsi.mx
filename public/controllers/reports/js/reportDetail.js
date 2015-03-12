var chart1;  
var chartIngreso;
var chartFormaPago;
var chartCurso;
var chartViajes;
var chartRatings;

$( document ).ready(function() {
      //Make the dashboard widgets sortable Using jquery UI
    /*$(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection(); 
*/
    chart1 = new cfx.Chart();
    chart1.setGallery(cfx.Gallery.Pie);  
    chart1.setDataSource(aResumeTravels);
    chart1.getAllSeries().getPointLabels().setVisible(true);
    chart1.getLegendBox().setVisible(false);
    chart1.getAnimations().getLoad().setEnabled(true);
    var divHolder = document.getElementById('ChartDiv');
    chart1.create(divHolder); 


    chartIngreso = new cfx.Chart();
    chartIngreso.setGallery(cfx.Gallery.Bar); 
    chartIngreso.setDataSource(dataIngresos);
    chartIngreso.getAnimations().getLoad().setEnabled(true);
    var divHolIngreso = document.getElementById('ChartIngresos');
    chartIngreso.create(divHolIngreso);   

    chartViajes = new cfx.Chart();
    chartViajes.setGallery(cfx.Gallery.Bar); 
    chartViajes.setDataSource(dataTaxis);
    chartViajes.getAnimations().getLoad().setEnabled(true);
    var divHolViajes = document.getElementById('ChartViajes');
    chartViajes.create(divHolViajes);    

    chartRatings = new cfx.Chart();
    chartRatings.setGallery(cfx.Gallery.Bar);  
    chartRatings.setDataSource(dataRating);
    chartRatings.getAnimations().getLoad().setEnabled(true);
    var divRating = document.getElementById('ChartRating');
    chartRatings.create(divRating);           
});