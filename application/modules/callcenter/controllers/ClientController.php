<?php
class callcenter_ClientController extends My_Controller_Action
{
	protected $_clase = 'matencion';
	
    public function init()
    { 
		$sessions = new My_Controller_Auth();
		$perfiles = new My_Model_Perfiles();
        if($sessions->validateSession()){
	        $this->_dataUser   = $sessions->getContentSession();   		
		}else{
			$this->_redirect("/login/main/index");
		}
		
		$this->_dataIn 					= $this->_request->getParams();
		$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
    	if(isset($this->_dataIn['optReg'])){
			$this->_dataOp = $this->_dataIn['optReg'];				
		}

		$this->view->layout()->setLayout('admin_layout');	
		$this->view->dataUser   = $this->_dataUser;
		$this->view->modules    = $perfiles->getModules($this->view->dataUser['TIPO_USUARIO']);
		$this->view->moduleInfo = $perfiles->getDataMenu($this->_clase);
    }
    
    public function indexAction(){
    	
    }
    
    public function newAction(){
    	try{
    		if($this->_dataOp=='new'){    			
				$classObject = new My_Model_Clientes();
    			$insert	= $classObject->insertRow($this->_dataIn);
    			if($insert['status']){
    				$idInsert = $insert['id'];
    				$this->_redirect('/callcenter/client/clientinfo?strClient='.$idInsert);
    				$this->resultop = 'okRegister';
    			}else{
    				$this->_aErrors['status'] = 'no-insert';
    			}
    		}
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;  
			$this->view->data		= $this->_dataIn; 		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }      	
    }
    
    public function clientinfoAction(){
    	try{
    		$cFunctions = new My_Controller_Functions();
    		$cClients	= new My_Model_Clientes();
    		$aDataClient= Array();
    		$aDataViajes= Array();
    		
    		if(isset($this->_dataIn['strClient']) && $this->_dataIn['strClient']!=""){
    			$aDataClient = $cClients->getDataClient($this->_dataIn['strClient']);
    			$aDataViajes = $cClients->getViajesClient($this->_dataIn['strClient']);
    		}else{
    			$this->_redirect('/callcenter/main/index');
    		}
    		$this->view->dataClient = $aDataClient;	
    		$this->view->dataViajes = $aDataViajes;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	    	
    }
}    