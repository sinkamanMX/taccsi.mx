<?php   
   //$soap_client = new SoapClient("http://www.grupouda.com.mx/wbs/wbsAppTerceros.php?wsdl");
   
   $soap_client = new SoapClient("http://192.168.6.106/wbs/wbs_taccsi.php?wsdl");

/*

$nombre,
                              $apaterno,
                              $amaterno,
                              $movil,
                              $email,
                              $email2,
                              $taxi_propio,
                              $asociacion,
                              $identificador,
                              $clave_empresa
*/

   $param = array('usuario' => 'DEMO',
                  'dispositivo' => 'BC:72:B1:F8:5C:8D',
                  'push_token' => 'APA91bH7pr2nY-vRK1aH5ySz77ZPEWsCCaMTZNQzNl8p3KQWRVAfnQrdIuE_YT8Kep5pNwZ9pcTBpvr3qMr7YUYmuouR9xXHbLdaZuxE2E_vitDwPI4Bk9qzmKXDR_8-ThsYe7SXM-pp9-5lOcTC43xW6KB70mYAEddJYLj29iE2SZvoUOQJBmI',
                  'origen' => 'DIRECCION ORIGEN',
                  'destino' => '123',
                  'lat_origen' => '123',
                  'lon_origen' => '123',
                  'lat_destino' => '123',
                  'lon_destino' => '123',
                  'personas' => '123',
                  'pago' => '1',
                  'descuento'=>'',
                  'id_conductor'=>'0');
//    print_r($param);
    $result=$soap_client->__call('usrNuevoViaje',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>
