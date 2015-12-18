<?php  
  error_reporting(0);
  set_time_limit(60000);
  require_once('./lib/nusoap.php'); 
  include 'lib/phpmailer.php';
  include('funciones_push.php');
  $debug_ = true;
  $miURL = 'http://201.131.96.45/wbs_taccsi';
  $server = new soap_server(); 
  $server->configureWSDL('wbs_taccsi', $miURL); 
  $server->wsdl->schemaTargetNamespace=$miURL;
  
  $server->register('FijaTarjeta', // Nombreface de la funcion 
                   array('id_tarjeta'  => 'xsd:string',
                         'id_usuario'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('InactivaTarjeta', // Nombre de la funcion 
                   array('id_tarjeta'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('ActivaTarjeta', // Nombre de la funcion 
                   array('id_tarjeta'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('DameTarjetas', // Nombre de la funcion 
                   array('id_usuario'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('RegistraTDC', // Nombre de la funcion 
                   array('id_usuario' => 'xsd:string',
                         'tipo_tdc'  => 'xsd:string',
                         'tdc' => 'xsd:string',
                         'nombre'  => 'xsd:string',
                         'month' => 'xsd:string',
                         'year'  => 'xsd:string',
                         'cod_aut'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('ActualizaTDC', // Nombre de la funcion 
                   array('id_tarjeta' => 'xsd:string',
                         'tipo_tdc'  => 'xsd:string',
                         'tdc' => 'xsd:string',
                         'nombre'  => 'xsd:string',
                         'month' => 'xsd:string',
                         'year'  => 'xsd:string',
                         'cod_aut'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('DameTipoTarjeta', // Nombre de la funcion 
                   array('id_usuario'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

  $server->register('RecuperarPassword', // Nombre de la funcion 
                   array('usName' => 'xsd:string',
                         'llave'  => 'xsd:string'), // Parametros de entrada 
                   array('return' => 'xsd:string'), // Parametros de salida 
                   $miURL);

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
                          'referencias'  => 'xsd:string',
                          'tipo'         => 'xsd:string',
                          'iave'         => 'xsd:string',
                          'ac'           => 'xsd:string',
                          'conectores'   => 'xsd:string',
                          'wifi'         => 'xsd:string',
                          'id_tarjeta'   => 'xsd:string',
                          'servicios'    => 'xsd:string'), // Parametros de entrada 
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
                          'importe'         => 'xsd:string',
                          'distancia'       => 'xsd:string',
                          'tiempo'          => 'xsd:string',
                          'extras'          => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('DameLugares', // Nombre de la funcion 
                    array('usuario'  => 'xsd:string'), // Parametros de entrada 
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
                          'Anio'          => 'xsd:string',
                          'tipo'          => 'xsd:string',
                          'iave'          => 'xsd:string',
                          'ac'            => 'xsd:string',
                          'wifi'          => 'xsd:string',
                          'cargador'      => 'xsd:string'), // Parametros de entrada 
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
                          'so'          => 'xsd:string',
                          'img_perfil'  => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('RegistraUsuarioFacebook', 
                    array('nombre'      => 'xsd:string',
                          'apaterno'    => 'xsd:string',
                          'amaterno'    => 'xsd:string',
                          'movil'       => 'xsd:string',
                          'email'       => 'xsd:string',
                          'regID'       => 'xsd:string',
                          'img_perfil'  => 'xsd:string',
                          'dispositivo' => 'xsd:string',
                          'push_token'  => 'xsd:string',
                          'so'          => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);  
  $server->register('RazonesCancelacion', 
                    array('app'       => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('MotivosBajaCalificacion', 
                    array('app'       => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL);
  $server->register('MensajesPredefinidos', 
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

   $server->register('dame_tipo_taxis',
                      array('latitud'  => 'xsd:string',
                            'longitud'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('dame_servicios_taxis',
                      array('latitud'  => 'xsd:string',
                            'longitud'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('historico_usuario',
                      array('id_usuario'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('historico_taccsista',
                      array('id_usuario'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('opr_avisa_arribo',
                      array('id_viaje'  => 'xsd:string'), 
                      array('return' => 'xsd:string'),
                    $miURL);

   $server->register('CalificarViaje',
                     array('id_viaje'     => 'xsd:string',
                           'comentarios'  => 'xsd:string',
                           'puntos '      => 'xsd:string',
                           'app'          => 'xsd:string'),
                     array('return' => 'xsd:string'),
                     $miURL);

   $server->register('getReservaciones',
                     array('id_usuario'     => 'xsd:string'),
                     array('return' => 'xsd:string'),
                     $miURL);  

    $server->register('usrNuevaReservacion',// Nombre de la funcion 
                    array(
                          'usuario'      => 'xsd:string',
                          'dispositivo'  => 'xsd:string',
                          'push_token'   => 'xsd:string',
                          'origen'       => 'xsd:string',
                          'destino'      => 'xsd:string',
                          'lat_origen'   => 'xsd:string',
                          'lon_origen'   => 'xsd:string',
                          'lat_destino'  => 'xsd:string',
                          'lon_destino'  => 'xsd:string',                          
                          'pago'         => 'xsd:string',
                          'referencias'  => 'xsd:string',
                          'tipo'         => 'xsd:string',
                          'iave'         => 'xsd:string',
                          'ac'           => 'xsd:string',
                          'conectores'   => 'xsd:string',
                          'wifi'         => 'xsd:string',
                          'so'           => 'xsd:string',
                          'id_tarjeta'   => 'xsd:string',
                          'fecha_reservacion'=> 'xsd:string',
                          'servicios'    => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);      

  $server->register('cancelarReservacion', // Nombre de la funcion 
                    array('id_usuario'        => 'xsd:string',
                          'id_reservacion'    => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('getStatusReservacion',
                     array('id_reservacion' => 'xsd:string'),
                     array('return'         => 'xsd:string'),
                     $miURL); 

  $server->register('oprTomarReservacion', // Nombre de la funcion 
                    array('id_usuario'   => 'xsd:string',
                          'id_reservacion'     => 'xsd:string',
                          'dispositivo'  => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);    

  $server->register('oprRechazarReservacion',
                     array('id_reservacion' => 'xsd:string',
                           'id_usuario' => 'xsd:string'),
                     array('return' => 'xsd:string'),
                     $miURL);  

  $server->register('dameViajeActivo', 
                    array('id_usuario'  => 'xsd:string'),
                    array('return' => 'xsd:string'),
                    $miURL); 

  $server->register('usrNuevoLugar',// Nombre de la funcion 
                    array(
                          'usuario'     => 'xsd:string',
                          'descripcion' => 'xsd:string',                         
                          'latitud'     => 'xsd:string',
                          'longitud'    => 'xsd:string',
                          'direccion'   => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);

  $server->register('usrEditarLugar',// Nombre de la funcion 
                    array(
                          'idLugar'     => 'xsd:string',
                          'usuario'     => 'xsd:string',
                          'descripcion' => 'xsd:string',                         
                          'latitud'     => 'xsd:string',
                          'longitud'    => 'xsd:string',
                          'direccion'   => 'xsd:string'
                          ), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);  

  $server->register('usrEliminarLugar',// Nombre de la funcion 
                    array(
                          'idLugar'     => 'xsd:string',
                          'usuario'     => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);   

  $server->register('usrValidaUsuario',// Nombre de la funcion 
                    array('usuario'     => 'xsd:string'), // Parametros de entrada 
                    array('return' => 'xsd:string'), // Parametros de salida 
                    $miURL);   

/****************/
function envia_mail($archivo,$destinatarios, $asunto, $mensaje, $from, $FromName){
    $mail  = new PHPMailer();
    $mail->IsSMTP(); // set mailer to use SMTP
    $mail->Host    = "192.168.6.184"; // specify main and backup server
    $mail->SMTPAuth  = true; // turn on SMTP authentication
    $mail->Username  = "avl.4togo"; // SMTP username
    $mail->Password  = "qazwsx"; // SMTP password
    $mail->From    = $from;
    $mail->FromName  = $FromName;
      $mail->Subject   = $asunto;
      $mail->Body    = $mensaje;
    $mail->AddAttachment($archivo,'');
    $dest = explode(';',$destinatarios);
    $n = count($dest);
          for ($i=0; $i<$n; $i++){
            $mail->AddAddress($dest[$i],$dest[$i]);
    }

    //$mail->AddAddress($destinatarios,'');
    $mail->AddAttachment($archivo,'');
    $exito = $mail->Send();
          //Si el mensaje no ha podido ser enviado se realizaran 4 intentos mas como mucho 
          //para intentar enviar el mensaje, cada intento se hara 5 segundos despues 
          //del anterior, para ello se usa la funcion sleep 
          $intentos=1; 
          while ((!$exito) && ($intentos < 2)) {
      sleep(5);
      //este error deberia guardarlo en un log
            //echo $mail->ErrorInfo."/n \n";
            $exito = $mail->Send();
            $intentos=$intentos+1;  
          }
    return $exito;
    //return $mail-> send() ? true:false;
  }


function checkEmail($email) {  
    $reg = "#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i";  
    return preg_match($reg, $email);  
} 

  function RecuperarPassword($usName,$llave){
    if ($llave == 't4ccs1'){
      $con = mysql_connect("localhost","dba","t3cnod8A!");
      if ($con){
        $base = mysql_select_db("taccsi",$con);
        //$res = RecuperaPass($usName);

        $sql = "SELECT PASSWORD AS EXISTE 
                FROM SRV_USUARIOS 
                WHERE EMAIL = '".$usName."'";
        if ($qry = mysql_query($sql)){
            if (mysql_num_rows($qry) > 0){
              $row  = mysql_fetch_object($qry);
              $pass = $row->EXISTE;
              if(checkEmail($usName)){
                $mensaje = "Buen día,

  Usted ha solicitado la recuperación de su password desde nuestra Aplicación Móvil.

  Su password es: ".$pass."

  En caso de que no haya solicitado su password, le recomendamos tome las medidas necesarias, realizando su cambio en http://www.taccsi.com.

  Atentamente 
  TACCSI";                
                if (envia_mail('',$usName, utf8_decode('Recuperación de password'), utf8_decode($mensaje),'no-reply@taccsi.com.','TACCSI')){
                  $res = '<?xml version="1.0" encoding="UTF-8"?>
                          <space>
                            <Response> 
                              <Status>
                                <code>1</code>
                                <msg>'.('Su contraseña ha sido enviada al e-mail que proporcionó para su registro.').'</msg>
                              </Status>
                            </Response>
                          </space>'; 
                }else{
                  $res = '<?xml version="1.0" encoding="UTF-8"?>
                          <space>
                            <Response> 
                              <Status>
                                <code>-1</code>
                                <msg>No fue posible completar el proceso. Intente mas tarde.</msg>
                              </Status>
                            </Response>
                          </space>'; 
                }
              }else{
                $res = '<?xml version="1.0" encoding="UTF-8"?>
                          <space>
                            <Response> 
                              <Status>
                                <code>-2</code>
                                <msg>'.('No dispone de una cuenta de correo para el envío. Comuníquese al 01 800 444 82 94').'</msg>
                              </Status>
                            </Response>
                          </space>';
              }
            }else{
              $res = '<?xml version="1.0" encoding="UTF-8"?>
                          <space>
                            <Response> 
                              <Status>
                                <code>-3</code>
                                <msg>El correo que propociono no esta registrado. Comuniquese al 01 800 444 82 94</msg>
                              </Status>
                            </Response>
                          </space>';
            }
        }
      } else {
        $res = '<?xml version="1.0" encoding="UTF-8"?> 
                  <space>
                    <Response> 
                      <Status>
                        <code>-2</code>
                        <msg>Sucedio un problema de comunicación</msg>
                      </Status>
                    </Response>
                  </space>';  
      }
    } else {
      $res = '<?xml version="1.0" encoding="UTF-8"?> 
                  <space>
                    <Response> 
                      <Status>
                        <code>-3</code>
                        <msg>Llave incorrecta</msg>
                      </Status>
                    </Response>
                  </space>';   
    }
    return new soapval('return', 'xsd:string', $res);
  }


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

  function dame_tipo_taxis($latitud,$longitud){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $sql = "SELECT ID_TIPO_TAXI,
                     DESCRIPCION,
                     PASAJEROS_MAX,
                     ICONO
              FROM ADMIN_TIPO_TAXIS
              WHERE ESTATUS = 1";
      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat='<tipo_taxis>';
          while ($row = mysql_fetch_object($qry)){
              $dat= $dat.'<tipo>
                        <id_tipo>'.$row->ID_TIPO_TAXI.'</id_tipo>
                        <descripcion>'.$row->DESCRIPCION.'</descripcion>
                        <max_pasajeros>'.$row->PASAJEROS_MAX.'</max_pasajeros>
                        <texto>Máximo '.$row->PASAJEROS_MAX.' pasajeros</texto>
                        <icono>'.$row->ICONO.'</icono>
                      </tipo>';
          }
          $dat=$dat.'</tipo_taxis>';
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
                   '.($dat).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);

  }

  function dame_servicios_taxis($latitud,$longitud){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $sql = "SELECT ID_SERVICIO,
                     DESCRIPCION,
                     ICONO,
                     ICONO_AMARILLO,
                     ICONO_BLANCO
              FROM ADMIN_SERVICIOS
              WHERE ESTATUS = 1";
      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat='<servicios>';
          while ($row = mysql_fetch_object($qry)){
              $dat= $dat.'<servicio>
                            <id_tipo>'.$row->ID_SERVICIO.'</id_tipo>
                            <descripcion>'.$row->DESCRIPCION.'</descripcion>
                            <icono_negro>'.$row->ICONO.'</icono_negro>
                            <icono_amarillo>'.$row->ICONO_AMARILLO.'</icono_amarillo>
                            <icono_blanco>'.$row->ICONO_BLANCO.'</icono_blanco>
                         </servicio>';
          }
          $dat=$dat.'</servicios>';
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

  function marca_arribo($idViaje){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES
            SET ARRIBO = CURRENT_TIMESTAMP,
            WHERE ID_VIAJES = ".$idViaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
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
      marca_arribo($id_viaje);
      envia_push('dev','usuario','Tu TACCSI ha llegado',$push,$so,'Tu TACCSI ha llegado');
      $idx = 0;
      $msg = 'OK';
      mysql_close($con);
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

function historico_usuario($id_usuario){

    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("201.131.96.45","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);

      $sql = "SELECT ADMIN_VIAJES.ID_VIAJES,
                   CONCAT(ADMIN_USUARIOS.NOMBRE,' ',ADMIN_USUARIOS.APATERNO,' ' ,ADMIN_USUARIOS.AMATERNO) AS TACCSISTA,
                   ADMIN_USUARIOS.FOTO,
                   ADMIN_VIAJES.FECHA_VIAJE,
                   ADMIN_MODELO.DESCRIPCION AS VEHICULO,
                   ADMIN_VIAJES.MONTO_TAXISTA,
                   ADMIN_VIAJES.ORIGEN,
                   ADMIN_VIAJES.ORIGEN_LATITUD,
                   ADMIN_VIAJES.ORIGEN_LONGITUD,
                   ADMIN_VIAJES.DESTINO,
                   ADMIN_VIAJES.DESTINO_LATITUD,
                   ADMIN_VIAJES.DESTINO_LONGITUD,
                   ADMIN_VIAJES.RATING_USUARIO,
                   ADMIN_VIAJES.DISTANCIA_TAXISTA,
                   ADMIN_VIAJES.COMENTARIOS,
                   ADMIN_VIAJES.TIEMPO_VIAJE,
                   SRV_ESTATUS.ESTATUS,
                   IF (SRV_FORMAS_PAGO.TARJETA_VIEW IS NULL, 'EFECTIVO',SRV_FORMAS_PAGO.TARJETA_VIEW) AS FORMA_PAGO,
                   IF (ADMIN_TIPO_TARJETAS.IMAGEN IS NULL, 'EFECTIVO',ADMIN_TIPO_TARJETAS.IMAGEN) AS ICONO_PAGO,
                   ADMIN_TAXIS.PLACAS,
                   IF(ADMIN_VIAJES.EXTRAS IS NULL,'0.00',ADMIN_VIAJES.EXTRAS) AS N_EXTRAS                   
            FROM ADMIN_VIAJES
            INNER JOIN SRV_ESTATUS ON
                       SRV_ESTATUS.ID_ADMIN_ESTATUS = ADMIN_VIAJES.ID_SRV_ESTATUS
            INNER JOIN ADMIN_USUARIOS ON
                       ADMIN_USUARIOS.ID_USUARIO = ADMIN_VIAJES.ID_TAXISTA
            INNER JOIN ADMIN_TAXIS ON
                       ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO= ADMIN_VIAJES.ID_TAXISTA
            INNER JOIN ADMIN_MODELO ON
                       ADMIN_MODELO.ID_MODELO=ADMIN_TAXIS.ID_MODELO
            LEFT JOIN SRV_FORMAS_PAGO ON
                      SRV_FORMAS_PAGO.ID_FORMA_PAGO = ADMIN_VIAJES.ID_TARJETA
            LEFT JOIN ADMIN_TIPO_TARJETAS ON 
                      ADMIN_TIPO_TARJETAS.ID_TIPO_TARJETA  = SRV_FORMAS_PAGO.ID_TIPO_TARJETA
            WHERE ADMIN_VIAJES.ID_CLIENTE=".$id_usuario." 
             AND  ADMIN_VIAJES.ID_SRV_ESTATUS IN (3,4,8)
            ORDER BY ADMIN_VIAJES.ID_VIAJES DESC";
      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat.='<historico>';
          while ($row = mysql_fetch_object($qry)){
              $dat.= '<viaje>
                        <id_viaje>'.$row->ID_VIAJES.'</id_viaje>
                        <estatus>'.$row->ESTATUS.'</estatus>
                        <taccsista>'.$row->TACCSISTA.'</taccsista>
                        <placas>'.$row->PLACAS.'</placas>
                        <foto>'.$row->FOTO.'</foto>
                        <fecha>'.$row->FECHA_VIAJE.'</fecha>
                        <vehiculo>'.$row->VEHICULO.'</vehiculo>
                        <monto>'.$row->MONTO_TAXISTA.'</monto>
                        <extras>'.$row->N_EXTRAS.'</extras>
                        <forma_pago>'.$row->FORMA_PAGO.'</forma_pago>
                        <icono_pago>'.$row->ICONO_PAGO.'</icono_pago>
                        <origen>'.$row->ORIGEN.'</origen>
                        <lat_origen>'.$row->ORIGEN_LATITUD.'</lat_origen>
                        <lon_origen>'.$row->ORIGEN_LONGITUD.'</lon_origen>
                        <destino>'.$row->DESTINO.'</destino>
                        <lat_destino>'.$row->DESTINO_LATITUD.'</lat_destino>
                        <lon_destino>'.$row->DESTINO_LONGITUD.'</lon_destino>
                        <puntos>'.$row->RATING_USUARIO.'</puntos>
                        <distancia>'.$row->DISTANCIA_TAXISTA.'</distancia>
                        <comentarios>'.$row->COMENTARIOS.'</comentarios>
                        <tiempo>'.$row->TIEMPO_VIAJE.'</tiempo>
                        '.getServiciosViaje($row->ID_VIAJES).'
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

  function getServiciosViaje($idviaje){
    $res = "<servicios>";
    global $base;
    $sql = "SELECT S.ID_SERVICIO, S.DESCRIPCION, S.ICONO, S.ICONO_AMARILLO,S.ICONO_BLANCO 
            FROM ADMIN_VIAJES_SERVICIOS R
            INNER JOIN ADMIN_SERVICIOS S ON R.ID_SERVICIO = S.ID_SERVICIO
            WHERE R.ID_VIAJE = ".$idviaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (mysql_num_rows($qry) > 0){
        $res .= '<servicio>
                    <id_tipo>'.$row->ID_SERVICIO.'</id_tipo>
                    <descripcion>'.$row->DESCRIPCION.'</descripcion>
                    <icono_negro>'.$row->ICONO.'</icono_negro>
                    <icono_amarillo>'.$row->ICONO_AMARILLO.'</icono_amarillo>
                    <icono_blanco>'.$row->ICONO_BLANCO.'</icono_blanco>
                 </servicio>';
        $res .= $infoTaccista;                
      }
      mysql_free_result($qry);
    } 
    $res .= '</servicios>';
    return $res;    
  }


  function historico_taccsista($id_usuario){
    $idx = -1;
    $msg = 'Eror al conecctarse con el servico';
    $dat = "";
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){

      $base = mysql_select_db("taccsi",$con);

      $sql = "SELECT ADMIN_VIAJES.ID_VIAJES,
                   CONCAT(SRV_USUARIOS.NOMBRE,' ',SRV_USUARIOS.APATERNO) AS USUARIO,
                   SRV_USUARIOS.IMAGEN,
                   ADMIN_VIAJES.FECHA_VIAJE,
                   ADMIN_MODELO.DESCRIPCION AS VEHICULO,
                   ADMIN_VIAJES.MONTO_TAXISTA,
                   ADMIN_VIAJES.ORIGEN,
                   ADMIN_VIAJES.ORIGEN_LATITUD,
                   ADMIN_VIAJES.ORIGEN_LONGITUD,
                   ADMIN_VIAJES.DESTINO,
                   ADMIN_VIAJES.DESTINO_LATITUD,
                   ADMIN_VIAJES.DESTINO_LONGITUD,
                   ADMIN_VIAJES.RATING_TAXISTA,
                   ADMIN_VIAJES.DISTANCIA_TAXISTA,
                   ADMIN_VIAJES.COMENTARIOS_TAXISTA,
                   ADMIN_VIAJES.TIEMPO_VIAJE,
                   SRV_ESTATUS.ESTATUS,
                   IF (SRV_FORMAS_PAGO.TARJETA_VIEW IS NULL, 'EFECTIVO',SRV_FORMAS_PAGO.TARJETA_VIEW) AS FORMA_PAGO,
                   IF (ADMIN_TIPO_TARJETAS.IMAGEN IS NULL, 'EFECTIVO',ADMIN_TIPO_TARJETAS.IMAGEN) AS ICONO_PAGO,
                   IF(ADMIN_VIAJES.EXTRAS IS NULL,'0.00',ADMIN_VIAJES.EXTRAS) AS N_EXTRAS                    
            FROM ADMIN_VIAJES
            INNER JOIN SRV_ESTATUS ON
                       SRV_ESTATUS.ID_ADMIN_ESTATUS = ADMIN_VIAJES.ID_SRV_ESTATUS
            INNER JOIN SRV_USUARIOS ON
                       SRV_USUARIOS.ID_SRV_USUARIO = ADMIN_VIAJES.ID_CLIENTE
            INNER JOIN ADMIN_TAXIS ON
                       ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO= ADMIN_VIAJES.ID_TAXISTA
            INNER JOIN ADMIN_MODELO ON
                       ADMIN_MODELO.ID_MODELO=ADMIN_TAXIS.ID_MODELO
            LEFT JOIN SRV_FORMAS_PAGO ON
                      SRV_FORMAS_PAGO.ID_FORMA_PAGO = ADMIN_VIAJES.ID_TARJETA
            LEFT JOIN ADMIN_TIPO_TARJETAS ON 
                      ADMIN_TIPO_TARJETAS.ID_TIPO_TARJETA  = SRV_FORMAS_PAGO.ID_TIPO_TARJETA
            WHERE ADMIN_VIAJES.ID_TAXISTA=".$id_usuario." 
            ORDER BY ADMIN_VIAJES.ID_VIAJES DESC";
      if ($qry = mysql_query($sql)){
        if (mysql_num_rows($qry) > 0){
          $dat.='<historico>';
          while ($row = mysql_fetch_object($qry)){
              $dat.= '<viaje>
                        <id_viaje>'.$row->ID_VIAJES.'</id_viaje>
                        <estatus>'.$row->ESTATUS.'</estatus>
                        <usuario>'.$row->USUARIO.'</usuario>
                        <foto>'.$row->IMAGEN.'</foto>
                        <fecha>'.$row->FECHA_VIAJE.'</fecha>
                        <vehiculo>'.$row->VEHICULO.'</vehiculo>
                        <monto>'.$row->MONTO_TAXISTA.'</monto>
                        <extras>'.$row->N_EXTRAS.'</extras>
                        <forma_pago>'.$row->FORMA_PAGO.'</forma_pago>
                        <icono_pago>'.$row->ICONO_PAGO.'</icono_pago>
                        <origen>'.$row->ORIGEN.'</origen>
                        <lat_origen>'.$row->ORIGEN_LATITUD.'</lat_origen>
                        <lon_origen>'.$row->ORIGEN_LONGITUD.'</lon_origen>
                        <destino>'.$row->DESTINO.'</destino>
                        <lat_destino>'.$row->DESTINO_LATITUD.'</lat_destino>
                        <lon_destino>'.$row->DESTINO_LONGITUD.'</lon_destino>
                        <puntos>'.$row->RATING_TAXISTA.'</puntos>
                        <distancia>'.$row->DISTANCIA_TAXISTA.'</distancia>
                        <comentarios>'.$row->COMENTARIOS_TAXISTA.'</comentarios>
                        <tiempo>'.$row->TIEMPO_VIAJE.'</tiempo>
                        '.getServiciosViaje($row->ID_VIAJES).'
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


function registra_taxis($empresa,$modelo,$id_usuario,$chofer,$placas,$eco,$anio,$tipo,$iave,$ac,$wifi,$cargador){
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
              FECHA_REGISTRO,
              ID_TIPO_TAXI,
              IAVE,
              AC,
              WIFI,
              CONECTOR_CELULAR
            ) VALUES (
              ".$empresa.",
              ".$modelo.",
              1,
              ".$id_usuario.",
              '".$chofer."',
              '".$placas."',
              '".$eco."',
              ".$anio.",
              CURRENT_TIMESTAMP,
              ".$tipo.",
              ".$iave.",
              ".$ac.",
              ".$wifi.",
              ".$cargador."
              )";
    if (mysql_query($sql)){
      
      $sql2 = "SELECT ADMIN_USUARIOS_ID_USUARIO
            FROM ADMIN_TAXIS
            WHERE ADMIN_USUARIOS_ID_USUARIO = ".$id_usuario;
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
                              $Anio,
                              $tipo,
                              $iave,
                              $ac,
                              $wifi,
                              $cargador){
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
                registra_taxis($id_empresa,$Modelo,$id,$chofer,$Placas,$Eco,$Anio,$tipo,$iave,$ac,$wifi,$cargador);


                $dat = dame_usuario($email,$password,'T');

                //registra_token($identificador,$push_token,$so);
                //$msg = 'OK';  
                $msg = 'Un miembro de nuestro equipo se pondra en contacto contigo para indicarte, lo que debes hacer a continuación.';
                $idx = -1;
                //$idx = $id;
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
            SET ID_SRV_ESTATUS = 5,
                INICIO = CURRENT_TIMESTAMP
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
                  CANCELADO = CURRENT_TIMESTAMP,
                  ID_RAZON_CANCELACION=".$razon."
              WHERE ID_VIAJES = ".$idViaje;
    } else {
      $sql = "UPDATE ADMIN_VIAJES
              SET ID_SRV_ESTATUS = 8,
                  CANCELADO = CURRENT_TIMESTAMP,
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
                RATING_USUARIO = ".$puntos_servicio.",
                COMENTARIOS = '".$comentarios."',
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
            SET VIAJES = IF(VIAJES IS NULL OR VIAJES = 0,1,VIAJES+1),
                RATING = IF(RATING IS NULL OR RATING = 0,".$puntos_taxista.",((RATING + ".$puntos_taxista.")/2))
            WHERE ID_USUARIO = ".$id_taxista;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }


  function califica_viaje_usr($id_viaje,$puntos, $comentarios){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET COMENTARIOS = '".$comentarios."',
                RATING_USUARIO = ".$puntos."
            WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }

  function califica_viaje_taxista($id_viaje,$puntos, $comentarios){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET COMENTARIOS_TAXISTA = '".$comentarios."',
                RATING_TAXISTA      = ".$puntos."
            WHERE ID_VIAJES         = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $res = true;
    }        
    return $res;
  }

  /**********************************************************************
  * En esta funcion que reciba los comentarios -> comentarios
  **/
  function CalificarViaje($id_viaje,$comentarios,$puntos,$app){
     $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $str_inser_log = "antes de las validaciones: id_viaje =".$id_viaje.", comentarios = ".$comentarios.", puntos = ".$puntos.", app = ".$app;
      $push_token='';
      InsertaLog("CalificarViaje",$str_inser_log,$push_token);
      if ($app == 'U'){
        $id_taxista = dame_id_taxista($id_viaje);
        califica_taxista($id_taxista,$puntos);
        califica_viaje_usr($id_viaje,$puntos, $comentarios);
      } else {
        $id_usuario = dame_id_usuario($id_viaje);
        califica_usuario($id_usuario,$puntos);
        califica_viaje_taxista($id_viaje,$puntos, $comentarios);
      }
      $idx = 0;
      $msg = 'OK';
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

  function usrFinViaje($id_usuario,$id_viaje,$comentarios,$puntos_taxista, $importe,$distancia){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $str_inser_log = "antes de las validaciones: id_usuario =".$id_usuario.", id_viaje = ".$id_viaje.", comentarios = ".$comentarios.", puntos_servicio = ".$puntos_servicio.", puntos_taxista = ".$puntos_taxista.", importe = ".$importe.", distancia = ".$distancia.", so = ".$so;
      InsertaLog("usrFinViaje",$str_inser_log,$push_token);
      if (finaliza_viaje($id_viaje,$comentarios,$puntos_taxista,$importe,$distancia)){
        $id_taxista = dame_id_taxista($id_viaje);
        if ($id_taxista > -1){
          califica_taxista($id_taxista,$puntos_taxista);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'T');

          $str_inser_log = "Todo salio OK : id_usuario =".$id_usuario.", id_viaje = ".$id_viaje.", comentarios = ".$comentarios.", puntos_servicio = ".$puntos_servicio.", puntos_taxista = ".$puntos_taxista.", importe = ".$importe.", distancia = ".$distancia.", so = ".$so;
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
      $sql = "SELECT ADMIN_USUARIOS.ID_USUARIO,
                     ADMIN_USUARIOS.NOMBRE,
                     ADMIN_USUARIOS.APATERNO,
                     ADMIN_USUARIOS.AMATERNO,
                     ADMIN_USUARIOS.TELEFONO,
                     ADMIN_USUARIOS.FOTO,
                     ADMIN_USUARIOS.NICKNAME AS EMAIL,
                     ADMIN_TAXIS.ID_TAXI,
                     ADMIN_USUARIOS.DOCUMENTOS_VALIDADOS              
              FROM ADMIN_USUARIOS
              LEFT OUTER JOIN ADMIN_TAXIS ON ADMIN_TAXIS.ADMIN_USUARIOS_ID_USUARIO= ADMIN_USUARIOS.ID_USUARIO
              WHERE NICKNAME = '".$usuario."' AND
                    PASSWORD_TEXT = '".$password."'";
    }
    if ($qry = mysql_query($sql)){
      if (mysql_num_rows($qry) > 0){
        $row = mysql_fetch_object($qry);
        if($row->FOTO=="" or $row->FOTO=="null"){
            $foto="http://taccsi.com/images/taxis/sin_foto_perfil.png";
        }else{
            $foto="http://taccsi.com/images/taxis/".$row->FOTO;
        }

        if ($tipo == 'U'){
          $res = '<usuario>
                    <id>'.$row->ID_USUARIO.'</id>
                    <foto_perfil>'.$foto.'</foto_perfil>
                    <nombre>'.$row->NOMBRE.'</nombre>
                    <apaterno>'.$row->APATERNO.'</apaterno>
                    <amaterno>'.$row->AMATERNO.'</amaterno>
                    <telefono>'.$row->TELEFONO.'</telefono>
                    <correo>'.$row->EMAIL.'</correo>
                  </usuario>';
          }else{
            $res = '<usuario>
                    <id>'.$row->ID_USUARIO.'</id>
                    <id_taccsi>'.$row->ID_TAXI.'</id_taccsi>
                    <foto_perfil>'.$foto.'</foto_perfil>
                    <nombre>'.$row->NOMBRE.'</nombre>
                    <apaterno>'.$row->APATERNO.'</apaterno>
                    <amaterno>'.$row->AMATERNO.'</amaterno>
                    <telefono>'.$row->TELEFONO.'</telefono>
                    <correo>'.$row->EMAIL.'</correo>
                  </usuario>';
          }
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

  function taccsista_activo_octubre($usuario){
    $res = '';  
    $foto= "";
    global $base;
    $sql = "SELECT COUNT(*) AS ACTIVO
            FROM   ADMIN_USUARIOS
            WHERE  NICKNAME = '".$usuario."' AND
                   DEMOS = 1";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ACTIVO;
      mysql_free_result($qry);
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
        if ($tipo <> 'U'){
          if (taccsista_activo_octubre($usuario) == 0){
            $idx = 2;
            $msg = 'Disponible apartir del 15 de Octubre de 2015';
          }else{
            if (taccista_activo($usuario) == 0){
              $idx = -2;
              $msg = 'Usuario sin verificar.';
            }            
          }
        }
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
                         $referencias,
                         $id_tarjeta,
                         $tipo,
                         $iave,
                         $ac,
                         $conectores,
                         $wifi){
    $res = false;
    global $base;

    $codigo = rand(1,9999);

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
              ORIGEN_REFERENCIAS,
              CLAVE_VIAJE,
              ID_TARJETA,
              ID_TIPO_TAXI,
              CARGADOR,
              AC,
              WIFI,
              IAVE
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
              '".$referencias."',
              ".$codigo.",
              ".$id_tarjeta.",
              ".$tipo.",
              ".$conectores.",
              ".$ac.",
              ".$wifi.",
              ".$iave.")";
    
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
                   A.ORIGEN_REFERENCIAS,
                   A.CLAVE_VIAJE,
                   A.IAVE,
                   A.WIFI,
                   A.AC,
                   A.CARGADOR,
                   A.MONTO_TAXISTA,
                   A.TIEMPO_VIAJE,
                   A.DISTANCIA_TAXISTA,
                   IF(A.EXTRAS IS NULL,'0.00',A.EXTRAS) AS N_EXTRAS,
                   A.ID_VIAJES
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_USUARIOS B ON A.ID_CLIENTE = B.ID_SRV_USUARIO 
              INNER JOIN ADMIN_FORMA_PAGO C ON A.ID_FORMA_PAGO = C.ID_FORMA_PAGO
            WHERE  A.ID_SRV_ESTATUS NOT IN (-1,2,3,4,5) AND
                   ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (mysql_num_rows($qry) > 0){


        if($row->FOTO=="" or $row->FOTO=="null"){
           $foto_="http://taccsi.com/images/taxis/sin_foto_perfil.png";
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
               <clave>".$row->CLAVE_VIAJE."</clave>
               <tarifa>banderazo=13.10@costo_minuto=1.73@costo_distancia=1.3@mts=250@nocturno=20@comision=3@inicio_nocturno=23:00@fin_nocturno=06:00</tarifa>               
               <monto>".$row->MONTO_TAXISTA."</monto>
               <extras>".$row->N_EXTRAS."</extras>
               <distancia>".$row->DISTANCIA_TAXISTA."</distancia>
               <tiempo>".$row->TIEMPO_VIAJE."</tiempo> 
               ".getServiciosViaje($row->ID_VIAJES)."              
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

  function manda_viaje_taxistas($id_viaje,$latitud,$longitud,$tipo,$iave,$ac,$conector,$wifi,$servicios){
    $sFilter = ($servicios!="") ? 'INNER JOIN ADMIN_TAXI_SERVICIOS S ON T.ID_TAXI = S.ID_TAXI AND S.ID_SERVICIO IN ('.$servicios.')': '';
    /*
    if ($iave == 1){
      $where = $where."T.IAVE = 1 AND ";
    }
    if ($ac == 1){
      $where = $where."T.AC = 1 AND ";
    }
    if ($conector == 1){
      $where = $where."T.CONECTOR_CELULAR = 1 AND ";
    }
    if ($wifi == 1){
      $where = $where."T.WIFI = 1 AND ";
    }*/

    $sql = "SELECT A.ID_USUARIO, 
                   C.PUSH_TOKEN, 
                   C.SO,
                   ROUND(DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") ,2) AS DIST
            FROM ADMIN_USUARIOS A
              INNER JOIN DISP_ULTIMA_POSICION B ON B.IDENTIFICADOR = A.DISPOSITIVO
              INNER JOIN DISP_REGISTRO_TOKENS C ON DEVICE_ID = B.IDENTIFICADOR
              INNER JOIN ADMIN_TAXIS T ON T.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO
              ".$sFilter." 
            WHERE A.DISPONIBLE = 0 AND
                  A.DEMOS = 1 AND
                  DISTANCIA(B.LATITUD,B.LONGITUD,".$latitud.",".$longitud.") < 400 AND
                  CAST(B.FECHA_GPS AS DATE)=CURRENT_DATE
            GROUP BY A.ID_USUARIO                  
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

  function taccista_activo($usuario){
    //aquimequede
    $res = '';  
    $foto= "";
    global $base;
    $sql = "SELECT DOCUMENTOS_VALIDADOS
            FROM   ADMIN_USUARIOS
            WHERE  NICKNAME = '".$usuario."'";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->DOCUMENTOS_VALIDADOS;
      mysql_free_result($qry);
    }
    return $res;
  }  

  function usuario_activo_octubre($usuario){

    //aquimequede
    $res = '';  
    $foto= "";
    global $base;
    $sql = "SELECT COUNT(*) AS ACTIVO
            FROM   ADMIN_USUARIOS
            WHERE  NICKNAME = '".$usuario."' AND
                   DEMOS = 1";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->ACTIVO;
      mysql_free_result($qry);
    }
    return $res;
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
                         $referencias,
                         $tipo,
                         $iave,
                         $ac,
                         $conectores,
                         $wifi,
                         $id_tarjeta,
                         $servicios){

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
                      ", tipo = ".$tipo.
                      ", iave = ".$iave.
                      ", ac = ".$ac.
                      ", conectores = ".$conectores.
                      ", wifi = ".$wifi.
                      ", id_tarjeta = ".$tarjeta.
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
                         $referencias,
                         $id_tarjeta,
                         $tipo,
                         $iave,
                         $ac,
                         $conectores,
                         $wifi)){

            if(registra_servicios($id_viaje,$servicios,'s')){
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
                  manda_viaje_taxistas($id_viaje,$lat_origen,$lon_origen,$tipo,$iave,$ac,$conectores,$wifi,$servicios);
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
            SET ID_TAXISTA = '.$id_usuario.',
                ID_SRV_ESTATUS = 2,
                ASIGNADO = CURRENT_TIMESTAMP
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


  function dame_codigo_viaje($id_viaje){
    $res = '';
    $sql = "SELECT CLAVE_VIAJE
              FROM ADMIN_VIAJES
              WHERE ID_VIAJES = ".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->CLAVE_VIAJE;
      mysql_free_result($close);  
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
        $codigo = dame_codigo_viaje($id_viaje);
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
                   TT.DESCRIPCION AS TIPO,
                   B.PLACAS,
                   B.ECO,
                   B.IMAGEN AS FOTO_TAXI,
                   C.LATITUD,
                   C.LONGITUD,
                   C.FECHA_SERVER,
                   IF(B.AC IS NULL,0,B.AC) AS AC,
                   IF(B.IAVE IS NULL,0,B.IAVE) AS IAVE,
                   IF(B.CONECTOR_CELULAR IS NULL,0,B.CONECTOR_CELULAR) AS CONECTOR_CELULAR,
                   IF(B.WIFI IS NULL,0,B.WIFI) AS WIFI
            FROM ADMIN_USUARIOS A,
                 ADMIN_TAXIS B, 
                 DISP_ULTIMA_POSICION C,
                 ADMIN_MARCA MA,
                 ADMIN_MODELO MO,
                 ADMIN_TIPO_TAXIS TT
            WHERE A.ID_USUARIO =  ".$id_taxista." AND
                  MO.ID_MODELO =B.ID_MODELO AND 
                  MA.ID_MARCA= MO.ID_MARCA AND
                  B.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO AND
                  A.ID_USUARIO = C.ID_USUARIO AND
                  B.ID_TIPO_TAXI = TT.ID_TIPO_TAXI";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $foto_taxista="";
      $foto_taxi="";

      if($row->FOTO_TAXISTA=="" or $row->FOTO_TAXISTA=="null"){
          $foto_taxista="http://taccsi.com/images/taxis/sin_foto_perfil.png";
      }else{
          $foto_taxista="http://taccsi.com/images/taxis/".$row->FOTO_TAXISTA;
      }

      if($row->FOTO_TAXI=="" or $row->FOTO_TAXI=="null"){
          $foto_taxi="http://taccsi.com/images/taxis/sin_foto_perfil.png";
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
                <tipo>".$row->TIPO."</tipo>
                <placas>".$row->PLACAS."</placas>
                <eco>".$row->ECO."</eco>
                <foto_taxi>".$foto_taxi."</foto_taxi>
                <latitud>".$row->LATITUD."</latitud>
                <longitud>".$row->LONGITUD."</longitud>
                <fecha_gps>".$row->FECHA_SERVER."</fecha_gps>
                <ac>".$row->AC."</ac>
                <iave>".$row->IAVE."</iave>
                <conector>".$row->CONECTOR_CELULAR."</conector>
                <wifi>".$row->WIFI."</wifi>
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
            SET ID_SRV_ESTATUS = 7,
                RECHAZADO = CURRENT_TIMESTAMP
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
 
  function registra_usuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$img_perfil){
    $res = false;
    $codeActivacion = getRandomCode();
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
              VIAJES,
              COD_CONFIRMACION,
              IMAGEN
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
              0,
              '".$codeActivacion."',
              '".$img_perfil."'
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

  function RegistraUsuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$push_token,$so,$img_perfil){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $dat = '';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      if (valida_correo($email,'usuario')){
        if (valida_telefono($email,'usuario')){
          if (registra_usuario($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$img_perfil)){
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
                   TT.ID_TIPO_TAXI,
                   TT.DESCRIPCION AS TIPO,
                   A.LATITUD,
                   A.LONGITUD,
                   A.ANGULO,
                   ROUND(DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud."),2) AS DIST,
                   C.IMAGEN,
                   IF(B.RATING IS NULL,0,(B.RATING/VIAJES)) AS RATING,
                   IF(B.VIAJES IS NULL,0,B.VIAJES) AS VIAJES,
                   IF(C.AC IS NULL,0,C.AC) AS AC,
                   IF(C.IAVE IS NULL,0,C.IAVE) AS IAVE,
                   IF(C.CONECTOR_CELULAR IS NULL,0,C.CONECTOR_CELULAR) AS CONECTOR_CELULAR,
                   IF(C.WIFI IS NULL,0,C.WIFI) AS WIFI,
                   C.ID_TAXI
            FROM DISP_ULTIMA_POSICION A
              INNER JOIN ADMIN_USUARIOS B ON B.ID_USUARIO = A.ID_USUARIO
              INNER JOIN ADMIN_TAXIS C ON C.ADMIN_USUARIOS_ID_USUARIO = A.ID_USUARIO 
              INNER JOIN ADMIN_MODELO M ON M.ID_MODELO = C.ID_MODELO
              INNER JOIN ADMIN_TIPO_TAXIS TT ON C.ID_TIPO_TAXI = TT.ID_TIPO_TAXI
            WHERE CAST(A.FECHA_SERVER AS DATE) = CURRENT_DATE AND
                  B.DISPONIBLE = 0 AND 
                  B.DEMOS = 1 AND
                  DISTANCIA(A.LATITUD,A.LONGITUD,".$latitud.",".$longitud.") < 400
            ORDER BY DIST
            LIMIT 10";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){

        if($row->FOTO_TAXISTA=="" or $row->FOTO_TAXISTA=="null"){
           $foto_taxista="http://taccsi.com/images/taxis/sin_foto_perfil.png";
        }else{
           $foto_taxista="http://taccsi.com/images/taxis/".$row->FOTO_TAXISTA;
        }

        if (($row->ANGULO >= 331) && ($row->ANGULO <= 29)) {
          $icono = 'http://taccsi.com/wbs/taccsi_331_29.png';
        } else if (($row->ANGULO >= 30) && ($row->ANGULO <= 119)) {
          $icono = 'http://taccsi.com/wbs/taccsi_30_119.png';
        } else if (($row->ANGULO >= 120) && ($row->ANGULO <= 240)) {
          $icono = 'http://taccsi.com/wbs/taccsi_120_240.png';
        } else if (($row->ANGULO >= 241) && ($row->ANGULO <= 330)) {
          $icono = 'http://taccsi.com/wbs/taccsi_240_330.png';
        } else {
          $icono = 'http://taccsi.com/wbs/taccsi_331_29.png';
        }

        $imagen = ($row->IMAGEN=="" or $row->IMAGEN=="null") ? 'sin_foto_perfil.png': $row->IMAGEN;

        $res = $res."<taxi>
                  <id>".$row->ID_USUARIO."</id>
                  <conductor>".$row->NOMBRE." ".$row->APATERNO." ".$row->AMATERNO."</conductor>
                  <placas>".$row->PLACAS."</placas>
                  <modelo>".$row->MODELO."</modelo>
                  <estatus>".$row->ESTATUS."</estatus>
                  <id_tipo>".$row->ID_TIPO_TAXI."</id_tipo>
                  <tipo>".$row->TIPO."</tipo>
                  <latitud>".$row->LATITUD."</latitud>
                  <longitud>".$row->LONGITUD."</longitud>
                  <distancia>".$row->DIST."</distancia>
                  <foto>"."http://taccsi.com/images/taxis/".$imagen."</foto>
                  <puntos>".$row->RATING."</puntos>
                  <foto_taxista>".$foto_taxista."</foto_taxista>                  
                  <ac>".$row->AC."</ac>
                  <iave>".$row->IAVE."</iave>
                  <conector>".$row->CONECTOR_CELULAR."</conector>
                  <wifi>".$row->WIFI."</wifi>
                  <icono>".$icono."</icono>
                  ".getServiciosTaccsi($row->ID_TAXI)."
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

   function finaliza_viaje_operador($id_viaje,$importe,$distancia,$tiempo,$extras){
    $res = false;
    $sql = "UPDATE ADMIN_VIAJES 
            SET ID_SRV_ESTATUS = 3,
                FIN_VIAJE_TAXISTA = CURRENT_TIMESTAMP,
                MONTO_TAXISTA = ".$importe.",
                DISTANCIA_TAXISTA = ".$distancia.",
                TIEMPO_VIAJE='".$tiempo."',
                EXTRAS=".$extras."
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
            SET VIAJES = IF(VIAJES IS NULL OR VIAJES = 0,1,VIAJES+1),
                RATING = IF(RATING IS NULL OR RATING = 0,".$puntos_usuario.",(RATING + ".$puntos_usuario.")/2)
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

  function oprFinViaje($id_usuario,$id_viaje,$importe,$distancia,$tiempo,$extras){
    $idx = -1;
    $msg = 'El servicio TACCSI, no esta disponible';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      
      $base = mysql_select_db("taccsi",$con);

      $str_inser_log = "id_usuario =".$id_usuario.
                      ", id_viaje = ".$id_viaje.
                      ", importe = ".$importe.
                      ", distancia = ".$distancia.
                      ", tiempo = ".$tiempo.
                      ", extras = ".$extras;

      InsertaLog("oprFinViaje",$str_inser_log,$token);

      $fin = finaliza_viaje_operador($id_viaje,$importe,$distancia,$tiempo,$extras);
      if ($fin){
        $id_cliente = dame_id_usuario($id_viaje);
        if ($id_cliente > -1){
          //califica_usuario($id_cliente,$puntos_usuario);
          $forma = forma_pago($id_viaje);
          //Manda push al taxista
          $push_token = dame_pushtoken_viaje($id_viaje,'U');
          if (strlen($push_token) > 0){
            $so = dame_so_pushtoken($push_token);
            //envia_push('Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago $forma.@'.$importe.'@'.$forma,$push_token);
            envia_push('dev',
                       'usuario',
                       $importe.'@'.$distancia.'@'.$extras.'@'.$tiempo.'@Su viaje ha concluido. Fue un placer atenderlo.',
                       $push_token,
                       $so,
                       'Su viaje ha concluido. Fue un placer atenderlo.');

            /*envia_push('dev',
                       'usuario',
                       'Su viaje ha concluido. Su TACCSISTA ha enviado una solicitud de pago.@'.$importe.'@'.$forma."@".$id_viaje,
                       $push_token,
                       $so,
                       'Su viaje ha concluido. Fue un placer atenderlo.');*/

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
                   U.ANGULO,
                   A.ID_RAZON_CANCELACION,
                   A.DISTANCIA_TAXISTA,
                   A.TIEMPO_VIAJE,
                   IF(A.EXTRAS IS NULL,'0.00',A.EXTRAS) AS N_EXTRAS
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_ESTATUS B ON A.ID_SRV_ESTATUS = B.ID_ADMIN_ESTATUS
              LEFT OUTER JOIN DISP_ULTIMA_POSICION U ON U.ID_USUARIO=A.ID_TAXISTA
            WHERE A.ID_VIAJES=".$id_viaje;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
        //ESTA EN ESPERA
        if (($row->ANGULO >= 331) && ($row->ANGULO <= 29)) {
          $icono = 'http://taccsi.com/wbs/taccsi_331_29.png';
        } else if (($row->ANGULO >= 30) && ($row->ANGULO <= 119)) {
          $icono = 'http://taccsi.com/wbs/taccsi_30_119.png';
        } else if (($row->ANGULO >= 120) && ($row->ANGULO <= 240)) {
          $icono = 'http://taccsi.com/wbs/taccsi_120_240.png';
        } else if (($row->ANGULO >= 241) && ($row->ANGULO <= 330)) {
          $icono = 'http://taccsi.com/wbs/taccsi_240_330.png';
        } else {
          $icono = 'http://taccsi.com/wbs/taccsi_331_29.png';
        }

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
          /*$res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$row->MONTO_TAXISTA.'@'.$row->ID_FORMA_PAGO.'</msg>'; */

          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->MONTO_TAXISTA.'@'.$row->DISTANCIA_TAXISTA.'@0@'.$row->TIEMPO_VIAJE.'@Su viaje ha concluido. Fue un placer atenderlo.</msg>';

        } elseif ($row->ID_ESTATUS == 4) {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'@'.$msg .'</msg>'; 
        } else {
          $res = '<code>'.$row->ID_ESTATUS.'</code>
                  <msg>'.$row->ESTATUS.'@'.$row->TAXISTA.'</msg>'; 
        }

        $res=$res.'<ll>'.$row->LATITUD.'@'.$row->LONGITUD.'@'.$icono.'</ll>';
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

  function MensajesPredefinidos($app){

      $dat = '<mensajes>
                <mensaje>
                  <id>1</id>
                  <descripcion>Trafico denso, tardare 10 minutos mas.</descripcion>
                </mensaje>
                <mensaje>
                  <id>2</id>
                  <descripcion>He llegado</descripcion>
                </mensaje>
                <mensaje>
                  <id>3</id>
                  <descripcion>Estoy esperando</descripcion>
                </mensaje>
                <mensaje>
                  <id>4</id>
                  <descripcion>Estoy por llegar</descripcion>
                </mensaje>
              </mensajes>';
    
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

  function MotivosBajaCalificacion($app){

      $dat = '<motivos>
                <motivo>
                  <id>1</id>
                  <descripcion>Falta de limpieza</descripcion>
                </motivo>
                <motivo>
                  <id>2</id>
                  <descripcion>Mala atención del taxista</descripcion>
                </motivo>
                <motivo>
                  <id>3</id>
                  <descripcion>Tiempo de arribo excesivo</descripcion>
                </motivo>
                <motivo>
                  <id>4</id>
                  <descripcion>Auto en malas condiciones</descripcion>
                </motivo>
                <motivo>
                  <id>5</id>
                  <descripcion>Forma de conducir</descripcion>
                </motivo>
                <motivo>
                  <id>6</id>
                  <descripcion>Mala elección de ruta</descripcion>
                </motivo>
              </motivos>';
    
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

  function dame_tipo_tarjetas(){
    $res = '';
    $sql = "SELECT ID_TIPO_TARJETA,
                   DESCRIPCION,
                   DIGITOS,
                   IMAGEN
            FROM ADMIN_TIPO_TARJETAS
            WHERE ESTATUS = 1";
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res = $res.'<tipo_tarjeta>
                       <id_tipo>'.$row->ID_TIPO_TARJETA.'</id_tipo>
                       <descripcion>'.$row->DESCRIPCION.'</descripcion>
                       <digitos>'.$row->DIGITOS.'</digitos>
                       <icono>'.$row->IMAGEN.'</icono>
                     </tipo_tarjeta>';
      }
      mysql_free_result($qry);
    }        
    return $res; 
  }

  function DameTipoTarjeta($id_usuario){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $tipo_tarjetas = dame_tipo_tarjetas();
      if (strlen($tipo_tarjetas) > 0){
        $res = $tipo_tarjetas ;
      }
       
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

  function dame_tarjetas($id_usuario){
    $res = '';
    $sql = "SELECT B.ID_TIPO_TARJETA,
                   B.DESCRIPCION,
                   B.IMAGEN,
                   A.ID_FORMA_PAGO,
                   A.NOMBRE_TARJETA,
                   A.TARJETA_VIEW,
                   A.ESTATUS,
                   A.POR_DEFECTO,
                   AES_DECRYPT(A.MES_VENCIMIENTO, 'myTa4ss1.c0m') as M_VENCE,
                   AES_DECRYPT(A.ANO_VENCIMIENTO, 'myTa4ss1.c0m') AS A_VENCE
            FROM SRV_FORMAS_PAGO A
                 INNER JOIN ADMIN_TIPO_TARJETAS B ON B.ID_TIPO_TARJETA = A.ID_TIPO_TARJETA
            WHERE A.ID_SRV_USUARIO = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res = $res.'<tarjeta>
                       <id_tarjeta>'.$row->ID_FORMA_PAGO.'</id_tarjeta>
                       <id_tipo>'.$row->ID_TIPO_TARJETA.'</id_tipo>
                       <descripcion>'.$row->DESCRIPCION.'</descripcion>
                       <icono>'.$row->IMAGEN.'</icono>
                       <nombre_tdc>'.$row->NOMBRE_TARJETA.'</nombre_tdc>
                       <no_tdc>'.$row->TARJETA_VIEW.'</no_tdc>
                       <estatus>'.$row->ESTATUS.'</estatus>
                       <por_defecto>'.$row->POR_DEFECTO.'</por_defecto>
                       <m_vence>'.$row->M_VENCE.'</m_vence>
                       <a_vence>'.$row->A_VENCE.'</a_vence>
                     </tarjeta>';
      }
      mysql_free_result($qry);
    }        
    return $res; 
  }

  function DameTarjetas($id_usuario){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $tarjetas = dame_tarjetas($id_usuario);
      if (strlen($tarjetas) > 0){
        $res = '<code>0</code>
                <msg>OK</msg>';  
      } else {
        $res = '<code>-1</code>
                <msg>No tiene tarjetas registradas</msg>';  
      }
       
      mysql_close($con); 
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     '.$res.'
                   </Status>'.utf8_encode($tarjetas).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function guarda_tdc($id_usuario,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut){
    $res = '';
    
    $view = substr($tdc,-4);
    if (strlen($tdc) == 16){
      $view = "XXXX XXXX XXXX ".$view;
    } else {
      $view = "XXXX XXXXXX X".$view;  
    }
    $sql="INSERT INTO SRV_FORMAS_PAGO
          SET  ID_SRV_USUARIO  = ".$id_usuario.",
               ID_TIPO_TARJETA = '".$tipo_tdc."', 
               NO_TARJETA = AES_ENCRYPT('".$tdc."', 'myTa4ss1.c0m'), 
               TARJETA_VIEW = '".$view."', 
               NOMBRE_TARJETA = '".$nombre."', 
               MES_VENCIMIENTO = AES_ENCRYPT('".$month."', 'myTa4ss1.c0m'),  
               ANO_VENCIMIENTO = AES_ENCRYPT('".$year."', 'myTa4ss1.c0m'),  
               CODIGO_AUTORIZACION = AES_ENCRYPT('".$cod_aut."', 'myTa4ss1.c0m'), 
               POR_DEFECTO = 0, 
               ESTATUS     = 1,  
               CREADO =  CURRENT_TIMESTAMP";
    if ($qry = mysql_query($sql)){
      $res = '<code>0</code>
              <msg>Su tarjeta fue registrada correctamente</msg>';
    } else {
      $res = '<code>-2</code>
            <msg>No fue posible registrar su Tarjeta</msg>';
    }     
    return $res; 
  }

  function RegistraTDC($id_usuario,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = guarda_tdc($id_usuario,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut);
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


  function actualiza_tdc($id_tarjeta,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut){
    $res = '';
    
    $view = substr($tdc,-4);
    if (strlen($tdc) == 16){
      $view = "XXXX XXXX XXXX ".$view;
    } else {
      $view = "XXXX XXXXXX X".$view;  
    }
    $sql="UPDATE SRV_FORMAS_PAGO
          SET  NO_TARJETA = AES_ENCRYPT('".$tdc."', 'myTa4ss1.c0m'), 
               TARJETA_VIEW = '".$view."', 
               NOMBRE_TARJETA = '".$nombre."', 
               ID_TIPO_TARJETA = '".$tipo_tdc."',
               MES_VENCIMIENTO = AES_ENCRYPT('".$month."', 'myTa4ss1.c0m'),  
               ANO_VENCIMIENTO = AES_ENCRYPT('".$year."', 'myTa4ss1.c0m'),  
               CODIGO_AUTORIZACION = AES_ENCRYPT('".$cod_aut."', 'myTa4ss1.c0m'), 
               POR_DEFECTO = 0, 
               ESTATUS     = 1,  
               CREADO =  CURRENT_TIMESTAMP
          WHERE ID_FORMA_PAGO = '".$id_tarjeta."'";
    if ($qry = mysql_query($sql)){
      $res = '<code>0</code>
              <msg>Su tarjeta fue registrada correctamente</msg>';
    } else {
      $res = '<code>-2</code>
            <msg>No fue posible registrar su Tarjeta</msg>';
    }     
    return $res; 
  }

  function ActualizaTDC($id_tarjeta,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = actualiza_tdc($id_tarjeta,$tipo_tdc,$tdc,$nombre,$month,$year,$cod_aut);
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




  function fija_tdc($id_tarjeta,$id_usuario){
    $res = '<code>-1</code>
            <msg>No fue posible registrar su cambio</msg>';
    $sql="UPDATE SRV_FORMAS_PAGO
          SET  POR_DEFECTO = 0
          WHERE ID_SRV_USUARIO  = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $sql2="UPDATE SRV_FORMAS_PAGO
          SET  POR_DEFECTO = 1
          WHERE ID_FORMA_PAGO = ".$id_tarjeta." AND
                ID_SRV_USUARIO  = ".$id_usuario;
      if ($qry2 = mysql_query($sql2)){    
        $res = '<code>0</code>
              <msg>Su tarjeta fue registrada correctamente</msg>';
      } else {
        $res = '<code>-3</code>
            <msg>No fue posible registrar su Tarjeta</msg>';  
      }
    } else {
      $res = '<code>-2</code>
            <msg>No fue posible registrar su Tarjeta</msg>';
    }     
    return $res; 
  }


  function FijaTarjeta($id_tarjeta,$id_usuario){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = fija_tdc($id_tarjeta,$id_usuario);
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

  function inactiva_tdc($id_tarjeta){
    $res = '<code>-1</code>
            <msg>No fue posible registrar su cambio</msg>';
    $sql="UPDATE SRV_FORMAS_PAGO
          SET  ESTATUS = 0,
               POR_DEFECTO = 0
          WHERE ID_FORMA_PAGO  = ".$id_tarjeta;
    if ($qry = mysql_query($sql)){
        
        $res = '<code>0</code>
              <msg>Su cambio se realizo correctamente</msg>';
    } else {
      $res = '<code>-2</code>
            <msg>No fue posible registrar su cambio</msg>';
    }     
    return $res; 
  }


  function InactivaTarjeta($id_tarjeta){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = inactiva_tdc($id_tarjeta);
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

  function activa_tdc($id_tarjeta){
    $res = '<code>-1</code>
            <msg>No fue posible registrar su cambio</msg>';
    $sql="UPDATE SRV_FORMAS_PAGO
          SET  ESTATUS = 1,
               POR_DEFECTO = 0
          WHERE ID_FORMA_PAGO  = ".$id_tarjeta;
    if ($qry = mysql_query($sql)){
        
        $res = '<code>0</code>
              <msg>Su cambio se realizo correctamente</msg>';
    } else {
      $res = '<code>-2</code>
            <msg>No fue posible registrar su cambio</msg>';
    }     
    return $res; 
  }


  function ActivaTarjeta($id_tarjeta){
   // $idx = -10;
   // $msg = 'El servicio TACCSI, no esta disponible';
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';

    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $res = activa_tdc($id_tarjeta);
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

  function getRandomCode(){
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $su = strlen($an) - 1;
    $resultado = '';
    $codigo = substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1); 
    if(validateCodigo($codigo)){
      $resultado = $codigo;
    }else{
      $codigo = substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1) .
              substr($an, rand(0, $su), 1);       
      $resultado = $codigo;
    }

    return $resultado;
  } 

  function validateCodigo($codeActivacion){
    global $base;;
    $res = false; 
    $sql = "SELECT COUNT(USUARIO) AS EXISTE
              FROM SRV_USUARIOS
              WHERE COD_CONFIRMACION = '".$codeActivacion."'";
    if ($qry=mysql_query($sql)){      
      $row = mysql_fetch_object($qry);
      if ($row->EXISTE == 0){
        $res = true;
      }
      mysql_free_result($qry);
    }       
    return $res;
  }

  function getReservaciones($idUsuario){
    $sResult           = '';
    $siReservacion     = '';
    $sReservaciones    = '';
    $totalReservaciones= 0;
    $codex             = -1;
    $msg               = 'Sin Reservaciones';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if($con){
      //$base = mysql_select_db("BD_TACCSI",$con);
      $base = mysql_select_db("taccsi",$con);
      $sql  = "SELECT R.*, T.DESCRIPCION AS N_TIPO
              FROM ADMIN_RESERVACIONES R
              INNER JOIN ADMIN_TIPO_TAXIS T ON R.ID_TIPO_TAXI = T.ID_TIPO_TAXI
              WHERE R.ID_CLIENTE             = ".$idUsuario."
               AND  R.ID_ESTATUS_RESERVACION = 0
              ORDER BY R.FECHA_RESERVACION ASC";
      if ($qry = mysql_query($sql)){         
        while ($row = mysql_fetch_object($qry)){

          $siReservacion .= "<reservacion>
                                <id_reservacion>".$row->ID_RESERVACION."</id_reservacion>
                                <id_estatus>".$row->ID_ESTATUS_RESERVACION."</id_estatus>
                                <id_formapago>".$row->ID_FORMA_PAGO."</id_formapago>
                                <id_tarjeta>".$row->ID_TARJETA."</id_tarjeta>
                                <id_tipo_taxi>".$row->ID_TIPO_TAXI."</id_tipo_taxi>
                                <tipo_taxi>".$row->N_TIPO."</tipo_taxi>
                                <fecha_reservacion>".$row->FECHA_RESERVACION."</fecha_reservacion>
                                <origen>".$row->ORIGEN."</origen>
                                <origen_latitud>".$row->ORIGEN_LATITUD."</origen_latitud>
                                <origen_longitud>".$row->ORIGEN_LONGITUD."</origen_longitud>
                                <origen_refs>".$row->ORIGEN_REFERENCIAS."</origen_refs>
                                <destino>".$row->DESTINO."</destino>
                                <destino_latitud>".$row->DESTINO_LATITUD."</destino_latitud>
                                <destino_longitud>".$row->DESTINO_LONGITUD."</destino_longitud>
                                <destino_refs>".$row->DESTINO_REFERENCIAS."</destino_refs>
                                <ac>".$row->AC."</ac>
                                <iave>".$row->IAVE."</iave>
                                <wifi>".$row->WIFI."</wifi>
                                <cargador>".$row->CARGADOR."</cargador>
                                ".getServiciosReservacion($row->ID_RESERVACION)."                            
                              </reservacion>";                          
          $totalReservaciones++;
        }    
        mysql_free_result($qry);        
      }
      
      mysql_close($con); 
    }

    if($totalReservaciones>0){
      $codex  = 1;      
      $msg    = 'OK';

      $sReservaciones = '<reservaciones>
                          '.$siReservacion.'
                        </reservaciones>';
    }

    $sResult =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response>
                   <Status>
                     <code>'.$codex.'</code>
                     <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($sReservaciones).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $sResult);
  }

  function genIdReservacion(){
    $res = -1;
    $sql = "LOCK TABLE ADMIN_RESERV_GEN WRITE";
    if ($qry = mysql_query($sql)){
      $sql2 = "INSERT INTO ADMIN_RESERV_GEN (ID_RESERVACION) VALUES (0)";
      mysql_query($sql2);
      $sql3 = "SELECT MAX(ID_RESERVACION) AS ID FROM ADMIN_RESERV_GEN";
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

  function getIdReservacion(){
    $res = false;
    while ($res <> true){
      $id = genIdReservacion();
      $sql = "SELECT COUNT(*) AS ID 
              FROM ADMIN_RESERVACIONES
              WHERE ID_RESERVACION = ".$id;
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

  function registraReservacion($aInsert){
    $res = false;
    global $base;

    $sql = "INSERT INTO ADMIN_RESERVACIONES
            SET ID_RESERVACION          = ".$aInsert['idReservacion'].",
                ID_VIAJE                = NULL,
                ID_ESTATUS_RESERVACION  = 0,
                ID_FORMA_PAGO           = ".$aInsert['pago'].",
                ID_TARJETA              = ".$aInsert['id_tarjeta'].",
                ID_CLIENTE              = ".$aInsert['id_usuario'].",
                ID_TIPO_TAXI            = ".$aInsert['tipo'].",
                FECHA_RESERVACION       ='".$aInsert['fecha_reservacion']."',
                ORIGEN                  ='".$aInsert['origen']."',
                ORIGEN_LATITUD          ='".$aInsert['lat_origen']."',
                ORIGEN_LONGITUD         ='".$aInsert['lon_origen']."',
                ORIGEN_REFERENCIAS      ='".$aInsert['referencias']."',
                DESTINO                 ='".$aInsert['destino']."',
                DESTINO_LATITUD         ='".$aInsert['lat_destino']."',
                DESTINO_LONGITUD        ='".$aInsert['lon_destino']."',
                SOLICITADO_DESDE        ='M',
                DISPOSITIVO_ORIGEN      ='".$aInsert['dispositivo']."',
                CARGADOR                = ".$aInsert['conectores'].",
                AC                      = ".$aInsert['ac'].",
                WIFI                    = ".$aInsert['wifi'].",
                IAVE                    = ".$aInsert['iave'].",
                CREADO                  = CURRENT_TIMESTAMP";
    if ($qry = mysql_query($sql)){
      $res = true;
    } 
    return $res;
  }  

  function usrNuevaReservacion(
                      $usuario,
                      $dispositivo,
                      $push_token,
                      $origen,
                      $destino,
                      $lat_origen,
                      $lon_origen,
                      $lat_destino,
                      $lon_destino,
                      $pago,
                      $referencias,
                      $tipo,
                      $iave,
                      $ac,
                      $conectores,
                      $wifi,
                      $so,
                      $id_tarjeta,
                      $fecha_reservacion,
                      $servicios){    
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);
      $id_usuario = valida_usuario($usuario);
      if ($id_usuario > 0){
        if(validaSinReservaciones($id_usuario,$fecha_reservacion)){
          $idReservacion = 0;
          registra_token($dispositivo,$push_token,$so);
          $idReservacion = getIdReservacion();

          $aInsert  = Array();
          $aInsert['idReservacion']     = $idReservacion;
          $aInsert['fecha_reservacion'] = $fecha_reservacion; 
          $aInsert['id_usuario']        = $id_usuario;
          $aInsert['dispositivo']       = $dispositivo;
          $aInsert['origen']            = $origen; 
          $aInsert['destino']           = $destino;
          $aInsert['lat_origen']        = $lat_origen;
          $aInsert['lon_origen']        = $lon_origen;
          $aInsert['lat_destino']       = $lat_destino;
          $aInsert['lon_destino']       = $lon_destino;
          $aInsert['personas']          = $personas;
          $aInsert['pago']              = $pago;
          $aInsert['referencias']       = $referencias;
          $aInsert['id_tarjeta']        = $id_tarjeta;
          $aInsert['tipo']              = $tipo;
          $aInsert['iave']              = $iave;
          $aInsert['ac']                = $ac;
          $aInsert['conectores']        = $conectores;
          $aInsert['wifi']              = $wifi;

          if(registraReservacion($aInsert)){
            if(registra_servicios($idReservacion,$servicios,'r')){

              $idx = $idReservacion;
              $msg = 'Su reservacion ha sido registrada.'; 
            }else{
              $idx = -2;
              $msg = 'No fue posible asignar su reservacion, intente mas tarde '.$idReservacion;
            }
          }else{
            $idx = -2;
            $msg = 'No fue posible asignar su reservacion, intente mas tarde '.$idReservacion;
          }     
        }else{
          $idx = -3;
          $msg = 'Ya existe una reservacion en ese horario.';
        }             
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

  function validaSinReservaciones($id_cliente,$fecha_reservacion){
    $res = false;
    $sql = "SELECT COUNT(ID_RESERVACION) AS TOTAL
            FROM ADMIN_RESERVACIONES
            WHERE ID_CLIENTE = ".$id_cliente."
             AND  ID_ESTATUS_RESERVACION = 0
             AND  FECHA_RESERVACION BETWEEN '".$fecha_reservacion."'
                                    AND (INTERVAL 1 HOUR + '".$fecha_reservacion."')";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if($row->TOTAL==0){
        $res = true;
      }
      mysql_free_result($qry);
    } 
    return $res;
  }    

  function cancelarReservacion($id_usuario,$id_reservacion){
    $idx = -1;
    $msg = 'Servicio TACSSI no disponible, intente mas tarde.';
    /*Buscar viaje*/
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);
      $aValidation =  validaReservacion($id_usuario,$id_reservacion);
      if($aValidation['ESTATUS']==0){
        if($aValidation['N_ESTATUS']==1 && $aValidation['TIEMPO']>=60){          
           if(cancelReservacion($id_reservacion)){
            $idx = 1;
            $msg = 'Su reservacion ha sido cancelada.';
           }else{
            $idx = -2;
            $msg = 'No fue posible registrar su cancelación. Intente mas tarde.';  
           }
        }else{
          $idx = '3';
          $msg = 'No es posible cancelar la reservacion';
        }
      }else{
        $idx = '2';
        $msg = 'El viaje ya fue cancelado previamente.';        
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

  function validaReservacion($id_usuario,$id_reservacion){
    $res = Array();
    $sql = "SELECT ID_ESTATUS_RESERVACION AS ESTATUS, 
            IF(FECHA_RESERVACION > CURRENT_TIMESTAMP,'1','0') AS N_ESTATUS, 
            (TIMESTAMPDIFF(MINUTE , CURRENT_TIMESTAMP,FECHA_RESERVACION )) AS TIEMPO
            FROM ADMIN_RESERVACIONES
            WHERE ID_RESERVACION = ".$id_reservacion.
            " AND  ID_CLIENTE     = ".$id_usuario." LIMIT 1";    
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res['ESTATUS']    = $row->ESTATUS;
      $res['N_ESTATUS']  = $row->N_ESTATUS;  
      $res['TIEMPO']     = $row->TIEMPO;
      mysql_free_result($qry);
    }
    return $res;
  }

  function cancelReservacion($idReservacion){
    $res = false;
    $sql = "UPDATE ADMIN_RESERVACIONES
            SET ID_ESTATUS_RESERVACION = 3,
                FECHA_CANCELACION = CURRENT_TIMESTAMP
            WHERE ID_RESERVACION = ".$idReservacion." LIMIT 1";       
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }    

  function getStatusReservacion($id_reservacion){    
    $iEstatus   = -1;
    $msgEstatus = 'Servicio TACSSI no disponible, intente mas tarde.';
    $idx        = -2;
    $msg        = 'Sin Viaje';
    $dat        = '';
    //Buscar viaje
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base  = mysql_select_db("BD_TACCSI",$con);
      $aInfo = getInfoReservacion($id_reservacion);

      $iEstatus = $aInfo['ID_ESTATUS_RESERVACION'];

      if($aInfo['ID_ESTATUS_RESERVACION']==0){
        $msgEstatus = 'Reservacion Pendiente';
      }else if($aInfo['ID_ESTATUS_RESERVACION']==1){
        $msgEstatus = 'Reservacion Atendida';
        $id_viaje   = 

        $dat = dame_taxista($aInfo['ID_TAXISTA'],$aInfo['ID_VIAJE']);
        if (strlen($dat) == 0){
          $idx = -2;
          $msg = 'No se logro obtener la información del TACCSISTA, intente mas tarde';
        } else {
          $idx = 0;
          $msg = 'OK';
        }

      }else if($aInfo['ID_ESTATUS_RESERVACION']==2){
        $msgEstatus = 'Reservacion cancelada';
      }
      mysql_close($con);
    }
    $foto = '';

    if($aInfo['FOTO']=="" or $aInfo['FOTO']=="null"){
        $foto="http://taccsi.com/images/taxis/sin_foto_perfil.png";
    }else{
        $foto="http://taccsi.com/images/taxis/".$aInfo['FOTO'];
    }

    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                      <codeReservacion>'.$iEstatus.'</codeReservacion>
                      <msgReservacion>'.$msgEstatus.'</msgReservacion>
                      <code>'.$idx.'</code>
                      <msg>'.$msg.'</msg>
                   </Status>
                   '.utf8_encode($dat).'
                    <reservacion>
                      <id_reservacion>'.$aInfo['ID_RESERVACION'].'</id_reservacion>
                      <id_estatus>'.$aInfo['ID_ESTATUS_RESERVACION'].'</id_estatus>
                      <id_formapago>'.$aInfo['ID_FORMA_PAGO'].'</id_formapago>
                      <id_tarjeta>'.$aInfo['ID_TARJETA'].'</id_tarjeta>
                      <id_tipo_taxi>'.$aInfo['ID_TIPO_TAXI'].'</id_tipo_taxi>
                      <fecha_reservacion>'.$aInfo['FECHA_RESERVACION'].'</fecha_reservacion>
                      <origen>'.$aInfo['ORIGEN'].'</origen>
                      <origen_latitud>'.$aInfo['ORIGEN_LATITUD'].'</origen_latitud>
                      <origen_longitud>'.$aInfo['ORIGEN_LONGITUD'].'</origen_longitud>
                      <origen_refs>'.$aInfo['ORIGEN_REFERENCIAS'].'</origen_refs>
                      <destino>'.$aInfo['DESTINO'].'</destino>
                      <destino_latitud>'.$aInfo['DESTINO_LATITUD'].'</destino_latitud>
                      <destino_longitud>'.$aInfo['DESTINO_LONGITUD'].'</destino_longitud>
                      <destino_refs>'.$aInfo['DESTINO_REFERENCIAS'].'</destino_refs>
                      <ac>'.$aInfo['AC'].'</ac>
                      <iave>'.$aInfo['IAVE'].'</iave>
                      <wifi>'.$aInfo['WIFI'].'</wifi>
                      <cargador>'.$aInfo['CARGADOR'].'</cargador> 
                      <cliente>'.$aInfo['NOMBRE'].' '.$aInfo['APATERNO'].' '.$aInfo['AMATERNO'].'</cliente>
                      <telefono>'.$aInfo['TELEFONO'].'</telefono>
                      <rating>'.$aInfo['RATING'].'</rating>
                      <foto>'.$foto.'</foto>
                      '.getServiciosReservacion($aInfo['ID_RESERVACION']).'
                    </reservacion>                 
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);     
  }  

  function oprTomarReservacion($id_usuario,$id_reservacion,$dispositivo){
    /*Funccion para asignar el viaje a un taxista, cuando ya fue confirmado*/
    $idx = -1;
    $msg = '';
    $id_viaje = -1;
    $codigo   = -1; 
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");

    if($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);
      if(valida_reservacion_libre($id_reservacion)){
        $aInfo    = getinfoReservacion($id_reservacion);
        $id_viaje = dame_id_viaje();

        if(registra_viaje($id_viaje,
                         $aInfo['ID_CLIENTE'],
                         $aInfo['DISPOSITIVO_ORIGEN'], 
                         $aInfo['ORIGEN'], 
                         $aInfo['DESTINO'], 
                         $aInfo['ORIGEN_LATITUD'], 
                         $aInfo['ORIGEN_LONGITUD'], 
                         $aInfo['DESTINO_LATITUD'],
                         $aInfo['DESTINO_LONGITUD'], 
                         0,
                         $aInfo['ID_FORMA_PAGO'],
                         0,
                         $aInfo['ORIGEN_REFERENCIAS'],
                         $aInfo['ID_TARJETA'],
                         $aInfo['ID_TIPO_TAXI'],
                         $aInfo['IAVE'],
                         $aInfo['AC'],
                         $aInfo['CARGADOR'],
                         $aInfo['WIFI'])){
          if (tomar_reservacion($id_usuario,$id_reservacion,$id_viaje)){
            $codigo = dame_codigo_viaje($id_viaje);
            $token  = dame_pushtoken_cliente($id_viaje);
            marca_tomar_reservacion($id_reservacion,$id_usuario,$id_viaje,$codigo);
            if (strlen($token) > 0){

              $so = dame_so_pushtoken($token);
              //envia_push_dev('Su TACCSI va en camino, Código de confirmación: '.$codigo.' .@'.$id_usuario,$pushkey_taccsista,$so);
              $str_inser_log = "id_usuario =".$id_usuario.
                        ", id_viaje = ".$id_viaje.
                        ", dispositivo = ".$dispositivo.
                        ", token_cliente = ".$token;

              InsertaLog("oprTomarReservacion",$str_inser_log,$push_token);

              //envia_push('dev','usuario','Su TACCSI va en camino, Confirmación: '.$codigo.' @'.$id_usuario,$token,$so,'Su TACCSI va en camino, Confirmación: '.$codigo);
              $push_token = dame_pushtoken_viaje($id_viaje,'U');
              if (strlen($push_token) > 0){
                $so = dame_so_pushtoken($push_token);
                //envia_push('dev','usuario', 'Su TACCSI va en camino,'.$id_viaje.'@'.$codigo.'@'.$id_usuario ,$push_token,$so,'Su TACCSI va en camino');
                envia_push('dev',
                            'usuario', 
                            'Su TACCSI va en camino,'.$id_viaje.'@'.$codigo.' @'.$id_usuario.'@'.$aInfo['ORIGEN_LATITUD'].'@'.$aInfo['ORIGEN_LONGITUD'].'@'.$aInfo['DESTINO_LATITUD'].'@'.$aInfo['DESTINO_LONGITUD'] , 
                            $push_token,
                            $so,
                            'Su TACCSI va en camino');
              }
            }
            $idx = 0;
            $msg = 'Su viaje ha sido asignado.';
          } else {
            $idx = -4;
            $msg = 'No fue posible asignar la reservacion.'.$x;   
          }
        } else {
          $idx = -3;
          $msg = 'No fue posible atender la reservacion.'.$x;   
        }      
      } else {
        $idx = -2;
        $msg = 'La reservacion ya fue atendida.';  
      }
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
                     <id_viaje>'.$id_viaje.'</id_viaje>
                     <codigo>'.$codigo.'</codigo>
                   </Status>
                 </Response>
               </space>';

               
    return new soapval('return', 'xsd:string', $res);
  }

  function valida_reservacion_libre($id_reservacion){
    $res = false;
    /*sql para validar que el estatus del viaje sea libre*/ 
    $sql = "SELECT ID_ESTATUS_RESERVACION
            FROM ADMIN_RESERVACIONES
            WHERE ID_RESERVACION = ".$id_reservacion;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      
      if ($row->ID_ESTATUS_RESERVACION == 4){
        $res = true;
      }
      
      mysql_free_result($qry);
    }
    return $res;
  }  

  function tomar_reservacion($id_usuario,$id_reservacion,$id_viaje){
    $res = false;
    //marca el viaje al taxista
    //$x = marca_viaje($id_usuario,$id_viaje,$codigo);
    if (marca_reservacion($id_usuario,$id_reservacion,$id_viaje)){ 
      $res = true;
    } 
    return $res;
  }  

  function marca_reservacion($id_usuario,$id_reservacion,$id_viaje){
    $res = false;
    //revisar
    $sql = 'UPDATE ADMIN_RESERVACIONES 
            SET ID_VIAJE               = '.$id_viaje.',
                ID_ESTATUS_RESERVACION = 1,
                ATENDIDO = CURRENT_TIMESTAMP
            WHERE ID_RESERVACION = '.$id_reservacion;
    if ($qry = mysql_query($sql)){
      $res = true;
    }  
    return $res;
  }  

  function getinfoReservacion($id_reservacion){
    $res = Array();
    /*
    $sql = "SELECT *
            FROM ADMIN_RESERVACIONES
            WHERE ID_RESERVACION = ".$id_reservacion." LIMIT 1";    */
    $sql = "SELECT R.*, U.APATERNO,U.AMATERNO, U.RATING, U.IMAGEN AS FOTO,U.NOMBRE,U.TELEFONO
          FROM ADMIN_RESERVACIONES R
          INNER JOIN SRV_USUARIOS U ON R.ID_CLIENTE = U.ID_SRV_USUARIO
          WHERE R.ID_RESERVACION = ".$id_reservacion."
          LIMIT 1";            
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_array($qry);
      $res = $row;
      mysql_free_result($qry);
    }
    return $res;
    
  }

  function marca_tomar_reservacion($id_reservacion,$id_usuario,$id_viaje,$codigo_viaje){
    $sql = "UPDATE RESERV_ASIGNACIONES SET ACEPTO = 1 WHERE ID_RESERVACION = ".$id_reservacion." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      registra_asignacion($id_viaje,$id_usuario);
      marca_tomar_viaje($id_viaje,$id_usuario);
      tomar_viaje($id_usuario,$id_viaje,$codigo_viaje);
      $res = true;
    }
    return $res;
  }

  function oprRechazarReservacion($id_reservacion,$id_usuario){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");

    if($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);

      if(marcaRechazoAsignacion($id_reservacion,$id_usuario)){
        $rechazados = rechazados_reserv($id_reservacion); 
        $asignados  = asignados_reserv($id_reservacion); 

        if ($rechazados == $asignados){
          marca_rechazo_Reserv($id_reservacion);
          $aInfo = getInfoReservacion($id_reservacion);
          $token = dame_pushtoken_cliente($aInfo['ID_VIAJE']);
          if (strlen($token) > 0){
            $so = dame_so_pushtoken($token);
            envia_push('dev',
                       'usuario',
                       'No hay Taccsistas disponibles intente mas tarde.@'.$id_reservacion.'@',
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

  function marcaRechazoAsignacion($id_reservacion,$id_usuario){
    $sql = "UPDATE RESERV_ASIGNACIONES SET ACEPTO = 0 WHERE ID_RESERVACION = ".$id_reservacion." AND ID_TACCSISTA = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }

  function marca_rechazo_Reserv($id_reservacion){
    $res = false;
    /*revisar*/
    $sql = 'UPDATE ADMIN_RESERVACIONES 
            SET ID_ESTATUS_RESERVACION = 3,
                RECHAZADO = CURRENT_TIMESTAMP
            WHERE ID_RESERVACION = '.$id_reservacion;
    if ($qry = mysql_query($sql)){
      $res = true;
    }  
    return $res;
  }  

  function rechazados_reserv($id_reservacion){
    $res = '';
    $sql = "SELECT  COUNT(*) AS TOTAL
                  FROM RESERV_ASIGNACIONES
                  WHERE ACEPTO = 0  AND
                        ID_RESERVACION = ".$id_reservacion;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->TOTAL; 
      mysql_free_result($qry);
    } 
    return $res;
  }

  function asignados_reserv($id_reservacion){
    $res = '';
    $sql = "SELECT  COUNT(*) AS TOTAL
                  FROM RESERV_ASIGNACIONES
                  WHERE ID_RESERVACION = ".$id_reservacion;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res = $row->TOTAL; 
      mysql_free_result($qry);
    } 
    return $res;
  }  

  function dameViajeActivo($id_usuario){
    $idx = -1;
    $msg = 'Servicio TACCSI no disponible, intente mas tarde.';  
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $dat  = getViajeActivo($id_usuario);
      if (strlen($dat) > 0){
        $idx = 0;
        $msg = 'OK';
      } else {
        $idx = -2;
        $msg = 'No hay viajes Activos.';   
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

  function getViajeActivo($id_usuario){
    $res = "";
    global $base;
    $sql = "SELECT A.ID_VIAJES,
                    B.NOMBRE,
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
                   A.ORIGEN_REFERENCIAS,
                   A.CLAVE_VIAJE,
                   A.IAVE,
                   A.WIFI,
                   A.AC,
                   A.CARGADOR,
                   A.MONTO_TAXISTA,
                   A.TIEMPO_VIAJE,
                   A.DISTANCIA_TAXISTA,
                   E.ESTATUS,
                   A.ID_SRV_ESTATUS,
                   A.ID_TAXISTA,
                   A.ID_FORMA_PAGO,
                   A.ID_TARJETA,
                   IF(A.EXTRAS IS NULL,'0.00',A.EXTRAS) AS N_EXTRAS                  
            FROM ADMIN_VIAJES A
              INNER JOIN SRV_USUARIOS B ON A.ID_CLIENTE = B.ID_SRV_USUARIO 
              INNER JOIN ADMIN_FORMA_PAGO C ON A.ID_FORMA_PAGO = C.ID_FORMA_PAGO
              INNER JOIN SRV_ESTATUS      E ON A.ID_SRV_ESTATUS = E.ID_ADMIN_ESTATUS 
            WHERE  A.ID_SRV_ESTATUS IN (2,5) 
              AND  A.ID_CLIENTE     = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if (mysql_num_rows($qry) > 0){

        if($row->FOTO=="" or $row->FOTO=="null"){
           $foto_="http://taccsi.com/images/taxis/sin_foto_perfil.png";
        }else{
           $foto_="http://taccsi.com/images/taxis/".$row->FOTO;
        }

        $infoTaccista = dame_taxista($row->ID_TAXISTA,$row->ID_VIAJES);

        $res = "<viaje>
               <id_viaje>".$row->ID_VIAJES."</id_viaje>
               <id_taxista>".$row->ID_TAXISTA."</id_taxista>
               <clave>".$row->CLAVE_VIAJE."</clave>
               <id_estatus>".$row->ID_SRV_ESTATUS."</id_estatus>
               <id_formapago>".$row->ID_FORMA_PAGO."</id_formapago>
               <id_tarjeta>".$row->ID_TARJETA."</id_tarjeta>
               <estatus>".$row->ESTATUS."</estatus>
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
               <tarifa>banderazo=13.10@costo_minuto=1.73@costo_distancia=1.3@mts=250@nocturno=20@comision=3@inicio_nocturno=23:00@fin_nocturno=06:00</tarifa>               
               <monto>".$row->MONTO_TAXISTA."</monto>
               <extras>".$row->N_EXTRAS."</extras>
               <distancia>".$row->DISTANCIA_TAXISTA."</distancia>
               <tiempo>".$row->TIEMPO_VIAJE."</tiempo>
               ".getServiciosViaje($row->ID_VIAJES)."            
              </viaje>"; 
        $res .= $infoTaccista;                
      }
      mysql_free_result($qry);
    } 
    return $res;
  }  

  function registra_usuariofb($nombre,$apaterno,$amaterno,$movil,$email,$password,$dispositivo,$img_perfil){
    $res = false;
    $codeActivacion = getRandomCode();
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
              VIAJES,
              COD_CONFIRMACION,
              TIPO_REGISTRO,
              PERFIL_FACEBOOK,
              IMAGEN
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
              0,
              '".$codeActivacion."',
              'Facebook',
              '".$password."',       
              '".$img_perfil."'
            )";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;
  }  

  function RegistraUsuarioFacebook($nombre,$apaterno,$amaterno,$movil,$email,$regID,$img_perfil,$dispositivo,$push_token,$so){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $dat = '';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    //$con = mysql_connect("localhost","root","root");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);
      if (valida_correo($email,'usuario')){
        if (valida_telefono($email,'usuario')){

          if (registra_usuariofb($nombre,$apaterno,$amaterno,$movil,$email,$regID,$dispositivo,$img_perfil)){
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

  function usrNuevoLugar($usuario,$descripcion,$latitud,$longitud,$direccion){    
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $con = mysql_connect("localhost","dba","t3cnod8A!");

    if($con){
      $base = mysql_select_db("taccsi",$con);
      if(registraLugar($usuario,$descripcion,$latitud,$longitud,$direccion)){
        $msg = 'OK';
        $idx = 0;
      } else {
        $msg = 'No es posible registrar el lugar.';
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

  function usrEditarLugar($idLugar,$usuario,$descripcion,$latitud,$longitud,$direccion){
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $con = mysql_connect("localhost","dba","t3cnod8A!");

    if($con){
      $base = mysql_select_db("taccsi",$con);
      if(valida_lugar($idLugar,$usuario)){
        if(actualizaLugar($idLugar,$usuario,$descripcion,$latitud,$longitud,$direccion)){
          $msg = 'OK';
          $idx = 0;
        } else {
          $msg = 'No es posible actualizar el lugar.';
          $idx = -2; 
        }
      }else{
        $msg = 'No es posible actualizar el lugar.';
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
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }  

  function usrEliminarLugar($idLugar,$usuario){
    $msg = 'No hay conexión con el servicio TACCSI';
    $idx = -1;
    $con = mysql_connect("localhost","dba","t3cnod8A!");

    if($con){
      $base = mysql_select_db("taccsi",$con);
      if(valida_lugar($idLugar,$usuario)){
        if(eliminaLugar($idLugar,$usuario)){
          $msg = 'OK';
          $idx = 0;
        } else {
          $msg = 'No es posible eliminar el lugar.';
          $idx = -2; 
        }
      }else{
        $msg = 'No es posible eliminar el lugar.';
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
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);
  }

  function DameLugares($usuario){
    $res = '<code>-1</code>
            <msg>El servicio TACCSI, no esta disponible</msg>';
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      $lugares = dame_lugares($usuario);
      if (strlen($lugares) > 0){
        $res = '<code>0</code>
                <msg>OK</msg>';  
      } else {
        $res = '<code>-1</code>
                <msg>No tiene lugares registrados</msg>';  
      }
       
      mysql_close($con); 
    }
    $res =  '<?xml version="1.0" encoding="UTF-8"?>
               <space>
                 <Response> 
                   <Status>
                     '.$res.'
                   </Status>'.utf8_encode($lugares).'
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res);    
  }  

  function registraLugar($id_cliente,$descripcion,$latitud,$longitud,$direccion){  
    $res = false;
    $sql = "INSERT INTO SRV_PUNTOS_FAVORITOS SET
            ID_SRV_USUARIO  = ".$id_cliente.",
            DESCRIPCION     = '".$descripcion."',
            LATITUD         =  ".$latitud.",
            LONGITUD        =  ".$longitud.",
            DIRECCION_COMPLETA = '".$direccion."',          
            FECHA_REGISTRO  = CURRENT_TIMESTAMP,
            ESTATUS         = 1";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;    
  }

  function actualizaLugar($idLugar,$id_cliente,$descripcion,$latitud,$longitud,$direccion){  
    $res = false;
    $sql = "UPDATE SRV_PUNTOS_FAVORITOS SET
              DESCRIPCION     = '".$descripcion."',
              LATITUD         =  ".$latitud.",
              LONGITUD        =  ".$longitud.",
              DIRECCION_COMPLETA = '".$direccion."'
            WHERE ID_PUNTO        = ".$idLugar."
              AND ID_SRV_USUARIO  = ".$id_cliente." LIMIT 1";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;    
  }  

  function valida_lugar($id_lugar,$id_usuario){
    global $base;
    $res = false; 
    $sql = "SELECT COUNT(ID_PUNTO) AS EXISTE
            FROM SRV_PUNTOS_FAVORITOS
            WHERE ID_PUNTO       = ".$id_lugar."
              AND ID_SRV_USUARIO = ".$id_usuario;
    if ($qry=mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      if ($row->EXISTE == 1){
        $res = true;
      }
      mysql_free_result($qry);
    }       
    return $res; 
  }

  function eliminaLugar($idLugar,$id_cliente){
    $res = false;
    $sql = "DELETE FROM SRV_PUNTOS_FAVORITOS
            WHERE ID_PUNTO        = ".$idLugar."
              AND ID_SRV_USUARIO  = ".$id_cliente." 
            LIMIT 1";
    if ($qry = mysql_query($sql)){
      $res = true;
    }
    return $res;    
  }  

  function dame_lugares($id_usuario){
    $res = '<sitios>';
    $sql = "SELECT ID_PUNTO,DESCRIPCION,DIRECCION_COMPLETA,LATITUD,LONGITUD
            FROM SRV_PUNTOS_FAVORITOS
            WHERE ID_SRV_USUARIO = ".$id_usuario;
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res = $res.'<sitio>
                       <id>'.$row->ID_PUNTO.'</id>
                       <nombre>'.$row->DESCRIPCION.'</nombre>   
                       <direccion>'.$row->DIRECCION_COMPLETA.'</direccion>
                       <latitud>'.$row->LATITUD.'</latitud>
                       <longitud>'.$row->LONGITUD.'</longitud>
                     </sitio>';
      }
      mysql_free_result($qry);
    }
    $res .= '</sitios>';
    return $res; 
  }   

  function getServiciosTaccsi($idtaccsi){
    $res = "<servicios>";
    global $base;
    $sql = "SELECT S.ID_SERVICIO, S.DESCRIPCION, S.ICONO, S.ICONO_AMARILLO, S.ICONO_BLANCO
            FROM ADMIN_TAXI_SERVICIOS R
            INNER JOIN ADMIN_SERVICIOS S ON R.ID_SERVICIO = S.ID_SERVICIO
            WHERE R.ID_TAXI  = ".$idtaccsi;
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res .= '<servicio>
                    <id_tipo>'.$row->ID_SERVICIO.'</id_tipo>
                    <descripcion>'.$row->DESCRIPCION.'</descripcion>
                    <icono_negro>'.$row->ICONO.'</icono_negro>
                    <icono_amarillo>'.$row->ICONO_AMARILLO.'</icono_amarillo>
                    <icono_blanco>'.$row->ICONO_BLANCO.'</icono_blanco>
                 </servicio>';             
      }
      mysql_free_result($qry);
    } 
    $res .= '</servicios>';
    return $res;    
  }  

  function getServiciosReservacion($idReservacion){
    $res = "<servicios>";
    global $base;
    $sql = "SELECT S.ID_SERVICIO, S.DESCRIPCION, S.ICONO, S.ICONO_AMARILLO, S.ICONO_BLANCO
            FROM ADMIN_RESERVACIONES_SERVICIOS R
            INNER JOIN ADMIN_SERVICIOS S ON R.ID_SERVICIO = S.ID_SERVICIO
            WHERE R.ID_RESERVACION  = ".$idReservacion;
    if ($qry = mysql_query($sql)){
      while ($row = mysql_fetch_object($qry)){
        $res .= '<servicio>
                    <id_tipo>'.$row->ID_SERVICIO.'</id_tipo>
                    <descripcion>'.$row->DESCRIPCION.'</descripcion>
                    <icono_negro>'.$row->ICONO.'</icono_negro>
                    <icono_amarillo>'.$row->ICONO_AMARILLO.'</icono_amarillo>
                    <icono_blanco>'.$row->ICONO_BLANCO.'</icono_blanco>
                 </servicio>';             
      }
      mysql_free_result($qry);
    } 
    $res .= '</servicios>';
    return $res;    
  }    

  function usrValidaUsuario($usuario){
    $idx = -1;
    $msg = 'El servicio TACSSI no esta disponible, intente mas tarde.';
    $tipo_registro = '';
    //$con = mysql_connect("localhost","root","root");
    $con = mysql_connect("localhost","dba","t3cnod8A!");
    if ($con){
      $base = mysql_select_db("taccsi",$con);
      //$base = mysql_select_db("BD_TACCSI",$con);

      $aResult = usuario_existe($usuario);
      if($aResult['existe']==1){
        $idx = -1;
        $msg = 'El correo ya esta en uso.';
        $tipo_registro = $aResult['t_registro'];
      }else{
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
                     <tipo_registro>'.$tipo_registro.'</tipo_registro>
                   </Status>
                 </Response>
               </space>';
    return new soapval('return', 'xsd:string', $res); 
  }  

  function usuario_existe($usuario){
    $res = Array();
    global $base;
    $sql = "SELECT COUNT(ID_SRV_USUARIO) AS TOTAL,IF(TIPO_REGISTRO='Facebook','facebook','email') AS N_REGISTRO
            FROM SRV_USUARIOS
            WHERE UPPER(USUARIO) = UPPER('".$usuario."')";
    if ($qry = mysql_query($sql)){
      $row = mysql_fetch_object($qry);
      $res['existe'] = $row->TOTAL;
      $res['t_registro'] = $row->N_REGISTRO; 
      mysql_free_result($qry);
    } 
    return $res;
  }  

   function registra_servicios($idObject,$aServicios,$tipo){
    $res = false;
    $countValidator=0;
    $total = 0;
    if($aServicios!=""){
      $aData = explode($aServicios,",");
      $total = count($aData);
      for($i=0;$i<count($aData);$i++){
        if($tipo=='s'){//viaje
          $sql = "INSERT INTO ADMIN_VIAJES_SERVICIOS 
                  SET ID_VIAJE    = ".$idObject.", 
                      ID_SERVICIO = ".$aData[$i];
        }else if($tipo=='r'){//servicio
          $sql = "INSERT INTO ADMIN_RESERVACIONES_SERVICIOS
                  SET ID_RESERVACION = ".$idObject.", 
                      ID_SERVICIO    = ".$aData[$i];
        }

        if($qry = mysql_query($sql)){
          $countValidator++;            
        }
      }

      if($total==$countValidator){
        $res = true;
      }
    }else{
      $res = true;
    }
    return $res;
  }
  
  $server->service(@$HTTP_RAW_POST_DATA);   
?>