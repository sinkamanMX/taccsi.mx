<?php
    $con = mysql_connect("201.131.96.45","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      processPositions(0);
      processPositions(1);
      mysql_close($con);
    }
    
  function processPositions($tabla=0){
    $res = '';
    $table = ($tabla==0) ? 'DISP_ULTIMA_POSICION': 'DISP_HISTORICO_POSICION'; 
    $sql = "SELECT ID_POSICION, LATITUD,LONGITUD 
			FROM $table
			WHERE UBICACION IS NULL 
			 AND  LATITUD IS NOT NULL
			 AND  LONGITUD IS NOT NULL 
			 LIMIT 10";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
      	
      	$sLatitud   = $row->LATITUD;
      	$sLongitud  = $row->LONGITUD;
      	$sDireccion = dame_google($sLatitud, $sLongitud);      	
      	updatePosition($row->ID_POSICION,$sDireccion,$tabla);
      }
      mysql_free_result($qry);
    } 

    return $res;
  }
  
  function updatePosition($idRow,$sDireccion,$tabla=0){
    $res = false;
    $table = ($tabla==0) ? 'DISP_ULTIMA_POSICION': 'DISP_HISTORICO_POSICION'; 
    $sql = "UPDATE $table
            SET UBICACION     = '".$sDireccion."'
            WHERE ID_POSICION = ".$idRow."
            LIMIT 1";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;  	
  }
    

function dame_google ($lat,$lng){
  $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false'; 
    $json = @file_get_contents($url); 
    $data=json_decode($json); 
    $status = $data->status; 
    if($status=="OK"){ 
        $address = $data->results[0]->formatted_address; 
     }else{ 
          $address=''; 
      }
     $address = str_replace(' State of',' ',$address);
     $address = str_replace(' Mexico City', 'Ciudad de Mxico',$address);
     $address = str_replace('Federal District', 'Distrito Federal',$address);
     $address = utf8_decode($address);
	 return  $address;
  }