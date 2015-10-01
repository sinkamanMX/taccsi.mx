<?php
  set_time_limit(60000);
  require_once('./lib/nusoap.php'); 
  $miURL = 'http://201.131.96.45/wbs_publicidad';
  $server = new soap_server(); 
  $server->configureWSDL('wbs_publicidad', $miURL); 
  $server->wsdl->schemaTargetNamespace=$miURL;

  $server->register('addComercio', // Nombre de la funcion 
                    array('nombre'  => 'xsd:string',//parametros de entrada
                          'horario' => 'xsd:string',
                          'ubicacion' => 'xsd:string',
                          'foto' => 'xsd:string',
                          'descripcion' => 'xsd:string',
                          'latitud' => 'xsd:string',
                          'longitud' => 'xsd:string'), 
                    array('return'   => 'xsd:string'), // Parametros de salida 
                    $miURL);

 $server->register('dame_comercios', // Nombre de la funcion 
                    array('data1'  => 'xsd:string'), 
                    array('return'   => 'xsd:string'), // Parametros de salida 
                    $miURL);  

function addComercio($nombre,$horario,$ubicacion,$foto,$descripcion,$latitud,$longitud){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
    	$base = mysql_select_db("Publicidad",$con);

        $sql = "INSERT INTO COMERCIO(
              NOMBRE,
              HORARIO,
              UBICACION,
              FOTO,
              DESCRIPCION,
              LATITUD,
              LONGITUD) VALUES (
              '".$nombre."',
              '".$horario."',
              '".$ubicacion."',
              '".$foto."',
              '".$descripcion."',
              ".$latitud.",
              ".$longitud.")";
        if ($qry = mysql_query($sql)){
            $res = true;
        }

        if($res==true){
            $idx = 1;
            $msg = 'OK';
        }else{
            $idx = -2;
            $msg = 'Eror al guardar el nuevo Comercio'.$sql;
        }


    }



    $res =  '<?xml version="1.0" encoding="UTF-8"?>
                 <space>
                   <Response> 
                     <Status>
                       <code>'.$idx.'</code>
                       <msg>'.$msg.'</msg>
                     </Status>
                   </Response>
                 </space>';

    return new soapval('return', 'xsd:string', $res);
}

Function dame_comercios($data1){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){

      $base = mysql_select_db("Publicidad",$con);

      $sql = "SELECT NOMBRE,
                     HORARIO,
                     UBICACION,
                     FOTO,
                     DESCRIPCION,
                     LATITUD,
                     LONGITUD
              FROM COMERCIO";
    
     

      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat.='<comercios>';
          while ($row = mysql_fetch_object($qry)){
              $dat.= '<comercio>
                        <nombre>'.$row->NOMBRE.'</nombre>
                        <horario>'.$row->HORARIO.'</horario>
                        <ubicacion>'.$row->UBICACION.'</ubicacion>
                        <foto>'.$row->FOTO.'</foto>
                        <descripcion>'.$row->DESCRIPCION.'</descripcion>
                        <latitud>'.$row->LATITUD.'</latitud>
                        <longitud>'.$row->LONGITUD.'</longitud>
                      </comercio>';
          }
          $dat.='</comercios>';
        }else{
          $dat="";
        }
        mysql_free_result($qry);
      }

      if (strlen($dat) > 0){
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'No se pudo obtener la información';  
      }


    }

    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);

  }

$server->service($HTTP_RAW_POST_DATA);
?>