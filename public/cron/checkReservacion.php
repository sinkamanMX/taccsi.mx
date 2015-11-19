<?php
	error_reporting(E_ALL);
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	//$conexion = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));

	$sql = "SELECT R.ID_RESERVACION AS ID, R.ID_ESTATUS_RESERVACION AS ESTATUS, 
            IF(CAST(R.FECHA_RESERVACION AS DATE) = CURRENT_DATE,'1','0') AS TODAY, 
            (TIMESTAMPDIFF(MINUTE , CURRENT_TIMESTAMP,R.FECHA_RESERVACION )) AS TIEMPO, 
            R.*              
            FROM ADMIN_RESERVACIONES R
            WHERE R.ID_ESTATUS_RESERVACION = 0";
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;
	while($result = mysqli_fetch_array($query)){
		if($result['TODAY']==1){
			if($result['TIEMPO']>0 && $result['TIEMPO']<=30 ){
				//echo "Debe de empezar a atenderse";	
				activarReservacion($result['ID']);	
			}elseif($result['TIEMPO']<0){
				//echo "Ya paso el horario";
				//Se cancela la reservacion, ya expiró
				$cancel = cancelReservacion($result['ID']);	
			}			
		}else{			
			if($result['TIEMPO']<0){
				//Se cancela la reservacion, ya expiró
				$cancel = cancelReservacion($result['ID']);	
			}
		}
	}

  	function cancelReservacion($idOject){
	    global $conexion;
	    $result = false;
	      $sql ="UPDATE ADMIN_RESERVACIONES 
	            SET ID_ESTATUS_RESERVACION 		= 3,
	      			FECHA_CANCELACION   = CURRENT_TIMESTAMP
	            WHERE ID_RESERVACION 	= $idOject	            		
	            LIMIT 1";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}

  	function activarReservacion($idOject){
	    global $conexion;
	    $result = false;
	      $sql ="UPDATE ADMIN_RESERVACIONES 
	            SET ID_ESTATUS_RESERVACION 		= 4
	            WHERE ID_RESERVACION 	= $idOject	            		
	            LIMIT 1";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}
?>	