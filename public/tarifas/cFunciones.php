<?php
	$conexion =null;
	
	function conectar(){
		global $conexion;
		//$conexion   = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));		
		//$conexion   = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));		
		$conexion   = new mysqli('201.131.96.45','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
		return $conexion;
	}

	function getZona($iLatitud,$iLongitud,$tipoTaxi){
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT Z.ID_ZONA, Z.TIPO_ZONA, Z.DESCRIPCION AS N_ZONA, Z.COSTO, Z.COSTO_ACUMULABLE,Z.LATITUD, Z.LONGITUD,Z.RADIO, 
	    		astext(CENTROID(MAP_OBJECT)) AS N_POINT, DISTANCIA($iLatitud,$iLongitud,X(CENTROID(MAP_OBJECT)),Y(CENTROID(MAP_OBJECT))) AS DISTANCIA,
				T.* , IF(CURRENT_TIME BETWEEN T.HORARIO_INICIO  AND T.HORARIO_FIN, T.COSTO_RECORRIDO, T.COSTO_FUERA_HORARIO ) AS N_COSTO				
			FROM ADMIN_ZONAS Z 
			INNER JOIN ADMIN_TARIFAS T ON Z.ID_TARIFA = T.ID_TARIFA
			WHERE Z.ID_TARIFA IN ( 
				SELECT ID_TARIFA 
				FROM ADMIN_TARIFAS 
				WHERE /*ID_CLASE  = $tipoTaxi 
				AND */ TIPO_TARIFA = 0)
				AND CONTAINS(MAP_OBJECT, geomfromtext('Point($iLatitud $iLongitud)')) ORDER BY DISTANCIA ASC LIMIT 1";
		//var_dump($sql);
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    }
	    return $result;   		
	}

	function onZona($idZona,$iLatitud,$iLongitud){
		$aResultado = false;
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT CONTAINS(MAP_OBJECT, geomfromtext('Point($iLatitud $iLongitud)')) AS ON_ZONE
				FROM ADMIN_ZONAS
				WHERE ID_ZONA = $idZona";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    	$aResultado = $result['ON_ZONE'];
	    }
	    return $aResultado;   				
	}	

  	function distancia_puntos($iLat,$iLon,$iDlat,$iDlon){
	    global $conexion;
	    $result = 0;
	    $sql ="SELECT DISTANCIA($iLat,$iLon,$iDlat,$iDlon) AS DIST";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$resultQ = mysqli_fetch_array($query);
	    	$result  = $resultQ['DIST'];
	    }
	    return $result;   
  	}

	function curl($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}	

	function getZonaInit($iLatitud,$iLongitud,$tipoTaxi){
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT Z.* ,T.* ,
				astext(CENTROID(MAP_OBJECT)) AS N_POINT, DISTANCIA($iLatitud,$iLongitud,X(CENTROID(MAP_OBJECT)),Y(CENTROID(MAP_OBJECT))) AS DISTANCIA,
				Z.DESCRIPCION AS N_ZONA, Z.COSTO AS COSTO_ZONA, Z.COSTO_ACUMULABLE,
				IF(CURRENT_TIME BETWEEN T.HORARIO_INICIO  AND T.HORARIO_FIN, Z.COSTO, T.COSTO_FUERA_HORARIO ) AS COSTO_NT
				FROM ADMIN_ZONAS Z
				INNER JOIN ADMIN_TARIFAS T ON Z.ID_TARIFA = T.ID_TARIFA
				WHERE Z.ID_TARIFA 
				IN (
					SELECT ID_TARIFA
					FROM ADMIN_TARIFAS 
					WHERE /*ID_CLASE    = $tipoTaxi
					  AND */(TIPO_TARIFA = 0)
					)
				AND CONTAINS(MAP_OBJECT, geomfromtext('Point($iLatitud $iLongitud)'))
				ORDER BY DISTANCIA ASC LIMIT 1";
		//var_dump($sql);
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    }
	    return $result; 
	}	

	function getData($idTacssi){
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT T.*,R.*, T.ID_EMPRESA AS ID_EMP
				FROM ADMIN_TAXIS T
				LEFT JOIN ADMIN_TARIFAS R ON T.ID_TARIFA = R.ID_TARIFA
				WHERE ID_TAXI  = $idTacssi            		
	            LIMIT 1";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    }
	    return $result;   		
	}

	function getZonasByTarifa($idTarifa){
	    global $conexion;
	    $sResult = '';
	    $sql ="SELECT ASTEXT(Z.MAP_OBJECT) AS GEO, X(CENTROID(MAP_OBJECT))  AS C_X ,Y(CENTROID(MAP_OBJECT)) AS C_Y,  Z.*
				FROM ADMIN_ZONAS Z
				WHERE Z.ID_TARIFA = 7";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	while ($result = mysqli_fetch_array($query)) {
	    		$aPosiciones = processCoordenadas($result['GEO'],$result['C_X'],$result['C_Y']);
	    		$sResult .= '<zona>
	    						<descripcion>'.$result['DESCRIPCION'].'</descripcion>
	    						<costo>'.$result['COSTO'].'</costo>
	    						<costo_acumulable>'.$result['COSTO_ACUMULABLE'].'</costo_acumulable>
	    						<centroide>'.$result['C_X'].','.$result['C_Y'].'</centroide>
	    						<polygono>'.$aPosiciones.'</polygono>
	    					</zona>';
	    	}	    
	    }
	    return $sResult;  		
	}

	function processCoordenadas($sPosition,$latCentroide,$lonCentroide){
		$a_position= '';
		
		$sClean = substr($sPosition, 0, -3);
		$mult 	= substr($sClean ,9);			
		$pre_positions=explode(",",$mult);		
		
		for($p=0;$p<count($pre_positions);$p++){	
			$fixed   = str_replace(' ',',',$pre_positions[$p]);	
			$aLs     = explode(',', $fixed);
			$iLatitud  = $aLs[1];
			$iLongitud = $aLs[0];

			/*'.$fixed.','.$distacia.'*/
			$distacia = distancia_puntos($latCentroide,$lonCentroide,$iLatitud,$iLongitud);
			$a_position  .= '<posicion>
								<latitud>'.$iLatitud.'</latitud>
								<longitud>'.$iLongitud.'</longitud>
								<distancia>'.$distacia.'</distancia>
							</posicion>';
		}
					
		return $a_position;
	}

	//**Calcula la distancia entre dos puntos, el valor lo regresa en kms**//
	function distancia_entre_puntos($lat_a,$lon_a,$lat_b,$lon_b){
    	$lat_a = $lat_a * pi() / 180;
    	$lat_b = $lat_b * pi() / 180;
    	$lon_a = $lon_a * pi() / 180;
    	$lon_b = $lon_b * pi() / 180;
    	/**/
    	$angle = cos($lat_a) * cos($lat_b);
    	$xx = sin(($lon_a - $lon_b)/2);
    	$xx = $xx*$xx;
    	/**/
    	$angle = $angle * $xx;
    	$aa = sin(($lat_a - $lat_b)/2);
    	$aa = $aa*$aa;
    	$angle = sqrt($angle + $aa);
        //$salida = arco_seno($angle);
        $salida = asin($angle);
        /*gps_earth_radius = 6371*/
    	$salida = $salida * 2;
    	$salida = $salida * 6371;
		
		$salida = round($salida*100)/100;
    	return $salida;
  	}	