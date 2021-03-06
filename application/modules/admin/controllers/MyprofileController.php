<?php
class admin_MyprofileController extends My_Controller_Action
{
	protected $_clase = 'mprofile';
	
    public function init()
    {
    	try{
    		$this->view->layout()->setLayout('admin_layout');
    		
    		$sessions = new My_Controller_Auth();
			$perfiles = new My_Model_Perfiles();
			$fc 	  = Zend_controller_front::getInstance();
	        if($sessions->validateSession()){
		        $this->_dataUser   = $sessions->getContentSession();
		        $this->_baseUrl    = $fc->getBaseUrl();   		
			}else{
				$this->_redirect("/login/main/index");
			}    		  
			$this->view->dataUser   = $this->_dataUser;
			$this->view->modules    = $perfiles->getModules($this->_dataUser['TIPO_USUARIO']);
			$this->view->moduleInfo = $perfiles->getDataModule($this->_clase);
			$this->view->BaseUrl    = $this->_baseUrl;
						
			$this->_dataIn 					= $this->_request->getParams();
			$this->_dataIn['userCreate']	= $this->_dataUser['ID_USUARIO'];
	    	if(isset($this->_dataIn['optReg'])){
				$this->_dataOp = $this->_dataIn['optReg'];				
			}
			
			if(isset($this->_dataIn['catId'])){
				$this->_idUpdate = $this->_dataIn['catId'];				
			}	

			$this->_idUpdate =  $this->_dataUser['ID_USUARIO'];						
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  		
    }
    
    public function indexAction(){
    	try{
			$classObject = new My_Model_Usuarios();
			
    		$dataCompany 	= Array();
    		$cFunctions		= new My_Controller_Functions();
    		$cEmpresas		= new My_Model_Empresas();
			$estados   		= new My_Model_Estados();
			$aEstados  		= $estados->getCbo();
			$cMunicipios	= new My_Model_Municipios();
			$cColonias 		= new My_Model_Colonias();
			$cBancos		= new My_Model_Bancarios();
			$aBancos		= $cBancos->getCboBancos();
			$cKardex		= new My_Model_Kardex();
			
			$aDataBanco		= Array();
			$aDataKardex	= Array();
			
			$aMunicipios  = Array();
			$aMunicipiosF = Array();    		
    		$sTipoCliente= '';
    		$sDirDif	= '0';
    		$sEstado	= '';
    		$sMuni		= '';
    		$sEstadoF	= '';
    		$sMuniF		= '';
    		$sColonia	= '';
    		$sColoniaF	= '';
    		$sBanco		= '';
			
    		$idCompany  = $this->_dataUser['ID_EMPRESA'];
    	    if($this->_idUpdate >-1){
    	    	$dataInfo	= $classObject->getDataUser($this->_idUpdate);
    	    	$sEstatus	= $dataInfo['ACTIVO'];    	    	
			}
			
			$dataCompany = $cEmpresas->getData($idCompany);
    	    $sTipoCliente= $dataCompany['TIPO_RAZON'];
    	    $sEstatus	 = $dataCompany['ESTATUS'];
    	    $sDirDif	 = $dataCompany['DIR_DIFERENTE'];
    	    $sEstado 	 = $dataCompany['ID_ESTADO'];
    	    $sEstadoF	 = $dataCompany['FIS_ID_ESTADO'];
    	    
    	    $aDataBanco  = $cBancos->getData($this->_dataUser['ID_USUARIO']);
    	    $sBanco		 = @$aDataBanco['ID_BANCO'];
    	    
    	    $aDataKardex = $cKardex->getData($this->_dataUser['ID_USUARIO']);
    	    	
			$dMunicipios = $cMunicipios->getCbo($sEstado);
			$aMunicipios = $cFunctions->selectDb($dMunicipios,@$dataCompany['ID_MUNICIPIO']);
				
			if($sEstadoF!="" && $sEstadoF!="NULL"){
				$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
				$aMunicipiosF = $cFunctions->selectDb($dMunicipios,@$dataCompany['FIS_ID_MUNICIPIO']);
			}
			
			if($this->_dataOp=="searchCP"){
				$aDataRetrieve = Array();
				if($this->_dataIn['inputSearch']!=""){
					$cColonias = new My_Model_Colonias();
										
					$validateCp = $cColonias->validateCP($this->_dataIn['inputSearch']);
					if(isset($validateCp['ID_COLONIA'])){						
						if($this->_dataIn['typeSearch']=="0"){
							$sEstado = $validateCp['ID_ESTADO'];							
							$dMunicipios = $cMunicipios->getCbo($validateCp['ID_ESTADO']);
							$aMunicipios = $cFunctions->selectDb($dMunicipios,$validateCp['ID_MUNICIPIO']);
				
							$sColonia    = $cColonias->getCbo($validateCp['ID_MUNICIPIO']);
							$aDataRetrieve['COLONIA'] = $sColonia['NAME'];
							$aDataRetrieve['CP']		 = $this->_dataIn['inputSearch'];						
						}else{
							$sEstadoF 	  = $validateCp['ID_ESTADO'];
							$dMunicipios  = $cMunicipios->getCbo($validateCp['ID_ESTADO']);
							$aMunicipiosF = $cFunctions->selectDb($dMunicipios,$validateCp['ID_MUNICIPIO']);
							
							$sColonia    = $cColonias->getCbo($validateCp['ID_MUNICIPIO']);
							$aDataRetrieve['FIS_COLONIA'] = $sColonia['NAME'];	
							$aDataRetrieve['FIS_CP']		 = $this->_dataIn['inputSearch'];				
						}
					}
				}				

            	$aDataRetrieve['NOMBRE_EMPRESA']	= $this->_dataIn['inputNameEmpresa'];
	           	$sTipoCliente						= $this->_dataIn['inputTipoRazon'];
	            $aDataRetrieve['RAZON_SOCIAL']		= $this->_dataIn['inputRazon'];
	            $aDataRetrieve['RFC']				= $this->_dataIn['inputRFC'];   
	            $aDataRetrieve['REPRESENTANTE_LEGAL']	= $this->_dataIn['inputRep'];        
	
	            $aDataRetrieve['CALLE']	= $this->_dataIn['inputCalle'];      
	            $aDataRetrieve['NOEXT']	= $this->_dataIn['inputNoext'];      
	            $aDataRetrieve['NOINT']	= $this->_dataIn['inputNoint'];      
	         
	            $sEstatus	= $this->_dataIn['inputEstatus'];    
	            $sDirDif	= $this->_dataIn['inputDirDif'];
	            $aDataRetrieve['DIR_DIFERENTE']	= $this->_dataIn['inputDirDif'];     
	
	            $aDataRetrieve['FIS_CALLE']	= $this->_dataIn['inputCalleF'];     
	            $aDataRetrieve['FIS_NOEXT']	= $this->_dataIn['inputNoextF'];     
	            $aDataRetrieve['FIS_NOINT']	= $this->_dataIn['inputNointF'];     
    			
	            $dataCompany = $aDataRetrieve;
			}				

    		if($this->_dataOp=='updateCompany'){	  		
    			$this->_dataIn['catId'] = $idCompany;
				$updated = $cEmpresas->updateRow($this->_dataIn);
				 if($updated['status']){	
	    	    	$dataCompany = $cEmpresas->getData($idCompany);
	    	    	$sTipoCliente= $dataCompany['TIPO_RAZON'];
	    	    	$sEstatus	 = $dataCompany['ESTATUS'];
	    	    	$sDirDif	 = $dataCompany['DIR_DIFERENTE'];
	    	    	$sEstado 	 = $dataCompany['ID_ESTADO'];
	    	    	$sEstadoF	 = $dataCompany['FIS_ID_ESTADO'];	
	    	    	
					$dMunicipios = $cMunicipios->getCbo($sEstado);
					$aMunicipios = $cFunctions->selectDb($dMunicipios,@$dataCompany['ID_MUNICIPIO']);
					
					$dMunicipiosF = $cMunicipios->getCbo($sEstadoF);
					$aMunicipiosF = $cFunctions->selectDb($dMunicipios,@$dataCompany['FIS_ID_MUNICIPIO']);		    	    	
	    	    	
			 		$this->_resultOp = 'okRegisterCommpany';
				 }else{
				 	$this->errors['eUsuario'] = '1';
				 }
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
							  	if(@$dataInfo['FOTO']!=""){
								  	$nameDelete = "images/taxis/".$dataInfo['FOTO'];
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
			
    	    if($this->_dataOp=='updatebank'){
				$updated = $cBancos->updateRow($this->_dataIn);
				 if($updated['status']){	    	    
		    	    $aDataBanco  = $cBancos->getData($this->_dataUser['ID_USUARIO']);
		    	    $sBanco		 = @$aDataBanco['ID_BANCO'];				 	
	    	    		    	    	
			 		$this->_resultOp = 'okRegisterBank';
				 }else{
				 	$this->errors['eUsuario'] = '1';
				 }
			}else if($this->_dataOp=='newBank'){
				$insert = $cBancos->insertRow($this->_dataIn);
				 if($insert['status']){				 	
		    	    $aDataBanco  = $cBancos->getData(@$this->_dataIn['catId']);
		    	    $sBanco		 = @$aDataBanco['ID_BANCO'];
	    	    		    	    	
			 		$this->_resultOp = 'okRegisterBank';
				 }else{
				 	$this->errors['eUsuario'] = '1';
				 }				
			}
			
			
			if($this->_dataOp=='updateDocs'){
				if(!isset($aDataKardex['ID_KARDEX'])){
					$insert = $cKardex->insertRow($this->_dataIn);								
				}
								
				$this->_dataIn['simageLicF']   	= $this->validateFields('imageCondFront');
				$this->_dataIn['simageLicB']   	= $this->validateFields('imageCondBack');
				$this->_dataIn['simageIde']   	= $this->validateFields('imageIDE');
				$this->_dataIn['simageCodom']  	= $this->validateFields('imageCoDom');
				$this->_dataIn['simageSat']   	= $this->validateFields('imageSat');
				$this->_dataIn['simageNopen']  	= $this->validateFields('imageNoPen');
				$this->_dataIn['imageEdoCu']   	= $this->validateFields('imageEdoCu');
								
				$updated = $cKardex->updateRow($this->_dataIn);
				if($updated['status']){
					$aDataKardex = $cKardex->getData($this->_dataUser['ID_USUARIO']);
				
					if($aDataKardex['LICENCIA_FRENTE']   !="" && $aDataKardex['LICENCIA_REVERSA']!="" 
						&& $aDataKardex['IDENTIFICACION']!="" && $aDataKardex['COMPROBANTE_DOMICILIO']!=""
						&& $aDataKardex['CEDULA_FISCAL'] !="" && $aDataKardex['ESTADO_CUENTA']!=""){
						
							$cHtmlMail = new My_Controller_Htmlmailing();							
							$cHtmlMail->documentsComplete($dataInfo);
					}					
					$this->_resultOp = 'okRegisterDocs';
				}
			}
			
			
			$this->view->aTipoClientes= $cFunctions->cboTipoCliente($sTipoCliente);
			$this->view->aDirDif	= $cFunctions->cboOptions($sDirDif);
			$this->view->aStatus  	= $cFunctions->cboStatus($sEstatus);
			
			$this->view->aEstados   = $cFunctions->selectDb($aEstados,$sEstado);
			$this->view->aEstadosF  = $cFunctions->selectDb($aEstados,$sEstadoF);
			$this->view->aMunicipios= $aMunicipios;
			$this->view->aMunicipiosF=$aMunicipiosF;		
			
			$this->view->sOperation = $this->_dataOp;
			$this->view->dataCompany= $dataCompany; 			
			$this->view->idCompany	= $idCompany;
			$this->view->data 		= $dataInfo; 
			$this->view->errors 	= $this->_aErrors;	
			$this->view->resultOp   = $this->_resultOp;
			$this->view->catId		= $this->_idUpdate;
			$this->view->idToUpdate = $this->_idUpdate;	
			
			$this->view->sBancos   = $cFunctions->selectDb($aBancos,$sBanco);
			$this->view->aDataBanco= $aDataBanco;
			
			$this->view->aDataKardex= $aDataKardex;
		} catch (Zend_Exception $e) {
            echo "Caught exception: " . get_class($e) . "\n";
        	echo "Message: " . $e->getMessage() . "\n";                
        }  
    } 

    public function validateFields($inputName){
    	$targetFolder 	 = '/var/www/vhosts/taccsi.com/htdocs/public/images/documentacion';
    	//$targetFolder 	 ='/Users/itecno2/Documents/workspace/taccsi.mx/public/images/documentacion';
    	$sResult = '';
		if(@$_FILES[$inputName]['name']!=""){
			$tempFile   = $_FILES[$inputName]['tmp_name'];				
			$targetFile = $targetFolder.'/'.$_FILES[$inputName]['name'];
			
			$fileTypes = array('png','jpg','jpeg','pdf');
			$fileParts = pathinfo($_FILES[$inputName]['name']);
								
			if (in_array($fileParts['extension'],$fileTypes)) {
				$nFile			= $inputName."_".date("YmdHis").'.'.$fileParts['extension'];
				$nameFinalFile  = $targetFolder.'/'.$nFile;				
				if(move_uploaded_file($tempFile,$nameFinalFile)){
					$sResult = $nFile;											
				}else{
					$this->_aErrors['errorImage'] = 1;
				}
			}else{
				$this->_aErrors['errorImage'] = 1;
			}
		}
		return $sResult;    	
    }    
}    