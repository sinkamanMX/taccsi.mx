<?php
	header('Content-type: text/plain; charset=utf-8');
	error_reporting(E_ALL);
	//$conexion = new mysqli('localhost','root','root','BD_TACCSI') or die("Some error occurred during connection " . mysqli_error($conexion));
	$conexion = new mysqli('localhost','dba','t3cnod8A!','taccsi') or die("Some error occurred during connection " . mysqli_error($conexion));
	
	$sText 	= 'Aviso: Este correo electrónico y sus archivos, pueden contener información de carácter confidencial y privado para uso exclusivo del destinatario. Si usted no es el destinatario a quien se dirige el presente correo electrónico, favor de contactar al remitente respondiendo al presente correo y eliminar el correo original incluyendo sus archivos, así como cualesquiera copia del mismo. A través de este correo se le notifica que está estrictamente prohibida su distribución, copia, o cualquier uso de este correo electrónico y sus archivos. Gracias.';
	$sShortMsg = "Es un placer servirle y proporciónale el servicio que se merece,desde ahora podra solicitar un taccsi desde donde se encuentre.";
	$sInfo	= 'Información';
	$sPass	= 'Contraseña';

  	$sql = "SELECT *
			FROM SRV_USUARIOS 
			WHERE COD_CONFIRMACION IS NOT NULL 
			LIMIT 20";
  	$query = mysqli_query($conexion, $sql);
  	$count = 0;
	while($result = mysqli_fetch_array($query)){

		ob_start();
		include('tBienvenida.html');
		$lBody = ob_get_clean();		

		$nameUsuario  = $result['NOMBRE']." ".$result['APATERNO']." ".$result['AMATERNO'];
		$sMensaje	  = 'Bienvenido a Taccsi';

		$lBody = str_ireplace('@_nombre_@'  , $nameUsuario   	   , $lBody);
		$lBody = str_ireplace('@_usuario_@' , $result['USUARIO']   , $lBody);
		$lBody = str_ireplace('@_password_@', $result['PASSWORD']  , $lBody);
		$lBody = str_ireplace('@_aviso_@'   , utf8_decode($sText)  , $lBody);
		$lBody = str_ireplace('@_smsg_@'    , utf8_decode($sShortMsg), $lBody);
		$lBody = str_ireplace('@_sinforms_@', utf8_decode($sInfo)  , $lBody);
		$lBody = str_ireplace('@_spassword_@',utf8_decode($sPass) , $lBody);

		$aMailer    = Array(
			'inputDestinatarios' => $nameUsuario,
			'inputEmails' 		 => $result['USUARIO'],
			'inputTittle' 		 => $sMensaje,
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com.mx',
			'inputFromEmail' 	 => 'Taccsi'
		);

		$insert = insertMailing($aMailer);
		if($insert){
			setMarkRow($result['ID_SRV_USUARIO']);
		}
	}

  	function insertMailing($data){
	    global $conexion;
	    $result = false;
 		$sql="INSERT INTO SYS_MAILING
				SET NOMBRES_DESTINATARIOS	= '".$data['inputDestinatarios']."', 
					DESTINATARIOS			= '".$data['inputEmails']."',
					TITULO_MSG			 	= '".$data['inputTittle']."',
					CUERPO_MSG				= '".$data['inputBody']."',
					REMITENTE_NOMBRE		= '".$data['inputFromName']."',
					REMITENTE_EMAIL			= '".$data['inputFromEmail']."',					
					FECHA_CREADO			= CURRENT_TIMESTAMP,
					ESTATUS 				= 0";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}	

  	function setMarkRow($idUsuario){
	    global $conexion;
	    $result = false;
	      $sql ="UPDATE SRV_USUARIOS 
	            SET COD_CONFIRMACION	= NULL
	            WHERE ID_SRV_USUARIO	= $idUsuario LIMIT 1";
	    $query  = mysqli_query($conexion, $sql);
	    if($query){
	      $result= true;
	    }
	    return $result;   
  	}