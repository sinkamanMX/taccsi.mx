<?php
class admin_DriversController extends My_Controller_Action
{
	protected $_clase = 'mdrivers';
	
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
			$classObject = new My_Model_Taxistas();
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
    		$classObject= new My_Model_Taxistas();
    		$cTaxis		= new My_Model_Taxis();
    		$sEstatus	= '';
    		    		
    		$this->_dataIn['dataIdEmpresa'] = $this->_dataUser['ID_EMPRESA'];
    		
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ACTIVO'];
			} 

    		if($this->_dataOp=='new'){  
    			$sNameImage = '';
				
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);
					
					$newNameimage = "TX_".Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "images/taxis/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;  	
							  }else{
							  	$nameDelete = "images/taxis/".$dataInfo['IMAGEN'];
							  	unlink($nameDelete);							  	
							  	$this->_dataIn['nameImagen'] = $sNameImage;							  	
							  }				
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}
	            }
	            
	            if(count($this->_aErrors)==0){
	            	 $this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';
	            	 $validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],-1,'user');
					 if($validateUser){
					 	$this->_dataIn['inputTipo'] = 4;
					 	$insert = $classObject->insertRow($this->_dataIn);
				 		if($insert['status']){
				 			$this->_idUpdate = $insert['id'];
				 			
				 			if(isset($this->_dataIn['inputIdAssign']) && $this->_dataIn['inputIdAssign']){
				 				$this->_dataIn['catId'] 	= $insert['id'];
				 				$this->_dataIn['inputTaxi'] = $this->_dataIn['inputIdAssign'];
								
				 				$cTaxis->setDriver($this->_dataIn);
				 			}
				 			
			    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
			    	    	$sEstatus	= $dataInfo['ACTIVO'];
					 		$this->_resultOp = 'okRegister';
						}else{
							$this->errors['status'] = 'no-insert';
						}
					 }else{
					 	$this->errors['eUsuario'] = '1';
					 }
	            }
			}else if($this->_dataOp=='update'){
				$sNameImage = '';
				
				if($_FILES['imageProfile']['name']!=""){
		            $allowedExts = array("jpeg", "jpg", "png");
					$temp = explode(".", $_FILES["imageProfile"]["name"]);
					$extension = end($temp);
					
					$newNameimage = "TX_".Date("Ymdhis").".".$extension;
					if ((($_FILES["imageProfile"]["type"] == "image/jpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/jpg")
						|| ($_FILES["imageProfile"]["type"] == "image/pjpeg")
						|| ($_FILES["imageProfile"]["type"] == "image/x-png")
						|| ($_FILES["imageProfile"]["type"] == "image/png"))
						&& ($_FILES["imageProfile"]["size"] < 10485760)
						&& in_array($extension, $allowedExts)) {
								
						  if ($_FILES["imageProfile"]["error"] > 0) {
						  		$this->_aErrors['errorImage'] = 1;
						  }else{
							  $sNameImage  = $newNameimage;

							  $upload = move_uploaded_file($_FILES["imageProfile"]["tmp_name"], "images/taxis/" . $newNameimage);
							  if(!$upload){
								$this->_aErrors['errorImage'] = 1;
							  }else{
							  	$nameDelete = "images/taxis/".$dataInfo['IMAGEN'];
							  	if(file_exists($nameDelete)){
							  		unlink($nameDelete);	
							  	}							  								  
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }				
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}
	            }
	            
	            if(count($this->_aErrors)==0){
	            	$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';       	
	            	if($this->_idUpdate>-1){
						$validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],$this->_idUpdate,'user');	
						if($validateUser){
						 	$this->_dataIn['inputTipo'] = 4;
							$updated = $classObject->updateRow($this->_dataIn);
							 if($updated['status']){	
					 			if(isset($this->_dataIn['inputIdAssign']) && $this->_dataIn['inputIdAssign']){
					 				$this->_dataIn['inputTaxi'] = $this->_dataIn['inputIdAssign'];
									
					 				$cTaxis->setDriver($this->_dataIn);
					 			}
					 			
				    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
				    	    	$sEstatus	= $dataInfo['ACTIVO'];
						 		$this->_resultOp = 'okRegister';
							 }
						 }else{
						 	$this->errors['eUsuario'] = '1';
						 }
					}else{
						$this->errors['status'] = 'no-info';
					}
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
			}else if($this->_dataOp=='deleteRel'){
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$answer = Array('answer' => 'no-data');
				    
 				$this->_dataIn['catId'] 		= 'NULL';
 				$this->_dataIn['inputNombre'] 	= '';
 				$this->_dataIn['inputApaterno'] = '';
 				$this->_dataIn['inputAmaterno'] = '';
 				$this->_dataIn['inputTaxi'] = $this->_dataIn['inputIdAssign'];
				
				$delete = $cTaxis->setDriver($this->_dataIn);
				if($delete){
					$answer = Array('answer' => 'deleted'); 
				}	
		
		        echo Zend_Json::encode($answer);
		        die();   			
			}
	
			if(count($this->_aErrors)>0 && $this->_dataOp!=""){
				
				/*
				$dataInfo['ID_SUCURSAL'] 	= $this->dataIn['inputSucursal'];
				$dataInfo['USUARIO'] 		= $this->dataIn['inputUsuario'];
				$dataInfo['NOMBRE'] 		= $this->dataIn['inputNombre'];
				$dataInfo['APELLIDOS'] 		= $this->dataIn['inputApps'];
				$dataInfo['EMAIL'] 			= $this->dataIn['inputEmail'];
				$dataInfo['TEL_MOVIL'] 		= $this->dataIn['inputMovil'];
				$dataInfo['TEL_FIJO'] 		= $this->dataIn['inputTelFijo'];
				$dataInfo['ACTIVO'] 		= $this->dataIn['inputEstatus'];
				$dataInfo['FLAG_OPERACIONES']= $this->dataIn['inputOperaciones'];
				*/
				
			}		

			$this->view->aStatus  	 = $cFunctions->cboStatus($sEstatus);
			
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

    public function searchtaxisAction(){
		try{
			$this->view->layout()->setLayout('layout_blank');
			 			
			$cObject    = new My_Model_Taxis();
			$aDataTable	= $cObject->getDataNoAssign($this->_dataUser['ID_EMPRESA']);			
			$this->view->dataTable= $aDataTable;
        } catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }       	
    }
}    