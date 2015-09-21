<?php
class admin_ValtaxisController extends My_Controller_Action
{
	protected $_clase = 'mvaltaxis';
	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
			$fc 	  = Zend_controller_front::getInstance();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   	
		        $this->_baseUrl    = $fc->getBaseUrl();	
			}else{
				$this->_redirect("/login/main/index");
			}    		
			$this->view->dataUser   = $this->_dataUser;			
			$this->view->modules    = $perfiles->getModules($this->_dataUser['TIPO_USUARIO']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->BaseUrl    = $this->_baseUrl;
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
			$this->_dataIn['dataIdEmpresa'] = $this->_dataUser['ID_EMPRESA'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}			
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{
			$classObject = new My_Model_Kardex();
			$this->view->datatTable = $classObject->validateTaxis();
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 

    public function getinfoAction(){
    	try{
			$cObject		= new My_Model_Taxis();			
			$aDataKardex	= Array();
			
			if($this->_idUpdate >-1){
				$aDataTaxi = $cObject->getData($this->_idUpdate);	
			}
			
			if($this->_dataOp=='validateFields'){
				$update = $cObject->updateValidate($this->_dataIn);
				if($update['status']){
					$aDataTaxi = $cObject->getData($this->_idUpdate);	
					
					if($aDataTaxi['VAL_TCIRCULACION']==2 && $aDataTaxi['VAL_TCIRCULACION_2']==2 && 
					   $aDataTaxi['VAL_FACTURA']==2 && $aDataTaxi['VAL_POLIZA']==2 ){
					   	$cObject->setCheckDocuments($aDataTaxi['ID_TAXI']);
					}
				}
			}
			
			$this->view->aDataTaxi  = $aDataTaxi;    		
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;		
			$this->view->resultOp   = $this->_resultOp;
						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 
        
    }    
}