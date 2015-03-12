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
                  DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud.") < 5";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res = $res."<taxi>
                <id>".$row->ID_USUARIO."</id>
                <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                <placas>".$row->PLACAS."</placas>
                <estatus>".$row->ESTATUS."</estatus>
                <latitud>".$row->LATITUD."</latitud>
                <longitud>".$row->LONGITUD."</longitud>
                <distancia>".$row->DIST."</distancia>
                <foto>".$row->IMAGEN."</foto>
                <puntos>".$row->RATING."</puntos>
                <servicios>".$row->VIAJES."</servicios>
              </taxi>"; 
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
                   <taxis>'.utf8_encode($dat).'<taxi>
                       <id>2</id>
                       <conductor>Pedro Ramírez</conductor>
                       <placas>BCW3455</placas>
                       <estatus>taxi_ocupado</estatus>
                       <latitud>19.5076166667</latitud>
                       <longitud>-99.2338333333</longitud>
                       <distancia>2</distancia>
                       <foto>http://www.motorspain.com/wp-content/uploads/2010/12/vw-up-taxi-concept-londres-2010-olgbn-5-400x300.jpg
                       </foto>
                       <puntos>4</puntos>
                       <servicios></servicios>
                     </taxi>
                      <taxi>
                      <id>3</id>
                       <conductor>Pablo Cárdenas</conductor>
                       <placas>XCW3455</placas>
                       <estatus>taxi_ocupado</estatus>
                       <latitud>19.6584333333</latitud>
                       <longitud>-99.2046333333</longitud>
                       <distancia>2</distancia>
                       <foto>http://static3.absolutalemania.com/wp-content/uploads/2011/04/taxis-en-alemania.jpg</foto>
                       <puntos>2</puntos>
                       <servicios></servicios>
                     </taxi>
                     <taxi>
                       <id>4</id>
                       <conductor>Alfonso Escobar</conductor>
                       <placas>ZCW3435</placas>
                       <estatus>taxi_servicio</estatus>
                       <latitud>19.4946166667</latitud>
                       <longitud>-99.0563666667</longitud>
                       <distancia>2</distancia>
                       <foto>http://media.bestofmicro.com/mercedes-benz-nimbus-taxi-concept,3-4-267088-13.jpg</foto>
                       <puntos>5</puntos>
                       <servicios></servicios>
                     </taxi>
                   </taxis>
                 </Response>
               </space>';
    return $res;
  }
 
  $lat = $_GET["lat"];
  $lon = $_GET["lon"];

  echo GetTaccsis($lat,$lon);

?>
