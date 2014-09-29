<?php
class reports_TravelcompController extends My_Controller_Action
{
	protected $_clase = 'mtravelcomp';
	
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
			$cEmpresas  = new My_Model_Empresas();
			$aTravelFin = $cEmpresas->getGlobalViajes();			
			$aResume    = $this->processResume($aTravelFin);
			
			$this->view->datatTable = $cEmpresas->getDataTables();			
			$this->view->aResumen 	= $aResume;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 
    
    public function processResume($aData){
    	try{
    		$dataResult = Array();
    		
			foreach($aData AS $key => $items){
				$intRowCount = 0;				
				$dataResult[$items['IDEMPRESA']]['TITULO'] = $items['N_EMPRESA'];
				
				if($items['ID_SRV_ESTATUS']==3){
					if(isset($dataResult[$items['IDEMPRESA']]['PRECIO'])){
						$costo = $dataResult[$items['IDEMPRESA']]['PRECIO'];
						$dataResult[$items['IDEMPRESA']]['PRECIO'] = $costo+$items['PRECIO']; 
					}else{
						$dataResult[$items['IDEMPRESA']]['PRECIO'] = $items['PRECIO'];	
					}	
					
					if(isset($items['RATING'])){
						$intRowCount++;
						if(isset($dataResult[$items['IDEMPRESA']]['RATING'])){
							$rating = $dataResult[$items['IDEMPRESA']]['RATING'];
							$dataResult[$items['IDEMPRESA']]['RATING'] = $rating+$items['RATING'];
							@$dataResult[$items['IDEMPRESA']]['TOTALT'] += $intRowCount; 
						}else{
							$dataResult[$items['IDEMPRESA']]['RATING'] = $items['RATING'];
							@$dataResult[$items['IDEMPRESA']]['TOTALT'] += $intRowCount;
						}
					}			
				}

				if(isset($dataResult[$items['IDEMPRESA']]['ESTATUS'][$items['ID_SRV_ESTATUS']])){
					$total  = $dataResult[$items['IDEMPRESA']]['ESTATUS'][$items['ID_SRV_ESTATUS']]['TOTAL'];
					$dataResult[$items['IDEMPRESA']]['ESTATUS'][$items['ID_SRV_ESTATUS']]['TOTAL'] = $total+1;					
				}else{
					$dataResult[$items['IDEMPRESA']]['ESTATUS'][$items['ID_SRV_ESTATUS']]['TOTAL'] = 1;
					$dataResult[$items['IDEMPRESA']]['ESTATUS'][$items['ID_SRV_ESTATUS']]['N_ESTATUS'] = $items['N_ESTATUS'];
				}
				
				if(isset($dataResult[$items['IDEMPRESA']]['FPAGOS'][$items['ID_FORMA_PAGO']])){
					$total  = $dataResult[$items['IDEMPRESA']]['FPAGOS'][$items['ID_FORMA_PAGO']]['TOTAL'];
					$dataResult[$items['IDEMPRESA']]['FPAGOS'][$items['ID_FORMA_PAGO']]['TOTAL'] = $total+1;					
				}else{
					$dataResult[$items['IDEMPRESA']]['FPAGOS'][$items['ID_FORMA_PAGO']]['TOTAL'] = 1;
					$dataResult[$items['IDEMPRESA']]['FPAGOS'][$items['ID_FORMA_PAGO']]['N_FORMA'] = $items['FPAGO'];
				}				
			}
			return $dataResult;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }      	
    }
}    