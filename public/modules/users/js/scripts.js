$( document ).ready(function() {
    var scrollY = $("#div-container").height()-250;
    $('#dt-users').dataTable({
        "sScrollY": scrollY+"px",
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bInfo": false,
        "bAutoWidth": true
    });  
    $('#modal-set-user').modal('hide');
  
});

function user_getData(idRow){
    $.ajax({
        url: "/admin/users/setdata",
        type: "GET",
        data: { rowid: idRow},
        success: function(data) {
            var result = data; 
            $("#modal-set-user").html(result);
            $('#modal-set-user').modal('show') ;
        }
    });	
}

