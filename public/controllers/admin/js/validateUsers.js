$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputTipo       : "required",
            inputNombre     : "required",
            inputApaterno   : "required",
            inputAmaterno   : "required",
            inputUsuario    : "required",
            inputEstatus    : "required",
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
            } ,
            inputExtPhone   : "required"
        },
        messages: {
            inputTipo       : "Campo Requerido",
            inputNombre     : "Campo Requerido",
            inputApaterno   : "Campo Requerido",
            inputAmaterno   : "Campo Requerido",
            inputUsuario    : "Campo Requerido",
            inputTipo       : "Campo Requerido",
            inputPassword   : "Campo Requerido",
            inputEstatus    : "Campo Requerido",
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
            inputExtPhone : "Campo Requerido"             
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
    
    if($("#catId").val()>-1){
        $("#inputPassword").rules("remove", "required");
        $("#inputCpassword").rules("remove", "required");          
    }

    if($("#inputTipo").val()==""){
        $("#inputExtPhone").rules("remove", "required");
    }
});

function changeProfile(idValue){
    if(idValue==2){
        $("#inputExtPhone").rules("add",  {required:true});
        $("#divExtension").show('slow');
    }else{
        $("#inputExtPhone").rules("remove", "required");
        $("#divExtension").hide('slow');
    }    
}

function addValidatePass(valueInput){
    if(valueInput!=""){
        $("#inputPassword").rules("add",  {required:true});
        $("#inputCpassword").rules("add", {required: true,equalTo: "#inputPassword"});   
    }else{
        $("#inputPassword").rules("remove", "required");
        $("#inputCpassword").rules("remove", "required");
    }
}

function backToMain(){
  var mainPage = $("#hRefLinkMain").val();
  location.href= mainPage;
}