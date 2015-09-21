<?php
	error_reporting(E_ALL);
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	//$conexion = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));
  	$sql = "SELECT *
			FROM DISP_HISTORICO_POSICION
			WHERE UBICACION IS NULL
			ORDER BY FECHA_GPS DESC
			LIMIT 15"; 
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;
	while($result = mysqli_fetch_array($query)){
		$sAddress = getLocation($result['LATITUD'],$result['LONGITUD']);

		if($sAddress!=""){
			setMarkRow($result['ID_POSICION'],$sAddress);
		}
	}

  	$sql2 = "SELECT *
			FROM DISP_ULTIMA_POSICION
			WHERE UBICACION IS NULL
			ORDER BY FECHA_GPS DESC
			LIMIT 15"; 
  	$query2 = mysqli_query($conexion, $sql2);
  	$count = 0;
	while($result2 = mysqli_fetch_array($query2)){
		$sAddress = getLocation($result2['LATITUD'],$result2['LONGITUD']);

		if($sAddress!=""){
			setMarkRowUlt($result2['ID_POSICION'],$sAddress);
		}
	}	

    function getLocation($latitud,$longitud){
    	$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitud.",".$longitud."&sensor=false";

        $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, "UTF-8");
		$curlData = curl_exec($curl);
		curl_close($curl);

		$data = json_decode($curlData);

		return $data->results[0]->formatted_address;
    }

  	function setMarkRow($idRow,$sAdress){
	    global $conexion;
	    $result = false;
	    $sql ="UPDATE DISP_HISTORICO_POSICION 
	            SET UBICACION 		= '".utf8_decode($sAdress)."'
	            WHERE ID_POSICION 	= $idRow";
	    var_dump($sql);
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}

  	function setMarkRowUlt($idRow,$sAdress){
	    global $conexion;
	    $result = false;
	    $sql ="UPDATE DISP_ULTIMA_POSICION 
	            SET UBICACION 		= '".utf8_decode($sAdress)."'
	            WHERE ID_POSICION 	= $idRow";
	    var_dump($sql);
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}