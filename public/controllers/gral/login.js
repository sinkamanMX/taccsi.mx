$( document ).ready(function() {
	$("input").keypress(function(event) {
	    if (event.which == 13) {
	        validatelogin()
	    }
	});
  
});

function validatelogin(){
	$("#lbl_user").hide();
	$("#lbl_pass").hide();
	$("#div-msg").hide();

	var userLogin = $("#txt-user").val();
	var passLogin = $("#txt-pass").val(); 

	
	if(userLogin=="" && userLogin.length==0){		
		$("#lbl_user").show();
		return false;
	}

	if(passLogin=="" && passLogin.length==0){		
		$("#lbl_pass").show();
		return false;
	}

	$.ajax({
	    url: "/admin/login/login",
	    type: "GET",
	    dataType : 'json',
	    data: { 
	    	usuario : userLogin,
	    	contrasena : passLogin,
	    	typaction: 'lg'
	    	},
	    success: function(data) {
            var result = data.answer; 
                
            if(result == 'logged'){
                location.href='/admin/admin/index';
            }else if(result == 'problem'){
            	$("#div-msg").show();
                $("#div-msg").html("Por cuestion de seguridad solo se puede ingresar una vez por usuario.");
            }else{
            	$("#div-msg").show();
                $("#div-msg").html("Usuario y/o contraseña incorrectos");
            }	        
	    }
	});
}

function user_load_datatable(){
    oTable = $('#user_table').dataTable({
      "bDestroy": true,
      "bLengthChange": false,
      "bPaginate": true,
      "bFilter": true,
      "bSort": true,
      "bJQueryUI": true,
      "iDisplayLength": 20,      
      "bProcessing": true,
      "bAutoWidth": false,
      "bSortClasses": false,
      "sAjaxSource": "index.php?m=mUsuarios&c=mGetTable",
      "aoColumns": [
        { "mData": " ", sDefaultContent: "" },
        { "mData": "NOMBRE", sDefaultContent: "" },
        { "mData": "USUARIO", sDefaultContent: "" },
        { "mData": "ESTATUS", sDefaultContent: "" },
        { "mData": "CREADO", sDefaultContent: "" },
      ] , 
      "aoColumnDefs": [
        {"aTargets": [0],
          "sWidth": "10%",
          "bSortable": false,        
          "mRender": function (data, type, full) {
            /*var edit  = '';
            var del   = '';

            if($("#user_update").val()==1){
                edit = "<td><div onclick='user_edit_function("+full.ID+");' class='custom-icon-edit-custom'>"+
                        "<img class='total_width total_height' src='data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAQAICVAEAOw=='/>"+
                        "</div></td>";
            }
            
            if($("#user_delete").val()==1){
                del = "<td><div onclick='user_delete_function("+full.ID+");' class='custom-icon-delete-custom'>"+
                        "<img class='total_width total_height' src='data:image/gif;base64,R0lGODlhAQABAJH/AP///wAAAMDAwAAAACH5BAEAAAIALAAAAAABAAEAQAICVAEAOw=='/>"+
                        "</div></td>";
            }    
            return '<table><tr>'+edit+del+'</tr></table>';*/
        }}
      ],
      "oLanguage": {
          "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
          "sEmptyTable": "No hay registros.",
          "sInfoEmpty" : "No hay registros.",
          "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
          "sLoadingRecords": "Leyendo información",
          "sProcessing": "Procesando",
          "sSearch": "Buscar:",
          "sZeroRecords": "No hay registros",
      }
    });
  }