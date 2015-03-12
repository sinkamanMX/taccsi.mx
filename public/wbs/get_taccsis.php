<?php

  function dame_taxis_libres($latitud,$longitud){
    $res = '';
    $sql = "SELECT B.ID_USUARIO,
                   B.NOMBRE,
                   B.APATERNO,
                   B.AMATERNO,
                   C.PLACAS,
                   'taxi_libre' AS ESTATUS,
                   A.LATITUD,
                   A.LONGITUD,
                   ROUND(DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud."),2) AS DIST,
                   C.IMAGEN,
                   IF(B.RATING IS NULL,0,B.RATING) AS RATING,
                   IF(B.VIAJES IS NULL,0,B.VIAJES) AS VIAJES
            FROM DISP_ULTIMA_POSICION A
              INNER JOIN ADMIN_USUARIOS B ON B.ID_USUARIO = A.ID_USUARIO
              INNER JOIN ADMIN_TAXIS C ON C.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO 
            WHERE CAST(A.FECHA_SERVER AS DATE) = CURRENT_DATE AND
                  B.DISPONIBLE = 0 AND 
                  DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud.") < 100
            ORDER BY DIST
            LIMIT 10";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        if($row->IMAGEN=="" or $row->IMAGEN=="null"){
           $res = $res."<taxi>
                  <id>".$row->ID_USUARIO."</id>
                  <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                  <placas>".$row->PLACAS."</placas>
                  <estatus>".$row->ESTATUS."</estatus>
                  <latitud>".$row->LATITUD."</latitud>
                  <longitud>".$row->LONGITUD."</longitud>
                  <distancia>".$row->DIST."</distancia>
                  <foto>"."http://taccsi.com/images/taxis/no_disponible.jpg</foto>
                  <puntos>".$row->RATING."</puntos>
                  <servicios>".$row->VIAJES."</servicios>
                </taxi>"; 
        }else{
          $res = $res."<taxi>
                  <id>".$row->ID_USUARIO."</id>
                  <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                  <placas>".$row->PLACAS."</placas>
                  <estatus>".$row->ESTATUS."</estatus>
                  <latitud>".$row->LATITUD."</latitud>
                  <longitud>".$row->LONGITUD."</longitud>
                  <distancia>".$row->DIST."</distancia>
                  <foto>"."http://taccsi.com/images/taxis/".$row->IMAGEN."</foto>
                  <puntos>".$row->RATING."</puntos>
                  <servicios>".$row->VIAJES."</servicios>
                </taxi>"; 

        }
      }
      mysql_free_result($qry);
    } 

    return $res;
  }

  function GetTaccsis($latitud,$longitud){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $dat = '';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_taxis_libres($latitud,$longitud);
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>2</code>
                     <msg>OK</msg>
                   </Status>
                   <taxis>'.utf8_encode($dat).'
                   </taxis>
                 </Response>
               </space>';
    return $res;
  }
 
  $lat = $_GET["lat"];
  $lon = $_GET["lon"];

  echo GetTaccsis($lat,$lon);

?>
