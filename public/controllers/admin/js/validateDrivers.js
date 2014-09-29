$().ready(function() {
	$("#FormData").validate({
        rules: {
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
            }    
        },
        messages: {
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
            }                         
        },
        submitHandler: function(form) {
            form.submit();
        }
    });	
    
    if($("#catId").val()>-1){
        $("#inputPassword").rules("remove", "required");
        $("#inputCpassword").rules("remove", "required");          
    }

    /*$(".chosen-select").chosen();*/
    $("#btnSearch").click(function() { openSearch(); return false; });
    $("#btnDelRel").click(function() { deleteRowRel(); return false; });  

    $('#iFrameSearch').on('load', function () {        
        $('#loader1').hide();
        $('#iFrameSearch').show();
    }); 
});

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

function deleteRowRel(){   
    var idItem = $("#catId").val();
    var idRel  = $("#inputIdAssign").val();
    $.ajax({
        url: "/admin/drivers/getinfo",
        type: "GET",
        dataType : 'json',
        data: { catId : idItem, 
                inputIdAssign :idRel,
                optReg: 'deleteRel'},
        success: function(data) {
            var result = data.answer; 

            if(result == 'deleted'){
                location.href = '/admin/drivers/getinfo?catId='+idItem;
            }else if(result == 'problem'){
                alert("hubo problema");          
            }else{
                alert("no hay data");          
            }
        }
    });    
}

function openSearch(){
    $('#loader1').show();
    $('#iFrameSearch').hide();    
    $('#iFrameSearch').attr('src','/admin/drivers/searchtaxis');
    $("#MyModalSearch").modal("show");
}

function assignValue(nameValue,IdValue){
    $("#inputIdAssign").val(IdValue);
    $("#inputSearch").val(nameValue);
    $("#MyModalSearch").modal("hide");
}