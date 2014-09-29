<?php
class reports_TravelsrepController extends My_Controller_Action
{
	protected $_clase = 'mtravelreport';
	
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
    		$cEstatusService= new My_Model_Estatuservicio();
    		$cFunctions     = new My_Controller_Functions();
			$cViajes 		= new My_Model_Viajes();
    		
    		$idUser 		= $this->_dataUser['ID_USUARIO'];
    		$idEmpresa  	= $this->_dataUser['ID_EMPRESA'];
    		$aResume		= Array();
    		$aTravels 		= Array();
    		$aEstatus   	= $cEstatusService->getCbo();
    		$sEstatus		= '';
    		$totalResume	= 0;
    		
    		$aFilters		= Array(
    							'FILTER_BY_USER'=> 	1,
    							'ID_EMPRESA'	=>  $idEmpresa,
    							'ID_ESTATUS'	=>	-99,	
    							'FECHA_IN'		=>	Date("Y-m-d 00:00:00"),
    							'FECHA_FIN'		=>	Date("Y-m-d 23:59:39"),    							
    						);    				
    		if(@$this->_dataOp == 'search'){
    			$aFilters['ID_ESTATUS']	= $this->_dataIn['inputEstatus'];
    			$aFilters['FECHA_IN']	= $this->_dataIn['inputFechaIn'];	
    			$aFilters['FECHA_FIN']	= $this->_dataIn['inputFechaFin'];
    			$sEstatus				= $this->_dataIn['inputEstatus'];
    			$this->view->data		= $this->_dataIn;
    		}
    		
			$aResume 	= $cViajes->getViajesResumeEmp($aFilters);
			$totalResume= $cViajes->resumeTotalEmp($aFilters);
    		$aTravels	= $cViajes->getTravelsResumeEmp($aFilters);
    			    		
    		$this->view->aResume  	= $aResume;
    		$this->view->aEstatus 	= $cFunctions->selectDb($aEstatus,$sEstatus);
    		$this->view->totalResume= $totalResume;
    		$this->view->aTravels  	= $aTravels;
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
    			
    		if(isset($this->_dataIn['strViaje'])){
    			$idViaje	= $this->_dataIn['strViaje'];
    			$aDataViaje = $cViajes->infoViaje($idViaje);
    			$aDataClient= $cCliente->getDataClient($aDataViaje['ID_CLIENTE']);  
				$aDataIncidencias = $cIncidencias->getIncidencias($idViaje);
    			$idTaxista 	= $aDataViaje['ID_TAXISTA'];
    			
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
			$this->view->aDataCliente 	  = $aDataClient;
			$this->view->aDataIncidencias = $aDataIncidencias;
    		$this->view->idViaje		  = $this->_dataIn['strViaje'];
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  	    	
    }    
}