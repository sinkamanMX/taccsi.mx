<?php
  include('funciones_push.php');
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	//$conexion = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));

	/**
	* Se obtienen todas las reservaciones que esten pendientes
	*/
  	$sql = "SELECT (TIMESTAMPDIFF(MINUTE , R.TIEMPO_ENVIADO,CURRENT_TIMESTAMP )) AS TIEMPO, R.*
            FROM ADMIN_RESERVACIONES R
            WHERE R.ID_ESTATUS_RESERVACION = 4         
            ORDER BY TIEMPO ASC";
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;
	while($result = mysqli_fetch_array($query)){
		//Si ya paso mas de 10 mins sin respuesta, se asigna al taccista mas cercano.
    if($result['TIEMPO']>10){
      asigna_taxistas($result['ID'],
             $result['ORIGEN_LATITUD'],
             $result['ORIGEN_LONGITUD'],
             $result['ID_TIPO_TAXI'],
             $result['IAVE'],
             $result['AC'],
             $result['CARGADOR'],
             $result['WIFI']);
    }
	}

  function asigna_taxistas($idReservacion,$latitud,$longitud,$tipo,$iave,$ac,$conector,$wifi){
  	global $conexion;
    $where = '';
    if ($iave == 1){
      $where = $where."T.IAVE = 1 AND ";
    }
    if ($ac == 1){
      $where = $where."T.AC = 1 AND ";
    }
    if ($conector == 1){
      $where = $where."T.CONECTOR_CELULAR = 1 AND ";
    }
    if ($wifi == 1){
      $where = $where."T.WIFI = 1 AND ";
    }

    $sql = "SELECT A.ID_USUARIO, 
                   C.PUSH_TOKEN, 
                   C.SO,
                   ROUND(DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") ,2) AS DIST
            FROM ADMIN_USUARIOS A
              INNER JOIN DISP_ULTIMA_POSICION B ON B.IDENTIFICADOR = A.DISPOSITIVO
              INNER JOIN DISP_REGISTRO_TOKENS C ON DEVICE_ID = B.IDENTIFICADOR
              INNER JOIN ADMIN_TAXIS T ON T.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO
            WHERE A.DISPONIBLE = 0 AND
                  A.DEMOS = 1 AND
                  DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") < 400 AND
                  CAST(B.FECHA_GPS AS DATE)=CURRENT_DATE
            ORDER BY DIST
            LIMIT 1";
            var_dump($sql);
  	$query = mysqli_query($conexion, $sql);
	  while($result = mysqli_fetch_array($query)){
      //registra_asignacion($idReservacion,$result['ID_USUARIO']);
      $aInfo    = getinfoReservacion($id_reservacion);
      $id_viaje = dame_id_viaje();

      if(registra_viaje($id_viaje,
                         $aInfo['ID_CLIENTE'],
                         $aInfo['DISPOSITIVO_ORIGEN'], 
                         $aInfo['ORIGEN'], 
                         $aInfo['DESTINO'], 
                         $aInfo['ORIGEN_LATITUD'], 
                         $aInfo['ORIGEN_LONGITUD'], 
                         $aInfo['DESTINO_LATITUD'],
                         $aInfo['DESTINO_LONGITUD'], 
                         0,
                         $aInfo['ID_FORMA_PAGO'],
                         0,
                         $aInfo['ORIGEN_REFERENCIAS'],
                         $aInfo['ID_TARJETA'],
                         $aInfo['ID_TIPO_TAXI'],
                         $aInfo['IAVE'],
                         $aInfo['AC'],
                         $aInfo['CARGADOR'],
                         $aInfo['WIFI'])){
         if (marca_reservacion($result['ID_USUARIO'],$id_reservacion,$id_viaje)){
            $codigo = dame_codigo_viaje($id_viaje);
            $token  = dame_pushtoken_cliente($id_viaje);

            marca_tomar_reservacion($id_reservacion,$result['ID_USUARIO'],$id_viaje,$codigo);

            if (strlen($token) > 0){
              $so = dame_so_pushtoken($token);            

              envia_push('dev','usuario','Su TACCSI va en camino,'.$id_viaje.'@codigo_cofirmacion:'.$codigo.' @'.$id_usuario);

              

            }
         }
      }

		  /*        
		    envia_push('dev','taxi',"Hay una reservacion disponible.@".$idReservacion,$result['PUSH_TOKEN'],$result['SO'],'');		
        sleep(2);*/
	   }
  }

  function registra_asignacion($id_reservacion,$id_taxista){
  	global $conexion;
    $sql = "INSERT INTO RESERV_ASIGNACIONES (
              ID_RESERVACION,
              ID_TACCSISTA,
              FECHA,
              HORA,
              ACEPTO
            ) VALUES (
              ".$id_reservacion.",
              ".$id_taxista.",
              CURRENT_DATE,
              CURRENT_TIME,
              1)";
	  $query = mysqli_query($conexion, $sql);  	
  }

  function dame_id_viaje(){
    global $conexion;
    $res = false;
    while ($res <> true){
      $id  = gen_id_viaje();
      $sql = "SELECT COUNT(*) AS ID 
              FROM ADMIN_VIAJES
              WHERE ID_VIAJES = ".$id;
      if($qry = mysqli_query($conexion, $sql)){
        $row = mysqli_fetch_array($qry); 
        if ($row['ID'] == 0){
          $res = true;
        }
      }      
    }
    return $id; 
  }

  function gen_id_viaje(){
    global $conexion;
    $res = -1;
    $sql = "LOCK TABLE ADMIN_VIAJES_GEN WRITE";
    if($query = mysqli_query($conexion, $sql)){
      $sql2  = "INSERT INTO ADMIN_VIAJES_GEN (ID_VIAJE) VALUES (0)";
      mysqli_query($conexion, $sql2);

      $sql3 = "SELECT MAX(ID_VIAJE) AS ID FROM ADMIN_VIAJES_GEN";
      if(mysqli_query($conexion, $sql3)){
        $row = mysqli_fetch_array($qry3);
        $res = $row['ID'];
        mysql_free_result($qry3);
      }

      $sql4 = "UNLOCK TABLES";
      mysqli_query($conexion, $sql4);
    }  
    return $res; 
  }  

  function registra_viaje($id_viaje,
                         $id_usuario,
                         $dispositivo, 
                         $origen, 
                         $destino, 
                         $lat_origen, 
                         $lon_origen, 
                         $lat_destino,
                         $lon_destino, 
                         $personas,
                         $pago,
                         $descuento,
                         $referencias,
                         $id_tarjeta,
                         $tipo,
                         $iave,
                         $ac,
                         $conectores,
                         $wifi){
    global $conexion;
    $res = false;
    global $base;

    $codigo = rand(1,9999);

    $sql = "INSERT ADMIN_VIAJES(
              ID_VIAJES,
              ID_FORMA_PAGO,
              FECHA_VIAJE,
              NO_PASAJEROS,
              ORIGEN,
              DESTINO,
              ORIGEN_LATITUD,
              ORIGEN_LONGITUD,
              DESTINO_LATITUD,
              DESTINO_LONGITUD,
              ID_CLIENTE,
              DISPOSITIVO_ORIGEN,
              ID_SRV_ESTATUS,
              ORIGEN_REFERENCIAS,
              CLAVE_VIAJE,
              ID_TARJETA,
              ID_TIPO_TAXI,
              CARGADOR,
              AC,
              WIFI,
              IAVE
            ) VALUES (
              ".$id_viaje.",
              ".$pago.",
              CURRENT_TIMESTAMP,
              ".$personas.",
              '".$origen."',
              '".$destino."',
              '".$lat_origen."',
              '".$lon_origen."',
              '".$lat_destino."',
              '".$lon_destino."',
              '".$id_usuario."',
              '".$dispositivo."',
              1,
              '".$referencias."',
              ".$codigo.",
              ".$id_tarjeta.",
              ".$tipo.",
              ".$conectores.",
              ".$ac.",
              ".$wifi.",
              ".$iave.")";    
    if ($qry = mysqli_query($conexion, $sql)){
      $res = true;
    } 
    return $res;
  }  

  function marca_reservacion($id_usuario,$id_reservacion,$id_viaje){
    global $conexion;
    $res = false;
    //revisar
    $sql = 'UPDATE ADMIN_RESERVACIONES 
            SET ID_VIAJE               = '.$id_viaje.',
                ID_ESTATUS_RESERVACION = 1,
                ATENDIDO = CURRENT_TIMESTAMP
            WHERE ID_RESERVACION = '.$id_reservacion;
    if ($qry = mysqli_query($conexion, $sql)){
      $res = true;
    }  
    return $res;
  }   

  function dame_codigo_viaje($id_viaje){
    global $conexion;
    $res = '';
    $sql = "SELECT CLAVE_VIAJE
              FROM ADMIN_VIAJES
              WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysqli_query($conexion, $sql)){
      $row = mysqli_fetch_array($qry);
      $res = $row['CLAVE_VIAJE'];
    }
    return $res;
  }  

  function dame_pushtoken_cliente($id_viaje){
    global $conexion;
    $res = '';
    $sql = "SELECT B.PUSH_TOKEN
              FROM ADMIN_VIAJES A
                   INNER JOIN DISP_REGISTRO_TOKENS B ON B.DEVICE_ID = A.DISPOSITIVO_ORIGEN
              WHERE A.ID_VIAJES = ".$id_viaje;
    if ($qry = mysqli_query($conexion, $sql)){
      $row = mysqli_fetch_array($qry);
      if (strlen($row['PUSH_TOKEN']) > 0){
        $res = $row['PUSH_TOKEN'];
      }
    }
    return $res;
  }  

  function marca_tomar_reservacion($id_reservacion,$id_usuario,$id_viaje,$codigo_viaje){
    global $conexion;
    $sql = "UPDATE RESERV_ASIGNACIONES SET ACEPTO = 1 WHERE ID_RESERVACION = ".$id_reservacion." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysqli_query($conexion, $sql)){
      registra_asignacion($id_viaje,$id_usuario);
      marca_tomar_viaje($id_viaje,$id_usuario);
      tomar_viaje($id_usuario,$id_viaje,$codigo_viaje);
      $res = true;
    }
    return $res;
  }  

  
  function registra_asignacion($id_viaje,$id_taxista){
    global $conexion;
    $sql = "INSERT INTO VIAJES_ASIGNACIONES (
              ID_VIAJE,
              ID_TACCSISTA,
              FECHA,
              HORA
            ) VALUES (
              ".$id_viaje.",
              ".$id_taxista.",
              CURRENT_DATE,
              CURRENT_TIME
            )";
    $qry = mysqli_query($conexion, $sql);
  }  

  function marca_tomar_viaje($id_viaje,$id_usuario){
    global $conexion;
    $sql = "UPDATE VIAJES_ASIGNACIONES SET ACEPTO = 1 WHERE ID_VIAJE = ".$id_viaje." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysqli_query($conexion, $sql)){
      $res = true;
    }
    return $res;
  }  

  function tomar_viaje($id_usuario,$id_viaje,$codigo){
    global $conexion;
    $res = false;
    /*marca el viaje al taxista*/
    //$x = marca_viaje($id_usuario,$id_viaje,$codigo);
    if (marca_viaje($id_usuario,$id_viaje,$codigo)){ 
      $res = true;
    } 
    return $res;
  }  

  function marca_viaje($id_usuario,$id_viaje,$codigo_viaje){
    global $conexion;
    $res = false;
    /*revisar*/
    $sql = 'UPDATE ADMIN_VIAJES 
            SET ID_TAXISTA = '.$id_usuario.',
                ID_SRV_ESTATUS = 2,
                ASIGNADO = CURRENT_TIMESTAMP
            WHERE ID_VIAJES = '.$id_viaje;
    if ($qry = mysqli_query($conexion, $sql)){
      $res = true;
    }  
    return $res;
  }  

  function dame_so_pushtoken($push){
    global $conexion;
    $res = '';
    //PUSH_TOKEN
      $sql = "SELECT SO
              FROM DISP_REGISTRO_TOKENS 
              WHERE PUSH_TOKEN = '".$push."'";
    if ($qry = mysqli_query($conexion, $sql)){
      $row = mysqli_fetch_array($qry);
      $res = $row['SO'];  
    }
    return $res;
  }  

?>
