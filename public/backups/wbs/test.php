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

   $param = array('nombre' => 'CESAR',
                 'apaterno' => 'SANCHEZ',
                 'amaterno' => 'HUERTA',
                 'movil' => '123',
                 'email' => '123',
                 'email2' => '123',
                 'taxi_propio' => '123',
                 'asociacion' => '123',
                 'identificador' => '123',
                 'clave_empresa' => '123');
//    print_r($param);
    $result=$soap_client->__call('RegistraTaccsista',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>
