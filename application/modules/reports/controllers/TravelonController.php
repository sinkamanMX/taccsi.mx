<?php
class reports_TravelonController extends My_Controller_Action
{
	protected $_clase = 'rtravelon';
	
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
    							'ID_USER'		=>  $idUser,
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
    		
			$aResume 	= $cViajes->getViajesResume($aFilters);
			$totalResume= $cViajes->resumeTotal($aFilters);
    		$aTravels	= $cViajes->getTravelsResume($aFilters);
    			    		
    		$this->view->aResume  	= $aResume;
    		$this->view->aEstatus 	= $cFunctions->selectDb($aEstatus,$sEstatus);
    		$this->view->totalResume= $totalResume;
    		$this->view->aTravels  	= $aTravels;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }

    public function otherAction(){
    	try{
    		/*
    		$cEstatusService= new My_Model_Estatuservicio();
    		$cFunctions     = new My_Controller_Functions();
			$cViajes 		= new My_Model_Viajes();
    		
    		$idUser 		= $this->_dataUser['ID_USUARIO'];
    		$idEmpresa  	= $this->_dataUser['ID_EMPRESA'];
    		$aResume		= Array();
    		$aEstatus   	= $cEstatusService->getCbo();
    		$sEstatus		= '';
    		$totalResume	= 0;
    		
    		if(@$this->_dataOp == 'search'){
    				
    		}else{
    			$aResume 	= $cViajes->getViajesResume($idUser);
    			$totalResume= $cViajes->resumeTotal($idUser);
    		}
    		
    		$this->view->aResume  	= $aResume;
    		$this->view->aEstatus 	= $cFunctions->selectDb($aEstatus,$sEstatus);
    		$this->view->totalResume= $totalResume;
    		*/
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }
}