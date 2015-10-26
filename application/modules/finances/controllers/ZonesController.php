<?php
class finances_ZonesController extends My_Controller_Action
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
			//$classObject = new My_Model_Clases();
			//$this->view->datatTable = $classObject->getDataTables();
			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }   

    public function getinfoAction(){
    	try{
    		$aOptions   = Array(
				array("id"=>"1",'name'=>'Estado' ),
				array("id"=>"0",'name'=>'Circunferencia'),
				array("id"=>"2",'name'=>'Pol&iacute;gono')
			); 
    		
    		$dataInfo 	= Array();
    		$aDataRate  = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$cZona 		= new My_Model_Zonas();
    		$cRates		= new My_Model_Tarifas();
    		$cEstados   = new My_Model_Spestados();
    		$sTipoa		= '';
    		$sEstado	= '';
    		$sAcumulado = '';
    		$sPositions	= '';
    		$aEstados   = $cEstados->getCbo();
    		
    		if(isset($this->_dataIn['catIdtar']) && $this->_dataIn['catIdtar']!="" ){
    	    	$idRate		= $this->_dataIn['catIdtar'];
    	    	$aDataRate  = $cRates->getData($idRate);    	    		
			}

			if($this->_idUpdate >-1){
    	    	$dataInfo	= $cZona->getData($this->_idUpdate);
				$sEstado	= $dataInfo['ID_ESTADO'];
				$sAcumulado = $dataInfo['COSTO_ACUMULABLE'];
				$sTipoa 	= $dataInfo['TIPO_ZONA'];
				
				if($dataInfo['TIPO_ZONA']==1){
					$aResultado = $cEstados->getData($dataInfo['ID_ESTADO']);					
					$sPositions = $this->processAreaSpatial($aResultado['GEO']);	
				}else{
					$sPositions = $this->processArea($dataInfo['GEO']);
				}
			}			
			
    		if($this->_dataOp=='new'){
    			if(@$this->_dataIn['inputPoints']!=""){				
					$this->_dataIn['inputPolygon'] = "POLYGON((".$this->_dataIn['inputPoints']."))";				
				}
			 	$insert = $cZona->insertRow($this->_dataIn);
		 		if($insert['status']){	
	 				$this->_idUpdate = $insert['id'];
	 				/*
					$dataInfo	= $cZona->getData($this->_idUpdate);
					$sEstado	= $dataInfo['ID_ESTADO'];
					$sAcumulado = $dataInfo['COSTO_ACUMULABLE'];
					$sTipoa 	= $dataInfo['TIPO_ZONA'];
					if($dataInfo['TIPO_ZONA']==1){
						$aResultado = $cEstados->getData($dataInfo['ID_ESTADO']);					
						$sPositions = $this->processAreaSpatial($aResultado['GEO']);	
					}*/
				
					$this->redirect('/finances/rates/getinfo?catId='.$this->_dataIn['catIdtar'].'&strTabSelected=2');
					$this->_resultOp = 'okRegister';
				}else{
					$this->errors['status'] = 'no-insert';
				}				 
			}else if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					if(@$this->_dataIn['inputPoints']!=""){				
						$this->_dataIn['inputPolygon'] = "POLYGON((".$this->_dataIn['inputPoints']."))";				
					}	
					$updated = $cZona->updateRow($this->_dataIn);
					if($updated['status']){	
						$dataInfo	= $cZona->getData($this->_idUpdate);
						$sEstado	= $dataInfo['ID_ESTADO'];
						$sAcumulado = $dataInfo['COSTO_ACUMULABLE'];
						$sTipoa 	= $dataInfo['TIPO_ZONA'];
						

						$this->_resultOp = 'okRegister';
					}else{
						$this->errors['status'] = 'no-insert';
					}			    	    
				}else{
					$this->errors['status'] = 'no-info';
				}
			}		
			
    		if($this->_dataOp=='searchState'){
				$sEstado    = $this->_dataIn['inputEstado']; 
				
				$aResultado = $cEstados->getData($sEstado);
				$sPositions = $this->processAreaSpatial($aResultado['GEO']);				
				$sTipoa 				= $this->_dataIn['inputTipo'];
				$dataInfo['DESCRIPCION']= $this->_dataIn['inputNombre'];
				$dataInfo['COSTO'] 		= $this->_dataIn['inputCosto'];
				$sAcumulado 			= $this->_dataIn['inputAcumulable'];
				$dataInfo['TIPO_ZONA']  = $this->_dataIn['inputTipo'];
			}			
			$aAllZones				= $cZona->getZonesTables($this->_dataIn['catIdtar'], $this->_idUpdate);
			$this->view->aZonas		= $this->processListGeo($aAllZones);
			$this->view->catIdtar   = $this->_dataIn['catIdtar'];
			$this->view->aAcum	    = $cFunctions->cboOptions($sAcumulado);
			$this->view->aEstados	= $cFunctions->selectDb($aEstados,$sEstado);
			$this->view->aTipos     = $cFunctions->cbo_from_array($aOptions,$sTipoa);
			$this->view->aPositions = $sPositions;	
			$this->view->dataRate 	= $aDataRate;
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
    
    public function processListGeo($aZones){
    	$aResult = Array();
    	$aColores = Array(0=>'#CF3232',1=>'#3c8dbc',2=>'#39cccc',
    					  3=>'#00a65a',4=>'#f56954',5=>'#f39c12',
    					  6=>'#ff851b',7=>'#00a65a',8=>'#3c8dbc',9=>'#4FB8F5');
    	
    	$control=0;
    	foreach($aZones as $key => $items){
    		$items['SPC']   = $this->processArea($items['GEO']);
    		$items['COLOR'] = $aColores[$control];
    		$aResult[]    = $items;
    		$control++;
    		if($control==10){
    			$control=0;
    		}
    	}
    	return $aResult;
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
		$sReplace  = Array("(",")");
		
		$sClean = substr($sPosition, 0, -3);
		$mult 	= substr($sClean ,15);			
		$pre_positions=explode(",",$mult);	
		$count=0;	
		$totalArray = (count($pre_positions)*.05); 
		
		for($p=0;$p<count($pre_positions);){	
			$a_position .= ($a_position=="") ? '':',';		
			$fixed   = str_replace(' ',',',$pre_positions[$p]);	
			$aLs     = explode(',', $fixed);
			$iLatitud  = str_replace($sReplace,'',$aLs[1]);
			$iLongitud = str_replace($sReplace,'',$aLs[0]);
			$a_position .= 'new google.maps.LatLng('.$iLatitud.','.$iLongitud.')';
			$p += 1;
		}
		return $a_position;
   	}   	
   	    
}