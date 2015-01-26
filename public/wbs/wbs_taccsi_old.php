<?php
  set_time_limit(60000);
  require_once('./lib/nusoap.php'); 
  //include('functions_solcat.php');
  $miURL = 'http://201.131.96.45/wbs_taccsi';
  $server = new soap_server(); 
  $server->configureWSDL('wbs_taccsi', $miURL); 
  $server->wsdl->schemaTargetNamespace=$miURL;

  $server->register('GetTaccsis', // Nombre de la funcion 
                    array('latitud'  => 'xsd:string',
                          'longitud' => 'xsd:string'), // Parametros de entrada 
                    array('return'   => 'xsd:string'), // Parametros de salida 
                    $miURL); 
  $server->register('RecibePosicion', // Nombre de la funcion vel, lat, lon, alt, ang, fecha, y gps proveedor
                    array('id_usuario' => 'xsd:string',
                          'equipo'     => 'xsd:string',
                          'push_token' => 'xsd:string',
                          'latitud'    => 'xsd:string',
                          'longitud'   => 'xsd:string',
                          'velocidad'  => 'xsd:string',
                          'altitud'    => 'xsd:string',
                          'angulo'     => 'xsd:string',
                          'fecha'      => 'xsd:string', 
                          'proveedor'  => 'xsd:string',
                          'error'      => 'xsd:string'), // Parametros de entrada 
                    array('return'     => 'xsd:string'), // Parametros de salida 
                    $miURL); 
  $server->register('usrNuevoViaje', // Nombre de la funcion 
                    array('usuario'      => 'xsd:string',
                          'dispositivo'  => 'xsd:string',
                          'push_token'   => 'xsd:string',
                          'origen'       => 'xsd:string',
                          'destino'      => 'xsd:string',
                          'lat_origen'   => 'xsd:string',
                          'lon_origen'   => 'xsd:string',
                          'lat_destino'  => 'xsd:string',
                          'lon_destino'  => 'xsd:string',
                          'personas'     => 'xsd:string',
                          'pago'         => 'xsd:string',
                          'descuento'    => 'xsd:string',
                          'id_conductor' => 'xsd:string' ), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL); 
  /*Metodo para login*/
  $server->register('Login', // Nombre de la funcion 
                    array('usuario'     => 'xsd:string',
                          'password'    => 'xsd:string',
                          'tipo'        => 'xsd:string',
                          'dispositivo' => 'xsd:string',
                          'push_token'  => 'xsd:string'), // <--- U = Cliente , T = Taxista 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL); 
  /*Inicio Viaje:
    Es el Método para que cuando al usuario le llegue la notificación de que el taxista llego al punto.
    El usuario pueda confirmar que ya iniciara su vije
  */
  $server->register('usrInicioViaje', // Nombre de la funcion 
                    array('usuario'  => 'xsd:string',
                          'password' => 'xsd:string',
                          'idViaje'  => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('usrFinViaje', // Nombre de la funcion 
                    array('id_usuario'      => 'xsd:string',
                          'id_viaje'        => 'xsd:string',
                          'comentarios'     => 'xsd:string',
                          'puntos_servicio' => 'xsd:string',
                          'puntos_taxista'  => 'xsd:string',
                          'importe'         => 'xsd:string',
                          'distancia'       => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('usrDameInfoTaxista', // Nombre de la funcion 
                    array('id_usuario'     => 'xsd:string',
                          'id_taxista'     => 'xsd:string',
                          'id_viaje'       => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('CancelarViaje', // Nombre de la funcion 
                    array('id_usuario'  => 'xsd:string',
                          'id_viaje'    => 'xsd:string',
                          'app'         => 'xsd:string',
                          'id_razon'    => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('oprInicioViaje', // Nombre de la funcion 
                    array('id_usuario' => 'xsd:string',
                          'idViaje'    => 'xsd:string',
                          'clave'      => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);
  
  $server->register('oprDameViaje',
                     array('id_viaje' => 'xsd:string'),
                     array('return' => 'xsd:string'),
                     $miURL);

  $server->register('oprRechazarViaje',
                     array('id_viaje' => 'xsd:string',
                           'id_usuario' => 'xsd:string'),
                     array('return' => 'xsd:string'),
                     $miURL);
  
  $server->register('oprTomarViaje', // Nombre de la funcion 
                    array('id_usuario'   => 'xsd:string',
                          'id_viaje'     => 'xsd:string',
                          'dispositivo'  => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('oprFinViaje', // Nombre de la funcion 
                    array('id_usuario'      => 'xsd:string',
                          'id_viaje'        => 'xsd:string',
                          'comentarios'     => 'xsd:string',
                          'puntos_usuario'  => 'xsd:string',
                          'importe'         => 'xsd:string',
                          'distancia'       => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('DameSitios', // Nombre de la funcion 
                    array('usuario'  => 'xsd:string',
                          'password' => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);


  $server->register('RegistraTaccsista', // Nombre de la funcion 
                    array('nombre'        => 'xsd:string',
                          'apaterno'      => 'xsd:string',
                          'amaterno'      => 'xsd:string',
                          'movil'         => 'xsd:string',
                          'email'         => 'xsd:string',
                          'password'      => 'xsd:string',
                          'taxi_propio'   => 'xsd:string',
                          'asociacion'    => 'xsd:string',
                          'identificador' => 'xsd:string',
                          'push_token'    => 'xsd:string',
                          'clave_empresa' => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);
  $server->register('RegistraUsuario', 
                    array('nombre'      => 'xsd:string',
                          'apaterno'    => 'xsd:string',
                          'amaterno'    => 'xsd:string',
                          'movil'       => 'xsd:string',
                          'email'       => 'xsd:string',
                          'password'    => 'xsd:string',
                          'dispositivo' => 'xsd:string',
                          'push_token'  => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('RazonesCancelacion', 
                    array('app'       => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('TengoViaje', 
                    array('id_usuario'  => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('EstatusMiViaje', 
                    array('id_viaje'   => 'xsd:string',
                          'id_usuario' => 'xsd:string'),
                    array('return'      => 'xsd:string'),
                    $miURL);
  /*funciones secundarias*/
  function registra_posicion($base,$id_usuario,$equipo, $latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error){
    global $base;
    $res = false;
    $sql = "INSERT INTO DISP_HISTORICO_POSICION (
              ID_USUARIO,
              IDENTIFICADOR,
              LATITUD,
              LONGITUD,
              VELOCIDAD,
              ALTITUD,
              ANGULO,
              FECHA_GPS,
              FECHA_SERVER,
              PROVEEDOR,
              ERROR
            ) VALUES (
              ".$id_usuario.",
              '".$equipo."',
              '".$latitud."',
              '".$longitud."',
              '".$velocidad."',
              '".$altitud."',
              '".$angulo."',
              '".$fecha."',
              CURRENT_TIMESTAMP,
              '".$proveedor."',
              '".$error."');";
    if (mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function valida_correo($email,$tipo_usuario){
    global $base;
    $res = false; 
    if ($tipo_usuario == 'taxista'){
      $sql = "SELECT COUNT(NICKNAME) AS EXISTE
              FROM ADMIN_USUARIOS
              WHERE NICKNAME = '".$email."'";
    } else {
      $sql = "SELECT COUNT(USUARIO) AS EXISTE
              FROM SRV_USUARIOS
              WHERE USUARIO = '".$email."'";
    }
    if ($qry=mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->EXISTE == 0){
        $res = true;
      }
      mysql_free_result($qry);
    }       
    return $res; 
  }


  function valida_telefono($telefono,$tipo_usuario){
    global $base;
    $res = false; 
    if ($tipo_usuario == 'taxista'){
      $sql = "SELECT COUNT(NICKNAME) AS EXISTE
              FROM ADMIN_USUARIOS
              WHERE TELEFONO = '".$telefono."'";
    } else {
       $sql = "SELECT COUNT(TELEFONO) AS EXISTE
              FROM SRV_USUARIOS
              WHERE TELEFONO = '".$telefono."'";  
    }
    if ($qry=mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->EXISTE == 0){
        $res = true;
      }
      mysql_free_result($qry);
    }       
    return $res;  
  }

  function registra_taxista($nombre,$apaterno,$amaterno,$movil,$email,$password,$taxi_propio,$asociacion,$identificador,$clave_empresa){
    global $base;
    //si viene una clave de emprea debe buscarla
    //generar una funcion para contraseñas dinamicas
     $res = -1;
    $sql = "INSERT INTO ADMIN_USUARIOS (
              ID_EMPRESA,
              TIPO_USUARIO,
              NOMBRE,
              APATERNO,
              AMATERNO,
              TELEFONO,
              NICKNAME,
              PASSWORD,
              PASSWORD_TEXT,
              TAXI_PROPIO,
              ASOCIACION,
              DISPOSITIVO,
              ACTIVO,
              FECHA_REGISTRO
            ) VALUES (
              1,
              4,
              '".$nombre."',
              '".$apaterno."',
              '".$amaterno."',
              '".$movil."',
              '".$email."',
              sha1('".$password."'),
              '".$password."',
              '".$taxi_propio."',
              '".$asociacion."',
              '".$identificador."',
              1,
              CURRENT_TIMESTAMP
            )";
    if (mysql_query($sql)){
      
      $sql2 = "SELECT ID_USUARIO
            FROM ADMIN_USUARIOS
            WHERE NICKNAME = '".$email."'";
      if ($qry2 = mysql_query($sql2)){
        $row = mysql_fetch_object($qry2);
        $res = $row->ID_USUARIO;
        mysql_free_result($qry2);
      }   
    }
    return $res; 
  }

  function agrega_taxi($id,$nombre_copleto){
    $res = false;
    $sql = "INSERT ADMIN_TAXIS (
              ID_EMPRESA,
              NOMBRE_CHOFER,
              MARCA,
              MODELO,
              PLACAS,
              COLOR,
              ECO,
              FECHA_REGISTRO,
              ID_ESTATUS,
              ADMIN_USUARIOS_ID_USUARIO,
              IMAGEN
            ) VALUES (
              '1',
              '".$nombre_completo."',
              'SIN MARCA',
              'DESCONOCIDO',
              'XXX0000',
              'NO INDICADO',
              'DESCONOCIDO',
              CURRENT_TIMESTAMP,
              1,
              '".$id."',
              'SIN FOTO'
            )";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  /*Funciones WBS taxis*/
  function RegistraTaccsista ($nombre,
                              $apaterno,
                              $amaterno,
                              $movil,
                              $email,
                              $password,
                              $taxi_propio,
                              $asociacion,
                              $identificador,
                              $push_token,
                              $clave_empresa){
    /*validar que el correo no este registrado*/
    /*validar que los correos coincidan*/
    /*validar que el telefono no exista*/
    /*registrar el usuario*/
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $dat = '';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
        if (valida_correo($email,'taxista')){
          if (valida_telefono($movil,'taxista')){
            $id = registra_taxista($nombre,$apaterno,$amaterno,$movil,$email,$password,$taxi_propio,$asociacion,$identificador,$clave_empresa);
            if ($id > 0){
              /*MANDA MAIL PARA NOTIFICAR QUE DEBE ENVIAR DOCUMENTACIÓN*/
              /*TIENE QUE DEJAR LA CUENTA INACTIVA*/
              /*QUitare mas adelante, se deja el update para efectos de prueba*/
              //agrega_taxi($id);
              agrega_taxi($id,$nombre." ".$apaterno." ".$amaterno);
              /*QUITAR */
              $dat = dame_usuario($email,$password,'T');
              registra_token($identificador,$push_token);
              $msg = 'OK';  
              $idx = $id;
            } else {
              $msg = $id;  
              $idx = -5;
            }
          } else {
            $msg = 'El teléfono ya fue registrado previamente';   
            $idx = -4;
          }
        } else {
          $msg = 'Este correo ya esta registrado';   
          $idx = -3;
        }
      
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);  
  }


  function RecibePosicion ($id_usuario,$equipo, $push_token,$latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error){
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = registra_token($equipo,$push_token);
      //$res = registra_posicion($base,$id_usuario,$equipo, $latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error);
      if (registra_posicion($base,$id_usuario,$equipo, $latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error)){
        //update_dispositivo_usuario($usuario,$password,'T',$dispositivo);
        $msg = 'OK';
        $idx = 0;
      } else {
        $msg = 'No es posible registrar la posición TACCSI';
        $idx = -2; 
      }
      
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);  
  }
  

  function DameSitios($usuario,$password){
    $cod = -1;
    $msg = 'Usuario no registrado';
    if (($usuario == 'demo') and ($password == 'demo')){
      $cod = 1;
      $msg = 'OK';    
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>1</code>
                     <msg>OK</msg>
                   </Status>
                   <sitios>
                     <sitio>
                       <id>MBB2554</id>
                       <nombre>Mi casa</nombre>
                       <idCategoria>1</idCategoria>
                       <Categoria>Casa</Categoria>
                       <calle>Calle 1</calle>
                       <no_int>12</no_int>
                       <no_ext></no_ext>
                       <colonia>Desconocida</colonia>
                       <cp>54400</cp>
                       <municipio>Nicolás Romero</municipio>
                       <estado>México</estado>
                       <latitud>19.7522166667</latitud>
                       <longitud>-101.17275</longitud>
                     </sitio>
                   </sitios>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function dame_push_viaje($idViaje,$clave){
   global $base;  
    $res = '';
    $sql = "SELECT B.PUSH_TOKEN
            FROM ADMIN_VIAJES A
              INNER JOIN DISP_REGISTRO_TOKENS B ON B.DEVICE_ID = A.DISPOSITIVO_ORIGEN
            WHERE A.ID_VIAJES = ".$idViaje." AND
                  A.CLAVE_VIAJE = ".$clave;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (strlen($row->PUSH_TOKEN) > 0){
        $res = $row->PUSH_TOKEN;  
      }
      mysql_free_result($qry);
    }/*else{
    $res = mysql_errno() . ": " . mysql_error() . "\n";
  }*/
    return $res;
  }

  function marca_inicio($idViaje){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES
            SET SRV_ESTATUS_ID_SRV_ESTATUS = 5
            WHERE ID_VIAJES = ".$idViaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function oprInicioViaje($id_usuario,$idViaje,$clave){
    $idx = -1;
    global $base;
    $msg = 'Usuario no registrado';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //valida que la clave corresponda al viaje
      $push_token = dame_push_viaje($idViaje,$clave);
      if (strlen($push_token) > 0){
        if (marca_inicio($idViaje)){
          envia_push('Su viaje ha iniciado.',$push_token);
          $idx = 0;
          $msg = 'OK';
        }  
      } else {
        $idx = -2;
        $msg = 'Clave de viaje incorrecta';  
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }
  
  function valida_viaje($id_viaje){
    $res = false;
    $sql = "SELECT SRV_ESTATUS_ID_SRV_ESTATUS
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->SRV_ESTATUS_ID_SRV_ESTATUS;  
      mysql_free_result($qry);
    }
    return $false;
  }

  function cancelar_viaje($idViaje,$app){
    $res = false;
    if ($app == 'T'){
      $sql = "UPDATE ADMIN_VIAJES
              SET SRV_ESTATUS_ID_SRV_ESTATUS = 4
              WHERE ID_VIAJES = ".$idViaje;
    } else {
      $sql = "UPDATE ADMIN_VIAJES
              SET SRV_ESTATUS_ID_SRV_ESTATUS = 8
              WHERE ID_VIAJES = ".$idViaje;   
    }
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function dame_pushtoken_viaje($id_viaje,$app){
    $res = '';
    //PUSH_TOKEN
    if ($app == 'T'){
      $sql = 'SELECT C.PUSH_TOKEN
              FROM ADMIN_VIAJES A
                INNER JOIN DISP_ULTIMA_POSICION B ON A.ID_TAXISTA = B.ID_USUARIO
                INNER JOIN DISP_REGISTRO_TOKENS C ON C.DEVICE_ID = B.IDENTIFICADOR
              WHERE A.ID_VIAJES ='.$id_viaje;
    } else {
      $sql = 'SELECT C.PUSH_TOKEN
              FROM ADMIN_VIAJES A
                  INNER JOIN DISP_REGISTRO_TOKENS C ON C.DEVICE_ID = A.DISPOSITIVO_ORIGEN
              WHERE A.ID_VIAJES = '.$id_viaje;
    }
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->PUSH_TOKEN;  
      mysql_free_result($qry);
    }
    return $res;
  }

  function CancelarViaje($id_usuario,$id_viaje,$app,$id_razon){
    $idx = -1;
    $msg = 'Servicio TACSSI no disponible, intente mas tarde.';
    /*Buscar viaje*/
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $estatus = valida_viaje($id_viaje);
      if ($estatus == 3){
        $idx = -2;
        $msg = 'El viaje ya fue finalizado previamente.';
      } else {
        $push_token = dame_pushtoken_viaje($id_viaje,$app);
        if (strlen($push_token) > 0){
          if (cancelar_viaje($id_viaje, strtoupper($app))){
            
            if ($id_razon == 1){
              $msg = 'Falla mécania';
            } elseif ($id_razon == 2) {
              $msg = 'Ponchadura';
            } elseif ($id_razon == 3) {
               $msg = 'Siniestro';
            } elseif ($id_razon == 4) {
               $msg = 'Manifestación';
            } elseif ($id_razon == 5) {
               $msg = 'Congestionamiento vial';
            } elseif ($id_razon == 6) {
               $msg = 'Prueba';
            } elseif ($id_razon == 7) {
               $msg = 'Excedio el tiempo de arribo';
            } elseif ($id_razon == 8) {
               $msg = 'No llego';
            } elseif ($id_razon == 9) {
               $msg = 'Use otro servicio';
            }


            if ($app == 'T'){
              //SI LO CANCELA EL USUARIO
              //OBTIENE EL PUSH TOKEN DEL TAXISTA
              $x = envia_push('El cliente ha cancelado el viaje.@'.$id_viaje.'@'.$msg,$push_token);   
            } else {
              //SI LO CANCELA EL TAXISTA
              //OBTIENE EL PUSH TOKEN DEL USUARIO
              $x = envia_push('El TACCSISTA ha cancelado el viaje.@'.$id_viaje.'@'.$msg,$push_token);
            }
            $idx = 1;
            $msg = 'Su viaje ha sido cancelado.';
          } else {
            $idx = -2;
            $msg = 'No fue posible registrar su cancelación. Intente mas tarde.';  
          }
        } else {
          $idx = -3;
          $msg = 'No es posible notificar su cancelación. Intente mas tarde.'.$id_viaje.' '.$id_usuario.' '.$app; 
        }
      }
      mysql_close($con);
    } 
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  /*..-.-.-------------------------------------*/
  function finaliza_viaje($id_viaje,$comentarios,$puntos_servicio,$importe,$distancia){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET SRV_ESTATUS_ID_SRV_ESTATUS = 3,
                FIN_VIAJE_USUARIO = CURRENT_TIMESTAMP,
                RATING = ".$puntos_servicio.",
                COMENTARIOS = '".$comentarios."',
                MONTO_TOTAL = ".$importe.",
                DISTANCIA = ".$distancia."
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }   
    return $res;  
  }

  function dame_id_taxista($id_viaje){
    $res = -1;
    $sql = "SELECT ID_TAXISTA
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ID_TAXISTA;  
      mysql_free_result($qry);
    }        
    return $res;  
  }

  function califica_taxista($id_taxista,$puntos_taxista){
    $res = false;
    $sql = "UPDATE ADMIN_USUARIOS 
            SET VIAJES = IF(VIAJES IS NULL,1,VIAJES+1),
                RATING = IF(RATING IS NULL,".$puntos_taxista.",(RATING + ".$puntos_taxista.")/2)
            WHERE ID_USUARIO = ".$id_taxista;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }

  function usrFinViaje($id_usuario,$id_viaje,$comentarios, $puntos_servicio, $puntos_taxista, $importe,$distancia){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (finaliza_viaje($id_viaje,$comentarios,$puntos_servicio,$importe,$distancia)){
        $id_taxista = dame_id_taxista($id_viaje);
        if ($id_taxista > -1){
          califica_taxista($id_taxista,$puntos_taxista);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'T');
          envia_push('El viaje ha sido finalizado por el cliente',$push_token);
        }
        $idx = 0;
        $msg = 'Viaje finalizado';  
      } else {
        $idx = -2;
        $msg = 'No fue posible finalizar el viaje, intente mas tarde';
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function usrInicioViaje($usuario,$password,$idViaje){
    $cod = -1;
    $msg = 'Usuario no registrado';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (($usuario == 'demo') and ($password == 'demo')){
        $cod = 1;
        $msg = 'OK';    
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>1</code>
                     <msg>OK</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function dame_usuario($usuario,$password,$tipo){
    $res = '';  
    global $base;
    if ($tipo == 'U'){
      $sql = "SELECT ID_SRV_USUARIO AS ID_USUARIO,
                   NOMBRE,
                   APATERNO ,
                   AMATERNO,
                   TELEFONO,
                   USUARIO as EMAIL
            FROM SRV_USUARIOS
            WHERE USUARIO = '".$usuario."'";
    } else {
      $sql = "SELECT ID_USUARIO,
                     NOMBRE,
                     APATERNO,
                     AMATERNO,
                     TELEFONO,
                     NICKNAME AS EMAIL
              FROM ADMIN_USUARIOS
              WHERE NICKNAME = '".$usuario."' AND
                    PASSWORD_TEXT = '".$password."'";
    }
    if ($qry = mysql_query($sql)){
      if (mysql_num_rows($qry) > 0){
        $row = mysql_fetch_object($qry);
        $res = '<usuario>
                  <id>'.$row->ID_USUARIO.'</id>
                  <nombre>'.$row->NOMBRE.'</nombre>
                  <apaterno>'.$row->APATERNO.'</apaterno>
                  <amaterno>'.$row->AMATERNO.'</amaterno>
                  <telefono>'.$row->TELEFONO.'</telefono>
                  <correo>'.$row->EMAIL.'</correo>
                </usuario>';
      }
      mysql_free_result($qry);
    }
    return $res;
  }

  function update_dispositivo_usuario($usuario,$password,$tipo,$dispositivo){
    $res = false;  
    global $base;
    if ($tipo == 'U'){
      $sql = "UPDATE SRV_USUARIOS
              SET DISPOSITIVO = '".$dispositivo."'
              WHERE USUARIO = '".$usuario."'";
    } else {
      //$dispositivo = '357957050119619';
      $sql = "UPDATE ADMIN_USUARIOS
              SET DISPOSITIVO = '".$dispositivo."'
              WHERE NICKNAME = '".$usuario."' AND
                    PASSWORD_TEXT = '".$password."'";
    }
    if ($qry = mysql_query($sql)){
        $res = true;
    }
    return $res;
  }


  function Login($usuario,$password,$tipo,$dispositivo,$push_token){
    $idx = -1;
    $msg = 'Usuario no registrado';
    $dat = "";
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_usuario($usuario,$password,$tipo);
      if (strlen($dat) > 0){
        update_dispositivo_usuario($usuario,$password,$tipo,$dispositivo);
        registra_token($dispositivo,$push_token);
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'Usuario o Password incorrecto';  
      }
      mysql_close($con);  
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function valida_usuario($usuario){
    $res = 0;
    global $base;
    $sql = "SELECT ID_SRV_USUARIO
            FROM SRV_USUARIOS
            WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ID_SRV_USUARIO;
      mysql_free_result($qry);
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
                         $descuento){
    $res = false;
    global $base;
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
              SRV_USUARIOS_ID_SRV_USUARIO,
              DISPOSITIVO_ORIGEN,
              SRV_ESTATUS_ID_SRV_ESTATUS
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
              1)";
    if ($qry = mysql_query($sql)){
      $res = true;
    } 
    return $res;
  }

  function envia_push($mensaje,$push_token){
    $url = "https://ws.pushapps.mobi/RemoteAPI/CreateNotification";
    
    $data = array(
                  'SecretToken' => '13695fb2-6fd8-4acc-981b-baedaf84a5e1', ## Your app secret token
                  'Message' => $mensaje, ## The message you want to send
                  'Platforms' => array(),## Optional, platforms to send to, if empty will send to all configured platforms, will be overridden if Devices is     specified
                  'Devices' => array(array(
                                           'PushToken' => $push_token, ## Device push token
                                           'DeviceType' => 1
                                           )) ## Optional, array of devices to send, if empty will send to all registered users, or by Platforms if specified
                  );

    $content = json_encode($data);

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

    $json_response = curl_exec($curl);

    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ( $status != 200 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }

    curl_close($curl);

   // echo "Response: " . $json_response . "\n";
    //         var_dump(json_decode($json_response));
          // echo $x->{'code'};
    $x =json_decode($json_response);
    $res = $x->Code;
    return $res;
  }
  
  function dame_viaje($id_viaje){
    $res = "";
    global $base;
    $sql = "SELECT B.NOMBRE,
                   B.APATERNO,
                   B.AMATERNO,
                   B.TELEFONO,
                   A.NO_PASAJEROS,
                   A.ORIGEN,
                   A.ORIGEN_LATITUD,
                   A.ORIGEN_LONGITUD,
                   A.DESTINO,
                   A.DESTINO_LATITUD,
                   A.DESTINO_LONGITUD,
                   B.RATING,
                   C.DESCRIPCION AS FORMA_PAGO
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_USUARIOS B ON A.SRV_USUARIOS_ID_SRV_USUARIO = B.ID_SRV_USUARIO 
              INNER JOIN ADMIN_FORMA_PAGO C ON A.ID_FORMA_PAGO = C.ID_FORMA_PAGO
            WHERE  A.SRV_ESTATUS_ID_SRV_ESTATUS NOT IN (2,3,4,5) AND
                   ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (mysql_num_rows($qry) > 0){
        $res = "<viaje>
               <cliente>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</cliente>
               <telefono>".$row->TELEFONO."</telefono>
               <pasajeros>".$row->NO_PASAJEROS."</pasajeros>
               <origen>".$row->ORIGEN."</origen>
               <lat_origen>".$row->ORIGEN_LATITUD."</lat_origen>
               <lon_origen>".$row->ORIGEN_LONGITUD."</lon_origen>
               <destino>".$row->DESTINO."</destino>
               <lat_destino>".$row->DESTINO_LATITUD."</lat_destino>
               <lon_destino>".$row->DESTINO_LONGITUD."</lon_destino>
               <rating>".$row->RATING."</rating>
               <formapago>".$row->FORMA_PAGO."</formapago>
              </viaje>";
      }
      mysql_free_result($qry);
    } 
    return $res;
  }

  function existe_token($dispositivo){
    $res = false;
    global $base;
    $sql = "SELECT COUNT(*) AS EXISTE 
            FROM DISP_REGISTRO_TOKENS
            WHERE DEVICE_ID = '".$dispositivo."'";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->EXISTE > 0){
        $res = true;
      }
      mysql_free_result($close);
    }
    return $res;  
  }

  function registra_token($dispositivo,$push_token){
    $res = false;
    global $base; 
    if (existe_token($dispositivo)){
      $sql = "UPDATE DISP_REGISTRO_TOKENS 
            SET PUSH_TOKEN = '".$push_token."', 
                ULTIMO_REGISTRO = CURRENT_TIMESTAMP 
            WHERE DEVICE_ID ='".$dispositivo."'";
    } else {
      $sql = "INSERT INTO DISP_REGISTRO_TOKENS (
                PUSH_TOKEN,
                DEVICE_ID,
                ULTIMO_REGISTRO
              ) VALUES (
                '".$push_token."',
                '".$dispositivo."',
                CURRENT_TIMESTAMP
              )";
    }
    if ($qry = mysql_query($sql)){
      $res = true;
    } 
    return $res;
  }

  function dame_pushkey_taccsista($id_usuario){
    $res = "";
    $sql = "SELECT A.PUSH_TOKEN
            FROM DISP_REGISTRO_TOKENS A,
                 DISP_ULTIMA_POSICION B
            WHERE A.DEVICE_ID = B.IDENTIFICADOR AND
                  B.ID_USUARIO = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (strlen($row->PUSH_TOKEN) > 0){
        $res = $row->PUSH_TOKEN;
      }
      mysql_free_result($close);  
    }
    return $res;  
  }


  function gen_id_viaje(){
    $res = -1;
    $sql = "LOCK TABLE ADMIN_VIAJES_GEN WRITE";
    if ($qry = mysql_query($sql)){
      $sql2 = "INSERT INTO ADMIN_VIAJES_GEN (ID_VIAJE) VALUES (0)";
      mysql_query($sql2);
      $sql3 = "SELECT MAX(ID_VIAJE) AS ID FROM ADMIN_VIAJES_GEN";
      if ($qry3 = mysql_query($sql3)){
        $row = mysql_fetch_object($qry3);
        $res = $row->ID;
        mysql_free_result($qry3);
      }
      $sql4 = "UNLOCK TABLES";
      mysql_query($sql4);
      mysql_free_result($qry);
    } 
    return $res; 
  }

  function dame_id_viaje(){
    $res = false;
    while ($res <> true){
      $id = gen_id_viaje();
      $sql = "SELECT COUNT(*) AS ID 
              FROM ADMIN_VIAJES
              WHERE ID_VIAJES = ".$id;
      if ($qry = mysql_query($sql)){
        $row = mysql_fetch_object($qry);     
        if ($row->ID == 0){
          $res = true;
        }
        mysql_free_result($qry);
      }
    }
    return $id; 
  }

  function max_taxista(){
    $res = false;
    while ($res <> true){
      $id = gen_id_viaje();
      $sql = "SELECT MAX(ID_USUARIO) AS ID 
              FROM ADMIN_USUARIOS";
      if ($qry = mysql_query($sql)){
        $row = mysql_fetch_object($qry);  
        if ($row->ID == 0){
          $res = true;
        }
        mysql_free_result($qry);
      }
    }
    return $id; 
  }
  
  function registra_asignacion($id_viaje,$id_taxista){
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
    $qry = mysql_query($sql);
  }

  function manda_viaje_taxistas($id_viaje,$latitud,$longitud){
    $sql = "SELECT A.ID_USUARIO, C.PUSH_TOKEN
            FROM ADMIN_USUARIOS A
              INNER JOIN DISP_ULTIMA_POSICION B ON B.IDENTIFICADOR = A.DISPOSITIVO
              INNER JOIN DISP_REGISTRO_TOKENS C ON DEVICE_ID = B.IDENTIFICADOR
            WHERE A.DISPONIBLE = 0 AND
                  DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") < 10
            LIMIT 3";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        if ($row->ID == 0){
          sleep(5);
          $mensaje = "Se le ha asignado un viaje.@".$id_viaje; 
          $pushkey_taccsista = dame_pushkey_taccsista($row->ID_USUARIO);
          registra_asignacion($id_viaje,$row->ID_USUARIO);
          $res = envia_push($mensaje,$row->PUSH_TOKEN);

        }
      }
      mysql_free_result($qry);
    }
  }

  function usrNuevoViaje($usuario,
                         $dispositivo, 
                         $push_token,
                         $origen, 
                         $destino, 
                         $lat_origen, 
                         $lon_origen, 
                         $lat_destino,
                         $lon_destino, 
                         $personas,
                         $pago,
                         $descuento,
                         $id_conductor){

    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $id_usuario = valida_usuario($usuario);
      if ($id_usuario > 0){
        $id_viaje = 0;
        registra_token($dispositivo,$push_token);
        $id_viaje = dame_id_viaje();
        if (registra_viaje($id_viaje,
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
                         $descuento)){
          //$id_viaje = dame_viaje($id_usuario,$dispositivo,$lat_origen,$lon_origen,$lat_destino,$lon_destino);
          //tiene que buscar el usuario
          $mensaje = "Se le ha asignado un viaje.@".$id_viaje;
          //$id_conductor = max_taxista();
          //$id_conductor = 23;
          if ($id_conductor == 0){
            //obtienen los 3 mas cercanos junto con su push key
            //les manda push
            /*$pushkey_taccsista = dame_pushkey_taccsista($id_conductor);
            registra_asignacion($id_viaje,$id_conductor);
            $res = envia_push($mensaje,$dispositivo);*/
            //manda la notifiacion  a los  mas cercanos al origen
            /*manda_viaje_taxistas($id_viaje,$lat_origen,$lon_origen);*/
          } else {
            //obtiene el push key del id del taxista
            $pushkey_taccsista = dame_pushkey_taccsista($id_conductor);
            registra_asignacion($id_viaje,$id_conductor);
            $res = envia_push($mensaje,$pushkey_taccsista);
             // manda la notifación al mas cercano
          }
          //$clave = rand(1,99999);
          //$idx = $clave;

          $idx = $id_viaje;
          $msg = 'Su viaje ha sido registrado.'.$res;
        } else {
          $idx = -2;
          $msg = 'No fue posible asignar su viaje, intente mas tarde '.$id_viaje;
        }
        //$idx = 1;
        //$msg = 'Viaje registrado correctamente';    
      } else {
        $idx = -1;
        $msg = 'Usuario no registrado'.$id_usuario;
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  /*Funciones para Tomar viaje*/

  function valida_viaje_libre($id_viaje){
    $res = false;
    /*sql para validar que el estatus del viaje sea libre*/ 
    $sql = "SELECT SRV_ESTATUS_ID_SRV_ESTATUS
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->SRV_ESTATUS_ID_SRV_ESTATUS == 1){
        $res = true;
      }
      mysql_free_result($qry);
    }
    return $res;
  }
  
  function marca_viaje($id_usuario,$id_viaje,$codigo_viaje){
    $res = false;
    /*revisar*/
    $sql = 'UPDATE ADMIN_VIAJES 
            SET CLAVE_VIAJE = '.$codigo_viaje.',
                ID_TAXISTA = '.$id_usuario.',
                SRV_ESTATUS_ID_SRV_ESTATUS = 2 
            WHERE ID_VIAJES = '.$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }  
    return $res;
  }

  function dame_pushtoken_cliente($id_viaje){
    $res = '';
    $sql = "SELECT B.PUSH_TOKEN
              FROM ADMIN_VIAJES A
                   INNER JOIN DISP_REGISTRO_TOKENS B ON B.DEVICE_ID = A.DISPOSITIVO_ORIGEN
              WHERE A.ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (strlen($row->PUSH_TOKEN) > 0){
        $res = $row->PUSH_TOKEN;
      }
      mysql_free_result($close);  
    }
    return $res;
  }

  function marca_tomar_viaje($id_viaje,$id_usuario){
    $sql = "UPDATE VIAJES_ASIGNACIONES SET ACEPTO = 1 WHERE ID_VIAJE = ".$id_viaje." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }
  
  function tomar_viaje($id_usuario,$id_viaje,$codigo){
    $res = false;
    /*marca el viaje al taxista*/
    if (marca_viaje($id_usuario,$id_viaje,$codigo)){ 
      $res = true;
    } 
    return $res;
  }



  function oprTomarViaje ($id_usuario,$id_viaje,$dispositivo){
    /*Funccion para asignar el viaje a un taxista, cuando ya fue confirmado*/
    $idx = -1;
    $msg = '';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (valida_viaje_libre($id_viaje)){
        $codigo = rand(1,9999);
        if (tomar_viaje($id_usuario,$id_viaje,$codigo)){
          $token = dame_pushtoken_cliente($id_viaje);
          marca_tomar_viaje($id_viaje,$id_usuario);
          $x = envia_push('Su TACCSI va en camino, Código de confirmación: '.$codigo.' .@'.$id_usuario,$token);

          $idx = 0;
          $msg = 'Su viaje ha sido asignado.';
        } else {
          $idx = -3;
          $msg = 'No fue posible asignar el viaje.';   
        }
      } else {
        $idx = -2;
        $msg = 'EL viaje ya fue atendido.';  
      }
      mysql_close($con);
    } else {
      $idx = -1;
      $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function dame_taxista($id_taxista,$id_viaje){
    $res = ""; 
    $sql = "SELECT A.FOTO AS FOTO_TAXISTA,
                   A.NOMBRE,
                   A.APATERNO,
                   A.AMATERNO,
                   A.TELEFONO,
                   B.MARCA,
                   B.MODELO,
                   B.PLACAS,
                   B.ECO,
                   B.IMAGEN AS FOTO_TAXI,
                   C.LATITUD,
                   C.LONGITUD,
                   C.FECHA_SERVER
            FROM ADMIN_USUARIOS A,
                 ADMIN_TAXIS B, 
                 DISP_ULTIMA_POSICION C
            WHERE A.ID_USUARIO = ".$id_taxista." AND
                  B.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO AND
                  A.ID_USUARIO = C.ID_USUARIO";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = "<taxista>
                <foto_taxista>".$row->FOTO_TAXISTA."</foto_taxista>
                <nombre>".$row->NOMBRE."</nombre>
                <apaterno>".$row->APATERNO."</apaterno>
                <amaterno>".$row->AMATERNO."</amaterno>
                <telefono>".$row->TELEFONO."</telefono>
                <marca>".$row->MARCA."</marca>
                <modelo>".$row->MODELO."</modelo>
                <placas>".$row->PLACAS."</placas>
                <eco>".$row->ECO."</eco>
                <foto_taxi>".$row->FOTO_TAXI."</foto_taxi>
                <latitud>".$row->LATITUD."</latitud>
                <longitud>".$row->LONGITUD."</longitud>
                <fecha_gps>".$row->FECHA_SERVER."</fecha_gps>
              </taxista>"; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function oprDameViaje($id_viaje){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_viaje($id_viaje);
      if (strlen($dat) > 0){
        $idx = 0;
        $msg = 'OK';
      } else {
        $idx = -2;
        $msg = 'El viaje ya no esta disponible.';   
      }
      mysql_close($con);
    } 
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res); 
  }

  function usrDameInfoTaxista ($id_usuario,$id_taxista,$id_viaje){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_taxista($id_taxista,$id_viaje);
      if (strlen($dat) == 0){
        $idx = -2;
        $msg = 'No se logro obtener la información del TACCSISTA, intente mas tarde';
      } else {
        $idx = 0;
        $msg = 'OK';
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function marca_rechazo($id_viaje,$id_usuario){
    $sql = "UPDATE VIAJES_ASIGNACIONES SET ACEPTO = 0 WHERE ID_VIAJE = ".$id_viaje." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function marca_rechazo_viaje($id_viaje){
    $res = false;
    /*revisar*/
    $sql = 'UPDATE ADMIN_VIAJES 
            SET SRV_ESTATUS_ID_SRV_ESTATUS = 7 
            WHERE ID_VIAJES = '.$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }  
    return $res;
  }

  function rechazados($id_viaje){
    $res = '';
    $sql = "SELECT  COUNT(*) AS TOTAL
                  FROM VIAJES_ASIGNACIONES
                  WHERE ACEPTO = 0  AND
                        ID_VIAJE = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->TOTAL; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function asignados($id_viaje){
    $res = '';
    $sql = "SELECT  COUNT(*) AS TOTAL
                  FROM VIAJES_ASIGNACIONES
                  WHERE ID_VIAJE = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->TOTAL; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function oprRechazarViaje($id_viaje,$id_usuario){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //actualiza la tabla viajes asignacion
      //revisa la tabla del viaje
      if (marca_rechazo($id_viaje,$id_usuario)){
        //cuenta si aun hay taxistas
        $rechazados = rechazados($id_viaje); 
        $asignados = asignados($id_viaje); 
        if ($rechazados == $asignados){
          marca_rechazo_viaje($id_viaje);
          $token = dame_pushtoken_cliente($id_viaje);
          $x = envia_push('Viaje Rechazado',$token);
        }  
        $idx = 0;
        $msg = 'OK';
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }
 
  function registra_usuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo){
    $res = false;
    $sql = "INSERT INTO SRV_USUARIOS (
              NOMBRE,
              USUARIO,
              PASSWORD,
              FECHA_CREADO,
              ESTATUS,
              APATERNO,
              AMATERNO,
              TELEFONO,
              EMAIL,
              DISPOSITIVO,
              RATING,
              VIAJES
            ) VALUES (
              '".$nombre."',
              '".$email."',
              '".$password."',
              CURRENT_TIMESTAMP,
              1,
              '".$apaterno."',
              '".$amaterno."',
              '".$movil."',
              '".$email."',
              '".$dispositivo."',
              0,
              0
            )";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function dame_info_usuario($movil,$email){
    $res = '';
    $sql = "SELECT  ID_SRV_USUARIO,
                    NOMBRE,
                    USUARIO,
                    PASSWORD,
                    ESTATUS,
                    APATERNO,
                    AMATERNO,
                    TELEFONO,
                    EMAIL,
                    IF (RATING IS NULL,0,RATING) AS CALIFICACION,
                    VIAJES
                  FROM SRV_USUARIOS
                  WHERE TELEFONO = '".$movil."' AND
                        EMAIL = '".$email."';";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = "<usuario>
                <id>".$row->ID_SRV_USUARIO."</id>
                <nombre>".$row->NOMBRE."</nombre>
                <apaterno>".$row->APATERNO."</apaterno>
                <amaterno>".$row->AMATERNO."</amaterno>
                <telefono>".$row->TELEFONO."</telefono>
                <mail>".$row->EMAIL."</mail>
                <rating>".$row->CALIFICACION."</rating>
                <viajes>".$row->VIAJES."</viajes>
              </usuario>"; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function RegistraUsuario ($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$push_token){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $dat = '';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (valida_correo($email,'usuario')){
        if (valida_telefono($email,'usuario')){
          if (registra_usuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo)){
            registra_token($dispositivo,$push_token);  
            envia_push('Bienvenido a TACCSI',$push_token);
            $dat = dame_info_usuario($movil,$email);
            $idx = 0;
            $msg = 'OK';
          } else {
            $idx = -4;
            $msg = 'No fue posible completar su registro, intente mas tarde';
          }
        } else {
          $idx = -3;
          $msg = 'El teléfono móvil ya esta en uso.';
        }
      } else {
        $idx = -2;
        $msg = 'El correo ya esta en uso.';
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res); 
  }
 
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
                   <taxis>
                     '.utf8_encode($dat).'
                     <taxi>
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
    return new soapval('return', 'xsd:string', $res);
  }

  function finaliza_viaje_operador($id_viaje,$comentarios,$importe,$distancia){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET SRV_ESTATUS_ID_SRV_ESTATUS = 3,
                FIN_VIAJE_TAXISTA = CURRENT_TIMESTAMP,
                COMENTARIOS_TAXISTA = '".$comentarios."',
                MONTO_TAXISTA = ".$importe.",
                DISTANCIA_TAXISTA = ".$distancia."
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }   
    return $sql;  
  }

  function dame_id_usuario($id_viaje){
    $res = -1;
    $sql = "SELECT SRV_USUARIOS_ID_SRV_USUARIO
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->SRV_USUARIOS_ID_SRV_USUARIO;  
      mysql_free_result($qry);
    }        
    return $res;  
  }

  function califica_usuario($id_cliente,$puntos_usuario){
    $res = false;
    $sql = "UPDATE SRV_USUARIOS 
            SET VIAJES = IF(VIAJES IS NULL,1,VIAJES+1),
                RATING = IF(RATING IS NULL,".$puntos_usuario.",(RATING + ".$puntos_usuario.")/2)
            WHERE ID_SRV_USUARIO = ".$id_cliente;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }

  function forma_pago($id_viaje){
    $res = -1;
    $sql = "SELECT ID_FORMA_PAGO
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ID_FORMA_PAGO;  
      mysql_free_result($qry);
    }        
    return $res;  
  }

  function oprFinViaje($id_usuario,$id_viaje,$comentarios,$puntos_usuario,$importe,$distancia){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $fin = finaliza_viaje_operador($id_viaje,$comentarios,$importe,$distancia);
      if ($fin){
        $id_cliente = dame_id_usuario($id_viaje);
        if ($id_cliente > -1){
          califica_usuario($id_cliente,$puntos_usuario);
          $forma = forma_pago($id_viaje);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'U');
          envia_push('Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago $forma.@'.$importe.'@'.$forma,$push_token);
          $idx = 0;
          $msg = 'Viaje finalizado';
        }
          
      } else {
        $idx = -2;
        $msg = 'No fue posible finalizar el viaje, intente mas tarde'.$fin;
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function tengo_viaje_asignado($id_usuario){
    $res = 0;
    $sql = "SELECT A.ID_VIAJE
            FROM VIAJES_ASIGNACIONES A
              INNER JOIN ADMIN_VIAJES B ON B.ID_VIAJES = A.ID_VIAJE
            WHERE B.SRV_ESTATUS_ID_SRV_ESTATUS = 1 AND
                  A.ACEPTO IS NULL AND
                  A.ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (strlen($row->ID_VIAJE) > 0){
        $res = $row->ID_VIAJE;  
      }
      mysql_free_result($qry);
    }        
    return $res; 
  }

  function TengoViaje($id_usuario){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $idx = tengo_viaje_asignado($id_usuario);
      $msg = "No hay viajes";
      if ($idx > 0 ){
        $msg = "Tiene un viaje asignado";  
      }
      mysql_close($con);
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>'.$idx.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function estatus_mi_viaje($id_viaje){
    $res = '<code>0</code>
            <msg>No hay información sobre su viaje</msg>';
    $sql = "SELECT A.SRV_ESTATUS_ID_SRV_ESTATUS AS ID_ESTATUS, 
                   IF(A.ID_TAXISTA IS NULL, 0,ID_TAXISTA) AS TAXISTA, 
                   B.ESTATUS,
                   A.CLAVE_VIAJE,
                   A.MONTO_TAXISTA,
                   A.ID_FORMA_PAGO
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_ESTATUS B ON A.SRV_ESTATUS_ID_SRV_ESTATUS = B.ID_ADMIN_ESTATUS
            WHERE A.ID_VIAJES =  ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
        //ESTA EN ESPERA
        if ($row->ID_ESTATUS == 2){
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$row->CLAVE_VIAJE.'</msg>';
        } elseif ($row->ID_ESTATUS == 3) {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$row->MONTO_TAXISTA.'@'.$row->ID_FORMA_PAGO.'</msg>'; 
        } elseif ($row->ID_ESTATUS == 4) {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@por que si</msg>'; 
        } else {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'</msg>'; 
        }
      mysql_free_result($qry);
    }        
    return $res.mysql_error(); 
  }

  function EstatusMiViaje($id_viaje,$id_usuario){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-10</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("192.168.6.106","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = estatus_mi_viaje($id_viaje);
      mysql_close($con); 
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     '.$res.'
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function RazonesCancelacion($app){
    if ($app == 'T'){
      $dat = '<razones>
                <razon>
                  <id>1</id>
                  <descripcion>Falla mécania</descripcion>
                </razon>
                <razon>
                  <id>2</id>
                  <descripcion>Ponchadura</descripcion>
                </razon>
                <razon>
                  <id>3</id>
                  <descripcion>Siniestro</descripcion>
                </razon>
                <razon>
                  <id>4</id>
                  <descripcion>Manifestación</descripcion>
                </razon>
                <razon>
                  <id>5</id>
                  <descripcion>Congestionamiento vial</descripcion>
                </razon>
              </razones>';
    } else {
      $dat = '<razones>
                <razon>
                  <id>6</id>
                  <descripcion>Prueba</descripcion>
                </razon>
                <razon>
                  <id>7</id>
                  <descripcion>Excedio el tiempo de arribo</descripcion>
                </razon>
                <razon>
                  <id>8</id>
                  <descripcion>No llego</descripcion>
                </razon>
                <razon>
                  <id>9</id>
                  <descripcion>Use otro servicio</descripcion>
                </razon>
              </razones>';
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     <code>2</code>
                     <msg>OK</msg>
                   </Status>
                   '.$dat.'
                  </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

$server->service($HTTP_RAW_POST_DATA); 
?>  
