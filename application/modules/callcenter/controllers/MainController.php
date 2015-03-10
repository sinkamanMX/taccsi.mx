<?php
class callcenter_MainController extends My_Controller_Action
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
		
		if(isset($this->_dataIn['catId'])){
			$this->_idUpdate = $this->_dataIn['catId'];				
		}	
		
		$this->view->layout()->setLayout('admin_layout');	
		$this->view->dataUser   = $this->_dataUser;
		$this->view->modules    = $perfiles->getModules($this->view->dataUser['TIPO_USUARIO']);
		$this->view->moduleInfo = $perfiles->getDataMenu($this->_clase);
    }
    
    public function indexAction(){
    	try{    		
    		$aDataTable = Array();
			$cClientes  = new My_Model_Clientes();
			 
			if($this->_dataOp=='search'){
				$aDataTable = $cClientes->getClients($this->_dataIn);						
			}
				
    		$this->view->aDataTable = $aDataTable;
    		$this->view->aData 		= $this->_dataIn;
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";
        }
    }   
    
    public function waitingAction(){
		try{
			
			$this->view->header = true;						
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }       
}