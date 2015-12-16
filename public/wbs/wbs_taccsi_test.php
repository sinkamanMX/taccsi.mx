<?php
  set_time_limit(60000);
  require_once('./lib/nusoap.php'); 
  include('funciones_push.php');
  $debug_ = true;
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
                          'error'      => 'xsd:string',
                          'so'         => 'xsd:string'), // Parametros de entrada 
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
                          'id_conductor' => 'xsd:string',
                          'so'           => 'xsd:string',
                          'referencias'  => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL); 
  /*Metodo para login*/
  $server->register('Login', // Nombre de la funcion 
                    array('usuario'     => 'xsd:string',
                          'password'    => 'xsd:string',
                          'tipo'        => 'xsd:string',
                          'dispositivo' => 'xsd:string',
                          'push_token'  => 'xsd:string',
                          'so'         => 'xsd:string'), // <--- U = Cliente , T = Taxista 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL); 


 $server->register('Actualiza_user', // Nombre de la funcion 
                    array('usuario'     => 'xsd:string',
                          'password'    => 'xsd:string',
                          'tipo'        => 'xsd:string',// <--- U = Cliente , T = Taxista 
                          'dispositivo' => 'xsd:string',
                          'push_token'  => 'xsd:string',
                          'nombre'  => 'xsd:string',
                          'Apellidop '  => 'xsd:string',
                          'Apellidom'  => 'xsd:string',
                          'telefono'  => 'xsd:string',
                          'correo'  => 'xsd:string',
                          'contrasena'  => 'xsd:string',
                          'so'         => 'xsd:string'), 
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
                          'clave_empresa' => 'xsd:string',
                          'so'            => 'xsd:string',
                          'Placas'        => 'xsd:string',
                          'Eco'           => 'xsd:string',
                          'Modelo'        => 'xsd:string',
                          'Anio'            => 'xsd:string' ), // Parametros de entrada 
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
                          'push_token'  => 'xsd:string',
                          'so'          => 'xsd:string'),
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

   $server->register('DatosTaccsi', 
                    array('id_usuario'  => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);

   $server->register('Actualiza_tacssi', // Nombre de la funcion 
                     array('Placas'     => 'xsd:string',
                          'Eco'        => 'xsd:string',
                          'Modelo '    => 'xsd:string',
                          'Anio'       => 'xsd:string'), 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL); 

   $server->register('Dame_marca_modelo',
                      array('id_usuario'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('historico_usuario',
                      array('id_usuario'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);
 $server->register('opr_avisa_arribo',
                      array('id_viaje'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

/****************/
function InsertaLog($funcion,$descripcion,$push_token = ""){

  $debug_ = true;
  if($debug_==true){
    $idx = -1;
    $msg = 'Eror al conecctarse con el servicio';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $sql = 'INSERT INTO PROD_SOLICITUDES_LOG SET
                FUNCION      = "'.$funcion.'",
                DESCRIPCION  = "'.$descripcion.'",
                PUSH_TOKEN   = "'.$push_token.'",
                FECHA_CREADO = CURRENT_TIMESTAMP';

      $qry = mysql_query($sql);
    }
  }
}
 /**************/

  function Dame_marca_modelo(){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){

      $base = mysql_select_db("taccsi",$con);

      $sql = "SELECT ADMIN_MARCA.DESCRIPCION AS MARCA,
                     ADMIN_MODELO.DESCRIPCION AS MODELO,
                     ADMIN_MARCA.ID_MARCA,
                     ADMIN_MODELO.ID_MODELO
              FROM ADMIN_MARCA
              INNER JOIN ADMIN_MODELO ON ADMIN_MODELO.ID_MARCA=ADMIN_MARCA.ID_MARCA
              ORDER BY ADMIN_MARCA.DESCRIPCION, ADMIN_MODELO.DESCRIPCION";
    
      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $row = mysql_fetch_object($qry);
          $dat.='<marca_modelo>';
          while ($row = mysql_fetch_object($qry)){
              $dat.= '<modelos>
                        <marca>'.$row->MARCA.'</marca>
                        <modelo>'.$row->MODELO.'</modelo>
                        <id_modelo>'.$row->ID_MODELO.'</id_modelo>
                      </modelos>';
          }
          $dat.='</marca_modelo>';
        }else{
          $dat="";
        }
        mysql_free_result($qry);
      }

      if (strlen($dat) > 0){
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'No se pudo obtener la información';  
      }


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

  function opr_avisa_arribo($id_viaje){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $push=dame_pushtoken_viaje($id_viaje,'U');
      $so = dame_so_pushtoken($push);
      $str_inser_log = "id_viaje =".$id_viaje;
      InsertaLog("opr_avisa_arribo",$str_inser_log,$push_token);

      envia_push('dev','usuario','Tu TACCSI ha llegado',$push,$so,'Tu TACCSI ha llegado');
      $idx = 0;
      $msg = 'OK';

    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
                 <space>
                   <Response> 
                     <Status>
                       <code>'.$idx.'</code>
                       <msg>'.$msg.' '.$push.' '.$id_viaje.'</msg>
                     </Status>
                   </Response>
                 </space>';

    return new soapval('return', 'xsd:string', $res);
  } 

Function historico_usuario($id_usuario){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){

      $base = mysql_select_db("taccsi",$con);

      $sql = "SELECT ADMIN_VIAJES.ID_VIAJES,
                   CONCAT(ADMIN_USUARIOS.NOMBRE,' ',ADMIN_USUARIOS.APATERNO,' ' ,ADMIN_USUARIOS.AMATERNO) AS TACCSISTA,
                   ADMIN_VIAJES.FECHA_VIAJE,
                   ADMIN_MODELO.DESCRIPCION AS VEHICULO,
                   ADMIN_VIAJES.MONTO_TOTAL,
                   ADMIN_VIAJES.ORIGEN,
                   ADMIN_VIAJES.ORIGEN_LATITUD,
                   ADMIN_VIAJES.ORIGEN_LONGITUD,
                   ADMIN_VIAJES.DESTINO,
                   ADMIN_VIAJES.DESTINO_LATITUD,
                   ADMIN_VIAJES.DESTINO_LONGITUD,
                   ADMIN_VIAJES.RATING
            FROM ADMIN_VIAJES
            INNER JOIN ADMIN_USUARIOS ON
                       ADMIN_USUARIOS.ID_USUARIO = ADMIN_VIAJES.ID_TAXISTA
            INNER JOIN ADMIN_TAXIS ON
                       ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO= ADMIN_VIAJES.ID_TAXISTA
            INNER JOIN ADMIN_MODELO ON
                       ADMIN_MODELO.ID_MODELO=ADMIN_TAXIS.ID_MODELO
            WHERE ADMIN_VIAJES.ID_CLIENTE=".$id_usuario." AND ADMIN_VIAJES.ID_SRV_ESTATUS=3 AND
                  EXTRACT(MONTH FROM ADMIN_VIAJES.FECHA_VIAJE) = EXTRACT(MONTH FROM CURRENT_DATE) 
            ORDER BY ADMIN_VIAJES.ID_VIAJES DESC";
    
     

      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat.='<historico>';
          while ($row = mysql_fetch_object($qry)){
               $dat.='entro3';
              $dat.= '<viaje>
                        <id_viaje>'.$row->ID_VIAJES.'</id_viaje>
                        <taccsista>'.$row->TACCSISTA.'</taccsista>
                        <fecha>'.$row->FECHA_VIAJE.'</fecha>
                        <vehiculo>'.$row->VEHICULO.'</vehiculo>
                        <monto>'.$row->MONTO_TOTAL.'</monto>
                        <origen>'.$row->ORIGEN.'</origen>
                        <lat_origen>'.$row->ORIGEN_LATITUD.'</lat_origen>
                        <lon_origen>'.$row->ORIGEN_LONGITUD.'</lon_origen>
                        <destino>'.$row->DESTINO.'</destino>
                        <lat_destino>'.$row->DESTINO_LATITUD.'</lat_destino>
                        <lon_destino>'.$row->DESTINO_LONGITUD.'</lon_destino>
                        <puntos>'.$row->RATING.'</puntos>
                      </viaje>';
          }
          $dat.='</historico>';
        }else{
          $dat="";
        }
        mysql_free_result($qry);
      }

      if (strlen($dat) > 0){
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'No se pudo obtener la información';  
      }


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

  function registra_taxista($nombre,$apaterno,$amaterno,$movil,$email,$password,$taxi_propio,$asociacion,$identificador,$id_empresa){
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
              ".$id_empresa.",
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


function registra_taxis($empresa,$modelo,$id_usuario,$chofer,$placas,$eco,$anio){
    global $base;
    //si viene una clave de emprea debe buscarla
    //generar una funcion para contraseñas dinamicas
     $res = -1;
    $sql = "INSERT INTO ADMIN_TAXIS(
              ID_EMPRESA,
              ID_MODELO,
              ID_ESTATUS_TAXI,
              ADMIN_USUARIOS_ID_USUARIO,
              NOMBRE_CHOFER,
              PLACAS,
              ECO,
              ANIO,
              FECHA_REGISTRO
            ) VALUES (
              ".$empresa.",
              ".$modelo.",
              1,
              ".$id_usuario.",
              '".$chofer."',
              '".$placas."',
              '".$eco."',
              ".$anio.",
              CURRENT_TIMESTAMP
              )";
    if (mysql_query($sql)){
      
      $sql2 = "SELECT ADMIN_USUARIOS_ID_USUARIO
            FROM ADMIN_TAXIS
            WHERE ADMIN_USUARIOS_ID_USUARIO = ".id_usuario;
      if ($qry2 = mysql_query($sql2)){
        $row = mysql_fetch_object($qry2);
        $res = $row->ADMIN_USUARIOS_ID_USUARIO;
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
              ID_USUARIO,
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
                              $clave_empresa,
                              $so,
                              $Placas,
                              $Eco,
                              $Modelo,
                              $Anio ){
    /*validar que el correo no este registrado*/
    /*validar que los correos coincidan*/
    /*validar que el telefono no exista*/
    /*registrar el usuario*/
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $dat = '';
    $id_empresa=-1;
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
        $base = mysql_select_db("taccsi",$con);
        if (valida_correo($email,'taxista')){
          if (valida_telefono($movil,'taxista')){
            $id_empresa=dame_id_empresa($clave_empresa);

            if($id_empresa==0 Or strlen($id_empresa)==0){
                $id_empresa='null';
            }

            $id = registra_taxista($nombre,$apaterno,$amaterno,$movil,$email,$password,$taxi_propio,$asociacion,$identificador,$id_empresa);
            if ($id > 0){
                
                
                $chofer=$nombre.' '.$apaterno.' '.$amaterno;
                registra_taxis($id_empresa,$Modelo,$id,$chofer,$Placas,$Eco,$Anio);


                $dat = dame_usuario($email,$password,'T');

                //registra_token($identificador,$push_token,$so);
                $msg = 'OK';  
                $idx = $id;
            }else{
              $msg = "Error al registrar el taxista";  
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


  function RecibePosicion ($id_usuario,$equipo, $push_token,$latitud, $longitud, $velocidad, $altitud, $angulo, $fecha, $proveedor,$error,$so){
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = registra_token($equipo,$push_token,$so);
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
    $sql = "SELECT B.PUSH_TOKEN,COUNT(1) AS EXISTE
            FROM ADMIN_VIAJES A
            LEFT OUTER JOIN DISP_REGISTRO_TOKENS B ON B.DEVICE_ID = A.DISPOSITIVO_ORIGEN
            WHERE A.ID_VIAJES = ".$idViaje." AND
                  A.CLAVE_VIAJE = ".$clave;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (strlen($row->PUSH_TOKEN) > 0){
        $res = $row->PUSH_TOKEN;  
      }else{
        if($row-> EXISTE > 0){
           $res = "EXISTE";  
        }
      }
      mysql_free_result($qry);
    }/*else{
    //$res = mysql_errno() . ": " . mysql_error() . "\n";
    }*/
    return $res;
  }

  function marca_inicio($idViaje){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES
            SET ID_SRV_ESTATUS = 5
            WHERE ID_VIAJES = ".$idViaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function oprInicioViaje($id_usuario,$idViaje,$clave){
    /*avisa que el taccsista ya inicio el viaje*/ 
    $idx = -1;
    global $base;
    $msg = 'Usuario no registrado';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //valida que la clave corresponda al viaje
      $push_token = dame_push_viaje($idViaje,$clave);
      $str_inser_log = "id_usuario =".$id_usuario.", idViaje = ".$idViaje.", clave = ".$clave;
      InsertaLog("oprInicioViaje",$str_inser_log,$push_token);

      if($idViaje>0){
        if (strlen($push_token) > 0){
          if (marca_inicio($idViaje)){

            if (strlen($push_token) > 1 && $push_token!="EXISTE" ){
             $so = dame_so_pushtoken($push_token);
             //envia_push_dev('Su viaje ha iniciado',$push_token,$so);
             envia_push('dev','usuario','Su viaje ha iniciado.@'.$idViaje,$push_token,$so,'Inicio de viaje');
            }
          //envia_push('Su viaje ha iniciado.',$push_token);
            $idx = 0;
            $msg = 'OK';
          }  
        } else {
          $idx = -2;
          $msg = 'Clave de viaje incorrecta';  
        }
      }else{
        $idx = -1;
        $msg = 'No se pudo recuperar la información del viaje';
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
    $sql = "SELECT ID_SRV_ESTATUS
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ID_SRV_ESTATUS;  
      mysql_free_result($qry);
    }
    return $false;
  }

  function cancelar_viaje($idViaje,$app,$razon){
    $res = false;
    if ($app == 'T'){
      $sql = "UPDATE ADMIN_VIAJES
              SET ID_SRV_ESTATUS = 4,
                  ID_RAZON_CANCELACION=".$razon."
              WHERE ID_VIAJES = ".$idViaje;
    } else {
      $sql = "UPDATE ADMIN_VIAJES
              SET ID_SRV_ESTATUS = 8,
                  ID_RAZON_CANCELACION=".$razon."
              WHERE ID_VIAJES = ".$idViaje;   
    }
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function dame_so_pushtoken($push){
    $res = '';
    //PUSH_TOKEN
      $sql = "SELECT SO
              FROM DISP_REGISTRO_TOKENS 
              WHERE PUSH_TOKEN = '".$push."'";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->SO;  
      mysql_free_result($qry);
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $estatus = valida_viaje($id_viaje);
      if ($estatus == 3){
        $idx = -2;
        $msg = 'El viaje ya fue finalizado previamente.';
      } else {
        $push_token = dame_pushtoken_viaje($id_viaje,$app);
        //if (strlen($push_token) > 0){
          if (cancelar_viaje($id_viaje, strtoupper($app),$id_razon)){
            
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

            if ($app == 'U'){
              //SI LO CANCELA EL USUARIO
              //OBTIENE EL PUSH TOKEN DEL TAXISTA
              $push_token = dame_pushtoken_viaje($id_viaje,'T');
              if (strlen($push_token) > 0){
                $so = dame_so_pushtoken($push_token);
                //envia_push_dev_taccista('El cliente ha cancelado el viaje.@'.$id_viaje.'@'.$msg,$push_token,$so);
                envia_push('dev','taxi','El cliente ha cancelado el viaje.@'.$id_viaje.'@'.$msg,$push_token,$so,'');
              }
            } else {
              //SI LO CANCELA EL TAXISTA
              //OBTIENE EL PUSH TOKEN DEL USUARIO
              $push_token = dame_pushtoken_viaje($id_viaje,'U');
              if (strlen($push_token) > 0){
                $so = dame_so_pushtoken($push_token);
                envia_push('dev','usuario','El TACCSISTA ha cancelado el viaje.@'.$id_viaje.'@'.$msg,$push_token,$so,'El TACCSISTA ha cancelado el viaje');
              }
            }
            $idx = 1;
            $msg = 'Su viaje ha sido cancelado.';
          } else {
            $idx = -2;
            $msg = 'No fue posible registrar su cancelación. Intente mas tarde.';  
          }
        //} else {
          //$idx = -3;
          //$msg = 'No es posible notificar su cancelación. Intente mas tarde.'.$id_viaje.' '.$id_usuario.' '.$app; 
        //}
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
            SET ID_SRV_ESTATUS = 3,
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
                RATING = IF(RATING IS NULL,".$puntos_taxista.",(RATING + ".$puntos_taxista."))
            WHERE ID_USUARIO = ".$id_taxista;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }

  function usrFinViaje($id_usuario,$id_viaje,$comentarios, $puntos_servicio, $puntos_taxista, $importe,$distancia,$so){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (finaliza_viaje($id_viaje,$comentarios,$puntos_servicio,$importe,$distancia)){
        $id_taxista = dame_id_taxista($id_viaje);
        if ($id_taxista > -1){
          califica_taxista($id_taxista,$puntos_taxista);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'T');

          $str_inser_log = "id_usuario =".$id_usuario.", id_viaje = ".$id_viaje.", comentarios = ".$comentarios.", puntos_servicio = ".$puntos_servicio.", puntos_taxista = ".$puntos_taxista.", importe = ".$importe.", distancia = ".$distancia.", so = ".$so;
          InsertaLog("usrFinViaje",$str_inser_log,$push_token);

          if (strlen($push_token) > 1){
            $so = dame_so_pushtoken($push_token);
            //envia_push_dev_taccista('El viaje ha sido finalizado por el cliente',$push_token,$so);
             envia_push('dev',
                       'taxista',
                       'El cliente ha finalizado el viaje.@'.$id_viaje.'@',
                       $push_token,
                       $so,
                       '');
          }
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);

      $str_inser_log = "usuario =".$usuario.", password = ".$password.", idViaje = ".$idViaje;
      InsertaLog("usrInicioViaje",$str_inser_log,$push_token);

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
    $foto= "";
    global $base;
    if ($tipo == 'U'){
      $sql = "SELECT ID_SRV_USUARIO AS ID_USUARIO,
                   NOMBRE,
                   APATERNO ,
                   AMATERNO,
                   TELEFONO,
                   IMAGEN as FOTO,
                   USUARIO as EMAIL
            FROM SRV_USUARIOS
            WHERE USUARIO = '".$usuario."' AND
             PASSWORD = '".$password."'";

    } else {
      $sql = "SELECT ID_USUARIO,
                     NOMBRE,
                     APATERNO,
                     AMATERNO,
                     TELEFONO,
                     FOTO,
                     NICKNAME AS EMAIL
              FROM ADMIN_USUARIOS
              WHERE NICKNAME = '".$usuario."' AND
                    PASSWORD_TEXT = '".$password."'";
    }



    if ($qry = mysql_query($sql)){
      if (mysql_num_rows($qry) > 0){
        $row = mysql_fetch_object($qry);
        if($row->FOTO=="" or $row->FOTO=="null"){
            $foto="http://taccsi.com/images/taxis/no_disponible.jpg";
        }else{
            $foto="http://taccsi.com/images/taxis/".$row->FOTO;
        }
        $res = '<usuario>
                  <id>'.$row->ID_USUARIO.'</id>
                  <foto_perfil>'.$foto.'</foto_perfil>
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




  function update_datos_usuario($usuario,$password,$tipo,$dispositivo,$push_token,$nombre,$Apellidop,$Apellidom,$telefono,$correo,$contrasena){
    $res = false;  
    global $base;
    if ($tipo == 'U'){
      $sql = "UPDATE SRV_USUARIOS
              SET DISPOSITIVO = '".$dispositivo."',
                  NOMBRE='".$nombre."',
                  APATERNO='".$Apellidop."',
                  AMATERNO='".$Apellidom."',
                  TELEFONO='".$telefono."',
                  EMAIL='".$correo."',
                  PASSWORD='".$contrasena."'
              WHERE USUARIO = '".$usuario."' AND
              PASSWORD='".$password."'";
    } else {
      //$dispositivo = '357957050119619';
      $sql = "UPDATE ADMIN_USUARIOS
              SET DISPOSITIVO = '".$dispositivo."',
                  NOMBRE='".$nombre."',
                  APATERNO='".$Apellidop."',
                  AMATERNO='".$Apellidom."',
                  TELEFONO='".$telefono."',
                  NICKNAME='".$correo."',
                  PASSWORD_TEXT='".$contrasena."',
                  PASSWORD=sha1('".$contrasena."')
              WHERE NICKNAME = '".$usuario."' AND
                    PASSWORD_TEXT = '".$password."'";
    }
    if ($qry = mysql_query($sql)){
        $res = true;
    }
    return $res;
  }




   function update_datos_taccsi($idusuario,$Placas,$Eco,$Modelo,$Anio){
    $res = false;  
    global $base;
  
    $sql = "UPDATE ADMIN_TAXIS
            SET PLACAS = '".$Placas."',
                  ECO='".$Eco."',
                  ID_MODELO=".$Modelo.",
                  ANIO=".$Anio."
             WHERE ADMIN_USUARIOS_ID_USUARIO= ".$idusuario;

    if ($qry = mysql_query($sql)){
        $res = true;
    }
    return $res;
  }


  function Login($usuario,$password,$tipo,$dispositivo,$push_token,$so){
    $idx = -1;
    $msg = 'Usuario no registrado';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_usuario($usuario,$password,$tipo);
      if (strlen($dat) > 0){
        update_dispositivo_usuario($usuario,$password,$tipo,$dispositivo);
        registra_token($dispositivo,$push_token,$so);
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




  function Actualiza_user($usuario,$password,$tipo,$dispositivo,$push_token,$nombre,$Apellidop,$Apellidom,$telefono,$correo,$contrasena,$so){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){

      $base = mysql_select_db("taccsi",$con);
      $dat = dame_usuario($usuario,$password,$tipo);
      if (strlen($dat) > 0){
        update_datos_usuario($usuario,$password,$tipo,$dispositivo,$push_token,$nombre,$Apellidop,$Apellidom,$telefono,$correo,$contrasena);
        registra_token($dispositivo,$push_token,$so);
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'Usuario o Password incorrecto';  
      }


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



  function Actualiza_tacssi($idusuario,$Placas,$Eco,$Modelo,$Anio){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if($con){

      $base = mysql_select_db("taccsi",$con);
      if(update_datos_taccsi($idusuario,$Placas,$Eco,$Modelo,$Anio)){
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'Error al actualiza la información, intentalo más tarde';  
      }


    }else {
        $idx = -1;
        $msg = 'Servicio no disponible';  
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
                         $descuento,
                         $referencias){
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
              ID_CLIENTE,
              DISPOSITIVO_ORIGEN,
              ID_SRV_ESTATUS,
              ORIGEN_REFERENCIAS
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
              1,
              '".$referencias."')";
    
    if ($qry = mysql_query($sql)){
      $res = true;
    } 
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
                   C.DESCRIPCION AS FORMA_PAGO,
                   B.IMAGEN AS FOTO,
                   A.ORIGEN_REFERENCIAS
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_USUARIOS B ON A.ID_CLIENTE = B.ID_SRV_USUARIO 
              INNER JOIN ADMIN_FORMA_PAGO C ON A.ID_FORMA_PAGO = C.ID_FORMA_PAGO
            WHERE  A.ID_SRV_ESTATUS NOT IN (-1,2,3,4,5) AND
                   ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (mysql_num_rows($qry) > 0){


        if($row->FOTO=="" or $row->FOTO=="null"){
           $foto_="http://taccsi.com/images/taxis/no_disponible.jpg";
        }else{
           $foto_="http://taccsi.com/images/taxis/".$row->FOTO;
        }

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
               <foto>".$foto_."</foto>
               <referencias>".$row->ORIGEN_REFERENCIAS."</referencias>
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

  function registra_token($dispositivo,$push_token,$so){
    $res = false;
    global $base; 
    if (existe_token($dispositivo)){
      $sql = "UPDATE DISP_REGISTRO_TOKENS 
            SET PUSH_TOKEN = '".$push_token."', 
                ULTIMO_REGISTRO = CURRENT_TIMESTAMP,
                SO = '".$so."'
            WHERE DEVICE_ID ='".$dispositivo."'";
    } else {
      $sql = "INSERT INTO DISP_REGISTRO_TOKENS (
                PUSH_TOKEN,
                DEVICE_ID,
                ULTIMO_REGISTRO,
                SO
              ) VALUES (
                '".$push_token."',
                '".$dispositivo."',
                CURRENT_TIMESTAMP,
                '".$so."'
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
    $sql = "SELECT A.ID_USUARIO, 
                   C.PUSH_TOKEN, 
                   C.SO,
                   ROUND(DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") ,2) AS DIST
            FROM ADMIN_USUARIOS A
              INNER JOIN DISP_ULTIMA_POSICION B ON B.IDENTIFICADOR = A.DISPOSITIVO
              INNER JOIN DISP_REGISTRO_TOKENS C ON DEVICE_ID = B.IDENTIFICADOR

            WHERE A.DISPONIBLE = 0 AND
                  DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") < 400 AND
                  CAST(B.FECHA_GPS AS DATE)=CURRENT_DATE
            ORDER BY DIST
            LIMIT 3";
    if ($qry = mysql_query($sql)){
      while($row = mysql_fetch_object($qry)){
        if ($row->ID_USUARIO > 0){
          
          //$mensaje = "Se le ha asignado un viaje.@".$id_viaje; 
          //$pushkey_taccsista = dame_pushkey_taccsista($row->ID_USUARIO);
          registra_asignacion($id_viaje,$row->ID_USUARIO);
          //if (strlen($pushkey_taccsista) > 0){
            //$res = envia_push($mensaje,$row->PUSH_TOKEN);
            //$so = dame_so_pushtoken($push_token);
            //envia_push_dev_taccista($mensaje,$row->PUSH_TOKEN,$row->SO);
          //}
          envia_push('dev','taxi',"Se le ha asignado un viaje.@".$id_viaje,$row->PUSH_TOKEN,$row->SO,'');
          sleep(2);
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
                         $id_conductor,
                         $so,
                         $referencias){

    $con = mysql_connect("localhost","dba","t3cnod8A!");

    $str_inser_log = "usuario =".$usuario.
                      ", dispositivo = ".$dispositivo.
                      ", push_token = ".$push_token.
                      ", origen = ".$origen.
                      ", destino = ".$destino.
                      ", lat_origen = ".$lat_origen.
                      ", lon_origen = ".$lon_origen.
                      ", lat_destino = ".$lat_destino.
                      ", lon_destino = ".$lon_destino.
                      ", personas = ".$personas.
                      ", pago = ".$pago.
                      ", descuento = ".$descuento.
                      ", id_conductor = ".$id_conductor.
                      ", so = ".$so.
                      ", referencias = ".$referencias;

    InsertaLog("usrNuevoViaje",$str_inser_log,$push_token);

    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $id_usuario = valida_usuario($usuario);
      if ($id_usuario > 0){
        $id_viaje = 0;
        registra_token($dispositivo,$push_token,$so);
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
                         $descuento,
                         $referencias)){
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
            //$res = envia_push($mensaje,$dispositivo);*/
            //manda la notifiacion  a los  mas cercanos al origen
            manda_viaje_taxistas($id_viaje,$lat_origen,$lon_origen);
          } else {
            //obtiene el push key del id del taxista
            $pushkey_taccsista = dame_pushkey_taccsista($id_conductor);
            registra_asignacion($id_viaje,$id_conductor);
            //$res = envia_push($mensaje,$pushkey_taccsista);
             // manda la notifación al mas cercano
            if (strlen($pushkey_taccsista) > 0){
              $so = dame_so_pushtoken($pushkey_taccsista);
              envia_push('dev','taxi',$mensaje,$pushkey_taccsista,$so,'');
              //envia_push_dev_taccista('Se le ha asignado un nuevo viaje. ¿Desea atenderlo?',$pushkey_taccsista,$so);
            }
          }
          //$clave = rand(1,99999);
          //$idx = $clave;

          $idx = $id_viaje;
          $msg = 'Su viaje ha sido registrado.'.$res;
        } else {
          $idx = -2;
          $msg = 'No fue posible asignar su viaje, intente mas tarde '.$msgq.' '.$id_viaje;
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
    $sql = "SELECT ID_SRV_ESTATUS
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->ID_SRV_ESTATUS == 1){
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
                ID_SRV_ESTATUS = 2 
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
    //$x = marca_viaje($id_usuario,$id_viaje,$codigo);
    if (marca_viaje($id_usuario,$id_viaje,$codigo)){ 
      $res = true;
    } 
    return $res;
  }



  function oprTomarViaje ($id_usuario,$id_viaje,$dispositivo){
    /*Funccion para asignar el viaje a un taxista, cuando ya fue confirmado*/
    $idx = -1;
    $msg = '';
    $con = mysql_connect("localhost","dba","t3cnod8A!");



    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (valida_viaje_libre($id_viaje)){
        $codigo = rand(1,9999);
        //$x = tomar_viaje($id_usuario,$id_viaje,$codigo);
        if (tomar_viaje($id_usuario,$id_viaje,$codigo)){
          $token = dame_pushtoken_cliente($id_viaje);
          marca_tomar_viaje($id_viaje,$id_usuario);
          if (strlen($token) > 0){
            $so = dame_so_pushtoken($token);
            //envia_push_dev('Su TACCSI va en camino, Código de confirmación: '.$codigo.' .@'.$id_usuario,$pushkey_taccsista,$so);

            $str_inser_log = "id_usuario =".$id_usuario.
                      ", id_viaje = ".$id_viaje.
                      ", dispositivo = ".$dispositivo.
                      ", token_cliente = ".$token;

            InsertaLog("oprTomarViaje",$str_inser_log,$push_token);


            envia_push('dev','usuario','Su TACCSI va en camino, Confirmación: '.$codigo.' @'.$id_usuario,$token,$so,'Su TACCSI va en camino, Confirmación: '.$codigo);
            
          }
          $idx = 0;
          $msg = 'Su viaje ha sido asignado.';
        } else {
          $idx = -3;
          $msg = 'No fue posible asignar el viaje.'.$x;   
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
                   MA.DESCRIPCION AS MARCA,
                   MO.DESCRIPCION AS MODELO,
                   B.PLACAS,
                   B.ECO,
                   B.IMAGEN AS FOTO_TAXI,
                   C.LATITUD,
                   C.LONGITUD,
                   C.FECHA_SERVER
            FROM ADMIN_USUARIOS A,
                 ADMIN_TAXIS B, 
                 DISP_ULTIMA_POSICION C,
                 ADMIN_MARCA MA,
                 ADMIN_MODELO MO
            WHERE A.ID_USUARIO =  ".$id_taxista." AND
                  MO.ID_MODELO =B.ID_MODELO AND 
                  MA.ID_MARCA= MO.ID_MARCA AND
                  B.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO AND
                  A.ID_USUARIO = C.ID_USUARIO";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $foto_taxista="";
      $foto_taxi="";

      if($row->FOTO_TAXISTA=="" or $row->FOTO_TAXISTA=="null"){
          $foto_taxista="http://taccsi.com/images/taxis/no_disponible.jpg";
      }else{
          $foto_taxista="http://taccsi.com/images/taxis/".$row->FOTO_TAXISTA;
      }

      if($row->FOTO_TAXI=="" or $row->FOTO_TAXI=="null"){
          $foto_taxi="http://taccsi.com/images/taxis/no_disponible.jpg";
      }else{
          $foto_taxi="http://taccsi.com/images/taxis/".$row->FOTO_TAXI;
      }
      $res = "<taxista>
                <foto_taxista>".$foto_taxista."</foto_taxista>
                <nombre>".$row->NOMBRE."</nombre>
                <apaterno>".$row->APATERNO."</apaterno>
                <amaterno>".$row->AMATERNO."</amaterno>
                <telefono>".$row->TELEFONO."</telefono>
                <marca>".$row->MARCA."</marca>
                <modelo>".$row->MODELO."</modelo>
                <placas>".$row->PLACAS."</placas>
                <eco>".$row->ECO."</eco>
                <foto_taxi>".$foto_taxi."</foto_taxi>
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_viaje($id_viaje);

        $str_inser_log = "id_viaje =".$id_viaje.
                      ", dat = ".$dat;

        InsertaLog("oprTomarViaje",$str_inser_log,$push_token);

      if (strlen($dat) > 0){
        $idx = 0;
        $msg = 'OK';

        $str_inser_log = "id_viaje =".$id_viaje.
                      ", dat = ".$dat;

        InsertaLog("oprDameViaje",$str_inser_log,$push_token);

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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_taxista($id_taxista,$id_viaje);

      $str_inser_log = "id_usuario =".$id_usuario.
                      ", id_taxista = ".$id_taxista.
                      ", id_viaje = ".$id_viaje.
                      ", dat = ".$dat;


      InsertaLog("usrDameInfoTaxista",$str_inser_log,$push_token);




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
            SET ID_SRV_ESTATUS = 7 
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
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

          $str_inser_log = "id_viaje =".$id_viaje.
                      ", id_usuario = ".$id_usuario;


      InsertaLog("oprRechazarViaje",$str_inser_log,$token);


          if (strlen($token) > 0){
            $so = dame_so_pushtoken($token);
            envia_push('dev',
                       'usuario',
                       'No hay Taccsistas disponibles intente mas tarde.@'.$id_viaje.'@',
                       $token,
                       $so,
                       'No hay Taccsistas disponibles intente mas tarde');
          }
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
                <email>".$row->EMAIL."</email>
                <rating>".$row->CALIFICACION."</rating>
                <viajes>".$row->VIAJES."</viajes>
              </usuario>"; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function RegistraUsuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$push_token,$so){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $dat = '';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (valida_correo($email,'usuario')){
        if (valida_telefono($email,'usuario')){
          if (registra_usuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo)){
            registra_token($dispositivo,$push_token,$so);  
            //envia_push('Bienvenido a TACCSI',$push_token);
            //envia_push('dev','usuario','Bienvenido a TACCSI',$push_token,$so);
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
                   B.FOTO AS FOTO_TAXISTA,
                   C.PLACAS,
                   M.DESCRIPCION AS MODELO,
                   'taxi_libre' AS ESTATUS,
                   A.LATITUD,
                   A.LONGITUD,
                   ROUND(DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud."),2) AS DIST,
                   C.IMAGEN,
                   IF(B.RATING IS NULL,0,(B.RATING/VIAJES)) AS RATING,
                   IF(B.VIAJES IS NULL,0,B.VIAJES) AS VIAJES
            FROM DISP_ULTIMA_POSICION A
              INNER JOIN ADMIN_USUARIOS B ON B.ID_USUARIO = A.ID_USUARIO
              INNER JOIN ADMIN_TAXIS C ON C.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO 
              INNER JOIN ADMIN_MODELO M ON M.ID_MODELO = C.ID_MODELO
            WHERE CAST(A.FECHA_SERVER AS DATE) = CURRENT_DATE AND
                  B.DISPONIBLE = 0 AND 
                  DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud.") < 400
            ORDER BY DIST
            LIMIT 10";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){

        if($row->FOTO_TAXISTA=="" or $row->FOTO_TAXISTA=="null"){
           $foto_taxista="http://taccsi.com/images/taxis/no_disponible.jpg";
        }else{
           $foto_taxista="http://taccsi.com/images/taxis/".$row->FOTO_TAXISTA;
        }

        if($row->IMAGEN=="" or $row->IMAGEN=="null"){
           $res = $res."<taxi>
                  <id>".$row->ID_USUARIO."</id>
                  <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                  <placas>".$row->PLACAS."</placas>
                  <modelo>".$row->MODELO."</modelo>
                  <estatus>".$row->ESTATUS."</estatus>
                  <latitud>".$row->LATITUD."</latitud>
                  <longitud>".$row->LONGITUD."</longitud>
                  <distancia>".$row->DIST."</distancia>
                  <foto>"."http://taccsi.com/images/taxis/no_disponible.jpg</foto>
                  <puntos>".$row->RATING."</puntos>
                  <foto_taxista>".$foto_taxista."</foto_taxista>
                  <servicios>".$row->VIAJES."</servicios>
                </taxi>"; 
        }else{
          $res = $res."<taxi>
                  <id>".$row->ID_USUARIO."</id>
                  <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                  <placas>".$row->PLACAS."</placas>
                  <modelo>".$row->MODELO."</modelo>
                  <estatus>".$row->ESTATUS."</estatus>
                  <latitud>".$row->LATITUD."</latitud>
                  <longitud>".$row->LONGITUD."</longitud>
                  <distancia>".$row->DIST."</distancia>
                  <foto>"."http://taccsi.com/images/taxis/".$row->IMAGEN."</foto>
                  <puntos>".$row->RATING."</puntos>
                  <foto_taxista>".$foto_taxista."</foto_taxista>
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
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
                   </taxis>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function finaliza_viaje_operador($id_viaje,$comentarios,$importe,$distancia){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET ID_SRV_ESTATUS = 3,
                FIN_VIAJE_TAXISTA = CURRENT_TIMESTAMP,
                COMENTARIOS_TAXISTA = '".$comentarios."',
                MONTO_TAXISTA = ".$importe.",
                DISTANCIA_TAXISTA = ".$distancia."
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }   
    return $res;  
  }

  function dame_id_usuario($id_viaje){
    $res = -1;
    $sql = "SELECT ID_CLIENTE
            FROM ADMIN_VIAJES
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row-> ID_CLIENTE;  
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      
      $base = mysql_select_db("taccsi",$con);

      $str_inser_log = "id_usuario =".$id_usuario.
                      ", id_viaje = ".$id_viaje.
                      ", comentarios = ".$comentarios.
                      ", puntos_usuario = ".$puntos_usuario.
                      ", importe = ".$importe.
                      ", distancia = ".$distancia;

      InsertaLog("oprFinViaje",$str_inser_log,$token);

      $fin = finaliza_viaje_operador($id_viaje,$comentarios,$importe,$distancia);
      if ($fin){
        $id_cliente = dame_id_usuario($id_viaje);
        if ($id_cliente > -1){
          califica_usuario($id_cliente,$puntos_usuario);
          $forma = forma_pago($id_viaje);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'U');
          if (strlen($push_token) > 0){
            $so = dame_so_pushtoken($push_token);
            //envia_push('Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago $forma.@'.$importe.'@'.$forma,$push_token);
            envia_push('dev',
                       'usuario',
                       'Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago.@'.$importe.'@'.$forma."@".$id_viaje,
                       $push_token,
                       $so,
                       'Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago');
            //envia_push_dev('Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago $forma.@'.$importe.'@'.$forma,$push_token,$so);
          }
          $idx = 0;
          $msg = 'Viaje finalizado';
        }else{
          $idx = -3;
          $msg = 'No se encontro id cliente';
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
            WHERE B.ID_SRV_ESTATUS = 1 AND
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
    $con = mysql_connect("localhost","dba","t3cnod8A!");
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
  

  function DatosTaccsi($id_usuario){
    $idx = -1;
    $msg = 'Servicio no disponible';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat = dame_datos_taccsi($id_usuario);
      if (strlen($dat) > 0){
        $idx = 1;
        $msg = 'OK';
      } else {
        $idx = -1;
        $msg = 'El taccsista no tiene un taccsi asignado';  
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

  function dame_datos_taccsi($usuario){
    $res = '';  
    global $base;
    $sql = "SELECT ADMIN_TAXIS.ECO,
                   ADMIN_MODELO.DESCRIPCION AS MODELO,
                   ADMIN_MARCA.DESCRIPCION AS MARCA,
                   ADMIN_TAXIS.ANIO,
                   ADMIN_TAXIS.PLACAS,
                   ADMIN_TAXIS.IMAGEN
            FROM ADMIN_TAXIS
                 INNER JOIN ADMIN_MODELO ON ADMIN_MODELO.ID_MODELO=ADMIN_TAXIS.ID_MODELO
                 INNER JOIN ADMIN_MARCA ON ADMIN_MARCA.ID_MARCA=ADMIN_MODELO.ID_MARCA
            WHERE ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO= ".$usuario;
    if ($qry = mysql_query($sql)){
      if (mysql_num_rows($qry) > 0){
        $row = mysql_fetch_object($qry);
        $res = '<taccsi>
                  <eco>'.$row->ECO.'</eco>
                  <marca>'.$row->MARCA.'</marca>
                  <modelo>'.$row->MODELO.'</modelo>
                  <anio>'.$row->ANIO.'</anio>
                  <placas>'.$row->PLACAS.'</placas>
                  <img>'.$row->IMAGEN.'</img>
                </taccsi>';
      }
      mysql_free_result($qry);
    }
    return $res;
  }



  function estatus_mi_viaje($id_viaje){
    $res = '<code>0</code>
            <msg>No hay información sobre su viaje</msg>';
    $sql = "SELECT A.ID_SRV_ESTATUS AS ID_ESTATUS, 
                   IF(A.ID_TAXISTA IS NULL, 0,ID_TAXISTA) AS TAXISTA, 
                   B.ESTATUS,
                   A.CLAVE_VIAJE,
                   A.MONTO_TAXISTA,
                   A.ID_FORMA_PAGO,
                   U.LATITUD,
                   U.LONGITUD,
                   A.ID_RAZON_CANCELACION
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_ESTATUS B ON A.ID_SRV_ESTATUS = B.ID_ADMIN_ESTATUS
              LEFT OUTER JOIN DISP_ULTIMA_POSICION U ON U.ID_USUARIO=A.ID_TAXISTA
            WHERE A.ID_VIAJES=".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
        //ESTA EN ESPERA

         $id_razon=$row->ID_RAZON_CANCELACION;

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

        if ($row->ID_ESTATUS == 2){
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$row->CLAVE_VIAJE.'</msg>';
        } elseif ($row->ID_ESTATUS == 3) {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$row->MONTO_TAXISTA.'@'.$row->ID_FORMA_PAGO.'</msg>'; 
        } elseif ($row->ID_ESTATUS == 4) {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$msg .'</msg>'; 
        } else {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'</msg>'; 
        }

        $res=$res.'<ll>'.$row->LATITUD.'@'.$row->LONGITUD.'</ll>';
      mysql_free_result($qry);
    }        
    return $res.mysql_error(); 
  }

  function EstatusMiViaje($id_viaje,$id_usuario){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-10</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
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



  function dame_id_empresa($clave_empresa){
    $res = -1;
    $sql = "SELECT ADMIN_EMPRESAS.ID_EMPRESA
            FROM ADMIN_EMPRESAS
            WHERE ADMIN_EMPRESAS.CODIGO_EMPRESA='".$clave_empresa."'";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ID_EMPRESA;  
      mysql_free_result($qry);
    }        
    return $res;  
  }


  $server->service($HTTP_RAW_POST_DATA); 
?>
