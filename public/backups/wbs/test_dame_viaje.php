<?php   
   //$soap_client = new SoapClient("http://www.grupouda.com.mx/wbs/wbsAppTerceros.php?wsdl");
   
   $soap_client = new SoapClient("http://192.168.6.106/wbs/wbs_taccsi.php?wsdl");

   $param = array('id_viaje' => '14');
//    print_r($param);
    $result=$soap_client->__call('oprDameViaje',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>

