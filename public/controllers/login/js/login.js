$( document ).ready(function() {
	$("input").keypress(function(event) {
	    if (event.which == 13) {
	        validatelogin()
	    }
	});
  
});

function validatelogin(){
	$("#formg-user").removeClass("has-warning");
	$("#formg-pass").removeClass("has-warning");

	$("#lbl_user").hide();
	$("#lbl_pass").hide();
	$("#div-msg").html("");
	$("#div-msgError").hide();
	
	var userLogin = $("#txt-user").val();
	var passLogin = $("#txt-pass").val(); 

	
	if(userLogin=="" && userLogin.length==0){		
		$("#lbl_user").show();
		$("#formg-user").addClass("has-warning");
		return false;
	}

	if(passLogin=="" && passLogin.length==0){		
		$("#lbl_pass").show();
		$("#formg-pass").addClass("has-warning");
		return false;
	}

	$.ajax({
	    url: "/login/main/login",
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
                location.href='/admin/main/inicio';
            }else if(result == 'problem'){
            	$("#div-msgError").show();            	
				$("#div-msg").html("Por cuestion de seguridad solo se puede ingresar una vez por usuario.");
            }else{
            	$("#div-msgError").show(); 
				$("#div-msg").html("Usuario y/o contraseña incorrectos.");
            }	        
	    }
	});
}