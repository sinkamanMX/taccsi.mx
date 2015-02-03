$().ready(function() {
    $("#FormData").validate({
        rules: {
            inputEmpresa     : "required",            
        },
        messages: {
            inputEmpresa     : "Campo Requerido",                        
        },
        submitHandler: function(form) {
            form.submit();
        }
    }); 
});

function declineData(){
    $("#inputEmpresa").rules("remove", "required");
    $("#iRespuesta").val(0);
    $("#FormData").submit();
}