<?php
class external_PaymentsController extends My_Controller_Action
{
	protected $_clase = 'mpayment';
	
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
    		$cMediosPago = new My_Model_MediosPago();			
			$this->view->datatTable = $cMediosPago->getDataTables($this->_dataUser['ID_SRV_USUARIO']);			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }
    
    public function getinfoAction(){
    	try{
    		$cMediosPago = new My_Model_MediosPago();			
			$cFunctions	 = new My_Controller_Functions();    					
			$idUsuario   = $this->_dataUser['ID_SRV_USUARIO'];
			$this->_dataIn['inputUsuario'] = $idUsuario;
			$sEstatus	 = '';
			$sTipo	 	 = '';
			$sMes		 = '';
			$sAnio		 = '';
			$sDefault    = 0;
			$dataInfo	 = Array();
			$aTIposTarjetas = $cMediosPago->getCboTipoTarjerta();
			$sDateNow	= Date("Y");
			$sDateDigits= substr($sDateNow, -2);
			
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $cMediosPago->getData($this->_idUpdate,$idUsuario);
    	    	$sEstatus	= @$dataInfo['ESTATUS'];
    	    	$sTipo		= @$dataInfo['ID_TIPO_TARJETA'];
    	    	$sDefault	= @$dataInfo['POR_DEFECTO'];
    	    	$sMes		= @$dataInfo['MES_VENCIMIENTO'];
    	    	$sAnio		= @$dataInfo['ANO_VENCIMIENTO'];
			}
			
			if($this->_dataOp=='new'){
				$insert = $cMediosPago->insertRow($this->_dataIn);
			 	if($insert['status']){	
			 		$this->_idUpdate = $insert['id'];				 			
	    	    	$dataInfo	= $cMediosPago->getData($this->_idUpdate,$idUsuario);
	    	    	$sEstatus	= @$dataInfo['ESTATUS'];
	    	    	$sTipo		= @$dataInfo['ID_TIPO_TARJETA'];
	    	    	$sDefault	= @$dataInfo['POR_DEFECTO'];
	    	    	$sMes		= @$dataInfo['MES_VENCIMIENTO'];
	    	    	$sAnio		= @$dataInfo['ANO_VENCIMIENTO'];	    	    	
		 			$this->_resultOp = 'okRegister';
			 	}else{
			 		$this->_aErrors['problem'] = 1;
			 	}				
			}elseif($this->_dataOp=='update'){
				$updated = $cMediosPago->updateData($this->_dataIn);
			 	if($updated['status']){				 					 		
	    	    	$dataInfo	= $cMediosPago->getData($this->_idUpdate,$idUsuario);
	    	    	$sEstatus	= @$dataInfo['ESTATUS'];
	    	    	$sTipo		= @$dataInfo['ID_TIPO_TARJETA'];
	    	    	$sDefault	= @$dataInfo['POR_DEFECTO'];
	    	    	$sMes		= @$dataInfo['MES_VENCIMIENTO'];
	    	    	$sAnio		= @$dataInfo['ANO_VENCIMIENTO'];	    	    	
		 			$this->_resultOp = 'okRegister';
			 	}else{
			 		$this->_aErrors['problem'] = 1;
			 	}
			}

    		$this->view->aEstatus   = $cFunctions->cboStatus($sEstatus);
    		$this->view->aDefault   = $cFunctions->cboOptions($sDefault);
			$this->view->aMes		= $cFunctions->cbo_number(13,$sMes);
			$this->view->aAnio		= $cFunctions->cboNumberRange($sDateDigits,($sDateDigits+6),$sAnio);
    		$this->view->aTipos   	= $cFunctions->selectDb($aTIposTarjetas,$sTipo); 
    		$this->view->cboTipos 	= $aTIposTarjetas;		   		
			$this->view->data 		= $dataInfo; 
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }      	
    }
    
    public function setdefaultAction(){
    	try{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$cMediosPago =  new My_Model_MediosPago();
			$answer = Array('answer' => 'no-data');
			$idUsuario = $this->_dataUser['ID_SRV_USUARIO'];
			if(isset($this->_dataIn['strInput']) && $this->_dataIn['strInput']!=""){
				$idMedio = $this->_dataIn['strInput'];
				
				$cMediosPago->resetDefault($idUsuario);
				$cMediosPago->setDefault($idMedio);
				$answer = Array('answer' => 'updated');
			}else{
				$answer = Array('answer' => 'problem'); 
			}

	        echo Zend_Json::encode($answer);
	        die();     		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }
    
        
}    