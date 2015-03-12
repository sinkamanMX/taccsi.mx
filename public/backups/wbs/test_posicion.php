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
//$id_usuario,$equipo, $push_token,$latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error
   $param = array('id_usuario' => '1',
                  'equipo' => '123',
                  'push_token' => 'DIRECCION ORIGEN',
                  'latitud' => '123',
                  'longitud' => '123',
                  'velocidad' => '123',
                  'altitud' => '123',
                  'angulo' => '123',
                  'fecha' => '123',
                  'proveedor' => '1',
                  'error'=>'0');
//    print_r($param);
    $result=$soap_client->__call('RecibePosicion',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>
