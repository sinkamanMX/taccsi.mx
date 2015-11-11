<?php
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	
	/**
	* Se obtienen todas las reservaciones que esten pendientes
	* 
	*/
  	$sql = "SELECT *
    		FROM SYS_MAILING
        	WHERE ESTATUS = 0 LIMIT 30";
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;



?>