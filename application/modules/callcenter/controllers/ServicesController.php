<?php
class callcenter_ServicesController extends My_Controller_Action
{		
	protected $_clase = "mservices";
	
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

    public function createserviceAction(){
    	try{
    		$cFunctions = new My_Controller_Functions();
    		$cFormaPago = new My_Model_Formapago();
    		$cTaxis		= new My_Model_Taxis();  
    		$cViajes	= new My_Model_Viajes();    				
    		if(isset($this->_dataIn['strClient']) && $this->_dataIn['strClient']!=""){
    			
	    		if($this->_dataOp=='new'){
	    			$aDataViaje		 = $this->_dataIn;
					$aDataViaje['userRegistro'] = $this->_dataUser['ID_USUARIO'];
					$registerService = $cViajes->insertRow($aDataViaje);
					
					if($registerService['status']){
						$idViaje = $registerService['id'];
						
						$this->_redirect('/callcenter/services/listtaccis?strViaje='.$idViaje);
						/*
							$aTaxis	 = $cTaxis->getTaxiService($aDataViaje);						
							$this->addTaxis($aTaxis, $idViaje);
							$this->_redirect('/callcenter/services/serviceinfo?strViaje='.$idViaje);
						*/
					}					
	    		}
    		}else{
    			$this->_redirect('/callcenter/services/index');
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
								$this->_redirect('/callcenter/services/serviceinfo?strViaje='.$idViaje);    						
	    						$rDataOpr = true;	
    						}    						
    					}
    				}
    			}
    		}else{
    			$this->_redirect('/callcenter/services/index');
    		}    		
    		
    		$this->view->dataOpr	  	  = $rDataOpr;		
			$this->view->aDataViaje   	  = $aDataViaje; 
			$this->view->recorrido  	  = $aRecorrido;
			$this->view->aDataCliente 	  = $aDataClient;
			$this->view->aDataIncidencias = $aDataIncidencias;
    		$this->view->idViaje		  = $this->_dataIn['strViaje'];
    		$this->view->aLastPosition	  = $aLastPosition;
    		$this->view->waiting		  = (isset($this->_dataIn['strOpt'])) ? '1': '0';
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
    
    public function cancelAction(){
    	try{    	    		
			$this->_redirect('/callcenter/services/index');				
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
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
    
    public function reportincidenciaAction(){
    	try{        			    		
			$this->view->layout()->setLayout('layout_blank');
			$cIncidencias = new My_Model_Incidencias();
			$cFunctions   = new My_Controller_Functions();	
    		$aData		  = Array();
    		$aTipos		  = Array();
    		$aEstatus	  = Array();
    		$aDataNotas	  = Array();
    		$dTipo		  = "";
    		$dEstatus	  = "1";
    		
    		$aTipos	  	  = $cIncidencias->getTipoInc();
    		$aEstatus	  = $cIncidencias->getEstatusInc(); 
    		    		
    	    if(isset($this->_dataIn['strInc']) && isset($this->_dataIn['idViaje'])){
    	    	$idIncidencia = $this->_dataIn['strInc'];
    	    	
    	    	if($idIncidencia>-1){
    	    		$aData 	  = $cIncidencias->getData($idIncidencia);
    	    		$dTipo 	  = $aData['ID_TIPO_INCIDENCIA'];
    	    		$dEstatus = $aData['ID_INCIDENCIAS_ESTATUS'];
    	    		$aDataNotas = $cIncidencias->getNotasInc($idIncidencia);
    	    	}  

    	    	if($this->_dataOp=='new'){
    	    		$insert = $cIncidencias->insertRow($this->_dataIn);
    	    		if($insert['status']){						
						$this->_dataIn['strInc'] = $insert['id'];
						
	    	    		$aData 	  = $cIncidencias->getData($this->_dataIn['strInc']);
	    	    		$dTipo 	  = $aData['ID_TIPO_INCIDENCIA'];
	    	    		$dEstatus = $aData['ID_INCIDENCIAS_ESTATUS'];
	    	    		$aDataNotas = $cIncidencias->getNotasInc($this->_dataIn['strInc']);
	    	    		$this->_resultOp = 'resultOk';
    	    		}else{
    	    			$this->_aErrors['errorInsert'] = 1;
    	    		}
    	    	}else if($this->_dataOp=='update'){
    	    		$update = $cIncidencias->updateRow($this->_dataIn);
    	    		if($update['status']){
	    	    		$aData 	  = $cIncidencias->getData($this->_dataIn['strInc']);
	    	    		$dTipo 	  = $aData['ID_TIPO_INCIDENCIA'];
	    	    		$dEstatus = $aData['ID_INCIDENCIAS_ESTATUS'];	    	    			    	    		
    	    			if($this->_dataIn['inputComment']!=""){
    	    				$insertComment = $cIncidencias->insertRowNota($this->_dataIn);
    	    				if($insertComment['status']){
    	    					$this->_resultOp = 'resultOk';
    	    					$aDataNotas = $cIncidencias->getNotasInc($this->_dataIn['strInc']);
    	    				}else{
    	    					$this->_aErrors['errorUpdate'] = 1;
    	    				}
    	    			}else{
    	    				$this->_resultOp = 'resultOk';	
    	    			}
    	    		}else{
    	    			$this->_aErrors['errorUpdate'] = 1;
    	    		}
    	    	} 
    		}
    		
    		$this->view->resultOp	= $this->_resultOp;
    		$this->view->aTipos		= $cFunctions->selectDb($aTipos,$dTipo);
    		$this->view->aEstatus	= $cFunctions->selectDb($aEstatus,$dEstatus);
    		$this->view->aData 		= $aData;
    		$this->view->idInc		= $this->_dataIn['strInc'];
    		$this->view->aDataNotas = $aDataNotas;
    		$this->view->errors		= $this->_aErrors;
    		$this->view->idViaje	= $this->_dataIn['idViaje'];
    	} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	    	
    }  

    public function listtaccisAction(){
    	try{
    		$cTaxis		= new My_Model_Taxis();
    		$cViajes	= new My_Model_Viajes();
    		$aDataViaje	= Array();
    		
    		if(isset($this->_dataIn['strViaje'])){
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);    			

    			$aDataViaje['inputLatOrigen'] = $aDataViaje['ORIGEN_LATITUD']; 
    			$aDataViaje['inputLonOrigen'] = $aDataViaje['ORIGEN_LONGITUD'];
    			
				$this->view->dataTable = $cTaxis->getTaxiService($aDataViaje,1);
				
				if($this->_dataOp=='assignDriver'){
					if(isset($this->_dataIn['inputDriver'])){
						$aDatainsert['idViaje']  = $idViaje;
    					$aDatainsert['idTaccsi'] = $this->_dataIn['inputDriver'];
    					$insertTaxis = $cTaxis->insertRelTaxis($aDatainsert);
    					if($insertTaxis){
    						$this->_redirect('/callcenter/services/serviceinfo?strViaje='.$idViaje.'&strOpt=WaitConfirm');
    					}
					}	
				}
    		}else{
    			$this->_redirect('/callcenter/services/index');
    		}
    		
    		$this->view->aData = $aDataViaje;
    	}catch(Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        } 	
    }
}