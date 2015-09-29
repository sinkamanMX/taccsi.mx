<?php
ini_set('mbstring.internal_encoding','UTF-8');
$sAviso = 'Aviso: Este correo electrónico y sus archivos, pueden contener información de carácter confidencial y privado para uso exclusivo del destinatario. Si usted no es el destinatario a quien se dirige el presente correo electrónico, favor de contactar al remitente respondiendo al presente correo y eliminar el correo original incluyendo sus archivos, así como cualesquiera copia del mismo. A través de este correo se le notifica que está estrictamente prohibida su distribución, copia, o cualquier uso de este correo electrónico y sus archivos. Gracias.';
//201.131.96.45
include 'functions.php';
$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));

if (!empty($_POST)){

  $data['success'] = true;
  $_POST  = multiDimensionalArrayMap('cleanEvilTags', $_POST);
  $_POST  = multiDimensionalArrayMap('cleanData', $_POST);

  $name    = @$_POST["name"];
  $email   = @$_POST["email"];
  $comment = @$_POST["comment"];
  $phone   = @$_POST["phone"];
  
  if($name == "")
   $data['success'] = false;
 
   if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) 
     $data['success'] = false;

   if($comment == "")
     $data['success'] = false;

   if($data['success'] == true){

    ob_start();
    include('solicitud_informacion.html');
    $lBodyUda = ob_get_clean();

    $lBodyUda = str_ireplace('@_mail_@',    $email  , $lBodyUda);
    $lBodyUda = str_ireplace('@_phone_@',   $phone  , $lBodyUda);
    $lBodyUda = str_ireplace('@_name_@',    utf8_decode($name)   , $lBodyUda);
    $lBodyUda = str_ireplace('@_comment_@', utf8_decode($comment), $lBodyUda);
    $lBodyUda = str_ireplace('@_aviso_@',   utf8_decode($sAviso) , $lBodyUda);
    
      $aUDA    = Array(
        'inputDestinatarios'=> utf8_decode('Atención a Clientes'),
        'inputEmails'       => 'contacto@taccsi.com',
        'inputTittle'       => 'Formulario de Contacto',
        'inputBody'         => $lBodyUda,
        'inputFromName'     => 'contacto@taccsi.com',
        'inputFromEmail'    => 'Taccsi'
      );

      $insert = insertMailing($aUDA);       
      $data['success'] = true;
      echo json_encode($data);
    }
}

function insertMailing($data){
  global $conexion;
  $result = false;
  $sql="INSERT INTO SYS_MAILING
      SET NOMBRES_DESTINATARIOS = '".$data['inputDestinatarios']."', 
        DESTINATARIOS         = '".$data['inputEmails']."',
        TITULO_MSG            = '".$data['inputTittle']."',
        CUERPO_MSG            = '".$data['inputBody']."',
        REMITENTE_NOMBRE      = '".$data['inputFromName']."',
        REMITENTE_EMAIL       = '".$data['inputFromEmail']."',
        FECHA_CREADO          = CURRENT_TIMESTAMP,
        ESTATUS               = 0";
    $query  = mysqli_query($conexion, $sql);
    if($query){
      $result= true;
    }
    return $result;   
  }