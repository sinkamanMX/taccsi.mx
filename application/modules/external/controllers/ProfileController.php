<?php
class external_ProfileController extends My_Controller_Action
{
	protected $_clase = 'mprofileext';	
	
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
			$this->_idUpdate =  $this->_dataUser['ID_SRV_USUARIO'];				
			
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
    
    public function indexAction(){
    	try{
			$classObject 	= new My_Model_ServUsuarios();			
    		$cFunctions		= new My_Controller_Functions();
    		$cEmpresas		= new My_Model_Empresas();
			$sEstatus		= '';
			
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ESTATUS'];
			}

    	 	if($this->_dataOp=='update'){
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
							  	if(@$dataInfo['IMAGEN']!=""){
								  	$nameDelete = "images/taxis/".$dataInfo['IMAGEN'];
								  	if(file_exists($nameDelete)){
								  		unlink($nameDelete);	
								  	}							  		
							  	}							  	
							  	$this->_dataIn['nameImagen'] = $sNameImage;
							  }				
						  }
					}else {
					  $this->_aErrors['errorImage'] = 1;
					}
	            }
	            
	            if(isset($this->_dataIn['inputPasswordNow']) && ($this->_dataIn['inputPasswordNow']!=$dataInfo['PASSWORD'])){							
					$this->_aErrors['ePassword'] = '1';
	            }		            
	            
	            if(count($this->_aErrors)==0){
	            	$this->_dataIn['nameImagen'] = ($sNameImage!="") ? $this->_dataIn['nameImagen'] : '';       	
	            	if($this->_idUpdate>-1){
						$validateUser = $classObject->validateData($this->_dataIn['inputUsuario'],$this->_idUpdate,'user');	
						if($validateUser){
							$updated = $classObject->updateProfile($this->_dataIn);
							 if($updated['status']){					 			
				    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
						 		$this->_resultOp = 'okRegister';
							 }
						 }else{
						 	$this->_aErrors['eUsuario'] = '1';
						 }
					}else{
						$this->_aErrors['status'] = 'no-info';
					}
	            }				
			}
			
			 			
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