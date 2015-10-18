<?php
class finances_RegionsController extends My_Controller_Action
{
	protected $_clase = 'mregions';
	
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
			$classObject = new My_Model_Regions();
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
    		$classObject= new My_Model_Regions();
    		$cEstados   = new My_Model_Spestados();
    		$cTarifas   = new My_Model_Tarifas();
    		
    		$sEstatus	= '';
    		$sEstado	= '';
    		$sPositions = '';
    		$aEstados   = $cEstados->getCbo(); 
    		$aTableTarifas= Array();
    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ESTATUS'];
    	    	$sEstado    = $dataInfo['ID_ESTADO'];
    	    	$sPositions = $this->processArea($dataInfo['MAP_OBJECT2']);
    	    	$aTableTarifas= $cTarifas->getDataTables($this->_idUpdate);	
			}
				
    		if($this->_dataOp=='new'){
    			if(@$this->_dataIn['inputPoints']!=""){				
					$this->_dataIn['inputPolygon'] = "POLYGON((".$this->_dataIn['inputPoints']."))";				
				}		
				
			 	$insert = $classObject->insertRow($this->_dataIn);
		 		if($insert['status']){
		 			$this->_idUpdate = $insert['id'];
	    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
	    	    	$sEstatus	= $dataInfo['ESTATUS'];
	    	    	$sEstado    = $dataInfo['ID_ESTADO'];
	    	    	$sPositions = $this->processArea($dataInfo['MAP_OBJECT2']);	
			 		$this->_resultOp = 'okRegister';
				}else{
					$this->errors['status'] = 'no-insert';
				}				 
			}else if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
				    if(@$this->_dataIn['inputPoints']!=""){				
						$this->_dataIn['inputPolygon'] = "POLYGON((".$this->_dataIn['inputPoints']."))";				
					}					
					$updated = $classObject->updateRow($this->_dataIn);
					if($updated['status']){	
		    	    	$dataInfo	= $classObject->getData($this->_idUpdate);
		    	    	$sEstatus	= $dataInfo['ESTATUS'];
		    	    	$sEstado    = $dataInfo['ID_ESTADO'];
		    	    	$sPositions = $this->processArea($dataInfo['MAP_OBJECT2']);	
				 		$this->_resultOp = 'okRegister';
					}else{
				 		$this->errors['eUsuario'] = '1';
				 	}
				}else{
					$this->errors['status'] = 'no-info';
				}
			}
			
			if($this->_dataOp=='searchState'){
				$sEstado    = $this->_dataIn['inputEstado']; 
				$spEstados  = new My_Model_Spestados();
				
				$aResultado = $spEstados->getData($sEstado);
				$sPositions = $this->processAreaSpatial($aResultado['GEO']);
				$sEstatus	= $this->_dataIn['inputEstatus'];
				$dataInfo['DESCRIPCION']= $this->_dataIn['inputNombre'];
			}
	
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				/*
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
				$sSucursales =$dataInfo['ID_SUCURSAL'];*/	
			}		
	
			$this->view->aTarifas   = $aTableTarifas;
			$this->view->aEstados	= $cFunctions->selectDb($aEstados,$sEstado);
    		$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);
    		$this->view->aPositions = $sPositions;	
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

    public function geodetailAction(){
    	$result = '';
		try{  			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			
			if(isset($this->_dataIn['catId']) && $this->_dataIn['catId']){
				$idEstado = $this->_dataIn['catId']; 
				$cEstados = new My_Model_Spestados();
				
				$aResultado = $cEstados->getData($idEstado);
				$sPosition  = $aResultado['GEO'];
				
								
				$sClean = substr($sPosition, 0, -3);
				$mult 	= substr($sClean ,15);				
				$pre_positions=explode(",",$mult);		
				for($p=0;$p<count($pre_positions);$p++){
					$result .= ($result=="") ? '':',';			
					$fixed   = str_replace(' ',',',$pre_positions[$p]);					
					$aLs     = explode(',', $fixed);
					$result .= 'new google.maps.LatLng('.$aLs[1].','.$aLs[0].')';
				}
			}
			echo 'geos_poins_polygon = ['.$result.'];';
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }
    
   	public function processArea($sPosition){
		$a_position= '';
		
		$sClean = substr($sPosition, 0, -3);
		$mult 	= substr($sClean ,9);			
		$pre_positions=explode(",",$mult);		
		
		
		for($p=0;$p<count($pre_positions);$p++){	
			$a_position .= ($a_position=="") ? '':',';		
			$fixed   = str_replace(' ',',',$pre_positions[$p]);	
			
			$a_position .= 'new google.maps.LatLng('.$fixed.')';
		}
					
		return $a_position;
   	}

   	public function processAreaSpatial($sPosition){
		$a_position= '';
		
		$sClean = substr($sPosition, 0, -3);
		$mult 	= substr($sClean ,15);			
		$pre_positions=explode(",",$mult);	
		$count=0;	
		$totalArray = (count($pre_positions)*.05); 
		
		for($p=0;$p<count($pre_positions);){	
			$a_position .= ($a_position=="") ? '':',';		
			$fixed   = str_replace(' ',',',$pre_positions[$p]);	
			$aLs     = explode(',', $fixed);
			$a_position .= 'new google.maps.LatLng('.$aLs[1].','.$aLs[0].')';
			$p += $totalArray;
		}
					
		return $a_position;
   	}   	
   	
}