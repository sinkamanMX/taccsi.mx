<?php
class reports_TraveldetController extends My_Controller_Action
{
	protected $_clase = 'rtraveldet';
	
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
			$cEmpresas		= new My_Model_Empresas();
    		
    		$idUser 		= $this->_dataUser['ID_USUARIO'];    		
    		$idEmpresa 		= (isset($this->_dataIn['catId'])) ? $this->_dataIn['catId'] : $this->_dataUser['ID_EMPRESA'];

    		$dataInfo 		  = $cEmpresas->getData($idEmpresa);    		
    		$this->view->data = $dataInfo;
				    		
			$aResume 	   	= $cEmpresas->getViajesByEmpresa($idEmpresa);   			
			$aResumeByTaxi 	= $cEmpresas->getViajesByTaxis($idEmpresa);	
			$aResumeTaxi   	= $this->setDataResumen($aResumeByTaxi);			
			$aDataDes		= $cEmpresas->getTaxisDes($aResumeTaxi);
			
			$this->view->aResume  	= $aResume;
			$this->view->aResumeTaxi= $aResumeTaxi; 
			$this->view->aDataDes  	= $aDataDes;			
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    }

    public function setDataResumen($aData){
        try{
    		$dataResult  = Array();
    		$intRowCount = 0;
			foreach($aData AS $key => $items){			
				$dataResult[$items['ID_TAXISTA']]['NOMBRE'] = $items['N_TAXISTA'];
				$dataResult[$items['ID_TAXISTA']]['TAXI'] 	= $items['N_TAXI'];

				if($items['ID_SRV_ESTATUS']==3){
					if(isset($dataResult[$items['ID_TAXISTA']]['PRECIO'])){
						$costo = $dataResult[$items['ID_TAXISTA']]['PRECIO'];
						$dataResult[$items['ID_TAXISTA']]['PRECIO'] = $costo+$items['PRECIO']; 
					}else{
						$dataResult[$items['ID_TAXISTA']]['PRECIO'] = $items['PRECIO'];	
					}
					
					if(isset($items['RATING'])){						
						if(isset($dataResult[$items['ID_TAXISTA']]['RATING'])){
							$intTotal = @$dataResult[$items['ID_TAXISTA']]['TOTALT'];
							$rating = $dataResult[$items['ID_TAXISTA']]['RATING'];
							$dataResult[$items['ID_TAXISTA']]['RATING'] = $rating+$items['RATING'];
							@$dataResult[$items['ID_TAXISTA']]['TOTALT'] = $intTotal+1; 
						}else{
							$dataResult[$items['ID_TAXISTA']]['RATING']  = $items['RATING'];
							@$dataResult[$items['ID_TAXISTA']]['TOTALT'] = 1;
						}
					}						
				}
				
				if($items['ID_SRV_ESTATUS']==7){
					if(isset($dataResult[$items['ID_TAXISTA']]['RECHAZADO'])){
						$countRe = $dataResult[$items['ID_TAXISTA']]['RECHAZADO'];
						$dataResult[$items['ID_TAXISTA']]['RECHAZADO'] = $countRe+1; 
					}else{
						$dataResult[$items['ID_TAXISTA']]['RECHAZADO'] = 1;
					}	
				}				

				if(isset($dataResult[$items['ID_TAXISTA']]['VIAJES'])){
					$count = $dataResult[$items['ID_TAXISTA']]['VIAJES'];
					$dataResult[$items['ID_TAXISTA']]['VIAJES'] = $count+1; 
				}else{
					$dataResult[$items['ID_TAXISTA']]['VIAJES'] = 1;
				}						
			}
			return $dataResult;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }      	  	
    }
    
}