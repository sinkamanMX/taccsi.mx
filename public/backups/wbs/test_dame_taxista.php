<?php   
   //$soap_client = new SoapClient("http://www.grupouda.com.mx/wbs/wbsAppTerceros.php?wsdl");
   
   $soap_client = new SoapClient("http://192.168.6.106/wbs/wbs_taccsi.php?wsdl");

   $param = array('id_usuario' => '1',
                  'id_taxista' => '19',
                  'id_viaje' => '49');
//    print_r($param);
    $result=$soap_client->__call('usrDameInfoTaxista',$param);

    //echo '<br>:'.$result.'<br>';
//   echo strlen($result);
   // print_r($result);  
    echo($result);
?>
