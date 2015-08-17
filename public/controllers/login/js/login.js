var mouse_is_inside=false;
$( document ).ready(function() {
	$("input").keypress(function(event) {
	    if (event.which == 13) {
	        validatelogin()
	    }
	});

    $('#loginPanel').hover(function(){ 
        mouse_is_inside=true; 
    }, function(){ 
        mouse_is_inside=false; 
    });

    $("body").mouseup(function(){ 
        if(! mouse_is_inside) $('#loginPanel').fadeOut('slow','swing');
    });

	$("#txt-user").removeClass("c-control-e");
	$("#txt-pass").removeClass("c-control-e");
	$("#lbl_user").hide();
	$("#lbl_pass").hide();  
});

function validatelogin(){
	$("#txt-user").removeClass("c-control-e");
	$("#txt-pass").removeClass("c-control-e");
	$("#lbl_user").hide();
	$("#lbl_pass").hide();
	$("#div-msg").html("");
	$("#div-msgError").hide();

	var bValidate = true;
	
	var userLogin = $("#txt-user").val();
	var passLogin = $("#txt-pass").val(); 

	if(userLogin=="" && userLogin.length==0){		
		$("#lbl_user").show();
		$("#txt-user").addClass("c-control-e");
		bValidate =  false;
	}

	if(passLogin=="" && passLogin.length==0){		
		$("#lbl_pass").show();
		$("#txt-pass").addClass("c-control-e");
		bValidate =  false;
	}

	if(!bValidate){
		return false;
	}

	var option = $("#formLogin input[type='radio']:checked").val();
	var myUrl  = '';
	if(option=='passenger'){		
		myUrl  = '/external/login/login';
	}else{
		myUrl  = '/login/main/login';
	}
	
	$.ajax({
	    url: myUrl,
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
            	if(option=='passenger'){
            		location.href='/external/login/inicio';
            	}else{					
					location.href='/admin/main/inicio';	
            	}
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

function showPanelLogin(){
	$("#loginPanel").fadeIn(1000);
}
