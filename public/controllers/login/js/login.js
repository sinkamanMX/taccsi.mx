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
            	$("#div-msg").show();
                $("#div-msg").html("Por cuestion de seguridad solo se puede ingresar una vez por usuario.");
            }else{
            	$("#div-msg").show();
                $("#div-msg").html("Usuario y/o contraseña incorrectos");
            }	        
	    }
	});
}