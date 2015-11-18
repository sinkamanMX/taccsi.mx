<?php
  	include('funciones_push.php');
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	//$conexion = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));

	/**
	* Se obtienen todas las reservaciones que esten pendientes
	* 
	*/
  	$sql = "SELECT R.ID_RESERVACION AS ID, R.ID_ESTATUS_RESERVACION AS ESTATUS, 
            IF(CAST(R.FECHA_RESERVACION AS DATE) = CURRENT_DATE,'1','0') AS TODAY, 
            (TIMESTAMPDIFF(MINUTE , CURRENT_TIMESTAMP,R.FECHA_RESERVACION )) AS TIEMPO, 
            R.*              
            FROM ADMIN_RESERVACIONES R
            WHERE R.ID_ESTATUS_RESERVACION = 4          
            ORDER BY TIEMPO ASC";
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;
	while($result = mysqli_fetch_array($query)){
		//Si tiene asignaciones no se hace nada
		if(!validaAsignaciones($result['ID'])){
			manda_reserva_taxistas($result['ID'],
					   $result['ORIGEN_LATITUD'],
					   $result['ORIGEN_LONGITUD'],
					   $result['ID_TIPO_TAXI'],
					   $result['IAVE'],
					   $result['AC'],
					   $result['CARGADOR'],
					   $result['WIFI']);
		}
	}

  function manda_reserva_taxistas($idReservacion,$latitud,$longitud,$tipo,$iave,$ac,$conector,$wifi){
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
            LIMIT 3";
            var_dump($sql);
  	$query = mysqli_query($conexion, $sql);
	while($result = mysqli_fetch_array($query)){
		registra_asignacion($idReservacion,$result['ID_USUARIO']);
		envia_push('dev','taxi',"Hay una reservacion disponible.@".$idReservacion,$result['PUSH_TOKEN'],$result['SO'],'');		
        sleep(2);
	}
  }

  function registra_asignacion($id_reservacion,$id_taxista){
  	global $conexion;
    $sql = "INSERT INTO RESERV_ASIGNACIONES (
              ID_RESERVACION,
              ID_TACCSISTA,
              FECHA,
              HORA
            ) VALUES (
              ".$id_reservacion.",
              ".$id_taxista.",
              CURRENT_DATE,
              CURRENT_TIME)";
	var_dump($sql);
	$query = mysqli_query($conexion, $sql);  	
  }

  function validaAsignaciones($id_reservacion){
  	global $conexion;
  	$bResult = false;
    $sql = "SELECT COUNT(ID_RESERVACION) AS TOTAL
			FROM RESERV_ASIGNACIONES
			WHERE ID_RESERVACION = ".$id_reservacion;
	$query 	= mysqli_query($conexion, $sql);
	$result = mysqli_fetch_array($query);
	$bResult = ($result['TOTAL']>0) ? true : false;

	return $bResult;
  }  

?>