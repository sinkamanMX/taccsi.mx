<?php

class login_MainController extends My_Controller_Action
{
    public function init()
    {
		$this->view->layout()->setLayout('admin_layout');
    }

    public function indexAction()
    {
		try{    	
	        $sessions = new My_Controller_Auth();
	        if($sessions->validateSession()){
	            $this->_redirect("/admin/main/index");		
			}    	
			    	
	    	$this->view->header = true;
	    	$this->view->layout()->setLayout('admin_layout'); 
          
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
    public function loginAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
                
        $answer = Array('answer' => 'no-data');
		$data = $this->_request->getParams();
        if(isset($data['usuario']) && isset($data['contrasena'])){
            $usuarios = new My_Model_Usuarios();
			$validate = $usuarios->validateUser($data); //recogemos los valores y mandamos            
			if($validate){
				 $dataUser = $usuarios->getDataUser($validate['ID_USUARIO']);
			     $sessions = new My_Controller_Auth();
                 $sessions->setContentSession($dataUser);
                 $sessions->startSession();
			     $answer = Array('answer' => 'logged');			    
			}else{ 
			    $answer = Array('answer' => 'no-perm'); 
			}
        }else{
            $answer = Array('answer' => 'problem');	
        }
        echo Zend_Json::encode($answer);                
    }    
    
    public function logoutAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();        	
		$mysession= new Zend_Session_Namespace('taccSession');
		$mysession->unsetAll();
		
		Zend_Session::namespaceUnset('taccSession');
		Zend_Session::destroy();
		$this->_redirect('/login/main/index');
    }
    
    public function recoveryAction(){
		try{
			$this->view->header = false;
	    	$this->view->layout()->setLayout('admin_layout');
	    	
	    	$cUsuarios  = new My_Model_Usuarios();
			$cFunctions = new My_Controller_Functions();
	    	$optionTodo = 'remember';
	    	$sResult	= '';
	    	$aError     = Array();	    	
	    	$dataIn     = $this->_request->getParams();
	    	header('Content-Type: text/html; charset=utf-8');
	    	if(isset($dataIn['optReg'])     && $dataIn['optReg']  !="remember" &&
	    	   isset($dataIn['inputUser']) && $dataIn['inputUser']!="" ){				
				$optionTodo = '';
				
				$validateUsuarios = $cUsuarios->userExist($dataIn['inputUser']);
				if(count($validateUsuarios)>0 && $validateUsuarios['NICKNAME']==$dataIn['inputUser']){
					$aDatauser  = $cUsuarios->getDataUser($validateUsuarios['ID_USUARIO']);
					$sNameuser  = $aDatauser['NOMBRE'].' '.$aDatauser['APATERNO'].' '.$aDatauser['AMATERNO'];
					$randomReset= $cFunctions->getRandomCodeReset();
					
					$aDataKey   = Array(
						'idUser' 	 => $aDatauser['ID_USUARIO'],
						'inputClave' => $randomReset,
					);
					$setKey   	= $cUsuarios->setKeyRestore($aDataKey);
					if($setKey){						
						$bodymail   = '<h3>Estimado '.$sNameuser.':</h3>'.
									  'Has solicitado recuperar tu contrase&ntilde;a <br/>'.
									  'Para hacerlo, debes de ingresar al siguiente link:'.
									  '<a href="http://taccsi.com/login/main/recoverresq?keyResetToker='.$randomReset.'">Da Click Aqui</a><br/>'.
									  'o bien copia y pega en tu navegador el siguiente enlace<br>'.
									  '<b> http://taccsi.com/login/main/recoverresq?keyResetToker='.$randomReset.'</b>';
						$aMailer    = Array(
							'emailTo' 	=> $aDatauser['NICKNAME'],
							'nameTo' 	=> $sNameuser,
							'subjectTo' => ('Taccsi - Recuperaci&oacute;n de Contrase&ntilde;a'),
							'bodyTo' 	=> $bodymail,
						);					
						
					 	$configMail = array(
							'ssl'      => 'ssl',
				            'port'     => '465','auth' => 'login',
							'urlSmtp'  => 'smtp.gmail.com',
							'username' => 'tienda.ricom@gmail.com',			
						    'password' => '7ienD4r1c0M.mX'); 
						
					 	$enviar = $cFunctions->sendMailSmtp($aMailer);
					   	$sResult= 'okRegister';						
					}									
				}else{
					$aError['eUsuario'] = 1;
				}
	    	}
	    	
	    	$this->view->optRegister = $optionTodo;
	    	$this->view->aErrors 	 = $aError;
	    	$this->view->resultOp    = $sResult;
		}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }	
    }
    
    public function recoverresqAction(){
    	try{
			$this->view->header = false;
	    	$this->view->layout()->setLayout('admin_layout');
	    	
	    	$cUsuarios  = new My_Model_Usuarios();
			$cFunctions = new My_Controller_Functions();
	    	$optionTodo = 'remember';
	    	$sResult	= '';
	    	$aError     = Array();	    	
	    	$dataIn     = $this->_request->getParams();
	    	$aDataUser  = Array();
	    	$catId		= 0;
	    	
	    	header('Content-Type: text/html; charset=utf-8');
	    	
	    	if(isset($dataIn['keyResetToker']) && $dataIn['keyResetToker']!="" ){
	    		$keyRestore = $dataIn['keyResetToker'];
	    		$aDataUser  = $cUsuarios->validateKeyRecovery($keyRestore);
	    		
	    		if(count($aDataUser)==0){
	    			$aError['errorConfig'] = 1;	    				    	
	    		}else{
	    			$catId = $aDataUser['ID_USUARIO'];	
	    			
		    		if(isset($dataIn['optReg']) && $dataIn['optReg']=='reset'){
		    			$update = $cUsuarios->updatePassword($dataIn);
		    			if($update){	    				
							$aDataKey   = Array(
								'idUser' 	 => $dataIn['idReset'],
								'inputClave' => '',
							);
							$setKey  = $cUsuarios->setKeyRestore($aDataKey);
							$sResult = 'updated';	    				
		    				//$this->_redirect("/login/main/index");	
		    			}else{
		    				$aError['errorConfig'] = 1;	
		    			}
		    		}	    			
	    		}	    		
	    	}else{
	    		$this->_redirect("/login/main/index");	
	    	}
	    	
	    	$this->view->aUserData = $aDataUser; 
	    	$this->view->catId	   = $catId;
	    	$this->view->dataIn	   = $dataIn;
	    	$this->view->result    = $sResult;
	    	$this->view->aErrors   = $aError;
    	}catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    	
    }
}