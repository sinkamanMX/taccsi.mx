<?php
class admin_UsersController extends My_Controller_Action
{
	protected $_clase = 'musers';
	
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
			$classObject = new My_Model_Usuarios();
			$this->view->datatTable = $classObject->getDataTables($this->_dataUser['ID_EMPRESA']);
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 

    public function getinfoAction(){
    	try{
    		$dataInfo = Array();
    		$cFunctions	= new My_Controller_Functions();
    		$classObject= new My_Model_Usuarios();
    		$cPerfiles 	= new My_Model_Perfiles();
    		    	
    		$sPerfil	= '';
    		$sEstatus	= '';
    		
    		$aPerfiles	= $cPerfiles->getCbo(4);
    		$this->_dataIn['dataIdEmpresa'] = $this->_dataUser['ID_EMPRESA'];
    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
    	    	$sPerfil	= $dataInfo['TIPO_USUARIO'];
    	    	$sEstatus	= $dataInfo['ACTIVO'];
			} 

    		if($this->_dataOp=='new'){    			
				$validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],-1,'user');
				 if($validateUser){
				 	$insert = $classObject->insertRow($this->_dataIn);
			 		if($insert['status']){
			 			$this->_idUpdate = $insert['id'];
		    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
		    	    	$sPerfil	= $dataInfo['TIPO_USUARIO'];
		    	    	$sEstatus	= $dataInfo['ACTIVO'];
				 		$this->_resultOp = 'okRegister';
					}else{
						$this->errors['status'] = 'no-insert';
					}
				 }else{
				 	$this->errors['eUsuario'] = '1';
				 }
			}else if($this->_dataOp=='update'){	  		
				if($this->_idUpdate>-1){
					 $validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],$this->_idUpdate,'user');
					 if($validateUser){
						$updated = $classObject->updateRow($this->_dataIn);
						 if($updated['status']){	
			    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
			    	    	$sPerfil	= $dataInfo['TIPO_USUARIO'];
			    	    	$sEstatus	= $dataInfo['ACTIVO'];
					 		$this->_resultOp = 'okRegister';
						 }
					 }else{
					 	$this->errors['eUsuario'] = '1';
					 }
				}else{
					$this->errors['status'] = 'no-info';
				}
			}else if($this->_dataOp=='delete'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');
				    
				$this->dataIn['idEmpresa'] = 1;
				$delete = $classObject->deleteRow($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}	
	
		        echo Zend_Json::encode($answer);
		        die();   			
			}
	
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
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

			$this->view->aStatus  	 = $cFunctions->cboStatus($sEstatus);
			$this->view->aPerfiles   = $cFunctions->selectDb($aPerfiles,$sPerfil);
			
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
}
	