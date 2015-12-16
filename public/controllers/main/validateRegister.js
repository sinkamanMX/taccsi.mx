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
            inputRFC        : "required"
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
            inputRFC        : "Campo Requerido"    
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
});

function getoptionsCbo(idCboTo,classObject,idObject,chosen,options){  
  $("#div"+idCboTo).html('<img id="loader1" class="col-xs-offset-4" src="/images/assets/loading.gif" alt="loading gif"/>');
    var classChosen = (chosen) ? 'chosen-select': '';
    var claseFind   = (options=='coloniaO') ? 'colonia': options;
    var optionSelect= (options!='') ? 'getoptionsCbo("'+options+'","'+claseFind+'",this.value,false,"");': '';
    var optsCP      = (idCboTo=='colonia' || idCboTo=='coloniaO') ? 'getCPdir(this.value,"'+idCboTo+'");': '';
    $.ajax({
        url: "/admin/json/getselect",
        type: "GET",
        data: { catId : idObject, 
            oprDb : classObject },
        success: function(data) { 
          $("#div"+idCboTo).html("");
          var dataCbo = '<select class=" form-control'+classChosen+'" id="input'+idCboTo+'" name="input'+idCboTo+'" onChange=\''+optionSelect+' '+optsCP+'\'>';
          if(data!="no-info"){
            dataCbo += '<option value="">Seleccionar una opción</option>'+data+'</select>';
          }else{
        dataCbo += '<option value="">Sin Información</option>';
          }
          dataCbo += '</select>';
                  
          $("#div"+idCboTo).html(dataCbo);
          /*$(".chosen-select").chosen({disable_search_threshold: 10});*/
        }
    });   
}

$().ready(function() {
    $('.upperClass').keyup(function()
    {
        $(this).val($(this).val().toUpperCase());
    }); 
});   




  
function statusChangeCallback(response) {
    if (response.status === 'connected') {
        getinfoAccount();
        getPhoto()
    }else if(response.status === 'not_authorized') {
        console.log('No autorizado');
    }else{
        console.log('Problema');
    }
}

function getinfoAccount(){
    FB.api('/me?fields=name,email,link', function(response) {
        <?php if(!preg_match('/registro.php/',$page):?>
        validateSessionUser(response);
        <?php endif;?>
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
                    registerUser(response);
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