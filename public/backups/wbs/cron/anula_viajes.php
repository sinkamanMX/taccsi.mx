<?php

  function marca_viaje($id_viaje){
  	$sql = "UPDATE ADMIN_VIAJES
  	        SET ID_SRV_ESTATUS = -1
			WHERE ID_VIAJES = '".$id_viaje."'";
    $qry = mysql_query($sql);
  }

  function recorre_viajes(){
  	$sql = "SELECT  ID_VIAJES
			FROM ADMIN_VIAJES
			WHERE CURRENT_TIMESTAMP > DATE_ADD(FECHA_VIAJE,INTERVAL 100 SECOND)   AND
			      ID_SRV_ESTATUS  IN (1,6)";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        marca_viaje($row->ID_VIAJES);
      }
      mysql_free_result($qry);
    } 

  }

  function anula_viajes(){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //actualiza la tabla viajes asignacion
      //revisa la tabla del viaje
      recorre_viajes();
      mysql_close($con);
    }
  }

  anula_viajes();
?>
