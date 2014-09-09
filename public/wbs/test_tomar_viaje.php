<?php   
   //$soap_client = new SoapClient("http://www.grupouda.com.mx/wbs/wbsAppTerceros.php?wsdl");
   
   $soap_client = new SoapClient("http://192.168.6.106/wbs/wbs_taccsi.php?wsdl");

   $param = array('id_usuario' => '19',
                  'id_viaje' => '49',
                  'dispositivo' => 'APA91bHgb4O4dBFRz1nqVGTaMnhxsN2GtwvjhdN0K7hTrEImpA_CQHcGNZqYiU97hwQj5zziV7kJU_yox-HQWBKnLKXFr5zaAY_coPIl_tjN8itKqVpNAds6Ap7Y8SfpaX6iC7wzZcCkcL28YsyvkDygWKhnd3zFFqljUwKeah81XAjh00AcD3c');
//    print_r($param);
    $result=$soap_client->__call('oprTomarViaje',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>

