<?php
class My_Controller_Htmlmailing
{
	public $realPath='/var/www/vhosts/sima/htdocs/public';
	//public $realPath='/Users/itecno2/Documents/workspace/taccsi.mx/public';
	
	public function documentsComplete($data){
		$cMailing   = new My_Model_Mailing();	
		ob_start();
		include($this->realPath.'/layouts/mail/docscomplete.html');
		
		$sName = $data['NOMBRE'].' '.$data['APATERNO'].' '.$data['AMATERNO'];
		$lBody = ob_get_clean();
		$lBody = str_ireplace('@_usuario_@', 	@$data['NICKNAME'] , $lBody);
		$lBody = str_ireplace('@_empresa_@', 	@$data['N_EMPRESA'], $lBody);
		$lBody = str_ireplace('@_nombre_@', 	@$sName			, $lBody);
		
		$aMailerUda    = Array(
			'inputDestinatarios' => 'contacto',
			'inputEmails' 		 => 'contacto@taccsi.com',
			'inputTittle' 		 => 'Documentacion Completa',
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
							
		$cMailing->insertRow($aMailerUda);		
	}
	
	public function documentsValidate($data){
		$cMailing   = new My_Model_Mailing();	
		ob_start();
		include($this->realPath.'/layouts/mail/notifcomplete.html');
		
		$sName = $data['NOMBRE'].' '.$data['APATERNO'].' '.$data['AMATERNO'];
		$lBody = ob_get_clean();
		$lBody = str_ireplace('@_usuario_@', 	@$data['NICKNAME'] , $lBody);
		$lBody = str_ireplace('@_empresa_@', 	@$data['N_EMPRESA'], $lBody);
		$lBody = str_ireplace('@_nombre_@', 	@$sName			, $lBody);
		
		$aMailerUda    = Array(
			'inputDestinatarios' => $sName,
			'inputEmails' 		 => @$data['NICKNAME'],
			'inputTittle' 		 => 'Problemas con los documentos',
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
							
		$cMailing->insertRow($aMailerUda);			
	}
	
	public function documentsinValidate($data,$iErrors){
		$cMailing   = new My_Model_Mailing();	
		ob_start();
		include($this->realPath.'/layouts/mail/notifincomplete.html');
		
		$sName = $data['NOMBRE'].' '.$data['APATERNO'].' '.$data['AMATERNO'];
		$lBody = ob_get_clean();
		$lBody = str_ireplace('@_usuario_@', 	@$data['NICKNAME'] 	, $lBody);
		$lBody = str_ireplace('@_nombre_@', 	@$sName				, $lBody);
		$lBody = str_ireplace('@_errors_@', 	utf8_decode(@$iErrors)			, $lBody);
		
		$aMailerUda    = Array(
			'inputDestinatarios' => $sName,
			'inputEmails' 		 => @$data['NICKNAME'],
			'inputTittle' 		 => 'Documentacion Completa',
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
							
		$cMailing->insertRow($aMailerUda);			
	}	
	
	public function documentsCompleteCar($data){
		$cMailing   = new My_Model_Mailing();	
		ob_start();
		include($this->realPath.'/layouts/mail/docscompleteCar.html');
				
		$lBody = ob_get_clean();
		$lBody = str_ireplace('@_empresa_@', 		@$data['NOMBRE_EMPRESA']	, $lBody);
		$lBody = str_ireplace('@_nombrechofer_@', 	@$data['NOMBRE_CHOFER']	, $lBody);
		$lBody = str_ireplace('@_placas_@', 		@$data['PLACAS']	, $lBody);
		
		$aMailerUda    = Array(
			'inputDestinatarios' => 'contacto',
			'inputEmails' 		 => 'contacto@taccsi.com',
			'inputTittle' 		 => 'Documentacion Completa Taxi',
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
							
		$cMailing->insertRow($aMailerUda);		
	}
	
	public function newUserTaxi($data){
		$cMailing   = new My_Model_Mailing();	
		ob_start();
		include($this->realPath.'/layouts/mail/registerusertax.html');
		
		$nameUsuario  = $data['inputNombre']." ".$data['inputApaterno']." ".$data['inputAmaterno'];
		
		$lBody = ob_get_clean();
		$lBody = str_ireplace('@_nombre_@', 	$nameUsuario			, $lBody);
		$lBody = str_ireplace('@_usuario_@', 	@$data['inputUsuario']  , $lBody);
		$lBody = str_ireplace('@_password_@', 	@$data['inputPassword'] , $lBody);
		
		$aMailerUda    = Array(
			'inputDestinatarios' => $nameUsuario,
			'inputEmails' 		 => $data['inputUsuario'],
			'inputTittle' 		 => 'Bienvenido a Taccsi',
			'inputBody' 		 => $lBody,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
							
		$cMailing->insertRow($aMailerUda);
		
		/* Se envia un mail al administrador del sistema */
		ob_start();
		include($this->realPath.'/layouts/mail/registeruseradmin.html');
		$lBodyUda = ob_get_clean();	
		
		$sMensaje = 'Taccsi - Registro Nuevo Taccista - Web';

		$lBodyUda = str_ireplace('@_nombre_@',  $nameUsuario			, $lBodyUda);
		$lBodyUda = str_ireplace('@_usuario_@', @$data['inputUsuario']  , $lBodyUda);
		$lBodyUda = str_ireplace('@_phone_@', 	 @$data['inputPhone']   , $lBodyUda);		

		$config     = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$aDataAdmin = $config->getOption('admin');				
		
		$aMailer    = Array(
			'inputDestinatarios' => $aDataAdmin['mails'],
			'inputEmails' 		 => $aDataAdmin['mails'],
			'inputTittle' 		 => 'Nuevo Usuario Taccista',
			'inputBody' 		 => $lBodyUda,
			'inputFromName' 	 => 'contacto@taccsi.com',
			'inputFromEmail' 	 => 'Taccsi'
		);
													
		$cMailing->insertRow($aMailer);			
		
	}	
}