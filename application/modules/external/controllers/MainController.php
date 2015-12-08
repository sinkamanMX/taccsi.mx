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
    		$cServicios	= new My_Model_Servicios();
    		$aServicios = $cServicios->getOptions();
    		$aTarjetas	= $cFormaPago->getTarjetas($this->_dataUser['ID_SRV_USUARIO']);
    		
    		$aCustom	 = Array(
				array("id"=>"1",'name'=> utf8_encode('Lo m&aacute;s pronto posible') ),
				array("id"=>"2",'name'=>'Quiero hacer una reservaci&oacute;n' )    
		    ); 
    		
    		if($this->_dataOp=='new'){
    			$aDataViaje		 = $this->_dataIn;    			
    			$aDataViaje['userRegistro'] = $this->_dataUser['ID_SRV_USUARIO'];
				$aDataViaje['strClient'] 	= $this->_dataUser['ID_SRV_USUARIO'];
				$aDataViaje['inputFechaViaje'] = date('Y-m-d H:i:s');
				$aDataViaje['inputSize']	= $this->_dataIn['optionSize'][0];
    			$registerService = $cViajes->insertNewRow($aDataViaje);
    			if($registerService['status']){
    				$idViaje = $registerService['id'];
    				
    				if(isset($this->_dataIn['optionServ']) && count($this->_dataIn['optionServ'])>0){
    					for($i=0;$i<count($this->_dataIn['optionServ']);$i++){
    						$insertService = $cViajes->insertServices($idViaje,$this->_dataIn['optionServ'][$i]);
    					}
    				}
    				
    				$aTaxis	 = $cTaxis->getTaxiService($aDataViaje);						
					$this->addTaxis($aTaxis, $idViaje);
					$this->_redirect('/external/main/serviceinfo?strViaje='.$idViaje);
    			}
    		}else if($this->_dataOp=='newres'){
    			$cReservaciones  = new My_Model_Reservaciones();
    		    $aDataViaje		 = $this->_dataIn;    			
    			$aDataViaje['userRegistro'] = $this->_dataUser['ID_SRV_USUARIO'];
				$aDataViaje['strClient'] 	= $this->_dataUser['ID_SRV_USUARIO'];
				$aDataViaje['inputSize']	= $this->_dataIn['optionSize'][0];
				
				if($cReservaciones->validaSinReservaciones($this->_dataUser['ID_SRV_USUARIO'],$this->_dataIn['inputFechaViaje'])){
	    			$registerService = $cReservaciones->insertNewRow($aDataViaje);
	    			if($registerService['status']){
	    				$idReservacion = $registerService['id'];
	    				
	    				if(isset($this->_dataIn['optionServ']) && count($this->_dataIn['optionServ'])>0){
	    					for($i=0;$i<count($this->_dataIn['optionServ']);$i++){
	    						$insertService = $cReservaciones->insertServices($idViaje,$this->_dataIn['optionServ'][$i]);
	    					}
	    				}
	    					    								
						$this->_redirect('/external/reports/index');						
	    			}
				}else{
					$this->_aErrors['existReservacion'] = '1';
				}
    		}
    		
    		$sFormaPago = '';
    		$sOptionDate= '';
    		
    		if(count($this->_aErrors)>0){
    			$sFormaPago = $this->_dataIn['inputTarjeta'];
    			$sOptionDate= $this->_dataIn['inputWhen'];
    		}
    		
			$aFpagos	= $cFormaPago->getCbo();
			$this->view->totalClientes   = $cFunctions->cbo_number(6);	
			$this->view->viajeProgramado = $cFunctions->cboOptions(0);		   	
			$this->view->formaPago		 = $cFunctions->selectDb($aFpagos,$sFormaPago);
			$this->view->aServicios		 = $aServicios;    		
			$this->view->aTarjetas		 = $cFunctions->selectDb($aTarjetas,$sFormaPago);
			$this->view->optionDate		 = $cFunctions->cbo_from_array($aCustom,$sOptionDate);
    		$this->view->data		= $this->_dataIn;
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;   
			$this->view->data 		= $this->_dataIn;	
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }
    }   

    public function serviceinfoAction(){
    	try{
    		$cTaxis				= new My_Model_Taxis();
    		$cCliente   		= new My_Model_Clientes();  
    		$cViajes			= new My_Model_Viajes();
    		$cIncidencias		= new My_Model_Incidencias();
			$aDataViaje 		= Array();
    		$aDataClient		= Array();
    		$aDataIncidencias 	= Array();
    		$rDataOpr			= false;
    		$aRecorrido			= Array();
    		$aPosition			= Array();
    		$aLastPosition		= Array();
    			
    		if(isset($this->_dataIn['strViaje'])){
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			$aDataClient= $cCliente->getDataClient($aDataViaje['ID_CLIENTE']);  
				$aDataIncidencias = $cIncidencias->getIncidencias($idViaje);
    			$idTaxista 	= $aDataViaje['ID_TAXISTA'];    			    			
    			$aRecorrido = $cViajes->getRecorrido($idViaje,$aDataViaje['ID_SRV_ESTATUS']);
    			
    			if($aDataViaje['ID_SRV_ESTATUS']==2){
    				$aLastPosition = $cTaxis->getLastPositionByTaccsi($aDataViaje['ID_TAXISTA']);	
    			}
    			
    			if($this->_dataOp=='renew'){
    				if($idTaxista==NULL){
    					$delete = $cViajes->deleteAssign($idViaje);    					
    					if($delete){	
    						$updated = $cViajes->resetViaje($idViaje);
    						if($updated['status']){
								$aDataViaje['inputLatOrigen'] = $aDataViaje['ORIGEN_LATITUD'];
	    						$aDataViaje['inputLonOrigen'] = $aDataViaje['ORIGEN_LONGITUD'];
								$aTaxis	 = $cTaxis->getTaxiService($aDataViaje);						
								$this->addTaxis($aTaxis, $idViaje);
								$this->_redirect('/external/main/serviceinfo?strViaje='.$idViaje);    						
	    						$rDataOpr = true;	
    						}    						
    					}
    				}
    			}else if($this->_dataOp=='cancel'){
    				$bCancelTravel = $cViajes->cancelViaje($idViaje);
    				if($bCancelTravel['status']){
    					$this->_redirect('/external/reports/index');
    				}
    			}
    		}else{
    			$this->_redirect('/external/login/inicio');
    		}    		
    		
    		$this->view->dataOpr	  	  = $rDataOpr;		
			$this->view->aDataViaje   	  = $aDataViaje; 
			$this->view->recorrido  	  = $aRecorrido;
			$this->view->aDataCliente 	  = $aDataClient;
			$this->view->aDataIncidencias = $aDataIncidencias;
    		$this->view->idViaje		  = $this->_dataIn['strViaje'];
    		$this->view->aLastPosition	  = $aLastPosition;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	    	
    }
    
    public function addTaxis($data,$idViaje){
    	$cTaxis	= new My_Model_Taxis();
    	$aDatainsert = Array();
    	foreach($data as $key  => $items){
    		$aDatainsert['idViaje']  = $idViaje;
    		$aDatainsert['idTaccsi'] = $items['ID_USUARIO'];
    		$insertTaxis = $cTaxis->insertRelTaxis($aDatainsert);
    	}
    }   

    public function getassignAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();    
  	    $answer = Array('answer' => 'no-data');  	    
  	    
    	try{
			$cViajes	= new My_Model_Viajes();
	            		
    		if(isset($this->_dataIn['strViaje'])){
    			$idViaje	= $this->_dataIn['strViaje'];
    			
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			if(isset($aDataViaje['ID_TAXISTA'])){
    				$answer = Array('answer' => 'assign');
    			}else{
    				$answer = Array('answer' => 'problem');	
    			} 			 	
    		}else{
    			$answer = Array('answer' => 'problem');	
    		}
    		echo Zend_Json::encode($answer);     
    		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }      	
    }

    public function getlastpAction(){
    	$result = '';
		try{  
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
    		$cViajes	= new My_Model_Viajes();
    		$cTaxis		= new My_Model_Taxis();		
    		
			if(isset($this->_dataIn['strViaje']) && $this->_dataIn['strViaje']){
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			$aLastPos	= $cTaxis->getPositionTaxi($aDataViaje['ID_TAXISTA']);
    			
    			$result 	= $aDataViaje['ID_SRV_ESTATUS']."|".
    						  $aLastPos['LATITUD']	."|".
    						  $aLastPos['LONGITUD']	."|".
    						  $aDataViaje['TAXISTA']."|".
    						  $aDataViaje['TAXI']	."|".
    						  $aLastPos['FECHA_GPS']."|".
    						  $aLastPos['PROVEEDOR'];			  
			}
			echo $result;    	
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }   	    	
    }   

    public function processServices($aServices,$iFind){
    	$result = false;    	
    	for($i=0;$i<count($aServices);$i++){
    		if($aServices[$i]==$iFind){
    			$result =  true;
    		}
    	}
    	return $result;
    }
}