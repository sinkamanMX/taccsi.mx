<?php
class main_RegisteruserController extends My_Controller_Action
{	
	public $_realPath='/var/www/vhosts/taccsi.com/htdocs/public';	
	//public $_realPath  ='/Users/itecno2/Documents/workspace/taccsi.mx/public';
	 
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('layout_register');						
			$this->_dataIn 					= $this->_request->getParams();
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}	
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{  
    		$cFunctions	= new My_Controller_Functions();
    		$cUsuarios  = new My_Model_ServUsuarios();

    		if($this->_dataOp == 'opRegister'){
    			$validateUser = $cUsuarios->validateData($this->_dataIn['inputUsuario'],-1,'user');
    			if($validateUser){
					$insert = $cUsuarios->insertRow($this->_dataIn);
					if($insert['status']){
						$idUsuario = $insert['id'];
						$nameUsuario  = $this->_dataIn['inputNombre']." ".$this->_dataIn['inputApaterno']." ".$this->_dataIn['inputAmaterno'];
					
						ob_start();
						include($this->_realPath.'/layouts/mail/registeruser.html');
						$lBody = ob_get_clean();
		
						$lBody = str_ireplace('@_nombre_@', 	$nameUsuario   , $lBody);
						$lBody = str_ireplace('@_usuario_@', 	@$this->_dataIn['inputUsuario']   , $lBody);
						$lBody = str_ireplace('@_password_@', 	@$this->_dataIn['inputPassword']  , $lBody);
						
						$aMailer    = Array(
							'emailTo' 	=> $this->_dataIn['inputUsuario'],
							'nameTo' 	=> $nameUsuario,
							'subjectTo' => ('Taccsi - Bienvenido!'),
							'bodyTo' 	=> $lBody,
						);										
					 	$enviar = $cFunctions->sendMailSmtp($aMailer);		
					 	$this->_resultOp= 'okRegisterMail';	
					}else{
							$this->_aErrors['status'] = 'no-insert';
					}
				}else{
					$this->_aErrors['eUsuario'] = '1';
				}
    		}	
    		$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->data		= $this->_dataIn; 
		}catch(Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";
        }  	
    }
}