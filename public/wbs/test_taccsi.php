<?php   
   //$soap_client = new SoapClient("http://www.grupouda.com.mx/wbs/wbsAppTerceros.php?wsdl");
   
   $soap_client = new SoapClient("http://192.168.6.106/wbs/wbs_taccsi.php?wsdl");

//$id_usuario,$equipo, $push_token,$latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error
   $param = array('id_usuario' => '1');
//    print_r($param);
    $result=$soap_client->__call('DameTipoTarjeta',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>