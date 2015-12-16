$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputNombre     : {
                required: true,
                minlength: 3,
            }, 
            inputApaterno   : {
                required: true,
                minlength: 3,
            }, 
            inputAmaterno   : {
                required: true,
                minlength: 3,
            }, 
            inputUsuario    : {
                required: true,
                email: true
            },
            inputPhone      : {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10
            }, 
            inputPassword   : "required",
            inputCpassword  : {
                required: true,
                equalTo: "#inputPassword",
            },
        },
        messages: {
            inputNombre     : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un nombre válido",
            }, 
            inputApaterno   : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un apellido paterno válido",
            },
            inputAmaterno   : {
                required  : "Campo Requerido",
                minlength : "Debe de ingresar un apellido materno válido",
            },
            inputUsuario    : {
                required  : "Campo Requerido",
                email     : "Debde ingresar un email válido."  
            },
            inputTipo       : "Campo Requerido",
            inputPassword   : "Campo Requerido",
            inputPhone    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            },
            inputCpassword  : {
                required    : "Campo Requerido",
                equalTo     : "La contraseña no coincide."
            }, 
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	

    $('.upperClass').keyup(function()
    {
        $(this).val($(this).val().toUpperCase());
    }); 
});

function statusChangeCallback(response) {
    /*
    if (response.status === 'connected') {
        getinfoAccount();
        getPhoto()
    }else if(response.status === 'not_authorized') {
        console.log('No autorizado');
    }else{
        console.log('Problema');
    }*/
}

function getinfoAccount(){
    FB.api('/me?fields=name,email,link', function(response) {
        /*
        <?php if(!preg_match('/registro.php/',$page):?>
        validateSessionUser(response);
        <?php endif;?>
        */
    });
  }     
 
    function login() {
        FB.login(function(response) {
           if (response.authResponse) {
                statusChangeCallback(response);
            }
        }, {scope: 'public_profile, email'});            
    }

    function loginRegister(){
        FB.login(function(response) {
           if (response.authResponse) {
                FB.api('/me?fields=name,first_name,last_name,email,link', function(response) {
                    var userid = response.id;
                    var sMail  = response.email;
                    var sName  = response.first_name;
                    var sFname = response.last_name;
                    var sLink  = response.link;    
                    
                    $("#inputNombre").val(sName);
                    $("#inputApaterno").val(sFname);
                    $("#inputUsuario").val(sMail);
                    $("#inputPassword").val(userid);
                    $("#inputCpassword").val(userid);
                    $("#box_login").hide('slow');
                    //registerUser(response);
                });
            }
        }, {scope: 'public_profile, email'});                               
    }

  function validateSessionUser(adatacontent){
    var userid = adatacontent.id;
    var aName  = adatacontent.email;

    $("#usuario").val(aName);
    $("#password").val(userid);

    $("#loginForm").submit();
  }  

  function registerUser(adatacontent){
    var userid = adatacontent.id;
    var sMail  = adatacontent.email;
    var sName  = adatacontent.first_name;
    var sFname = adatacontent.last_name;
    var sLink  = adatacontent.link;                 
     if(typeof sMail != 'undefined'){                               
        $.ajax({
            url: "registrofb_exec.php",
            type: "POST",
            dataType : 'json',
            data: { iduser      : userid, 
                    sunser      : sMail,
                    nameuser    : sName,
                    appuser     : sFname,
                    slink       : sLink,
                    option      : 'register'},
            success: function(data) {
                var result = data.answer; 
                console.log(result);

                if(result == 'register'){
                    window.location.href = "/mis_mascotas.php";
                }else if(result == 'uexist'){
                    alert("<b>¡Problema!</b>Este correo electrónico ya existe en nuestra base de datos, intente con otro por favor o recupere su contraseña.</div><br>");
                }else{
                    alert("<b>¡Problema!</b>Ocurrio un problema al realizar la operación.</div><br>");
                }
            }
        });
    }else{
        alert("Error! No se ha encontrado un e-mail válido, ligado a su cuenta de FaceBook.");
        FB.logout(function(){document.location.reload();});
    }
  }

  function logout(){                            
    FB.logout(function(){});
    window.location.href = "/auth_salir.php";
  }

  function getPhoto(){
      FB.api('/me/picture?type=large', function(response){
        $("#imgProfile").attr("src", response.data.url );
        /*
          var str="<br/><b>Pic</b> : <img src='"+response.data.url+"'/>";
          document.getElementById("status").innerHTML+=str;*/
        });
    }  