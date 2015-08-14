$().ready(function() {
	$("#formProfile").validate({
        rules: {
            inputNombre     : "required",
            inputApaterno   : "required",
            inputAmaterno   : "required",
            inputUsuario    : "required",            
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
            }    
        },
        messages: {
            inputNombre     : "Campo Requerido",
            inputApaterno   : "Campo Requerido",
            inputAmaterno   : "Campo Requerido",
            inputUsuario    : "Campo Requerido",
            inputPassword   : "Campo Requerido",
            inputCpassword  : {
                required    : "Campo Requerido",
                equalTo     : "La contraseña no coincide."
            }, 
            inputPhone    : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El Teléfono debe de ser de 10 dígitos",
                maxlength : "El Teléfono debe de ser de 10 dígitos"
            },
            inputPasswordNow: "Campo Requerido"                         
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
    
    if($("#catId").val()>-1){
        $("#inputPassword").rules("remove", "required");
        $("#inputCpassword").rules("remove", "required");          
    }
    
    $("#inputPasswordNow").rules("remove", "required"); 

    $("#FormData").validate({
        rules: {
            inputNameEmpresa: "required",
            inputTipoRazon  : "required",
            inputRazon      : "required",
            inputRFC        : "required",
            inputRep        : "required",

            inputCalle      : "required",
            inputNoext      : "required",
            inputNoint      : "required",
            inputEstado     : "required",
            inputMunicipio  : "required",
            inputColonia    : "required",
            inputCp         : "required",
            inputEstatus    : "required",
            inputDirDif     : "required",

            inputCalleF     : "required",
            inputNoextF     : "required",
            inputNointF     : "required",
            inputColoniaF   : "required",
            inputEstadoF    : "required",
            inputMunicipioF : "required",
            inputCpF        : "required",
            
            inputNombre     : "required",
            inputApaterno   : "required",
            inputAmaterno   : "required",
            inputUsuario    : "required",
            inputPassword   : "required",
            inputCpassword  : {
                required: true,
                equalTo: "#inputPassword",
            }
        },
        messages: {
            inputNameEmpresa: "Campo Requerido",
            inputTipoRazon  : "Campo Requerido",
            inputRazon      : "Campo Requerido",
            inputRFC        : "Campo Requerido",
            inputRep        : "Campo Requerido",

            inputCalle      : "Campo Requerido",
            inputNoext      : "Campo Requerido",
            inputNoint      : "Campo Requerido",
            inputEstado     : "Campo Requerido",
            inputMunicipio  : "Campo Requerido",
            inputColonia    : "Campo Requerido",
            inputCp         : "Campo Requerido",
            inputEstatus    : "Campo Requerido",
            inputDirDif     : "Campo Requerido",

            inputCalleF     : "Campo Requerido",
            inputNoextF     : "Campo Requerido",
            inputNointF     : "Campo Requerido",
            inputColoniaF   : "Campo Requerido",
            inputEstadoF    : "Campo Requerido",
            inputMunicipioF : "Campo Requerido",
            inputCpF        : "Campo Requerido",
            inputNombre     : "Campo Requerido",
            inputApaterno   : "Campo Requerido",
            inputAmaterno   : "Campo Requerido",
            inputUsuario    : "Campo Requerido",            
            inputPassword   : "Campo Requerido",
            inputCpassword  : {
                required    : "Campo Requerido",
                equalTo     : "La contraseña no coincide."
            }                                          
        },
        submitHandler: function(form) {
            form.submit();
        }
    }); 
    
    if($("#inputDirDif").val()==0){
        $("#divDirDif").hide('slow');

        $("#inputCalleF").rules("remove", "required");
        $("#inputNoextF").rules("remove", "required");
        $("#inputNointF").rules("remove", "required");
        $("#inputColoniaF").rules("remove", "required");
        $("#inputEstadoF").rules("remove", "required");
        $("#inputMunicipioF").rules("remove", "required");
        $("#inputCpF").rules("remove", "required");     
    }    
});


function directionDif(value){
    if(value=="0"){
        $("#divDirDif").hide('slow');

        $("#inputCalleF").rules("remove", "required");
        $("#inputNoextF").rules("remove", "required");
        $("#inputNointF").rules("remove", "required");
        $("#inputColoniaF").rules("remove", "required");
        $("#inputEstadoF").rules("remove", "required");
        $("#inputMunicipioF").rules("remove", "required");
        $("#inputCpF").rules("remove", "required");
    }else{
        $("#divDirDif").show('slow');
        $("#inputCalleF").rules("add",  {required:true});
        $("#inputNoextF").rules("add",  {required:true});
        $("#inputNointF").rules("add",  {required:true});
        $("#inputColoniaF").rules("add",  {required:true});
        $("#inputEstadoF").rules("add",  {required:true});
        $("#inputMunicipioF").rules("add",  {required:true});
        $("#inputCpF").rules("add",  {required:true});
        $("#FormData").validate();
    }
}

function searchCp(typeSearch){
  var cp = 0;

  if(typeSearch==0){
    cp = $("#inputCp").val();
  }else{
    cp = $("#inputCpF").val();
  }

    $("#inputNameEmpresa").rules("remove", "required");
    $("#inputTipoRazon").rules("remove", "required");
    $("#inputRazon").rules("remove", "required");
    $("#inputRFC").rules("remove", "required");
    $("#inputRep").rules("remove", "required");

    $("#inputCalle").rules("remove", "required");
    $("#inputNoext").rules("remove", "required");
    $("#inputNoint").rules("remove", "required");
    $("#inputEstado").rules("remove", "required");
    $("#inputMunicipio").rules("remove", "required");
    $("#inputColonia").rules("remove", "required");
    $("#inputCp").rules("remove", "required");
    $("#inputEstatus").rules("remove", "required");
    $("#inputDirDif").rules("remove", "required");

    $("#inputCalleF").rules("remove", "required");
    $("#inputNoextF").rules("remove", "required");
    $("#inputNointF").rules("remove", "required");
    $("#inputColoniaF").rules("remove", "required");
    $("#inputEstadoF").rules("remove", "required");
    $("#inputMunicipioF").rules("remove", "required");
    $("#inputCpF").rules("remove", "required");

    $("#optRegCompany").val("searchCP");
    $("#inputSearch").val(cp);
    $("#typeSearch").val(typeSearch);
    $("#FormData").submit();
}

function showChangePassword(){
    $("#bntChangePass").hide('slow');
    $(".inputPass").show('slow');
    $("#inputPasswordNow").rules("add",  {required:true});
    $("#inputPassword").rules("add",  {required:true});
    $("#inputCpassword").rules("add",  {required:true});
}