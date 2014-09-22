<?php
class admin_CompaniesController extends My_Controller_Action
{
	protected $_clase = 'mcompanies';
	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();   		
			}else{
				$this->_redirect("/login/main/index");
			}    		
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules($this->_dataUser['TIPO_USUARIO']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
						
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
			$classObject = new My_Model_Empresas();
			$this->view->datatTable = $classObject->getDataTables();
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 
    
    public function getinfoAction(){
    	try{
    		$dataInfo = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Empresas();
			$estados   = new My_Model_Estados();
			$aEstados  = $estados->getCbo();
			$cMunicipios=new My_Model_Municipios();
			$cColonias = new My_Model_Colonias();
			    		
			$aMunicipios  = Array();
			$aMunicipiosF = Array();
    		$sEstatus	= '';
    		$sTipoCliente= '';
    		$sDirDif	= '0';
    		$sEstado	= '';
    		$sMuni		= '';
    		$sEstadoF	= '';
    		$sMuniF		= '';
    		$sColonia	= '';
    		$sColoniaF	= '';

    	    if($this->_idUpdate >-1){
    	    	$dataInfo	 = $classObject->getData($this->_idUpdate);
    	    	$sTipoCliente= $dataInfo['TIPO_RAZON'];
    	    	$sEstatus	 = $dataInfo['ESTATUS'];
    	    	$sDirDif	 = $dataInfo['DIR_DIFERENTE'];
    	    	$sEstado 	 = $dataInfo['ID_ESTADO'];
    	    	$sEstadoF	 = $dataInfo['FIS_ID_ESTADO'];
    	    	
				$dMunicipios = $cMunicipios->getCbo($sEstado);
				$aMunicipios = $cFunctions->selectDb($dMunicipios,@$dataInfo['ID_MUNICIPIO']);
				
				$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
				$aMunicipiosF = $cFunctions->selectDb($dMunicipios,@$dataInfo['FIS_ID_MUNICIPIO']);    	    	
			}
			
			if(isset($this->_dataOp) && $this->_dataOp=="searchCP"){
				$aDataRetrieve = Array();
				if($this->_dataIn['inputSearch']!=""){
					$cColonias = new My_Model_Colonias();
										
					$validateCp = $cColonias->validateCP($this->_dataIn['inputSearch']);
					if(isset($validateCp['ID_COLONIA'])){						
						if($this->_dataIn['typeSearch']=="0"){
							$sEstado = $validateCp['ID_ESTADO'];							
							$dMunicipios = $cMunicipios->getCbo($validateCp['ID_ESTADO']);
							$aMunicipios = $cFunctions->selectDb($dMunicipios,$validateCp['ID_MUNICIPIO']);
				
							$sColonia    = $cColonias->getCbo($validateCp['ID_MUNICIPIO']);
							$aDataRetrieve['COLONIA'] = $sColonia['NAME'];
							$aDataRetrieve['CP']		 = $this->_dataIn['inputSearch'];						
						}else{
							$sEstadoF 	  = $validateCp['ID_ESTADO'];
							$dMunicipios  = $cMunicipios->getCbo($validateCp['ID_ESTADO']);
							$aMunicipiosF = $cFunctions->selectDb($dMunicipios,$validateCp['ID_MUNICIPIO']);
							
							$sColonia    = $cColonias->getCbo($validateCp['ID_MUNICIPIO']);
							$aDataRetrieve['FIS_COLONIA'] = $sColonia['NAME'];	
							$aDataRetrieve['FIS_CP']		 = $this->_dataIn['inputSearch'];				
						}
					}
				}

            	$aDataRetrieve['NOMBRE_EMPRESA']	= $this->_dataIn['inputNameEmpresa'];
	           	$sTipoCliente						= $this->_dataIn['inputTipoRazon'];
	            $aDataRetrieve['RAZON_SOCIAL']		= $this->_dataIn['inputRazon'];
	            $aDataRetrieve['RFC']				= $this->_dataIn['inputRFC'];   
	            $aDataRetrieve['REPRESENTANTE_LEGAL']	= $this->_dataIn['inputRep'];        
	
	            $aDataRetrieve['CALLE']	= $this->_dataIn['inputCalle'];      
	            $aDataRetrieve['NOEXT']	= $this->_dataIn['inputNoext'];      
	            $aDataRetrieve['NOINT']	= $this->_dataIn['inputNoint'];      
	         
	            $sEstatus	= $this->_dataIn['inputEstatus'];    
	            $sDirDif	= $this->_dataIn['inputDirDif'];
	            $aDataRetrieve['DIR_DIFERENTE']	= $this->_dataIn['inputDirDif'];     
	
	            $aDataRetrieve['FIS_CALLE']	= $this->_dataIn['inputCalleF'];     
	            $aDataRetrieve['FIS_NOEXT']	= $this->_dataIn['inputNoextF'];     
	            $aDataRetrieve['FIS_NOINT']	= $this->_dataIn['inputNointF'];     
    			
	            $dataInfo = $aDataRetrieve;
			}		
			
    		if($this->_dataOp=='new'){    			
			 	$insert = $classObject->insertRow($this->_dataIn);
		 		if($insert['status']){
		 			$this->_idUpdate = $insert['id'];
	    	    	$dataInfo	 = $classObject->getData($this->_idUpdate);
	    	    	$sTipoCliente= $dataInfo['TIPO_RAZON'];
	    	    	$sEstatus	 = $dataInfo['ESTATUS'];
	    	    	$sDirDif	 = $dataInfo['DIR_DIFERENTE'];
	    	    	$sEstado 	 = $dataInfo['ID_ESTADO'];
	    	    	$sEstadoF	 = $dataInfo['FIS_ID_ESTADO'];
	    	    		    	    	
					$dMunicipios = $cMunicipios->getCbo($sEstado);
					$aMunicipios = $cFunctions->selectDb($dMunicipios,@$dataInfo['ID_MUNICIPIO']);
					
					$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
					$aMunicipiosF = $cFunctions->selectDb($dMunicipios,@$dataInfo['FIS_ID_MUNICIPIO']);
						    	    	
			 		$this->_resultOp = 'okRegister';
				}else{
					$this->errors['status'] = 'no-insert';
				}
			}else if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					$updated = $classObject->updateRow($this->_dataIn);
					 if($updated['status']){	
		    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
		    	    	$sTipoCliente= $dataInfo['TIPO_RAZON'];
		    	    	$sEstatus	 = $dataInfo['ESTATUS'];
		    	    	$sDirDif	 = $dataInfo['DIR_DIFERENTE'];
		    	    	$sEstado 	 = $dataInfo['ID_ESTADO'];
		    	    	$sEstadoF	 = $dataInfo['FIS_ID_ESTADO'];	
		    	    	
						$dMunicipios = $cMunicipios->getCbo($sEstado);
						$aMunicipios = $cFunctions->selectDb($dMunicipios,@$dataInfo['ID_MUNICIPIO']);
						
						$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
						$aMunicipiosF = $cFunctions->selectDb($dMunicipios,@$dataInfo['FIS_ID_MUNICIPIO']);		    	    	
		    	    	
				 		$this->_resultOp = 'okRegister';
					 }else{
					 	$this->errors['eUsuario'] = '1';
					 }
				}else{
					$this->errors['status'] = 'no-info';
				}
			}else if($this->_dataOp=='delete'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');
				    
				$delete = $classObject->deleteRow($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}	
	
		        echo Zend_Json::encode($answer);
		        die();   			
			}
	
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
            	$aDataRetrieve['NOMBRE_EMPRESA']	= $this->_dataIn['inputNameEmpresa'];
	           	$sTipoCliente						= $this->_dataIn['inputTipoRazon'];
	            $aDataRetrieve['RAZON_SOCIAL']		= $this->_dataIn['inputRazon'];
	            $aDataRetrieve['RFC']				= $this->_dataIn['inputRFC'];   
	            $aDataRetrieve['REPRESENTANTE_LEGAL']	= $this->_dataIn['inputRep'];        
	
	            $aDataRetrieve['CALLE']	= $this->_dataIn['inputCalle'];      
	            $aDataRetrieve['NOEXT']	= $this->_dataIn['inputNoext'];      
	            $aDataRetrieve['NOINT']	= $this->_dataIn['inputNoint'];      
	         
	            $sEstatus	= $this->_dataIn['inputEstatus'];    
	            $sDirDif	= $this->_dataIn['inputDirDif'];
	            $aDataRetrieve['DIR_DIFERENTE']	= $this->_dataIn['inputDirDif'];     
	
	            $aDataRetrieve['FIS_CALLE']	= $this->_dataIn['inputCalleF'];     
	            $aDataRetrieve['FIS_NOEXT']	= $this->_dataIn['inputNoextF'];     
	            $aDataRetrieve['FIS_NOINT']	= $this->_dataIn['inputNointF']; 

				$sEstado = $this->_dataIn['inputEstado'];
				if($sEstado!="" && $sEstado>0){
					$dMunicipios = $cMunicipios->getCbo($sEstado);
					$aMunicipios = $cFunctions->selectDb($dMunicipios,@$this->_dataIn['inputMunicipio']);
				}

				$aDataRetrieve['COLONIA'] 	= $this->_dataIn['inputColonia'];
				$aDataRetrieve['CP']		= $this->_dataIn['inputCp'];
				
				$sEstadoF = $this->_dataIn['inputEstadoF'];
				if($sEstadoF!="" && $sEstadoF>0){
					$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
					$aMunicipiosF = $cFunctions->selectDb($dMunicipiosF,@$this->_dataIn['inputMunicipioF']);
				}				

				$aDataRetrieve['FIS_COLONIA'] 	= $this->_dataIn['inputColoniaF'];
				$aDataRetrieve['FIS_CP']		= $this->_dataIn['inputCpF'];	            
    			
	            $dataInfo = $aDataRetrieve;
			}	
					
			$this->view->aTipoClientes= $cFunctions->cboTipoCliente($sTipoCliente);
			$this->view->aDirDif	= $cFunctions->cboOptions($sDirDif);
			$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);
			
			$this->view->aEstados   = $cFunctions->selectDb($aEstados,$sEstado);
			$this->view->aEstadosF  = $cFunctions->selectDb($aEstados,$sEstadoF);
			$this->view->aMunicipios= $aMunicipios;
			$this->view->aMunicipiosF=$aMunicipiosF;		
			
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
    
}