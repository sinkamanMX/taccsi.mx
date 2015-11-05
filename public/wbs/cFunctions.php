
<?php 
	//$dataIn  = (isset($_GET)) ? $_GET : Array();
	if(@$_GET['optReg']=='search'){
		$conexion   = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
		$idTaccista = $_GET['inputTaccsi']; 
		$latOrigen	= $_GET['iOrigenLat']; 
		$lonOrigen	= $_GET['iOrigenLon'];

		$latDestino	= $_GET['iDestLat'];
		$lonDestino = $_GET['idestlon'];

		$dataTaxi    = getData($idTaccista);
		$aZonaOrigen = getZona($dataTaxi['ID_EMP'],$latOrigen,$lonOrigen,$dataTaxi['ID_CLASE']);
		$bOnZona	 = onZona($aZonaOrigen['ID_ZONA'],$latDestino,$lonDestino);

		$aCosto 	 = $aZonaOrigen['N_COSTO'];
		$aCostoFz    = $aZonaOrigen['COSTO_FUERA_ZONA'];
		$banderazo 	 = $aZonaOrigen['BANDERAZO'];
		$cobrarCada  = $aZonaOrigen['TAXIMETRO_KMS'];
		$cobrarCadaMins  = $aZonaOrigen['TAXIMETRO_MINS'];

		$aDestino	 = "";

		if($bOnZona){
			$distancia = distancia_puntos($latOrigen,$lonOrigen,$latDestino,$lonDestino);
			$costoDistancia = ($distancia/$cobrarCada)*$aCosto;
			//var_dump($costoDistancia);
			//echo "<br/>";
			$costoRecorrido = $costoDistancia+$banderazo;
			/*
			var_dump($distancia);
			echo "<br/>";
			var_dump($cobrarCada);
			echo "<br/>";
			echo "$ ".round($costoRecorrido,2)."<br/>";
			var_dump($costoRecorrido);*/
			//var_dump("Dentro de la zona");
			$aDestino	 = "Dentro de la zona";
		}else{
			$aDestino	 = "Fuera de la zona";
			$route = "https://maps.googleapis.com/maps/api/directions/json?origin=".$latOrigen.",".$lonOrigen."&destination=".$latDestino.",".$lonDestino."&avoid=highways&mode=driving&key=AIzaSyBTBZIN5X9lsstyry5yGDwC4s_Z3IXkIC0";
			$json  = file_get_contents($route); // this WILL do an http request for you
			$data  = json_decode($json);	
			$routes= $data->routes; 

			$aPocisiones 	= Array();
			$aControl    	= 0;
			$costoRecorrido = 0;
			$cotrolDistancia= 0;

			foreach($routes as $items){
				$positions  = $items->legs;
				foreach($positions as $p){
					$steps = $p->steps;
					foreach($steps as $s){
						$intLat= $s->start_location->lat;
						$inLon = $s->start_location->lng;

						if($aControl==0){
							$aPocisiones['lat'] = $intLat;
							$aPocisiones['lon'] = $inLon;
						}else{
							$inZone 	= onZona($aZonaOrigen['ID_ZONA'],$intLat,$inLon);
							$sDistancia	= distancia_puntos($aPocisiones['lat'],$aPocisiones['lon'],$intLat,$inLon);

							if(!$inZone){
								$aCosto      = $aZonaOrigen['COSTO_FUERA_ZONA'];
								//echo "es fuera de la zona <br>";
							}else{
								$aCosto 	 = $aZonaOrigen['COSTO_RECORRIDO'];
							}

							$cotrolDistancia += $sDistancia;
							$costoDistancia   = ($sDistancia/$cobrarCada)*$aCosto;
							$costoRecorrido  += $costoDistancia;

							$aPocisiones['lat'] = $intLat;
							$aPocisiones['lon'] = $inLon;
						}
						$aControl++;
					}
				}				
			}

			//var_dump("COSTO CON FUERA DE ZONA ES ".$costoRecorrido);
			//var_dump("distancia ".$cotrolDistancia);
		}
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

	function getZona($idEmpresa,$iLatitud,$iLongitud,$tipoTaxi){
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT Z.ID_ZONA, Z.TIPO_ZONA, Z.DESCRIPCION AS N_ZONA, Z.COSTO, Z.COSTO_ACUMULABLE,Z.LATITUD, Z.LONGITUD,Z.RADIO, 
	    		astext(CENTROID(MAP_OBJECT)) AS N_POINT, DISTANCIA($iLatitud,$iLongitud,X(CENTROID(MAP_OBJECT)),Y(CENTROID(MAP_OBJECT))) AS DISTANCIA,
				T.*,IF(CURRENT_TIME BETWEEN T.HORARIO_INICIO  AND T.HORARIO_FIN, T.COSTO_RECORRIDO, T.COSTO_FUERA_HORARIO ) AS N_COSTO
			FROM ADMIN_ZONAS Z 
			INNER JOIN ADMIN_TARIFAS T ON Z.ID_TARIFA = T.ID_TARIFA
			WHERE Z.ID_TARIFA IN ( 
				SELECT ID_TARIFA 
				FROM ADMIN_TARIFAS 
				WHERE ID_CLASE  = $tipoTaxi 
				AND (ID_EMPRESA = $idEmpresa OR TIPO_TARIFA = 0))
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
		//var_dump($sql);
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    	$aResultado = $result['ON_ZONE'];
	    }
	    return $aResultado;   				
	}

  	function getLastPosition($idOject){
	    global $conexion;
	    $result = Array();
	    $sql ="SELECT *
				FROM ADMIN_TAXIS T
				LEFT JOIN ADMIN_TARIFAS R ON T.ID_TARIFA = R.ID_TARIFA
				WHERE ID_TAXI = $idOject            		
	            LIMIT 1";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	    	$result = mysqli_fetch_array($query);
	    }
	    return $result;   
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

	
?>