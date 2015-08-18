
function checkDefault(inputValue){
    $.ajax({
        url: "/external/payments/setdefault",
        type: "GET",
        dataType : 'json',
        data: { strInput: inputValue},
        success: function(data){
            var result = data.answer; 
            if(result == 'updated'){
                location.reload();
            }else{
                alert("ocurrio un problema, favor de intentar mas tarde.");
            }
        }
    });           
}