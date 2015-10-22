<?php
class finances_RatesController extends My_Controller_Action
{
	protected $_clase = 'mrates';
	
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
			$classObject = new My_Model_Tarifas();
			$this->view->datatTable = $classObject->getDataTables();
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }    
    

    public function getinfoAction(){
    	try{
    		$dataInfo 	= Array();
    		$cFunctions	= new My_Controller_Functions();    		
    		$classObject= new My_Model_Tarifas();
    		$cClases	= new My_Model_Clases();
			$cEstados   = new My_Model_Spestados();
			$cZonas		= new My_Model_Zonas();
			
			$sEstado	= '';
    		$sEstatus	= '';
    		$sClase		= '';
    		$sbTaximetro= '';
    		$sHinicio	= '00:00:00';
    		$sFin 		= '23:59:00';
    		$aClase		= $cClases->getCbo();
    		$aEstados   = $cEstados->getCbo();
    		$aTableZonas= Array();
    		
    		$tabSelected  = (isset($this->_dataIn['strTabSelected']) &&
    						    $this->_dataIn['strTabSelected'] !="") ? $this->_dataIn['strTabSelected'] : 1;    		
    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ESTATUS'];
    	    	$sClase		= $dataInfo['ID_CLASE'];
    	    	$sbTaximetro= $dataInfo['USA_TAXIMETRO'];
    	    	$sEstado	= $dataInfo['ID_ESTADO'];
    	    	$sHinicio	= $dataInfo['HORARIO_INICIO'];
    	    	$sFin 		= $dataInfo['HORARIO_FIN'];
    	    	$aTableZonas=  $cZonas->getDataTables($dataInfo['ID_TARIFA']);
			}
			
    		if($this->_dataOp=='new'){
			 	$insert = $classObject->insertRow($this->_dataIn);
		 		if($insert['status']){
		 			$this->_idUpdate = $insert['id'];
	    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
	    	    	$sEstatus	= $dataInfo['ESTATUS'];
	    	    	$sClase		= $dataInfo['ID_CLASE'];
	    	    	$sbTaximetro= $dataInfo['USA_TAXIMETRO'];
	    	    	$sEstado	= $dataInfo['ID_ESTADO'];
	    	    	$sHinicio	= $dataInfo['HORARIO_INICIO'];
	    	    	$sFin 		= $dataInfo['HORARIO_FIN'];	    	    	
			 		$this->_resultOp = 'okRegister';
				}else{
					$this->errors['status'] = 'no-insert';
				}				 
    		}else if($this->_dataOp=='update'){
				if($this->_idUpdate>-1){
					$updated = $classObject->updateRow($this->_dataIn);
					if($updated['status']){	
		    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
		    	    	$sEstatus	= $dataInfo['ESTATUS'];
		    	    	$sClase		= $dataInfo['ID_CLASE'];
		    	    	$sbTaximetro= $dataInfo['USA_TAXIMETRO'];
		    	    	$sEstado	= $dataInfo['ID_ESTADO'];
		    	    	$sHinicio	= $dataInfo['HORARIO_INICIO'];
		    	    	$sFin 		= $dataInfo['HORARIO_FIN'];		    	    	
				 		$this->_resultOp = 'okRegister';
					}else{
				 		$this->errors['eUsuario'] = '1';
				 	}
				}else{
					$this->errors['status'] = 'no-info';
				}
			}
			
			/*if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				
				$dataInfo['ID_PERFIL'] 		= $this->dataIn['inputPerfil'];
				$dataInfo['ID_SUCURSAL'] 	= $this->dataIn['inputSucursal'];
				$dataInfo['USUARIO'] 		= $this->dataIn['inputUsuario'];
				$dataInfo['NOMBRE'] 		= $this->dataIn['inputNombre'];
				$dataInfo['APELLIDOS'] 		= $this->dataIn['inputApps'];
				$dataInfo['EMAIL'] 			= $this->dataIn['inputEmail'];
				$dataInfo['TEL_MOVIL'] 		= $this->dataIn['inputMovil'];
				$dataInfo['TEL_FIJO'] 		= $this->dataIn['inputTelFijo'];
				$dataInfo['ACTIVO'] 		= $this->dataIn['inputEstatus'];
				$dataInfo['FLAG_OPERACIONES']= $this->dataIn['inputOperaciones'];
				
    	    	$sPerfil	 = $dataInfo['ID_PERFIL'];
    	    	$sEstatus	 = $dataInfo['ACTIVO'];
				$sOperaciones= $dataInfo['FLAG_OPERACIONES'];
				$sSucursales =$dataInfo['ID_SUCURSAL'];	
			}	
			*/	
			
			$this->view->aTableZonas= $aTableZonas;
			$this->view->aTaximetro = $cFunctions->cboOptions($sbTaximetro);
			$this->view->aEstados	= $cFunctions->selectDb($aEstados,$sEstado);
    		$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);
			$this->view->aClases  	= $cFunctions->selectDb($aClase,$sClase);

			$this->view->aHin		= $cFunctions->getCboHoursDate($sHinicio); 
			$this->view->aHfin 		= $cFunctions->getCboHoursDate($sFin);
			
			$this->view->data 		= $dataInfo; 
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;
			$this->view->tabSelected= $tabSelected;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }     
}