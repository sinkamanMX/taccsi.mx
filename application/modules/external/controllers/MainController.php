<?php 

class external_MainController extends My_Controller_Action
{
	protected $_clase = 'mexternal';
		
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
			$sessions = new My_Controller_AuthContact();
			$perfiles = new My_Model_Perfiles();
						
    	    if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   		
			}else{
				$this->_redirect('/external/login/index');		
			}			
			
			$this->_dataIn 			= $this->_request->getParams();
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules(5);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->bUserContact = true;	

			$this->validateNumbers = new Zend_Validate_Digits();
					
			if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];
				
				if($this->_dataOp=='update'){
					$this->_dataOp = $this->_dataIn['optReg'];
	
					$this->validateAlpha   = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));				
				}
			}
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }

    public function indexAction()
    {
		try{  
    		$cFunctions = new My_Controller_Functions();
    		$cFormaPago = new My_Model_Formapago();
    		$cTaxis		= new My_Model_Taxis();  
    		$cViajes	= new My_Model_Viajes();   

    		if($this->_dataOp=='new'){
    			$aDataViaje		 = $this->_dataIn;
				$aDataViaje['userRegistro'] = $this->_dataUser['ID_SRV_USUARIO'];
				$aDataViaje['strClient'] 	= $this->_dataUser['ID_SRV_USUARIO'];
				$registerService = $cViajes->insertRow($aDataViaje);
				
				if($registerService['status']){
					$idViaje = $registerService['id'];
					
					$aTaxis	 = $cTaxis->getTaxiService($aDataViaje);						
					$this->addTaxis($aTaxis, $idViaje);					
					$this->_redirect('/external/main/serviceinfo?strViaje='.$idViaje);
				}
    		}

    		
			$aFpagos	= $cFormaPago->getCbo();
			$this->view->totalClientes   = $cFunctions->cbo_number(6);	
			$this->view->viajeProgramado = $cFunctions->cboOptions(0);		   	
			$this->view->formaPago		 = $cFunctions->selectDb($aFpagos);    		
    		$this->view->data		= $this->_dataIn;
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;   	
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }      
}