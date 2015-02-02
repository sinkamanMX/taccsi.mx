<?php
class admin_JsonController extends My_Controller_Action
{		
	public    $aDbTables = Array (  'mun'        => Array('nameClass'=>'Municpios'),
									'colonia'    => Array('nameClass'=>'Colonias'),
									'horario'    => Array('nameClass'=>'Cinstalaciones'),
									'modeloe'    => Array('nameClass'=>'Modelos'),
									'modelot'    => Array('nameClass'=>'Modelostel'),
									'modeloa'    => Array('nameClass'=>'Modelosa'),
									'tecnicos'    => Array('nameClass'=>'Tecnicos')
						);	
		
    public function init()
    {    			
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();   		
    }    
    
    public function getclientesAction(){
        $sessions = new My_Controller_Auth();
        if(!$sessions->validateSession()){
            $this->_redirect("/admin/login/index");	
		}
		    		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		    
		
        $result = "";
		$data   = $this->_request->getParams();
				
		$nameFilter	= $data['namefilter'];
		$appFilter	= $data['appfilter'];
		$apmFilter	= $data['apmfilter'];
		$telFilter 	= $data['telfilter'];		
		$clientes    = new My_Model_Clientes();		
		$dataClients = $clientes->getClients($data);

		if(count($dataClients)>0){
			
			 foreach($dataClients as $key => $items){
			 	$result[] = $items;
	    	}			
		}
		
		echo Zend_Json::encode( $result = array('aaData'=>$result ) );		       	
    }    
    
    public function getselectAction(){
    	try{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();    
			    	
	    	$result = 'no-info';
			$this->dataIn = $this->_request->getParams();
			$functions = new My_Controller_Functions();				
			$validateNumbers = new Zend_Validate_Digits();
			$validateAlpha   = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
			$this->idEmpresa = '';		
			
			if($validateNumbers->isValid($this->dataIn['catId']) && $validateAlpha->isValid($this->dataIn['oprDb'])){
				if(isset($this->aDbTables[$this->dataIn['oprDb']])){
					$classObject =  $functions->creationClass($this->dataIn['oprDb']);
					$cboValues   = $classObject->getCbo($this->dataIn['catId'],$this->idEmpresa);
					$result      = $functions->selectDb($cboValues);		
				}
			}
			
			echo $result;
		
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }    		
    }      
}